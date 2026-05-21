<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Buku;
use Illuminate\Http\UploadedFile;

trait FormatsBuku
{
    protected function defaultCoverUrl(int $kode): string
    {
        $files = ['Cover buku 1.jpg', 'Cover buku 2.jpg', 'Cover buku 3.jpg'];
        $file = $files[abs($kode) % 3];

        return asset('images/'.rawurlencode($file));
    }

    protected function parseDeskripsiMeta(?string $raw): array
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

    protected function encodeDeskripsiMeta(?string $desc, string $cover = ''): string
    {
        return json_encode([
            'desc' => trim((string) $desc),
            'cover' => trim($cover),
        ], JSON_UNESCAPED_UNICODE);
    }

    protected function resolveCoverUrl(string $cover, int $kode): string
    {
        if ($cover !== '' && is_file(public_path($cover))) {
            return asset($cover);
        }

        return $this->defaultCoverUrl($kode);
    }

    protected function saveCover(UploadedFile $file, int $kode): string
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

    protected function normalizeIsbn(?string $value, string $legacy = ''): string
    {
        $isbn = trim((string) ($value ?: $legacy));

        if ($isbn === '' || $isbn === '0') {
            return '-';
        }

        return $isbn;
    }

    protected function normalizeRak(?string $value, string $legacy = ''): string
    {
        $rak = trim((string) ($value ?: $legacy));

        return $rak !== '' ? $rak : '-';
    }

    protected function formatBukuRow(Buku $b): array
    {
        $meta = $this->parseDeskripsiMeta($b->deskripsi_buku);
        $stock = (int) $b->stok_buku;

        $kodeReg = trim((string) ($b->kode_registrasi ?? ''));

        return [
            'id' => (int) $b->kode_buku,
            'code' => $kodeReg !== '' ? $kodeReg : (string) $b->kode_buku,
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

    protected function bukuCategories(): array
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
