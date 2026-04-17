<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BarangController;


Route::get('/', function () {
      return view ('welcome');
   });

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

Route::view('/Halaman_Masuk', 'Halaman_Masuk');

Route::view('/Katalog_Buku', 'Katalog_Buku');

Route::view('/Beranda_Mahasiswa', 'Beranda_Mahasiswa');  

Route::get('/app', function () {
return view('app');
});