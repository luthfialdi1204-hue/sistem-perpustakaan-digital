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

    /**
     * Aplikasi ini memakai tabel `user` (legacy), bukan `users`.
     */
    protected $table = 'user';

    protected $primaryKey = 'id_user';

    public $timestamps = false;

    /**
     * Karena tabel `user` memakai kolom legacy (nama_pengguna, kata_sandi, role_user)
     * dan proses CRUD admin memakai mass-assignment, kita buka guarded agar kolom
     * tersebut bisa tersimpan.
     */
    protected $guarded = [];

    protected $hidden = [
        'password',
        'kata_sandi',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Map atribut standar Laravel -> kolom tabel `user`.
     */
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

    /**
     * Dipakai oleh guard `session` untuk validasi password.
     */
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
        return $this->isAdmin() ? $this->nip : $this->nim;
    }
}
