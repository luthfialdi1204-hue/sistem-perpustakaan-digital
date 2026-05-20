<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_buku' => Buku::query()->count(),
            'total_anggota' => User::query()->where('role_user', User::ROLE_MAHASISWA)->count(),
            'buku_dipinjam' => DB::table('peminjaman')
                ->whereIn('status_transaksi', ['dipinjam', 'terlambat', 'mengajukan'])
                ->count(),
            'transaksi_hari_ini' => DB::table('peminjaman')
                ->where(function ($q) {
                    $q->whereDate('tgl_Peminjaman', today())
                        ->orWhereDate('tgl_pengembalian', today());
                })
                ->count(),
        ];

        return view('Admin.Dashboard_Admin', compact('stats'));
    }
}
