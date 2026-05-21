<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class KelolaBukuAdminController extends Controller
{
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
            'nomor_panggil' => trim((string) $request->input('code')),
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

    public function list(Request $request)
    {
        $rows = $this->applyBukuSearch(Buku::query(), $request)
            ->orderByDesc('kode_buku')
            ->get()
            ->map(fn (Buku $b) => $this->formatBukuRow($b));

        return response()->json([
            'data' => $rows,
            'categories' => $this->bukuCategories(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        $validator->after(function ($v) use ($request) {
            $code = trim((string) $request->input('code'));
            if ($code !== '' && Buku::query()->where('nomor_panggil', $code)->exists()) {
                $v->errors()->add('code', 'Nomor panggil sudah digunakan.');
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
            'data' => $this->formatBukuRow($buku->fresh()),
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        $validator->after(function ($v) use ($request, $id) {
            $code = trim((string) $request->input('code'));
            if ($code !== '' && Buku::query()
                ->where('nomor_panggil', $code)
                ->where('kode_buku', '!=', $id)
                ->exists()) {
                $v->errors()->add('code', 'Nomor panggil sudah digunakan.');
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
            'data' => $this->formatBukuRow($buku),
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
            'code.required' => 'Nomor panggil wajib diisi.',
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

    private function applyBukuSearch(Builder $query, Request $request): Builder
    {
        $term = trim((string) $request->input('q', $request->input('search', '')));

        if ($term !== '') {
            $like = '%'.$term.'%';
            $query->where(function (Builder $q) use ($like) {
                $q->where('judul_buku', 'like', $like)
                    ->orWhere('pengarang', 'like', $like)
                    ->orWhere('penerbit', 'like', $like)
                    ->orWhere('kategori_buku', 'like', $like)
                    ->orWhere('nomor_panggil', 'like', $like)
                    ->orWhere('isbn', 'like', $like);
            });
        }

        $category = trim((string) $request->input('category', ''));
        if ($category !== '' && strtolower($category) !== 'all') {
            $query->where('kategori_buku', $category);
        }

        return $query;
    }

    private function defaultCoverUrl(int $kode): string
    {
        $files = ['Cover buku 1.jpg', 'Cover buku 2.jpg', 'Cover buku 3.jpg'];
        $file = $files[abs($kode) % 3];

        return asset('images/'.rawurlencode($file));
    }

    private function parseDeskripsiMeta(?string $raw): array
    {
        $raw = trim((string) $raw);
        if ($raw !== '' && str_starts_with($raw, '{')) {
            $json = json_decode($raw, true);
            if (is_array($json)) {
                return [
                    'desc' => (string) ($json['desc'] ?? ''),
                    'cover' => (string) ($json['cover'] ?? ''),
                    'legacy_isbn' => (string) ($json['isbn'] ?? ''),
                    'legacy_rak' => (string) ($json['rak'] ?? ''),
                ];
            }
        }

        return [
            'desc' => $raw,
            'cover' => '',
            'legacy_isbn' => '',
            'legacy_rak' => '',
        ];
    }

    private function encodeDeskripsiMeta(?string $desc, string $cover = ''): string
    {
        return json_encode([
            'desc' => trim((string) $desc),
            'cover' => trim($cover),
        ], JSON_UNESCAPED_UNICODE);
    }

    private function resolveCoverUrl(string $cover, int $kode): string
    {
        if ($cover !== '' && is_file(public_path($cover))) {
            return asset($cover);
        }

        return $this->defaultCoverUrl($kode);
    }

    private function saveCover(UploadedFile $file, int $kode): string
    {
        $dir = public_path('images/covers');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
            $ext = 'jpg';
        }

        $name = 'buku_'.$kode.'_'.time().'.'.$ext;
        $file->move($dir, $name);

        return 'images/covers/'.$name;
    }

    private function normalizeIsbn(?string $value, string $legacy = ''): string
    {
        $isbn = trim((string) ($value ?: $legacy));

        if ($isbn === '' || $isbn === '0') {
            return '-';
        }

        return $isbn;
    }

    private function normalizeRak(?string $value, string $legacy = ''): string
    {
        $rak = trim((string) ($value ?: $legacy));

        return $rak !== '' ? $rak : '-';
    }

    private function formatBukuRow(Buku $b): array
    {
        $meta = $this->parseDeskripsiMeta($b->deskripsi_buku);
        $stock = (int) $b->stok_buku;
        $nomorPanggil = trim((string) ($b->nomor_panggil ?? ''));

        return [
            'id' => (int) $b->kode_buku,
            'code' => $nomorPanggil !== '' ? $nomorPanggil : (string) $b->kode_buku,
            'nomor_panggil' => $nomorPanggil !== '' ? $nomorPanggil : (string) $b->kode_buku,
            'title' => $b->judul_buku,
            'author' => $b->pengarang,
            'publisher' => $b->penerbit,
            'category' => $b->kategori_buku,
            'year' => (string) $b->tahun_terbit,
            'stock' => $stock,
            'isbn' => $this->normalizeIsbn($b->isbn ?? null, $meta['legacy_isbn']),
            'rack' => $this->normalizeRak($b->lokasi_rak ?? null, $meta['legacy_rak']),
            'description' => $meta['desc'] !== '' ? $meta['desc'] : '-',
            'img' => $this->resolveCoverUrl($meta['cover'], (int) $b->kode_buku),
            'available' => $stock > 0,
            'status' => $stock > 0 ? 'available' : 'borrowed',
            'stockLabel' => $stock > 0 ? "{$stock} tersedia" : 'Dipinjam semua',
        ];
    }

    private function bukuCategories(): array
    {
        return Buku::query()
            ->select('kategori_buku')
            ->distinct()
            ->orderBy('kategori_buku')
            ->pluck('kategori_buku')
            ->filter()
            ->values()
            ->all();
    }
}
