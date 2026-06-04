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
        $stats = [
            'total_buku' => Buku::query()->count(),
            'total_anggota' => User::query()->where('role_user', User::ROLE_MAHASISWA)->count(),
            'buku_dipinjam' => DB::table('detail_peminjaman')
                ->whereIn('status_transaksi', ['dipinjam', 'terlambat', 'mengajukan'])
                ->count(),
            'transaksi_hari_ini' => DB::table('detail_peminjaman')
                ->where(function ($q) {
                    $q->whereDate('tgl_Peminjaman', today())
                        ->orWhereDate('tgl_pengembalian', today());
                })
                ->count(),
        ];

        $this->syncOverdueStatuses();

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
