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
      return view('Admin.kelola_peminjaman');
   });

Route::get('/Laporan_Admin', function () {
      return view('Admin.laporan_admin');
   });

Route::get('/Profil_Admin', function () {
      return view('Admin.Profil_Admin');
   });

Route::get('/Product',[ProductController::class,'tampilkan'] ); 

Route::get('/app', function () {
return view('app');
});
