<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FormatsBuku;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelolaBukuAdminController extends Controller
{
    use FormatsBuku;

    private function formatRow(Buku $b): array
    {
        return $this->formatBukuRow($b);
    }

    private function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:100'],
            'publisher' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:50'],
            'year' => ['required', 'numeric', 'min:1900', 'max:2100'],
            'stock' => ['required', 'numeric', 'min:0'],
            'isbn' => ['nullable', 'string', 'max:50'],
            'rack' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'cover' => ['nullable', 'image', 'max:2048'],
        ];
    }

    private function bookAttributes(Request $request, string $coverPath = ''): array
    {
        return [
            'kode_registrasi' => strtoupper(trim((string) $request->input('code'))),
            'judul_buku' => $request->input('title'),
            'pengarang' => $request->input('author'),
            'penerbit' => $request->input('publisher'),
            'kategori_buku' => $request->input('category'),
            'tahun_terbit' => (int) $request->input('year'),
            'stok_buku' => (int) $request->input('stock'),
            'isbn' => $this->normalizeIsbn($request->input('isbn')),
            'lokasi_rak' => $this->normalizeRak($request->input('rack')),
            'deskripsi_buku' => $this->encodeDeskripsiMeta($request->input('description'), $coverPath),
        ];
    }

    public function index()
    {
        return view('Admin.kelola_buku_admin');
    }

    public function list()
    {
        $rows = Buku::query()
            ->orderByDesc('kode_buku')
            ->get()
            ->map(fn (Buku $b) => $this->formatRow($b));

        return response()->json([
            'data' => $rows,
            'categories' => $this->bukuCategories(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        $validator->after(function ($v) use ($request) {
            $code = strtoupper(trim((string) $request->input('code')));
            if ($code !== '' && Buku::query()->where('kode_registrasi', $code)->exists()) {
                $v->errors()->add('code', 'Kode buku sudah digunakan.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $buku = Buku::query()->create($this->bookAttributes($request));

        if ($request->hasFile('cover')) {
            $coverPath = $this->saveCover($request->file('cover'), (int) $buku->kode_buku);
            $buku->deskripsi_buku = $this->encodeDeskripsiMeta($request->input('description'), $coverPath);
            $buku->save();
        }

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data' => $this->formatRow($buku->fresh()),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        $validator->after(function ($v) use ($request, $id) {
            $code = strtoupper(trim((string) $request->input('code')));
            if ($code !== '' && Buku::query()
                ->where('kode_registrasi', $code)
                ->where('kode_buku', '!=', $id)
                ->exists()) {
                $v->errors()->add('code', 'Kode buku sudah digunakan.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $buku = Buku::query()->where('kode_buku', $id)->first();
        if (! $buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $meta = $this->parseDeskripsiMeta($buku->deskripsi_buku);
        $coverPath = $meta['cover'];

        if ($request->hasFile('cover')) {
            $coverPath = $this->saveCover($request->file('cover'), (int) $buku->kode_buku);
        }

        $buku->fill($this->bookAttributes($request, $coverPath));
        $buku->save();

        return response()->json([
            'message' => 'Buku berhasil diperbarui',
            'data' => $this->formatRow($buku),
        ]);
    }

    public function destroy(int $id)
    {
        $buku = Buku::query()->where('kode_buku', $id)->first();
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
            'code.required' => 'Kode buku wajib diisi.',
            'title.required' => 'Judul buku wajib diisi.',
            'author.required' => 'Pengarang wajib diisi.',
            'publisher.required' => 'Penerbit wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'year.required' => 'Tahun terbit wajib diisi.',
            'year.min' => 'Tahun terbit tidak valid.',
            'stock.required' => 'Jumlah buku wajib diisi.',
            'stock.min' => 'Jumlah buku minimal 0.',
            'cover.image' => 'Cover harus berupa gambar.',
            'cover.max' => 'Ukuran cover maksimal 2 MB.',
        ];
    }
}
