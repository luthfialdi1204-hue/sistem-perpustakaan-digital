<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('buku')) {
            return;
        }

        if (! Schema::hasColumn('buku', 'kode_registrasi')) {
            DB::statement("ALTER TABLE buku ADD kode_registrasi VARCHAR(50) NOT NULL DEFAULT '' AFTER kode_buku");
        }

        $rows = DB::table('buku')->select('kode_buku', 'kode_registrasi')->get();
        foreach ($rows as $row) {
            if (trim((string) $row->kode_registrasi) !== '') {
                continue;
            }

            DB::table('buku')->where('kode_buku', $row->kode_buku)->update([
                'kode_registrasi' => 'BK'.str_pad((string) $row->kode_buku, 4, '0', STR_PAD_LEFT),
            ]);
        }

        try {
            DB::statement('ALTER TABLE buku ADD UNIQUE INDEX buku_kode_registrasi_unique (kode_registrasi)');
        } catch (\Throwable) {
            // Index mungkin sudah ada.
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('buku') || ! Schema::hasColumn('buku', 'kode_registrasi')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE buku DROP INDEX buku_kode_registrasi_unique');
        } catch (\Throwable) {
        }

        Schema::table('buku', function ($table) {
            $table->dropColumn('kode_registrasi');
        });
    }
};
