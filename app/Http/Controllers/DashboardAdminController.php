<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FormatsBuku;
use App\Http\Controllers\Concerns\FormatsPeminjaman;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    use FormatsBuku;
    use FormatsPeminjaman;

    public function index()
    {
        $this->syncOverdueStatuses();

        // 1. Total semua buku
        $totalBuku = Buku::query()->count();

        // 2. Jumlah anggota (mahasiswa, bukan admin)
        $totalAnggota = User::query()->where('role_user', User::ROLE_MAHASISWA)->count();

        // 3. Buku yang sedang dipinjam saat ini
        $bukuDipinjam = DetailPeminjaman::whereIn('status_transaksi', [
            DetailPeminjaman::STATUS_DIPINJAM,
            DetailPeminjaman::STATUS_TERLAMBAT,
        ])->count();

        // 4. Total seluruh transaksi peminjaman
        $jumlahTransaksi = DB::table('detail_peminjaman')->count();

        // 5. Transaksi hari ini
        $transaksiHariIni = DB::table('detail_peminjaman')
            ->where(function ($q) {
                $q->whereDate('tgl_Peminjaman', today())
                    ->orWhereDate('tgl_pengembalian', today());
            })
            ->count();

        // 6. Jumlah buku per kategori
        $bukuPerKategori = Buku::select('kategori_buku', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('kategori_buku')
            ->orderByDesc('jumlah')
            ->get()
            ->pluck('jumlah', 'kategori_buku')
            ->toArray();

        $stats = [
            'total_buku'         => $totalBuku,
            'total_anggota'      => $totalAnggota,
            'buku_dipinjam'      => $bukuDipinjam,
            'jumlah_transaksi'   => $jumlahTransaksi,
            'transaksi_hari_ini' => $transaksiHariIni,
            'buku_per_kategori'  => $bukuPerKategori,
        ];

        $recentLoans = DetailPeminjaman::query()
            ->with(['peminjaman.user', 'buku'])
            ->orderByDesc('kode_detail')
            ->limit(7)
            ->get()
            ->map(function (DetailPeminjaman $detail) {
                $row = $this->formatPeminjamanRow($detail);
                $user = $detail->peminjaman?->user;
                $row['initials'] = $user ? $user->initials() : '—';
                return $row;
            });

        return view('Admin.Dashboard_Admin', compact('stats', 'recentLoans'));
    }
}
