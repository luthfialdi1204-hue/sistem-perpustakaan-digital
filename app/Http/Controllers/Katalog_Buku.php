<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Katalog_Buku extends Controller
{
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
        $query = $this->applyBukuSearch(Buku::query(), $request);

        if ($request->boolean('random')) {
            $query->inRandomOrder();
        } else {
            $query->orderByDesc('kode_buku');
        }

        $limit = $request->integer('limit');
        if ($limit > 0) {
            $query->limit($limit);
        }

        $rows = $query->get()->map(fn (Buku $b) => $this->formatBukuRow($b))->values();

        return response()->json([
            'data' => $rows,
            'categories' => $this->bukuCategories(),
        ]);
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

    private function resolveCoverUrl(string $cover, int $kode): string
    {
        if ($cover !== '' && is_file(public_path($cover))) {
            return asset($cover);
        }

        return $this->defaultCoverUrl($kode);
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
