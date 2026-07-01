<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelolaPeminjamanAdminController extends Controller
{
    // Menampilkan halaman kelola peminjaman beserta list peminjaman & kategori
    public function index(Request $request)
    {
        // 1. Sinkronisasi status denda terlambat
        $this->syncOverdueStatuses();

        // 2. Query dasar detail peminjaman
        $query = DetailPeminjaman::with(['peminjaman.user', 'buku']);

        // Filter pencarian berdasarkan nama anggota, judul buku, atau nomor panggil
        $search = $request->input('q', $request->input('search'));
        if ($search != '') {
            $like = '%' . $search . '%';
            $query->where(function ($q) use ($like) {
                $q->whereHas('peminjaman.user', function ($qu) use ($like) {
                    $qu->where('nama_pengguna', 'like', $like);
                })
                ->orWhereHas('buku', function ($qb) use ($like) {
                    $qb->where('judul_buku', 'like', $like)
                       ->orWhere('nomor_panggil', 'like', $like);
                });
            });
        }

        // Filter berdasarkan kategori buku
        $category = $request->input('category');
        if ($category != '' && $category != 'all') {
            $query->whereHas('buku', function ($qb) use ($category) {
                $qb->where('kategori_buku', $category);
            });
        }

        // Ambil data peminjaman terbaru dengan pagination
        $loans = $query->orderByDesc('kode_detail')->paginate(10)->withQueryString();

        // Format data agar mudah ditampilkan langsung di Blade
        foreach ($loans as $loan) {
            $meta = json_decode($loan->buku->deskripsi_buku ?? '', true);
            $cover = isset($meta['cover']) ? $meta['cover'] : '';

            // Cover URL
            if ($cover != '' && is_file(public_path($cover))) {
                $loan->book_cover = asset($cover);
            } else {
                $loan->book_cover = asset('images/Cover buku 1.jpg');
            }

            // Status label
            $dbStatus = $loan->status_transaksi;
            $loan->status_label = match ($dbStatus) {
                DetailPeminjaman::STATUS_MENGAJUKAN => 'Mengajukan',
                DetailPeminjaman::STATUS_DIPINJAM => 'Sedang Dipinjam',
                DetailPeminjaman::STATUS_TERLAMBAT => 'Terlambat',
                DetailPeminjaman::STATUS_DIKEMBALIKAN => 'Dikembalikan',
                DetailPeminjaman::STATUS_SUDAH_LUNAS => 'Sudah Lunas',
                DetailPeminjaman::STATUS_DITOLAK => 'Ditolak',
                DetailPeminjaman::STATUS_DIBATALKAN => 'Dibatalkan',
                default => ucfirst(str_replace('_', ' ', $dbStatus)),
            };

            // Hitung telat & denda
            $loan->telat_display = '—';
            $loan->denda_display = '—';

            if ($loan->tgl_pengembalian) {
                $due = Carbon::parse($loan->tgl_pengembalian)->startOfDay();
                $today = now()->startOfDay();
                if ($today->gt($due) && in_array($dbStatus, [DetailPeminjaman::STATUS_DIPINJAM, DetailPeminjaman::STATUS_TERLAMBAT])) {
                    $diff = $due->diffInDays($today);
                    $loan->telat_display = $diff . ' Hari';
                    $loan->denda_display = 'Rp' . number_format($diff * 2000, 0, ',', '.');
                }
            }

            if ($dbStatus == DetailPeminjaman::STATUS_DIKEMBALIKAN || $dbStatus == DetailPeminjaman::STATUS_SUDAH_LUNAS) {
                $loan->denda_display = 'Rp' . number_format((int) $loan->subtotal, 0, ',', '.');
                if ($loan->tgl_pengembalian && $loan->tgl_Peminjaman) {
                    $due = Carbon::parse($loan->tgl_pengembalian)->startOfDay();
                    // Gunakan data denda historis
                    $loan->telat_display = $loan->subtotal > 0 ? ceil($loan->subtotal / 2000) . ' Hari' : '—';
                }
            }
        }

        return view('Admin.kelola_peminjaman_Admin', [
            'categories' => Buku::kategoriList(),
            'loans' => $loans
        ]);
    }

    // Menyetujui pengajuan peminjaman
    public function approve($id)
    {
        $detail = DetailPeminjaman::findOrFail((int) $id);
        if ($detail->status_transaksi !== DetailPeminjaman::STATUS_MENGAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang dapat disetujui.');
        }

        $buku = $detail->buku;
        if (! $buku || (int) $buku->stok_buku < 1) {
            return back()->with('error', 'Stok buku tidak tersedia.');
        }

        DB::transaction(function () use ($detail, $buku) {
            $pinjam = $detail->tgl_Peminjaman ? Carbon::parse($detail->tgl_Peminjaman)->startOfDay() : now()->startOfDay();
            
            if ($pinjam->isBefore(now()->startOfDay())) {
                $pinjam = now()->startOfDay();
            }

            $jatuhTempo = $detail->tgl_pengembalian ? Carbon::parse($detail->tgl_pengembalian)->startOfDay() : $pinjam->copy()->addDays(DetailPeminjaman::LOAN_DAYS);
            
            if ($jatuhTempo->lte($pinjam)) {
                $jatuhTempo = $pinjam->copy()->addDays(DetailPeminjaman::LOAN_DAYS);
            }

            $detail->status_transaksi = DetailPeminjaman::STATUS_DIPINJAM;
            $detail->tgl_Peminjaman = $pinjam;
            $detail->tgl_pengembalian = $jatuhTempo;
            $detail->subtotal = 0;
            $detail->save();

            // Kurangi stok buku
            $buku->stok_buku = (int) $buku->stok_buku - 1;
            $buku->save();

            $this->syncPeminjamanHeader($detail);
        });

        return back()->with('success', 'Pengajuan peminjaman disetujui.');
    }

    // Menolak pengajuan peminjaman
    public function reject($id)
    {
        $detail = DetailPeminjaman::findOrFail((int) $id);
        if ($detail->status_transaksi !== DetailPeminjaman::STATUS_MENGAJUKAN) {
            return back()->with('error', 'Hanya pengajuan yang dapat ditolak.');
        }

        $detail->status_transaksi = DetailPeminjaman::STATUS_DITOLAK;
        $detail->subtotal = 0;
        $detail->save();

        $this->syncPeminjamanHeader($detail);

        return back()->with('success', 'Pengajuan peminjaman ditolak.');
    }

    // Mengupdate data transaksi peminjaman
    public function update(Request $request, $id)
    {
        $detail = DetailPeminjaman::findOrFail((int) $id);

        $request->validate([
            'status' => ['required', 'string'],
            'borrow_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'denda' => ['nullable', 'string'],
        ]);

        $statusMap = [
            'Mengajukan' => DetailPeminjaman::STATUS_MENGAJUKAN,
            'Sedang Dipinjam' => DetailPeminjaman::STATUS_DIPINJAM,
            'Terlambat' => DetailPeminjaman::STATUS_TERLAMBAT,
            'Dikembalikan' => DetailPeminjaman::STATUS_DIKEMBALIKAN,
            'Sudah Lunas' => DetailPeminjaman::STATUS_SUDAH_LUNAS,
            'Ditolak' => DetailPeminjaman::STATUS_DITOLAK,
            'Dibatalkan' => DetailPeminjaman::STATUS_DIBATALKAN,
        ];

        $newStatus = isset($statusMap[$request->input('status')]) ? $statusMap[$request->input('status')] : $request->input('status');

        if (! in_array($newStatus, [
            DetailPeminjaman::STATUS_DIKEMBALIKAN,
            DetailPeminjaman::STATUS_SUDAH_LUNAS,
            DetailPeminjaman::STATUS_TERLAMBAT,
            DetailPeminjaman::STATUS_DIPINJAM,
        ], true)) {
            return back()->with('error', 'Status tidak valid untuk pembaruan.');
        }

        $wasActive = in_array($detail->status_transaksi, [
            DetailPeminjaman::STATUS_DIPINJAM,
            DetailPeminjaman::STATUS_TERLAMBAT,
        ], true);

        DB::transaction(function () use ($request, $detail, $newStatus, $wasActive) {
            if ($request->filled('borrow_date')) {
                $detail->tgl_Peminjaman = Carbon::parse($request->input('borrow_date'))->startOfDay();
            }
            if ($request->filled('due_date') && ! in_array($newStatus, [DetailPeminjaman::STATUS_DIKEMBALIKAN, DetailPeminjaman::STATUS_SUDAH_LUNAS])) {
                $detail->tgl_pengembalian = Carbon::parse($request->input('due_date'))->startOfDay();
            }

            if ($newStatus === DetailPeminjaman::STATUS_SUDAH_LUNAS) {
                $detail->status_transaksi = DetailPeminjaman::STATUS_SUDAH_LUNAS;
                $detail->subtotal = 0;
                $detail->tgl_pengembalian = now();
            } elseif ($newStatus === DetailPeminjaman::STATUS_DIKEMBALIKAN) {
                // Hitung denda otomatis
                $dendaAmount = 0;
                if ($detail->tgl_pengembalian) {
                    $due = Carbon::parse($detail->tgl_pengembalian)->startOfDay();
                    $today = now()->startOfDay();
                    if ($today->gt($due)) {
                        $dendaAmount = $due->diffInDays($today) * 2000;
                    }
                }

                $detail->status_transaksi = DetailPeminjaman::STATUS_DIKEMBALIKAN;

                // Jika ada input denda manual
                if ($request->filled('denda')) {
                    $dendaAmount = (int) preg_replace('/\D+/', '', $request->input('denda'));
                }
                $detail->subtotal = $dendaAmount;
                $detail->tgl_pengembalian = now();
            } else {
                $detail->status_transaksi = $newStatus;
                if ($request->filled('denda')) {
                    $detail->subtotal = (int) preg_replace('/\D+/', '', $request->input('denda'));
                }
            }

            $detail->save();

            $this->syncPeminjamanHeader($detail);

            $nowReturned = in_array($detail->status_transaksi, [
                DetailPeminjaman::STATUS_DIKEMBALIKAN,
                DetailPeminjaman::STATUS_SUDAH_LUNAS,
            ], true);

            // Jika dikembalikan, kembalikan stok buku
            if ($wasActive && $nowReturned && $detail->buku) {
                $detail->buku->stok_buku = (int) $detail->buku->stok_buku + 1;
                $detail->buku->save();
            }
        });

        return back()->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    // Sinkronisasi status header peminjaman
    private function syncPeminjamanHeader(DetailPeminjaman $detail)
    {
        $headerId = $detail->kode_peminjaman;
        if (! $headerId) {
            return;
        }

        $allDetails = DetailPeminjaman::where('kode_peminjaman', $headerId)->get();

        $anyMengajukan = $allDetails->contains('status_transaksi', DetailPeminjaman::STATUS_MENGAJUKAN);
        $anyActive = $allDetails->contains('status_transaksi', DetailPeminjaman::STATUS_DIPINJAM)
            || $allDetails->contains('status_transaksi', DetailPeminjaman::STATUS_TERLAMBAT);
        
        $allReturned = $allDetails->every(fn ($d) => in_array($d->status_transaksi, [
            DetailPeminjaman::STATUS_DIKEMBALIKAN,
            DetailPeminjaman::STATUS_SUDAH_LUNAS,
            DetailPeminjaman::STATUS_DITOLAK,
            DetailPeminjaman::STATUS_DIBATALKAN,
        ], true));

        $newStatus = 'Mengajukan';
        if ($allReturned) {
            $newStatus = 'Selesai';
        } elseif ($anyActive) {
            $newStatus = 'Dipinjam';
        } elseif ($anyMengajukan) {
            $newStatus = 'Mengajukan';
        }

        $totalDenda = $allDetails->sum('subtotal');

        DB::table('peminjaman')
            ->where('kode_peminjaman', $headerId)
            ->update([
                'status_peminjaman' => $newStatus,
                'total_denda' => $totalDenda,
            ]);
    }

    // Sinkronisasi keterlambatan denda secara otomatis
    private function syncOverdueStatuses()
    {
        DetailPeminjaman::query()
            ->where('status_transaksi', DetailPeminjaman::STATUS_DIPINJAM)
            ->where('tgl_pengembalian', '<', now()->startOfDay())
            ->update(['status_transaksi' => DetailPeminjaman::STATUS_TERLAMBAT]);
    }
}
