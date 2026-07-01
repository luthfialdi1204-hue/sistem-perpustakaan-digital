<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Buku;
use App\Models\DetailPeminjaman;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait FormatsPeminjaman
{
    protected function peminjamanBaseQuery(): Builder
    {
        return DetailPeminjaman::query()
            ->with(['peminjaman.user', 'buku']);
    }

    protected function scopeForUser(Builder $query, int|string $userId): Builder
    {
        return $query->whereHas('peminjaman', fn (Builder $q) => $q->where('id_user', (int) $userId));
    }

    protected function detailBorrowAt(DetailPeminjaman $detail): ?Carbon
    {
        if ($detail->status_transaksi === DetailPeminjaman::STATUS_MENGAJUKAN) {
            return null;
        }

        return $detail->tgl_Peminjaman ? Carbon::parse($detail->tgl_Peminjaman) : null;
    }

    protected function detailDueAt(DetailPeminjaman $detail): ?Carbon
    {
        if (in_array($detail->status_transaksi, [
            DetailPeminjaman::STATUS_MENGAJUKAN,
            DetailPeminjaman::STATUS_DITOLAK,
            DetailPeminjaman::STATUS_DIBATALKAN,
        ], true)) {
            return null;
        }

        return $detail->tgl_pengembalian ? Carbon::parse($detail->tgl_pengembalian) : null;
    }

    protected function detailSubmittedAt(DetailPeminjaman $detail): ?Carbon
    {
        if ($detail->tgl_Peminjaman) {
            return Carbon::parse($detail->tgl_Peminjaman);
        }

        return $detail->peminjaman?->tgl_Peminjaman
            ? Carbon::parse($detail->peminjaman->tgl_Peminjaman)
            : null;
    }

    protected function syncOverdueStatuses(): void
    {
        DetailPeminjaman::query()
            ->where('status_transaksi', DetailPeminjaman::STATUS_DIPINJAM)
            ->where('tgl_pengembalian', '<', now()->startOfDay())
            ->update(['status_transaksi' => DetailPeminjaman::STATUS_TERLAMBAT]);
    }

    protected function statusLabel(string $dbStatus): string
    {
        return match ($dbStatus) {
            DetailPeminjaman::STATUS_MENGAJUKAN => 'Mengajukan',
            DetailPeminjaman::STATUS_DIPINJAM => 'Sedang Dipinjam',
            DetailPeminjaman::STATUS_TERLAMBAT => 'Terlambat',
            DetailPeminjaman::STATUS_DIKEMBALIKAN => 'Dikembalikan',
            DetailPeminjaman::STATUS_SUDAH_LUNAS => 'Sudah Lunas',
            DetailPeminjaman::STATUS_DITOLAK => 'Ditolak',
            DetailPeminjaman::STATUS_DIBATALKAN => 'Dibatalkan',
            default => ucfirst(str_replace('_', ' ', $dbStatus)),
        };
    }

    protected function statusFromLabel(string $label): ?string
    {
        return match ($label) {
            'Mengajukan' => DetailPeminjaman::STATUS_MENGAJUKAN,
            'Sedang Dipinjam' => DetailPeminjaman::STATUS_DIPINJAM,
            'Terlambat' => DetailPeminjaman::STATUS_TERLAMBAT,
            'Dikembalikan' => DetailPeminjaman::STATUS_DIKEMBALIKAN,
            'Sudah Lunas' => DetailPeminjaman::STATUS_SUDAH_LUNAS,
            'Ditolak' => DetailPeminjaman::STATUS_DITOLAK,
            'Dibatalkan' => DetailPeminjaman::STATUS_DIBATALKAN,
            default => null,
        };
    }

    protected function isReturnedStatus(string $status): bool
    {
        return in_array($status, [
            DetailPeminjaman::STATUS_DIKEMBALIKAN,
            DetailPeminjaman::STATUS_SUDAH_LUNAS,
        ], true);
    }

    protected function lateDays(?Carbon $due, ?Carbon $returned = null): int
    {
        if (! $due) {
            return 0;
        }

        $end = $returned ?? now();
        if ($end->lte($due)) {
            return 0;
        }

        return (int) $due->startOfDay()->diffInDays($end->startOfDay());
    }

    protected function computeFineAmount(DetailPeminjaman $detail): float
    {
        if (in_array($detail->status_transaksi, [
            DetailPeminjaman::STATUS_MENGAJUKAN,
            DetailPeminjaman::STATUS_DITOLAK,
            DetailPeminjaman::STATUS_DIBATALKAN,
            DetailPeminjaman::STATUS_SUDAH_LUNAS,
        ], true)) {
            return 0.0;
        }

        $stored = (float) ($detail->subtotal ?? 0);
        if ($stored > 0 && in_array($detail->status_transaksi, [
            DetailPeminjaman::STATUS_DIKEMBALIKAN,
            DetailPeminjaman::STATUS_TERLAMBAT,
        ], true)) {
            return $stored;
        }

        $due = $this->detailDueAt($detail);
        $returned = $this->isReturnedStatus($detail->status_transaksi)
            ? Carbon::parse($detail->tgl_pengembalian)
            : null;
        $days = $this->lateDays($due, $returned);

        if ($days <= 0 && $detail->status_transaksi === DetailPeminjaman::STATUS_TERLAMBAT) {
            $days = $this->lateDays($due);
        }

        return max(0, $days) * DetailPeminjaman::FINE_PER_DAY;
    }

    protected function formatRp(float $amount): string
    {
        return 'Rp'.number_format($amount, 0, ',', '.');
    }

    protected function formatLateText(int $days): string
    {
        if ($days <= 0) {
            return '';
        }

        return $days.' hari';
    }

    protected function formatDueNote(DetailPeminjaman $detail, string $displayStatus): ?string
    {
        if (in_array($displayStatus, ['Mengajukan', 'Ditolak', 'Dibatalkan', 'Dikembalikan', 'Sudah Lunas', 'Terlambat'], true)) {
            return null;
        }

        $due = $this->detailDueAt($detail)?->startOfDay();
        if (! $due) {
            return null;
        }

        $diff = now()->startOfDay()->diffInDays($due, false);

        if ($diff > 0) {
            return $diff.' hari lagi';
        }

        if ($diff === 0) {
            return 'Jatuh tempo hari ini';
        }

        return null;
    }

    protected function loanUser(DetailPeminjaman $detail)
    {
        return $detail->peminjaman?->user;
    }

    protected function formatPeminjamanRow(DetailPeminjaman $detail, bool $includeMember = true): array
    {
        $user = $this->loanUser($detail);
        $buku = $detail->buku;
        $meta = $buku ? $this->parseDeskripsiMeta($buku->deskripsi_buku) : ['desc' => '', 'cover' => '', 'legacy_isbn' => '', 'legacy_rak' => ''];

        $displayStatus = $this->statusLabel($detail->status_transaksi);
        $borrow = $this->detailBorrowAt($detail);
        $due = $this->detailDueAt($detail);
        $returned = $this->isReturnedStatus($detail->status_transaksi) ? $due : null;

        $lateDays = $this->lateDays($due, $returned);
        if ($lateDays <= 0 && $detail->status_transaksi === DetailPeminjaman::STATUS_TERLAMBAT) {
            $lateDays = $this->lateDays($due);
        }

        $fine = $this->computeFineAmount($detail);
        $noFine = in_array($displayStatus, ['Mengajukan', 'Ditolak', 'Dibatalkan'], true);
        $submitted = $this->detailSubmittedAt($detail);

        $row = [
            'id' => (string) $detail->kode_detail,
            'bookCode' => $buku ? $this->bookCallNumber($buku) : '',
            'bookTitle' => $buku?->judul_buku ?? '',
            'bookAuthor' => $buku?->pengarang ?? '',
            'publisher' => $buku?->penerbit ?? '',
            'category' => $buku?->kategori_buku ?? '',
            'yearPublished' => $buku ? (string) $buku->tahun_terbit : '',
            'isbn' => $buku ? $this->normalizeIsbn($buku->isbn ?? null, $meta['legacy_isbn']) : '',
            'rack' => $buku ? $this->normalizeRak($buku->lokasi_rak ?? null, $meta['legacy_rak']) : '',
            'cover' => $buku ? $this->resolveCoverUrl($meta['cover'], (int) $buku->kode_buku) : '',
            'borrowIso' => $borrow?->format('Y-m-d') ?? '',
            'dueIso' => $due?->format('Y-m-d') ?? '',
            'borrowAt' => $borrow ? $borrow->translatedFormat('d M Y') : null,
            'dueAt' => $due ? $due->translatedFormat('d M Y') : null,
            'telat' => $lateDays > 0 ? $this->formatLateText($lateDays) : '',
            'denda' => $noFine ? '—' : $this->formatRp($fine),
            'status' => $displayStatus,
            'dueNote' => $this->formatDueNote($detail, $displayStatus),
            'submittedAt' => $submitted ? $submitted->format('d/m/Y H:i') : '',
        ];

        if ($includeMember) {
            $row['member'] = $user?->nama_pengguna ?? '';
            $row['nim'] = $user?->loginIdentifier() ?? '';
        }

        return $row;
    }

    protected function applyPeminjamanSearch(Builder $query, Request $request): Builder
    {
        $term = trim((string) $request->input('q', $request->input('search', '')));
        if ($term !== '') {
            $like = '%'.$term.'%';
            $query->where(function (Builder $q) use ($like) {
                $q->whereHas('buku', function (Builder $b) use ($like) {
                    $b->where('judul_buku', 'like', $like)
                        ->orWhere('pengarang', 'like', $like)
                        ->orWhere('penerbit', 'like', $like);
                })->orWhereHas('peminjaman.user', function (Builder $u) use ($like) {
                    $u->where('nama_pengguna', 'like', $like)
                        ->orWhere('nim', 'like', $like)
                        ->orWhere('nip', 'like', $like);
                });
            });
        }

        $category = trim((string) $request->input('category', ''));
        if ($category !== '' && strtolower($category) !== 'all') {
            $query->whereHas('buku', fn (Builder $b) => $b->where('kategori_buku', $category));
        }

        $status = trim((string) $request->input('status', ''));
        if ($status !== '' && strtolower($status) !== 'all') {
            $db = $this->statusFromLabel($status) ?? $status;
            $query->where('status_transaksi', $db);
        }

        return $query;
    }

    protected function parseDendaInput(?string $value): float
    {
        if ($value === null || $value === '' || $value === '—') {
            return 0.0;
        }

        $digits = preg_replace('/\D/', '', (string) $value);

        return $digits !== '' ? (float) $digits : 0.0;
    }

    protected function adjustBookStock(Buku $buku, int $delta): void
    {
        $next = max(0, (int) $buku->stok_buku + $delta);
        $buku->stok_buku = $next;
        $buku->save();
    }

    protected function hasActiveLoanForBook(int|string $userId, int|string $kodeBuku, int|string|null $exceptDetailId = null): bool
    {
        $query = DetailPeminjaman::query()
            ->where('kode_buku', (int) $kodeBuku)
            ->whereIn('status_transaksi', [
                DetailPeminjaman::STATUS_MENGAJUKAN,
                DetailPeminjaman::STATUS_DIPINJAM,
                DetailPeminjaman::STATUS_TERLAMBAT,
            ])
            ->whereHas('peminjaman', fn (Builder $q) => $q->where('id_user', (int) $userId));

        if ($exceptDetailId !== null) {
            $query->where('kode_detail', '!=', (int) $exceptDetailId);
        }

        return $query->exists();
    }

    protected function syncPeminjamanHeader(DetailPeminjaman $detail): void
    {
        $subtotal = (float) ($detail->subtotal ?? 0);
        $pinjam = $detail->tgl_Peminjaman ?? $detail->peminjaman?->tgl_Peminjaman ?? now();
        $kembali = $detail->tgl_pengembalian ?? $detail->peminjaman?->tgl_pengembalian ?? now();

        $sumSubtotal = (float) DB::table('detail_peminjaman')
            ->where('kode_peminjaman', $detail->kode_peminjaman)
            ->sum('subtotal');

        DB::table('peminjaman')
            ->where('kode_peminjaman', $detail->kode_peminjaman)
            ->update([
                'kode_buku' => $detail->kode_buku,
                'tgl_Peminjaman' => $pinjam,
                'tgl_pengembalian' => $kembali,
                'total_denda' => $sumSubtotal > 0 ? $sumSubtotal : $subtotal,
            ]);
    }
}
