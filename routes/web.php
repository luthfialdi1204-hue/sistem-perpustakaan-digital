<?php

require __DIR__ . '/auth.php';

use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\KelolaAnggotaAdminController;
use App\Http\Controllers\KelolaBukuAdminController;
use App\Http\Controllers\KelolaPeminjamanAdminController;
use App\Http\Controllers\Katalog_Buku;
use App\Http\Controllers\LaporanAdminController;
use App\Http\Controllers\MahasiswaPeminjamanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    $books = [];
    $totalBooks = 0;
    $totalUsers = 0;

    try {
        if (class_exists('App\Models\Buku') && \Illuminate\Support\Facades\Schema::hasTable('buku')) {
            $books = App\Models\Buku::all()->map(function ($b) {
                return (new class {
                    use \App\Http\Controllers\Concerns\FormatsBuku;
                    public function format($b) {
                        return $this->formatBukuRow($b);
                    }
                })->format($b);
            })->values()->toArray();
            $totalBooks = count($books);
        }

        if (class_exists('App\Models\User') && \Illuminate\Support\Facades\Schema::hasTable('user')) {
            $totalUsers = App\Models\User::where('role_user', App\Models\User::ROLE_MAHASISWA)->count();
        }
    } catch (\Throwable $e) {
        $books = [];
    }

    return view('Landing_Page', compact('books', 'totalBooks', 'totalUsers'));
})->name('landing');


Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/Beranda_Mahasiswa', [Katalog_Buku::class, 'beranda'])->name('mahasiswa.beranda');
    Route::get('/Katalog_Buku', [Katalog_Buku::class, 'index'])->name('mahasiswa.katalog');
    Route::get('/mahasiswa/buku', [Katalog_Buku::class, 'list'])->name('mahasiswa.buku.list');

    Route::get('/Riwayat_Peminjaman', [MahasiswaPeminjamanController::class, 'index'])->name('mahasiswa.riwayat');
    Route::get('/mahasiswa/peminjaman', [MahasiswaPeminjamanController::class, 'list'])->name('mahasiswa.peminjaman.list');
    Route::post('/mahasiswa/peminjaman', [MahasiswaPeminjamanController::class, 'store'])->name('mahasiswa.peminjaman.store');
    Route::patch('/mahasiswa/peminjaman/{id}/batal', [MahasiswaPeminjamanController::class, 'cancel'])->name('mahasiswa.peminjaman.cancel');

    Route::get('/Profil_Mahasiswa', [ProfilController::class, 'mahasiswa'])->name('mahasiswa.profil');
    Route::post('/profil/foto', [ProfilController::class, 'uploadFoto'])->name('mahasiswa.profil.foto');
    Route::delete('/profil/foto', [ProfilController::class, 'hapusFoto'])->name('mahasiswa.profil.foto.hapus');
});


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/Dashboard_Admin', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

    // Kelola Buku
    Route::get('/Kelola_Buku', [KelolaBukuAdminController::class, 'index'])->name('admin.buku.index');
    Route::get('/admin/buku', [KelolaBukuAdminController::class, 'list'])->name('admin.buku.list');
    Route::get('/admin/buku/{id}', [KelolaBukuAdminController::class, 'show'])->name('admin.buku.show');
    Route::post('/admin/buku', [KelolaBukuAdminController::class, 'store'])->name('admin.buku.store');
    Route::match(['put', 'patch'], '/admin/buku/{id}', [KelolaBukuAdminController::class, 'update'])->name('admin.buku.update');
    Route::delete('/admin/buku/{id}', [KelolaBukuAdminController::class, 'destroy'])->name('admin.buku.destroy');

    // Kelola Anggota
    Route::get('/Kelola_Anggota', [KelolaAnggotaAdminController::class, 'index'])->name('admin.anggota.index');
    Route::get('/admin/anggota', [KelolaAnggotaAdminController::class, 'list'])->name('admin.anggota.list');
    Route::get('/admin/anggota/{id}', [KelolaAnggotaAdminController::class, 'show'])->name('admin.anggota.show');
    Route::post('/admin/anggota', [KelolaAnggotaAdminController::class, 'store'])->name('admin.anggota.store');
    Route::match(['put', 'patch'], '/admin/anggota/{id}', [KelolaAnggotaAdminController::class, 'update'])->name('admin.anggota.update');
    Route::delete('/admin/anggota/{id}', [KelolaAnggotaAdminController::class, 'destroy'])->name('admin.anggota.destroy');

    // Kelola Peminjaman
    Route::get('/Kelola_Peminjaman', [KelolaPeminjamanAdminController::class, 'index'])->name('admin.peminjaman');
    Route::get('/admin/peminjaman', [KelolaPeminjamanAdminController::class, 'list'])->name('admin.peminjaman.list');
    Route::post('/admin/peminjaman/{id}/setujui', [KelolaPeminjamanAdminController::class, 'approve'])->name('admin.peminjaman.approve');
    Route::post('/admin/peminjaman/{id}/tolak', [KelolaPeminjamanAdminController::class, 'reject'])->name('admin.peminjaman.reject');
    Route::patch('/admin/peminjaman/{id}', [KelolaPeminjamanAdminController::class, 'update'])->name('admin.peminjaman.update');

    // Laporan
    Route::get('/Laporan_Admin', [LaporanAdminController::class, 'index'])->name('admin.laporan');
    Route::get('/admin/laporan', [LaporanAdminController::class, 'list'])->name('admin.laporan.list');

    // Profil Admin
    Route::get('/Profil_Admin', [ProfilController::class, 'admin'])->name('admin.profil');
    Route::post('/admin/profil/foto', [ProfilController::class, 'uploadFoto'])->name('admin.profil.foto');
    Route::delete('/admin/profil/foto', [ProfilController::class, 'hapusFoto'])->name('admin.profil.foto.hapus');
});

Route::middleware('web')->group(function () {
 
    // Tampilkan form verifikasi OTP
    Route::get('/otp/verify', [OtpController::class, 'showVerificationForm'])
        ->name('otp.verify.form');
 
    // Kirim OTP ke email
    Route::post('/otp/send', [OtpController::class, 'send'])
        ->name('otp.send')
        ->middleware('throttle:5,1'); // max 5 request per menit
 
    // Verifikasi kode OTP
    Route::post('/otp/verify', [OtpController::class, 'verify'])
        ->name('otp.verify')
        ->middleware('throttle:10,1'); // max 10 attempt per menit
 
    // Kirim ulang OTP (AJAX)
    Route::post('/otp/resend', [OtpController::class, 'resend'])
        ->name('otp.resend')
        ->middleware('throttle:3,1'); // max 3 resend per menit
 
});