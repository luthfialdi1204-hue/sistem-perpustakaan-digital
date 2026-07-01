<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelolaBukuAdminController extends Controller
{
    // Menampilkan halaman utama kelola buku beserta list buku & statistik
    public function index(Request $request)
    {
        // 1. Ambil data statistik buku
        $totalBuku = Buku::count();
        $totalStok = Buku::sum('stok_buku');
        $bukuStokHabis = Buku::where('stok_buku', '<=', 0)->count();

        // Mengelompokkan jumlah buku per kategori
        $bukuPerKategori = Buku::select('kategori_buku', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('kategori_buku')
            ->orderBy('jumlah', 'desc')
            ->get()
            ->pluck('jumlah', 'kategori_buku')
            ->toArray();

        $jumlahKategori = count($bukuPerKategori);

        $stats = [
            'total_buku' => $totalBuku,
            'total_stok' => (int) $totalStok,
            'buku_stok_habis' => $bukuStokHabis,
            'buku_per_kategori' => $bukuPerKategori,
            'jumlah_kategori' => $jumlahKategori,
        ];

        // 2. Ambil data buku dengan pencarian & filter kategori
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

        // Filter berdasarkan kategori jika ada
        $category = $request->input('category');
        if ($category != '' && $category != 'all') {
            $query->where('kategori_buku', $category);
        }

        // Ambil data terurut dari yang terbaru dengan pagination
        $books = $query->orderBy('kode_buku', 'desc')->paginate(8)->withQueryString();

        // Format data cover & deskripsi agar mudah diakses di view
        foreach ($books as $buku) {
            $meta = json_decode($buku->deskripsi_buku, true);
            $buku->parsed_description = isset($meta['desc']) ? $meta['desc'] : $buku->deskripsi_buku;
            $cover = isset($meta['cover']) ? $meta['cover'] : '';

            // Tentukan URL gambar cover
            if ($cover != '' && is_file(public_path($cover))) {
                $buku->cover_url = asset($cover);
            } else {
                // Gambar default
                $files = ['Cover buku 1.jpg', 'Cover buku 2.jpg', 'Cover buku 3.jpg'];
                $file = $files[abs($buku->kode_buku) % 3];
                $buku->cover_url = asset('images/' . $file);
            }
        }

        return view('Admin.kelola_buku_admin', compact('stats', 'books'));
    }

    // Menyimpan buku baru ke database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:buku,nomor_panggil',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'publisher' => 'required|string|max:100',
            'category' => 'required|string',
            'year' => 'required|integer|min:1|max:9999',
            'stock' => 'required|numeric|min:0',
            'isbn' => 'required|string|max:50',
            'rack' => 'required|string|max:50',
            'description' => 'required|string',
            'cover' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ], $this->messages());

        $buku = Buku::create([
            'nomor_panggil' => trim($validated['code']),
            'judul_buku' => $validated['title'],
            'pengarang' => $validated['author'],
            'penerbit' => $validated['publisher'],
            'kategori_buku' => $validated['category'],
            'tahun_terbit' => (int) $validated['year'],
            'stok_buku' => (int) $validated['stock'],
            'isbn' => $validated['isbn'] ? trim($validated['isbn']) : '-',
            'lokasi_rak' => $validated['rack'] ? trim($validated['rack']) : '-',
            'deskripsi_buku' => json_encode(['desc' => trim($validated['description']), 'cover' => '']),
        ]);

        if ($request->hasFile('cover')) {
            $coverPath = $this->uploadCoverFile($request->file('cover'), $buku->kode_buku);
            $buku->update([
                'deskripsi_buku' => json_encode(['desc' => trim($validated['description']), 'cover' => $coverPath]),
            ]);
        }

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    // Mengupdate data buku
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:buku,nomor_panggil,' . $id . ',kode_buku',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'publisher' => 'required|string|max:100',
            'category' => 'required|string',
            'year' => 'required|integer|min:1|max:9999',
            'stock' => 'required|numeric|min:0',
            'isbn' => 'required|string|max:50',
            'rack' => 'required|string|max:50',
            'description' => 'required|string',
            'cover' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ], $this->messages());

        $buku = Buku::where('kode_buku', $id)->firstOrFail();

        // Mengambil data cover lama
        $meta = json_decode($buku->deskripsi_buku, true);
        $coverPath = isset($meta['cover']) ? $meta['cover'] : '';

        // Jika mengupload cover baru
        if ($request->hasFile('cover')) {
            $coverPath = $this->uploadCoverFile($request->file('cover'), $buku->kode_buku);
        }

        $buku->update([
            'nomor_panggil' => trim($validated['code']),
            'judul_buku' => $validated['title'],
            'pengarang' => $validated['author'],
            'penerbit' => $validated['publisher'],
            'kategori_buku' => $validated['category'],
            'tahun_terbit' => (int) $validated['year'],
            'stok_buku' => (int) $validated['stock'],
            'isbn' => $validated['isbn'] ? trim($validated['isbn']) : '-',
            'lokasi_rak' => $validated['rack'] ? trim($validated['rack']) : '-',
            'deskripsi_buku' => json_encode(['desc' => trim($validated['description']), 'cover' => $coverPath]),
        ]);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    // Menghapus data buku
    public function destroy($id)
    {
        $buku = Buku::where('kode_buku', $id)->firstOrFail();

        // Hapus file cover dari public folder jika ada
        $meta = json_decode($buku->deskripsi_buku, true);
        $cover = isset($meta['cover']) ? $meta['cover'] : '';
        if ($cover != '' && is_file(public_path($cover))) {
            unlink(public_path($cover));
        }

        $buku->delete();

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus.');
    }

    // Helper: Upload file cover ke folder public/images/covers
    private function uploadCoverFile($file, $kodeBuku)
    {
        $folder = public_path('images/covers');
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $ekstensi = strtolower($file->getClientOriginalExtension());
        if ($ekstensi != 'png' && $ekstensi != 'jpg' && $ekstensi != 'jpeg') {
            $ekstensi = 'png';
        }

        $namaFile = 'buku_' . $kodeBuku . '_' . time() . '.' . $ekstensi;
        $file->move($folder, $namaFile);

        return 'images/covers/' . $namaFile;
    }

    // Pesan error validasi custom bahasa Indonesia
    private function messages()
    {
        return [
            'code.required' => 'Nomor panggil wajib diisi.',
            'code.unique' => 'Nomor panggil sudah digunakan buku lain.',
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Pengarang wajib diisi.',
            'publisher.required' => 'Penerbit wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'year.required' => 'Tahun terbit wajib diisi.',
            'stock.required' => 'Jumlah buku wajib diisi.',
            'stock.min' => 'Jumlah buku minimal 0.',
            'isbn.required' => 'ISBN wajib diisi.',
            'rack.required' => 'Lokasi rak wajib diisi.',
            'description.required' => 'Deskripsi buku wajib diisi.',
            'cover.required' => 'Cover buku wajib diunggah.',
            'cover.image' => 'Cover harus berupa gambar.',
            'cover.mimes' => 'Format cover harus berupa PNG atau JPG.',
            'cover.max' => 'Ukuran cover maksimal 2 MB.',
        ];
    }
}
