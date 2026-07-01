<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Http\Controllers\Concerns\FormatsBuku;

class LandingController extends Controller
{
    use FormatsBuku;

    public function index()
    {
        // Ambil semua data buku dan format menggunakan formatBukuRow dari trait FormatsBuku
        $books = Buku::all()->map(function ($b) {
            return $this->formatBukuRow($b);
        })->values()->toArray();

        // Hitung total buku
        $totalBooks = count($books);

        // Hitung total pengguna mahasiswa
        $totalUsers = User::where('role_user', User::ROLE_MAHASISWA)->count();

        return view('Landing_Page', compact('books', 'totalBooks', 'totalUsers'));
    }
}
