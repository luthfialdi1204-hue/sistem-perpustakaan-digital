<?php

use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Halaman_Masuk;
use App\Http\Controllers\KelolaAnggotaAdminController;
use App\Http\Controllers\KelolaBukuAdminController;
use App\Http\Controllers\Katalog_Buku;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Landing_Page');
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/Halaman_Masuk', [Halaman_Masuk::class, 'showLoginForm'])->name('login.form');
    Route::post('/login/mahasiswa', [Halaman_Masuk::class, 'loginMahasiswa'])->name('login.mahasiswa');
    Route::post('/login/admin', [Halaman_Masuk::class, 'loginAdmin'])->name('login.admin');

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

    Route::view('/Riwayat_Peminjaman', 'Mahasiswa.Riwayat_Peminjaman')->name('mahasiswa.riwayat');
    Route::view('/Profil_Mahasiswa', 'Mahasiswa.Profil_Mahasiswa')->name('mahasiswa.profil');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/Dashboard_Admin', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

    Route::get('/Kelola_Buku', [KelolaBukuAdminController::class, 'index'])->name('admin.buku.index');
    Route::get('/admin/buku', [KelolaBukuAdminController::class, 'list'])->name('admin.buku.list');
    Route::post('/admin/buku', [KelolaBukuAdminController::class, 'store'])->name('admin.buku.store');
    Route::match(['put', 'patch'], '/admin/buku/{id}', [KelolaBukuAdminController::class, 'update'])->name('admin.buku.update');
    Route::delete('/admin/buku/{id}', [KelolaBukuAdminController::class, 'destroy'])->name('admin.buku.destroy');

    Route::get('/Kelola_Anggota', [KelolaAnggotaAdminController::class, 'index'])->name('admin.anggota.index');
    Route::get('/admin/anggota', [KelolaAnggotaAdminController::class, 'list'])->name('admin.anggota.list');
    Route::post('/admin/anggota', [KelolaAnggotaAdminController::class, 'store'])->name('admin.anggota.store');
    Route::match(['put', 'patch'], '/admin/anggota/{id}', [KelolaAnggotaAdminController::class, 'update'])->name('admin.anggota.update');
    Route::delete('/admin/anggota/{id}', [KelolaAnggotaAdminController::class, 'destroy'])->name('admin.anggota.destroy');

    Route::view('/Kelola_Peminjaman', 'Admin.kelola_peminjaman_Admin')->name('admin.peminjaman');
    Route::view('/Laporan_Admin', 'Admin.laporan_admin')->name('admin.laporan');
    Route::view('/Profil_Admin', 'Admin.Profil_Admin')->name('admin.profil');
});
