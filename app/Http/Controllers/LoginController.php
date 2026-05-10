<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('Halaman_Masuk');
    }

    public function loginMahasiswa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => ['required', 'digits_between:6,20'],
            'password' => ['required', 'string', 'min:3'],
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.digits_between' => 'NIM harus berupa angka dengan panjang 6-20 digit.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'mahasiswa')->withInput();
        }

        return redirect('/Beranda_Mahasiswa');
    }

    public function loginAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => ['required', 'digits_between:6,20'],
            'password' => ['required', 'string', 'min:3'],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.digits_between' => 'NIP harus berupa angka dengan panjang 6-20 digit.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator, 'admin')->withInput();
        }

        return redirect('/Dashboard_Admin');
    }
}