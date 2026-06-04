<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPeminjaman extends Model
{
    public const STATUS_MENGAJUKAN = 'mengajukan';

    public const STATUS_DIPINJAM = 'dipinjam';

    public const STATUS_TERLAMBAT = 'terlambat';

    public const STATUS_DIKEMBALIKAN = 'dikembalikan';

    public const STATUS_SUDAH_LUNAS = 'sudah_lunas';

    public const STATUS_DITOLAK = 'ditolak';

    public const STATUS_DIBATALKAN = 'dibatalkan';

    public const LOAN_DAYS = 7;

    public const FINE_PER_DAY = 2000;

    protected $table = 'detail_peminjaman';

    protected $primaryKey = 'kode_detail';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'kode_detail' => 'integer',
            'kode_peminjaman' => 'integer',
            'kode_buku' => 'integer',
            'tgl_Peminjaman' => 'datetime',
            'tgl_pengembalian' => 'datetime',
            'subtotal' => 'decimal:2',
            'jumlah_buku' => 'integer',
        ];
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'kode_peminjaman', 'kode_peminjaman');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'kode_buku', 'kode_buku');
    }
}
