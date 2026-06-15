<?php

use App\Http\Controllers\Halaman_Masuk;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Route;

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
