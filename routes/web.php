<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
      return view ('Landing_Page');
   });

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


