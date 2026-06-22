<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpCode extends Model
{
    protected $table = 'otp_code';
    public $timestamps = false;

    protected $fillable = [
        'identifier',
        'code',
        'type',
        'is_used',
        'expires_at',
        'created_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
    ];  

    /**
     * pengecekan apakah kode OTP masih valid (belum digunakan dan belum kadaluarsa)
     */

    public function isValid(): bool
    {
        return ! $this->is_used && $this->expires_at->isFuture();
    }   


    /**
     * pengecekan apakah kode OTP sudah kadaluarsa
     */

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * hitung sisa waktu sebelum kode OTP kadaluarsa dalam detik
     */

    public function remainingSeconds(): int
    {
        if ($this->isExpired()) return 0;
        return (int) now()->diffInSeconds($this->expires_at); 
    }

    /**
     * scope untuk mengambil kode OTP yang masih valid berdasarkan identifier dan kode
     */

    public function scopeValid($query)
    {
        return $query->where('is_used', false)
                    ->where('expires_at', '>', now());
    }

    /**
     * Alias scope untuk kompatibilitas: active()
     */
    public function scopeActive($query)
    {
        return $this->scopeValid($query);
    }
}