<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FormatsBuku;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelolaBukuAdminController extends Controller
{
    use FormatsBuku;

    private function rules(int|string|null $exceptKodeBuku = null): array
    {
        $codeRule = Rule::unique('buku', 'nomor_panggil');
        if ($exceptKodeBuku !== null) {
            $codeRule = $codeRule->ignore((int) $exceptKodeBuku, 'kode_buku');
        }

        return [
            'code' => ['required', 'string', 'max:50', $codeRule],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:100'],
            'publisher' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', Rule::in(Buku::kategoriList())],
            'year' => ['required', 'numeric', 'min:1900', 'max:2100'],
            'stock' => ['required', 'numeric', 'min:0'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'rack' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'cover' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function index()
    {
        return view('Admin.kelola_buku_admin');
    }

    public function list(Request $request)
    {
        $perPage = (int) $request->input('per_page', 6);
        if ($perPage < 1) {
            $perPage = 6;
        }
        if ($perPage > 50) {
            $perPage = 50;
        }

        $paginated = $this->applyBukuSearch(Buku::query(), $request)
            ->orderByDesc('kode_buku')
            ->paginate($perPage);

        $rows = $paginated->getCollection()
            ->map(fn (Buku $b) => $this->formatBukuRow($b))
            ->values();

        return response()->json([
            'data' => $rows,
            'categories' => $this->bukuCategories(),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'total' => $paginated->total(),
                'per_page' => $paginated->perPage(),
            ],
        ]);
    }

    public function show(int|string $id)
    {
        $buku = Buku::query()->where('kode_buku', (int) $id)->first();
        if (! $buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        return response()->json([
            'data' => $this->formatBukuRow($buku),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules(), $this->messages());

        $buku = Buku::query()->create([
            'nomor_panggil' => trim($validated['code']),               
            'judul_buku' => $validated['title'],                       
            'pengarang' => $validated['author'],                       
            'penerbit' => $validated['publisher'],                     
            'kategori_buku' => $validated['category'],                   
            'tahun_terbit' => (int) $validated['year'],                  
            'stok_buku' => (int) $validated['stock'],                    
            'isbn' => $this->normalizeIsbn($validated['isbn'] ?? null),
            'lokasi_rak' => $this->normalizeRak($validated['rack'] ?? null),
            'deskripsi_buku' => $this->encodeDeskripsiMeta($validated['description'] ?? null, ''),
        ]);

        if ($request->hasFile('cover')) {
            $coverPath = $this->saveCover($request->file('cover'), (int) $buku->kode_buku);

            $buku->update([
                'deskripsi_buku' => $this->encodeDeskripsiMeta($validated['description'] ?? null, $coverPath),
            ]);
        }

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data' => $this->formatBukuRow($buku->fresh()),
        ], 201);
    }

    public function update(Request $request, int|string $id)
    {
        $kodeBuku = (int) $id;
        $validated = $request->validate($this->rules($kodeBuku), $this->messages());

        $buku = Buku::query()->where('kode_buku', $kodeBuku)->first();
        if (! $buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $meta = $this->parseDeskripsiMeta($buku->deskripsi_buku);
        $coverPath = $meta['cover'];
        if ($request->hasFile('cover')) {
            $coverPath = $this->saveCover($request->file('cover'), $kodeBuku);
        }

        $buku->update([
            'nomor_panggil' => trim($validated['code']),               // <- kode buku / nomor panggil
            'judul_buku' => $validated['title'],
            'pengarang' => $validated['author'],
            'penerbit' => $validated['publisher'],
            'kategori_buku' => $validated['category'],
            'tahun_terbit' => (int) $validated['year'],
            'stok_buku' => (int) $validated['stock'],
            'isbn' => $this->normalizeIsbn($validated['isbn'] ?? null),
            'lokasi_rak' => $this->normalizeRak($validated['rack'] ?? null),
            'deskripsi_buku' => $this->encodeDeskripsiMeta($validated['description'] ?? null, $coverPath),
        ]);

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data' => $this->formatBukuRow($buku->fresh()),
        ]);
    }

    public function destroy(int|string $id)
    {
        $buku = Buku::query()->where('kode_buku', (int) $id)->first();
        if (! $buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $meta = $this->parseDeskripsiMeta($buku->deskripsi_buku);
        if ($meta['cover'] !== '' && is_file(public_path($meta['cover']))) {
            @unlink(public_path($meta['cover']));
        }

        $buku->delete();

        return response()->json(['message' => 'Buku berhasil dihapus']);
    }

    private function messages(): array
    {
        return [
            'code.required' => 'Nomor panggil wajib diisi.',
            'code.unique' => 'Nomor panggil sudah digunakan buku lain.',
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Pengarang wajib diisi.',
            'publisher.required' => 'Penerbit wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'category.in' => 'Kategori tidak valid.',
            'year.required' => 'Tahun terbit wajib diisi.',
            'year.min' => 'Tahun terbit tidak valid.',
            'stock.required' => 'Jumlah buku wajib diisi.',
            'stock.min' => 'Jumlah buku minimal 0.',
            'cover.image' => 'Cover harus berupa gambar.',
            'cover.max' => 'Ukuran cover maksimal 2 MB.',
        ];
    }
}
