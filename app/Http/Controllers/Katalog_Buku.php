<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FormatsBuku;
use App\Models\Buku;
use Illuminate\Http\Request;

class Katalog_Buku extends Controller
{
    use FormatsBuku;

    public function beranda()
    {
        return view('Mahasiswa.Beranda_Mahasiswa');
    }

    public function index()
    {
        return view('Mahasiswa.katalog_Buku');
    }

    public function list(Request $request)
    {
        $books = Buku::query()->orderByDesc('kode_buku')->get();

        if ($request->boolean('random')) {
            $books = $books->shuffle();
        }

        $limit = $request->integer('limit');
        if ($limit > 0) {
            $books = $books->take($limit);
        }

        $rows = $books->map(fn (Buku $b) => $this->formatBukuRow($b))->values();

        return response()->json([
            'data' => $rows,
            'categories' => $this->bukuCategories(),
        ]);
    }
}
