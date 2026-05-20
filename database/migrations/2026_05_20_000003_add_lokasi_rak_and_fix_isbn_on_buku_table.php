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

        if (! Schema::hasColumn('buku', 'lokasi_rak')) {
            DB::statement("ALTER TABLE buku ADD lokasi_rak VARCHAR(50) NOT NULL DEFAULT '' AFTER isbn");
        }

        DB::statement("ALTER TABLE buku MODIFY isbn VARCHAR(50) NOT NULL DEFAULT ''");

        $rows = DB::table('buku')->get();

        foreach ($rows as $row) {
            $isbn = '';
            $rak = '';
            $desc = (string) $row->deskripsi_buku;
            $cover = '';

            $raw = trim($desc);
            if ($raw !== '' && str_starts_with($raw, '{')) {
                $json = json_decode($raw, true);
                if (is_array($json)) {
                    $isbn = (string) ($json['isbn'] ?? '');
                    $rak = (string) ($json['rak'] ?? '');
                    $desc = (string) ($json['desc'] ?? '');
                    $cover = (string) ($json['cover'] ?? '');
                }
            }

            if ($isbn === '' && isset($row->isbn) && (string) $row->isbn !== '' && (string) $row->isbn !== '0') {
                $isbn = (string) $row->isbn;
            }

            DB::table('buku')->where('kode_buku', $row->kode_buku)->update([
                'isbn' => $isbn !== '' ? $isbn : '-',
                'lokasi_rak' => $rak !== '' ? $rak : '-',
                'deskripsi_buku' => json_encode([
                    'desc' => $desc,
                    'cover' => $cover,
                ], JSON_UNESCAPED_UNICODE),
            ]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('buku')) {
            return;
        }

        if (Schema::hasColumn('buku', 'lokasi_rak')) {
            Schema::table('buku', function ($table) {
                $table->dropColumn('lokasi_rak');
            });
        }

        DB::statement('ALTER TABLE buku MODIFY isbn INT NOT NULL');
    }
};
