<?php

use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
      return view ('Landing_Page');
   });

Route::get('/Halaman_Masuk', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login/mahasiswa', [LoginController::class, 'loginMahasiswa'])->name('login.mahasiswa');
Route::post('/login/admin', [LoginController::class, 'loginAdmin'])->name('login.admin');
Route::get('/lupa-password', [ForgotPasswordController::class, 'showForm'])->name('password.forgot');
Route::get('/lupa-password/verifikasi-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.forgot.otp');
Route::get('/lupa-password/reset', [ForgotPasswordController::class, 'showResetForm'])->name('password.forgot.reset');
Route::post('/lupa-password/kirim-otp', [ForgotPasswordController::class, 'sendOtp'])->name('password.otp.send');
Route::post('/lupa-password/verifikasi-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.verify');
Route::post('/lupa-password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('password.reset');

Route::get('/Katalog_Buku', function () {
      return view ('Mahasiswa.Katalog_Buku');
   });

Route::get('/Beranda_Mahasiswa', function () {
      return view ('Mahasiswa.Beranda_Mahasiswa');
   });

Route::get('/Riwayat_Peminjaman', function () {
      return view ('Mahasiswa.Riwayat_Peminjaman');
   });

Route::get('/Profil_Mahasiswa', function () {
      return view ('Mahasiswa.Profil_Mahasiswa');
   });

Route::get('/Dashboard_Admin', function () {
      return view ('Admin.Dashboard_Admin');
   });

Route::get('/Kelola_Buku', function () {
      return view ('Admin.Kelola_Buku_admin');
   });

Route::get('/Kelola_Anggota', function () {
      return view ('Admin.kelola_anggota_admin');
   });

Route::get('/Kelola_Peminjaman', function () {
      return view('Admin.kelola_peminjaman_Admin');
   });

Route::get('/Laporan_Admin', function () {
      return view('Admin.laporan_admin');
   });

Route::get('/Profil_Admin', function () {
      return view('Admin.Profil_Admin');
   });


