<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';

    protected $primaryKey = 'kode_buku';

    public $incrementing = true;

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'stok_buku' => 'integer',
            'tahun_terbit' => 'integer',
        ];
    }
}
