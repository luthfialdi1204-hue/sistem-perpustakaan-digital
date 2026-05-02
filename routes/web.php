<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProductController;

Route::prefix('admin')->group(function () {
   Route::get('/dashboard', function () {
      return 'Admin Dashboard';
   });

   Route::get('/users', function () {
      return 'Admin Users';
   });
});

Route::get('/login', function (){
   return view('login');
});

Route::get('/dashboard', function (){
   return view('dashboard');
});


Route::get('/Landing_Page', function () {
      return view ('Landing_Page');
   });

Route::get('/barang',[BarangController::class,'tampilkan'] ); 

Route::get('/Halaman_Masuk', function () {
      return view ('Halaman_Masuk');
   });

Route::get('/Katalog_Buku', function () {
      return view ('Mahasiswa.Katalog_Buku');
   });

Route::get('/Beranda_Mahasiswa', function () {
      return view ('Mahasiswa.Beranda_Mahasiswa');
   });

Route::get('/Riwayat_Peminjaman', function () {
      return view ('Mahasiswa.Riwayat_Peminjaman');
   });

Route::get('/Profil_Pengguna', function () {
      return view ('Mahasiswa.Profil_Pengguna');
   });

Route::view('/Dashboard_Admin', 'Dashboard_Admin');

Route::view('/Profil_Admin', 'Profil_Admin');

Route::get('/Product',[ProductController::class,'tampilkan'] ); 

Route::get('/app', function () {
return view('app');
});
