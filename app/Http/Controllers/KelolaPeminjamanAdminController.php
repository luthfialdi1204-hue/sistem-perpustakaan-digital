<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FormatsBuku;
use App\Http\Controllers\Concerns\FormatsPeminjaman;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelolaPeminjamanAdminController extends Controller
{
    use FormatsBuku;
    use FormatsPeminjaman;

    public function index()
    {
        return view('Admin.kelola_peminjaman_Admin', [
            'categories' => $this->bukuCategories(),
        ]);
    }

    public function list(Request $request): JsonResponse
    {
        $this->syncOverdueStatuses();

        $rows = $this->applyPeminjamanSearch($this->peminjamanBaseQuery(), $request)
            ->orderByDesc('kode_detail')
            ->get()
            ->map(fn (DetailPeminjaman $d) => $this->formatPeminjamanRow($d));

        return response()->json(['data' => $rows]);
    }

    public function approve(int|string $id): JsonResponse
    {
        $detail = $this->findDetail($id);
        if ($detail->status_transaksi !== DetailPeminjaman::STATUS_MENGAJUKAN) {
            return response()->json(['message' => 'Hanya pengajuan yang dapat disetujui.'], 422);
        }

        $buku = $detail->buku;
        if (! $buku || (int) $buku->stok_buku < 1) {
            return response()->json(['message' => 'Stok buku tidak tersedia.'], 422);
        }

        DB::transaction(function () use ($detail, $buku) {
            $pinjam = now();
            $jatuhTempo = $pinjam->copy()->addDays(DetailPeminjaman::LOAN_DAYS);

            $detail->status_transaksi = DetailPeminjaman::STATUS_DIPINJAM;
            $detail->tgl_Peminjaman = $pinjam;
            $detail->tgl_pengembalian = $jatuhTempo;
            $detail->subtotal = 0;
            $detail->save();

            $this->adjustBookStock($buku, -1);
            $this->syncPeminjamanHeader($detail->fresh());
        });

        return response()->json([
            'message' => 'Pengajuan disetujui.',
            'data' => $this->formatPeminjamanRow($detail->fresh(['peminjaman.user', 'buku'])),
        ]);
    }

    public function reject(int|string $id): JsonResponse
    {
        $detail = $this->findDetail($id);
        if ($detail->status_transaksi !== DetailPeminjaman::STATUS_MENGAJUKAN) {
            return response()->json(['message' => 'Hanya pengajuan yang dapat ditolak.'], 422);
        }

        $detail->status_transaksi = DetailPeminjaman::STATUS_DITOLAK;
        $detail->subtotal = 0;
        $detail->save();
        $this->syncPeminjamanHeader($detail);

        return response()->json([
            'message' => 'Pengajuan ditolak.',
            'data' => $this->formatPeminjamanRow($detail->fresh(['peminjaman.user', 'buku'])),
        ]);
    }

    public function update(Request $request, int|string $id): JsonResponse
    {
        $detail = $this->findDetail($id);

        $request->validate([
            'status' => ['required', 'string'],
            'borrow_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'denda' => ['nullable', 'string'],
        ]);

        $newStatus = $this->statusFromLabel($request->input('status'))
            ?? $request->input('status');

        if (! in_array($newStatus, [
            DetailPeminjaman::STATUS_DIKEMBALIKAN,
            DetailPeminjaman::STATUS_SUDAH_LUNAS,
            DetailPeminjaman::STATUS_TERLAMBAT,
            DetailPeminjaman::STATUS_DIPINJAM,
        ], true)) {
            return response()->json(['message' => 'Status tidak valid untuk pembaruan.'], 422);
        }

        $wasActive = in_array($detail->status_transaksi, [
            DetailPeminjaman::STATUS_DIPINJAM,
            DetailPeminjaman::STATUS_TERLAMBAT,
        ], true);

        DB::transaction(function () use ($request, $detail, $newStatus, $wasActive) {
            if ($request->filled('borrow_date')) {
                $detail->tgl_Peminjaman = Carbon::parse($request->input('borrow_date'))->startOfDay();
            }
            if ($request->filled('due_date') && ! $this->isReturnedStatus($newStatus)) {
                $detail->tgl_pengembalian = Carbon::parse($request->input('due_date'))->startOfDay();
            }

            if ($newStatus === DetailPeminjaman::STATUS_SUDAH_LUNAS) {
                $detail->status_transaksi = DetailPeminjaman::STATUS_SUDAH_LUNAS;
                $detail->subtotal = 0;
                $detail->tgl_pengembalian = now();
            } elseif ($newStatus === DetailPeminjaman::STATUS_DIKEMBALIKAN) {
                $detail->status_transaksi = DetailPeminjaman::STATUS_DIKEMBALIKAN;
                $detail->subtotal = $this->parseDendaInput($request->input('denda'));
                $detail->tgl_pengembalian = now();
            } else {
                $detail->status_transaksi = $newStatus;
                if ($request->has('denda')) {
                    $detail->subtotal = $this->parseDendaInput($request->input('denda'));
                }
            }

            $detail->save();
            $this->syncPeminjamanHeader($detail);

            $nowReturned = $this->isReturnedStatus($detail->status_transaksi);

            if ($wasActive && $nowReturned && $detail->buku) {
                $this->adjustBookStock($detail->buku, 1);
            }
        });

        return response()->json([
            'message' => 'Data peminjaman diperbarui.',
            'data' => $this->formatPeminjamanRow($detail->fresh(['peminjaman.user', 'buku'])),
        ]);
    }

    private function findDetail(int|string $id): DetailPeminjaman
    {
        return DetailPeminjaman::query()
            ->with(['peminjaman.user', 'buku'])
            ->findOrFail((int) $id);
    }
}
