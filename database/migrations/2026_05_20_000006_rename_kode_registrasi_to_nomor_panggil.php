<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('buku') || ! Schema::hasColumn('buku', 'kode_registrasi')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE buku DROP INDEX buku_kode_registrasi_unique');
        } catch (\Throwable) {
        }

        DB::statement('ALTER TABLE buku CHANGE kode_registrasi nomor_panggil VARCHAR(50) NOT NULL');

        try {
            DB::statement('ALTER TABLE buku ADD UNIQUE INDEX buku_nomor_panggil_unique (nomor_panggil)');
        } catch (\Throwable) {
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('buku') || ! Schema::hasColumn('buku', 'nomor_panggil')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE buku DROP INDEX buku_nomor_panggil_unique');
        } catch (\Throwable) {
        }

        DB::statement('ALTER TABLE buku CHANGE nomor_panggil kode_registrasi VARCHAR(50) NOT NULL');

        try {
            DB::statement('ALTER TABLE buku ADD UNIQUE INDEX buku_kode_registrasi_unique (kode_registrasi)');
        } catch (\Throwable) {
        }
    }
};
