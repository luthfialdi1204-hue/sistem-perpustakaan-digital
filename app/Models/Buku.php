<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    public const KATEGORI_FIKSI = 'Fiksi';

    public const KATEGORI_PENDIDIKAN = 'Pendidikan';

    public const KATEGORI_BISNIS = 'Bisnis';

    public const KATEGORI_TEKNOLOGI = 'Teknologi';

    public const KATEGORI_AGAMA = 'Agama';

    /** @return list<string> */
    public static function kategoriList(): array
    {
        return [
            self::KATEGORI_FIKSI,
            self::KATEGORI_PENDIDIKAN,
            self::KATEGORI_BISNIS,
            self::KATEGORI_TEKNOLOGI,
            self::KATEGORI_AGAMA,
        ];
    }

    public static function isValidKategori(string $kategori): bool
    {
        return in_array($kategori, self::kategoriList(), true);
    }

    protected $table = 'buku';

    protected $primaryKey = 'kode_buku';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'kode_buku' => 'integer',
            'stok_buku' => 'integer',
            'tahun_terbit' => 'integer',
        ];
    }
}
