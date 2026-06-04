<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $primaryKey = 'kode_peminjaman';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'kode_peminjaman' => 'integer',
            'id_user' => 'integer',
            'kode_buku' => 'integer',
            'tgl_Peminjaman' => 'datetime',
            'tgl_pengembalian' => 'datetime',
            'total_denda' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'kode_buku', 'kode_buku');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class, 'kode_peminjaman', 'kode_peminjaman');
    }
}
