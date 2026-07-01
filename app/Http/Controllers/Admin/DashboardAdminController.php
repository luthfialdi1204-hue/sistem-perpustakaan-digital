<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\DetailPeminjaman;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    // Menampilkan halaman dashboard utama admin
    public function index()
    {
        // 1. Sinkronisasi status denda terlambat jika ada
        $this->syncOverdueStatuses();

        // 2. Hitung statistik dasar
        $totalBuku = Buku::count();
        $totalAnggota = User::where('role_user', User::ROLE_MAHASISWA)->count();
        $bukuDipinjam = DetailPeminjaman::whereIn('status_transaksi', [
            DetailPeminjaman::STATUS_DIPINJAM,
            DetailPeminjaman::STATUS_TERLAMBAT,
        ])->count();
        $jumlahTransaksi = DB::table('detail_peminjaman')->count();

        // Transaksi hari ini (peminjaman atau pengembalian hari ini)
        $transaksiHariIni = DB::table('detail_peminjaman')
            ->where(function ($q) {
                $q->whereDate('tgl_Peminjaman', today())
                  ->orWhereDate('tgl_pengembalian', today());
            })
            ->count();

        // Jumlah buku per kategori
        $bukuPerKategori = Buku::select('kategori_buku', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('kategori_buku')
            ->orderBy('jumlah', 'desc')
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

        // 3. Ambil 7 transaksi terbaru untuk ditampilkan di dashboard
        $recentLoansRaw = DetailPeminjaman::with(['peminjaman.user', 'buku'])
            ->orderByDesc('kode_detail')
            ->limit(7)
            ->get();

        $recentLoans = [];
        foreach ($recentLoansRaw as $detail) {
            $user = $detail->peminjaman ? $detail->peminjaman->user : null;
            $buku = $detail->buku;

            // Cover URL
            $meta = json_decode($buku->deskripsi_buku ?? '', true);
            $cover = isset($meta['cover']) ? $meta['cover'] : '';
            $coverUrl = '';
            if ($cover != '' && is_file(public_path($cover))) {
                $coverUrl = asset($cover);
            } else {
                $coverUrl = asset('images/Cover buku 1.jpg');
            }

            // Inisial Nama User
            $initials = '—';
            if ($user && $user->nama_pengguna) {
                $parts = preg_split('/\s+/', trim($user->nama_pengguna)) ?: [];
                $initials = '';
                if (count($parts) > 0) {
                    $initials .= mb_strtoupper(mb_substr($parts[0], 0, 1));
                }
                if (count($parts) > 1) {
                    $initials .= mb_strtoupper(mb_substr($parts[1], 0, 1));
                }
                if ($initials == '') {
                    $initials = 'MH';
                }
            }

            // Status label
            $dbStatus = $detail->status_transaksi;
            $statusLabel = match ($dbStatus) {
                DetailPeminjaman::STATUS_MENGAJUKAN => 'Mengajukan',
                DetailPeminjaman::STATUS_DIPINJAM => 'Sedang Dipinjam',
                DetailPeminjaman::STATUS_TERLAMBAT => 'Terlambat',
                DetailPeminjaman::STATUS_DIKEMBALIKAN => 'Dikembalikan',
                DetailPeminjaman::STATUS_SUDAH_LUNAS => 'Sudah Lunas',
                DetailPeminjaman::STATUS_DITOLAK => 'Ditolak',
                DetailPeminjaman::STATUS_DIBATALKAN => 'Dibatalkan',
                default => ucfirst(str_replace('_', ' ', $dbStatus)),
            };

            $recentLoans[] = [
                'member' => $user ? $user->nama_pengguna : '—',
                'nim' => $user ? $user->nim : '—',
                'cover' => $coverUrl,
                'bookTitle' => $buku ? $buku->judul_buku : '—',
                'bookAuthor' => $buku ? $buku->pengarang : '—',
                'borrowIso' => $detail->tgl_Peminjaman ? $detail->tgl_Peminjaman->toDateString() : '',
                'dueIso' => $detail->tgl_pengembalian ? $detail->tgl_pengembalian->toDateString() : '',
                'initials' => $initials,
                'status' => $statusLabel,
            ];
        }

        return view('Admin.Dashboard_Admin', compact('stats', 'recentLoans'));
    }

    // Sinkronisasi status denda terlambat otomatis
    private function syncOverdueStatuses()
    {
        DetailPeminjaman::query()
            ->where('status_transaksi', DetailPeminjaman::STATUS_DIPINJAM)
            ->where('tgl_pengembalian', '<', now()->startOfDay())
            ->update(['status_transaksi' => DetailPeminjaman::STATUS_TERLAMBAT]);
    }
}
