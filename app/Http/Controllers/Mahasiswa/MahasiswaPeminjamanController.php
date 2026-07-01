<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\FormatsBuku;
use App\Http\Controllers\Concerns\FormatsPeminjaman;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MahasiswaPeminjamanController extends Controller
{
    use FormatsBuku;
    use FormatsPeminjaman;

    public function index(Request $request)
    {
        $this->syncOverdueStatuses();

        $userId = (int) Auth::id();

        $perPage = 10;
        $baseQuery = $this->scopeForUser($this->peminjamanBaseQuery(), $userId);

        $paginated = $this->applyPeminjamanSearch($baseQuery, $request)
            ->orderByDesc('kode_detail')
            ->paginate($perPage)
            ->appends($request->query());

        $rows = $paginated->getCollection()
            ->map(fn (DetailPeminjaman $d) => $this->formatPeminjamanRow($d, false))
            ->values();

        $allRows = $this->applyPeminjamanSearch($baseQuery->clone(), $request)
            ->orderByDesc('kode_detail')
            ->get()
            ->map(fn (DetailPeminjaman $d) => $this->formatPeminjamanRow($d, false))
            ->values();

        $inactive = ['Ditolak', 'Dibatalkan'];
        $activeTotal = $allRows->reject(fn (array $r) => in_array($r['status'] ?? '', $inactive, true))->count();
        $selesai = $allRows->filter(fn (array $r) => in_array($r['status'] ?? '', ['Dikembalikan', 'Sudah Lunas'], true))->count();
        $terlambat = $allRows->where('status', 'Terlambat')->count();
        $totalDenda = $allRows->reduce(function (int $sum, array $row) {
            $status = (string) ($row['status'] ?? '');
            $raw = (string) ($row['denda'] ?? '');
            if ($raw === '' || $raw === '—') {
                return $sum;
            }
            if ($status !== 'Terlambat' && $status !== 'Dikembalikan') {
                return $sum;
            }
            $n = (int) preg_replace('/\D+/', '', $raw);
            return $sum + max(0, $n);
        }, 0);

        return view('Mahasiswa.Riwayat_Peminjaman', [
            'rows' => $rows,
            'paginator' => $paginated,
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
            ],
            'stats' => [
                'active_total' => $activeTotal,
                'selesai' => $selesai,
                'terlambat' => $terlambat,
                'total_denda' => $totalDenda,
            ],
            'filters' => [
                'q' => (string) $request->input('q', $request->input('search', '')),
                'status' => (string) $request->input('status', 'all'),
            ],
        ]);
    }

    public function list(Request $request): JsonResponse
    {
        $this->syncOverdueStatuses();

        $userId = (int) Auth::id();

        $perPage = (int) $request->input('per_page', 10);
        if ($perPage < 1) {
            $perPage = 10;
        }
        if ($perPage > 50) {
            $perPage = 50;
        }

        $baseQuery = $this->scopeForUser($this->peminjamanBaseQuery(), $userId);

        $paginated = $this->applyPeminjamanSearch($baseQuery, $request)
            ->orderByDesc('kode_detail')
            ->paginate($perPage);

        $rows = $paginated->getCollection()
            ->map(fn (DetailPeminjaman $d) => $this->formatPeminjamanRow($d, false))
            ->values();

        $allRows = $baseQuery
            ->orderByDesc('kode_detail')
            ->get()
            ->map(fn (DetailPeminjaman $d) => $this->formatPeminjamanRow($d, false))
            ->values();

        $inactive = ['Ditolak', 'Dibatalkan'];
        $activeTotal = $allRows->reject(fn (array $r) => in_array($r['status'] ?? '', $inactive, true))->count();
        $selesai = $allRows->filter(fn (array $r) => in_array($r['status'] ?? '', ['Dikembalikan', 'Sudah Lunas'], true))->count();
        $terlambat = $allRows->where('status', 'Terlambat')->count();
        $totalDenda = $allRows->reduce(function (int $sum, array $row) {
            $status = (string) ($row['status'] ?? '');
            $raw = (string) ($row['denda'] ?? '');
            if ($raw === '' || $raw === '—') {
                return $sum;
            }
            if ($status !== 'Terlambat' && $status !== 'Dikembalikan') {
                return $sum;
            }
            $n = (int) preg_replace('/\D+/', '', $raw);
            return $sum + max(0, $n);
        }, 0);

        return response()->json([
            'data' => $rows,
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
            ],
            'stats' => [
                'active_total' => $activeTotal,
                'selesai' => $selesai,
                'terlambat' => $terlambat,
                'total_denda' => $totalDenda,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $wantsJson = $request->expectsJson();

        $request->validate([
            'kode_buku' => ['required', 'integer', 'exists:buku,kode_buku'],
        ]);

        $userId = (int) Auth::id();
        $kodeBuku = (int) $request->input('kode_buku');

        $buku = Buku::query()->findOrFail($kodeBuku);
        if ((int) $buku->stok_buku < 1) {
            $msg = 'Stok buku habis.';
            return $wantsJson
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        if ($this->hasActiveLoanForBook($userId, $kodeBuku)) {
            $msg = 'Anda masih memiliki pengajuan atau pinjaman aktif untuk buku ini.';
            return $wantsJson
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        $hasLateBook = DetailPeminjaman::query()
            ->where('status_transaksi', DetailPeminjaman::STATUS_TERLAMBAT)
            ->whereHas('peminjaman', fn ($q) => $q->where('id_user', $userId))
            ->exists();

        if ($hasLateBook) {
            $msg = 'Anda memiliki buku yang terlambat dikembalikan. Harap kembalikan buku dan lunasi denda Anda terlebih dahulu.';
            return $wantsJson
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        $hasUnpaidFine = DetailPeminjaman::query()
            ->where('status_transaksi', DetailPeminjaman::STATUS_DIKEMBALIKAN)
            ->where('subtotal', '>', 0)
            ->whereHas('peminjaman', fn ($q) => $q->where('id_user', $userId))
            ->exists();

        if ($hasUnpaidFine) {
            $msg = 'Anda memiliki denda yang belum dibayar. Harap bayar denda Anda terlebih dahulu.';
            return $wantsJson
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        $activeCount = DetailPeminjaman::query()
            ->whereIn('status_transaksi', [
                DetailPeminjaman::STATUS_MENGAJUKAN,
                DetailPeminjaman::STATUS_DIPINJAM,
                DetailPeminjaman::STATUS_TERLAMBAT,
            ])
            ->whereHas('peminjaman', fn ($q) => $q->where('id_user', $userId))
            ->count();

        if ($activeCount >= 3) {
            $msg = 'Batas maksimal peminjaman adalah 3 buku. Harap kembalikan buku yang sedang dipinjam terlebih dahulu.';
            return $wantsJson
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        $detail = DB::transaction(function () use ($userId, $kodeBuku, $request) {
            $now        = now()->startOfDay();
            $maxDueDays = 14;
            $defaultDays = DetailPeminjaman::LOAN_DAYS;

            // Tanggal pinjam dari request (default: hari ini)
            $tglPinjam = $now->copy();
            if ($request->filled('tgl_peminjaman')) {
                $parsed = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('tgl_peminjaman'))->startOfDay();
                // Izinkan hari ini atau setelahnya (mendatang)
                if ($parsed->greaterThanOrEqualTo($now)) {
                    $tglPinjam = $parsed;
                }
            }

            // Tanggal kembali dari request (default: pinjam + 7 hari)
            $tglKembali = $tglPinjam->copy()->addDays($defaultDays);
            if ($request->filled('tgl_pengembalian')) {
                $parsedDue = \Carbon\Carbon::createFromFormat('Y-m-d', $request->input('tgl_pengembalian'))->startOfDay();
                $minDue = $tglPinjam->copy()->addDay();
                $maxDue = $tglPinjam->copy()->addDays($maxDueDays);
                if ($parsedDue->between($minDue, $maxDue)) {
                    $tglKembali = $parsedDue;
                }
            }

            $header = Peminjaman::query()->create([
                'id_user'          => $userId,
                'kode_buku'        => $kodeBuku,
                'tgl_Peminjaman'   => $tglPinjam,
                'tgl_pengembalian' => $tglKembali,
                'total_denda'      => 0,
            ]);

            return DetailPeminjaman::query()->create([
                'kode_peminjaman'  => $header->kode_peminjaman,
                'kode_buku'        => $kodeBuku,
                'status_transaksi' => DetailPeminjaman::STATUS_MENGAJUKAN,
                'tgl_Peminjaman'   => $tglPinjam,
                'tgl_pengembalian' => $tglKembali,
                'jumlah_buku'      => 1,
                'subtotal'         => 0,
            ]);
        });

        $detail->load(['peminjaman.user', 'buku']);

        $msg = 'Pengajuan peminjaman berhasil dikirim.';
        return $wantsJson
            ? response()->json([
                'message' => $msg,
                'data' => $this->formatPeminjamanRow($detail, false),
            ], 201)
            : back()->with('success', $msg);
    }

    public function cancel(Request $request, int|string $id)
    {
        $wantsJson = $request->expectsJson();

        $detail = DetailPeminjaman::query()
            ->with('peminjaman')
            ->whereHas('peminjaman', fn ($q) => $q->where('id_user', (int) Auth::id()))
            ->findOrFail((int) $id);

        if ($detail->status_transaksi !== DetailPeminjaman::STATUS_MENGAJUKAN) {
            $msg = 'Hanya pengajuan yang masih menunggu yang dapat dibatalkan.';
            return $wantsJson
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        $detail->status_transaksi = DetailPeminjaman::STATUS_DIBATALKAN;
        $detail->subtotal = 0;
        $detail->save();
        $this->syncPeminjamanHeader($detail);

        $msg = 'Pengajuan peminjaman berhasil dibatalkan.';
        return $wantsJson
            ? response()->json([
                'message' => $msg,
                'data' => $this->formatPeminjamanRow($detail->fresh(['peminjaman.user', 'buku']), false),
            ])
            : back()->with('success', $msg);
    }
}
