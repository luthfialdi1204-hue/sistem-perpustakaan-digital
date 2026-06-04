<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_MAHASISWA = 'mahasiswa';

    public const ROLE_ADMIN = 'admin';

    protected $table = 'user';

    protected $primaryKey = 'id_user';

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps = false;

    protected $guarded = [];

    protected $hidden = [
        'kata_sandi',
    ];

    protected function casts(): array
    {
        return [
            'id_user' => 'integer',
            'nim' => 'integer',
            'nip' => 'integer',
        ];
    }

    public function getNameAttribute(): ?string
    {
        return $this->attributes['nama_pengguna'] ?? null;
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['nama_pengguna'] = $value;
    }

    public function getPasswordAttribute(): ?string
    {
        return $this->attributes['kata_sandi'] ?? null;
    }

    public function setPasswordAttribute($value): void
    {
        $this->attributes['kata_sandi'] = $value;
    }

    public function getRoleAttribute(): ?string
    {
        return $this->attributes['role_user'] ?? null;
    }

    public function setRoleAttribute($value): void
    {
        $this->attributes['role_user'] = $value;
    }

    public function getAuthPassword()
    {
        return $this->attributes['kata_sandi'] ?? null;
    }

    public function isMahasiswa(): bool
    {
        return $this->role === self::ROLE_MAHASISWA;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function loginIdentifier(): ?string
    {
        if ($this->isAdmin()) {
            return $this->nip > 0 ? (string) $this->nip : null;
        }

        return $this->nim > 0 ? (string) $this->nim : null;
    }

    public function identifierLabel(): string
    {
        return $this->isAdmin() ? 'NIP' : 'NIM';
    }

    public function initials(int $fallbackLength = 2): string
    {
        $name = trim((string) ($this->nama_pengguna ?? ''));
        if ($name === '') {
            return $this->isAdmin() ? 'AD' : 'MH';
        }

        $parts = preg_split('/\s+/', $name) ?: [];
        $initials = collect($parts)
            ->filter()
            ->take($fallbackLength)
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->join('');

        return $initials !== '' ? $initials : ($this->isAdmin() ? 'AD' : 'MH');
    }
}
