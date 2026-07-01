<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;

class Katalog_Buku extends Controller
{
    // Menampilkan halaman beranda mahasiswa dengan 8 buku acak
    public function beranda()
    {
        $bukuAcak = Buku::inRandomOrder()->limit(8)->get();

        $rows = [];
        foreach ($bukuAcak as $b) {
            $rows[] = $this->formatBuku($b);
        }

        return view('Mahasiswa.Beranda_Mahasiswa', compact('rows'));
    }

    // Menampilkan halaman katalog utama untuk mahasiswa
    public function index(Request $request)
    {
        $perPage = 8;
        $query = Buku::query();

        // Cari buku jika ada kata kunci pencarian
        $search = $request->input('q', $request->input('search'));
        if ($search != '') {
            $query->where(function($q) use ($search) {
                $q->where('judul_buku', 'like', '%' . $search . '%')
                  ->orWhere('pengarang', 'like', '%' . $search . '%')
                  ->orWhere('penerbit', 'like', '%' . $search . '%')
                  ->orWhere('kategori_buku', 'like', '%' . $search . '%')
                  ->orWhere('nomor_panggil', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan kategori
        $category = $request->input('category');
        if ($category != '' && $category != 'all') {
            $query->where('kategori_buku', $category);
        }

        $paginated = $query->orderBy('kode_buku', 'desc')
            ->paginate($perPage)
            ->appends($request->query());

        $rows = [];
        foreach ($paginated as $b) {
            $rows[] = $this->formatBuku($b);
        }

        return view('Mahasiswa.katalog_Buku', [
            'rows' => $rows,
            'categories' => Buku::kategoriList(),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
            ],
            'filters' => [
                'q' => (string) $request->input('q', $request->input('search', '')),
                'category' => (string) $request->input('category', 'all'),
            ],
        ]);
    }

    // Mengambil daftar buku untuk kebutuhan load-more atau pencarian AJAX
    public function list(Request $request)
    {
        $query = Buku::query();

        // Cari buku jika ada kata kunci pencarian
        $search = $request->input('q', $request->input('search'));
        if ($search != '') {
            $query->where(function($q) use ($search) {
                $q->where('judul_buku', 'like', '%' . $search . '%')
                  ->orWhere('pengarang', 'like', '%' . $search . '%')
                  ->orWhere('penerbit', 'like', '%' . $search . '%')
                  ->orWhere('kategori_buku', 'like', '%' . $search . '%')
                  ->orWhere('nomor_panggil', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan kategori
        $category = $request->input('category');
        if ($category != '' && $category != 'all') {
            $query->where('kategori_buku', $category);
        }

        // Tentukan pengurutan
        if ($request->boolean('random')) {
            $query->inRandomOrder();
        } else {
            $query->orderBy('kode_buku', 'desc');
        }

        $limit = $request->input('limit');
        if ($limit > 0) {
            $query->limit($limit);
        }

        $perPage = $request->input('per_page');
        if ($limit <= 0 && $perPage > 0) {
            if ($perPage < 1) {
                $perPage = 8;
            }
            if ($perPage > 50) {
                $perPage = 50;
            }

            $paginated = $query->paginate($perPage);

            $rows = [];
            foreach ($paginated as $b) {
                $rows[] = $this->formatBuku($b);
            }

            return response()->json([
                'data' => $rows,
                'categories' => Buku::kategoriList(),
                'meta' => [
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                    'total' => $paginated->total(),
                    'per_page' => $paginated->perPage(),
                ],
            ]);
        }

        $bukuList = $query->get();
        $rows = [];
        foreach ($bukuList as $b) {
            $rows[] = $this->formatBuku($b);
        }

        return response()->json([
            'data' => $rows,
            'categories' => Buku::kategoriList(),
        ]);
    }

    // Helper: format data buku agar seragam untuk JSON response/view
    private function formatBuku($buku)
    {
        $meta = json_decode($buku->deskripsi_buku, true);
        $desc = isset($meta['desc']) ? $meta['desc'] : $buku->deskripsi_buku;
        $cover = isset($meta['cover']) ? $meta['cover'] : '';

        // Tentukan URL gambar cover
        if ($cover != '' && is_file(public_path($cover))) {
            $imgUrl = asset($cover);
        } else {
            // Gambar default jika cover kosong
            $files = ['Cover buku 1.jpg', 'Cover buku 2.jpg', 'Cover buku 3.jpg'];
            $file = $files[abs($buku->kode_buku) % 3];
            $imgUrl = asset('images/' . $file);
        }

        $stok = (int) $buku->stok_buku;

        return [
            'id' => (int) $buku->kode_buku,
            'code' => $buku->nomor_panggil ? trim($buku->nomor_panggil) : (string) $buku->kode_buku,
            'nomor_panggil' => $buku->nomor_panggil ? trim($buku->nomor_panggil) : (string) $buku->kode_buku,
            'title' => $buku->judul_buku,
            'author' => $buku->pengarang,
            'publisher' => $buku->penerbit,
            'category' => $buku->kategori_buku,
            'year' => (string) $buku->tahun_terbit,
            'stock' => $stok,
            'isbn' => $buku->isbn ? $buku->isbn : '-',
            'rack' => $buku->lokasi_rak ? $buku->lokasi_rak : '-',
            'description' => $desc ? $desc : '-',
            'img' => $imgUrl,
            'available' => $stok > 0,
            'status' => $stok > 0 ? 'available' : 'borrowed',
            'stockLabel' => $stok > 0 ? $stok . ' tersedia' : 'Dipinjam semua',
        ];
    }
}
