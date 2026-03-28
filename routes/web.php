<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ListBarangController;
use App\Http\Controllers\LoginController;

Route::prefix('admin')->group(function () {
   Route::get('/dashboard', function () {
      return 'Admin Dashboard';
   });

   Route::get('/users', function () {
      return 'Admin Users';
   });
});

Route::get('/listbarang/{id}/{nama}', function($id, $nama){ 
  return view('list_barang', compact('id', 'nama'));
});

Route::get('/login', function (){
   return view('login');
});

Route::get('/dashboard', function (){
   return view('dashboard');
});

Route::get('/list_barang', function (){
   return view('list_barang');
});