<?php

use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Halaman_Masuk;
use App\Http\Controllers\KelolaAnggotaAdminController;
use App\Http\Controllers\KelolaBukuAdminController;
use App\Http\Controllers\KelolaPeminjamanAdminController;
use App\Http\Controllers\Katalog_Buku;
use App\Http\Controllers\LaporanAdminController;
use App\Http\Controllers\MahasiswaPeminjamanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Landing_Page');
})->name('landing');

Route::get('/Halaman_Masuk', [Halaman_Masuk::class, 'showLoginForm'])->name('login.form');
Route::post('/login/mahasiswa', [Halaman_Masuk::class, 'loginMahasiswa'])->name('login.mahasiswa');
Route::post('/login/admin', [Halaman_Masuk::class, 'loginAdmin'])->name('login.admin');

Route::middleware('guest')->group(function () {
    Route::get('/lupa-password', [ForgotPasswordController::class, 'showForm'])->name('password.forgot');
    Route::get('/lupa-password/verifikasi-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.forgot.otp');
    Route::get('/lupa-password/reset', [ForgotPasswordController::class, 'showResetForm'])->name('password.forgot.reset');
    Route::post('/lupa-password/kirim-otp', [ForgotPasswordController::class, 'sendOtp'])->name('password.otp.send');
    Route::post('/lupa-password/verifikasi-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.verify');
    Route::post('/lupa-password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');
});

Route::post('/logout', [Halaman_Masuk::class, 'logout'])->middleware('auth')->name('logout');
  
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/Beranda_Mahasiswa', [Katalog_Buku::class, 'beranda'])->name('mahasiswa.beranda');
    Route::get('/Katalog_Buku', [Katalog_Buku::class, 'index'])->name('mahasiswa.katalog');
    Route::get('/mahasiswa/buku', [Katalog_Buku::class, 'list'])->name('mahasiswa.buku.list');

    Route::get('/Riwayat_Peminjaman', [MahasiswaPeminjamanController::class, 'index'])->name('mahasiswa.riwayat');
    Route::get('/mahasiswa/peminjaman', [MahasiswaPeminjamanController::class, 'list'])->name('mahasiswa.peminjaman.list');
    Route::post('/mahasiswa/peminjaman', [MahasiswaPeminjamanController::class, 'store'])->name('mahasiswa.peminjaman.store');
    Route::patch('/mahasiswa/peminjaman/{id}/batal', [MahasiswaPeminjamanController::class, 'cancel'])->name('mahasiswa.peminjaman.cancel');
    Route::view('/Profil_Mahasiswa', 'Mahasiswa.Profil_Mahasiswa')->name('mahasiswa.profil');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/Dashboard_Admin', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/Kelola_Buku', [KelolaBukuAdminController::class, 'index'])->name('admin.buku.index');
    Route::get('/admin/buku', [KelolaBukuAdminController::class, 'list'])->name('admin.buku.list');
    Route::get('/admin/buku/{id}', [KelolaBukuAdminController::class, 'show'])->name('admin.buku.show');
    Route::post('/admin/buku', [KelolaBukuAdminController::class, 'store'])->name('admin.buku.store');
    Route::match(['put', 'patch'], '/admin/buku/{id}', [KelolaBukuAdminController::class, 'update'])->name('admin.buku.update');
    Route::delete('/admin/buku/{id}', [KelolaBukuAdminController::class, 'destroy'])->name('admin.buku.destroy');

    Route::get('/Kelola_Anggota', [KelolaAnggotaAdminController::class, 'index'])->name('admin.anggota.index');
    Route::get('/admin/anggota', [KelolaAnggotaAdminController::class, 'list'])->name('admin.anggota.list');
    Route::get('/admin/anggota/{id}', [KelolaAnggotaAdminController::class, 'show'])->name('admin.anggota.show');
    Route::post('/admin/anggota', [KelolaAnggotaAdminController::class, 'store'])->name('admin.anggota.store');
    Route::match(['put', 'patch'], '/admin/anggota/{id}', [KelolaAnggotaAdminController::class, 'update'])->name('admin.anggota.update');
    Route::delete('/admin/anggota/{id}', [KelolaAnggotaAdminController::class, 'destroy'])->name('admin.anggota.destroy');

    Route::get('/Kelola_Peminjaman', [KelolaPeminjamanAdminController::class, 'index'])->name('admin.peminjaman');
    Route::get('/admin/peminjaman', [KelolaPeminjamanAdminController::class, 'list'])->name('admin.peminjaman.list');
    Route::post('/admin/peminjaman/{id}/setujui', [KelolaPeminjamanAdminController::class, 'approve'])->name('admin.peminjaman.approve');
    Route::post('/admin/peminjaman/{id}/tolak', [KelolaPeminjamanAdminController::class, 'reject'])->name('admin.peminjaman.reject');
    Route::patch('/admin/peminjaman/{id}', [KelolaPeminjamanAdminController::class, 'update'])->name('admin.peminjaman.update');
    Route::get('/Laporan_Admin', [LaporanAdminController::class, 'index'])->name('admin.laporan');
    Route::get('/admin/laporan', [LaporanAdminController::class, 'list'])->name('admin.laporan.list');
    Route::view('/Profil_Admin', 'Admin.Profil_Admin')->name('admin.profil');
});
