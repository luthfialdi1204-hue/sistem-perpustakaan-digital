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

        $this->dropForeignKeysOnColumn('buku', 'nomor_panggil');
        $this->dropForeignKeysReferencing('nomor_panggil');

        if (Schema::hasColumn('buku', 'kode_registrasi')) {
            DB::table('buku')
                ->where(function ($q) {
                    $q->whereNull('nomor_panggil')
                        ->orWhere('nomor_panggil', '');
                })
                ->whereNotNull('kode_registrasi')
                ->where('kode_registrasi', '!=', '')
                ->update([
                    'nomor_panggil' => DB::raw('kode_registrasi'),
                ]);

            try {
                DB::statement('ALTER TABLE `buku` DROP INDEX `buku_kode_registrasi_unique`');
            } catch (\Throwable) {
            }

            Schema::table('buku', function ($table) {
                $table->dropColumn('kode_registrasi');
            });
        }

        if (! Schema::hasColumn('buku', 'nomor_panggil')) {
            Schema::table('buku', function ($table) {
                $table->string('nomor_panggil', 50)->default('')->after('kode_buku');
            });
        }

        DB::statement("ALTER TABLE `buku` MODIFY `nomor_panggil` VARCHAR(50) NOT NULL DEFAULT ''");

        DB::table('buku')
            ->where(function ($q) {
                $q->whereNull('nomor_panggil')->orWhere('nomor_panggil', '');
            })
            ->orderBy('kode_buku')
            ->chunk(100, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('buku')->where('kode_buku', $row->kode_buku)->update([
                        'nomor_panggil' => 'BK'.str_pad((string) $row->kode_buku, 4, '0', STR_PAD_LEFT),
                    ]);
                }
            });

        // Nomor panggil: kolom biasa tanpa indeks / kunci di database.
    }

    public function down(): void
    {
        // Tidak mengembalikan foreign key — nomor panggil memang bukan relasi antar tabel.
    }

    private function dropForeignKeysOnColumn(string $table, string $column): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return;
        }

        foreach ($this->foreignKeyNames($table, $column) as $fk) {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fk}`");
        }
    }

    private function dropForeignKeysReferencing(string $referencedColumn): void
    {
        $rows = DB::select("
            SELECT TABLE_NAME, CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND REFERENCED_COLUMN_NAME = ?
        ", [$referencedColumn]);

        foreach ($rows as $row) {
            DB::statement("ALTER TABLE `{$row->TABLE_NAME}` DROP FOREIGN KEY `{$row->CONSTRAINT_NAME}`");
        }
    }

    /** @return list<string> */
    private function foreignKeyNames(string $table, string $column): array
    {
        $rows = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = ?
              AND COLUMN_NAME = ?
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ", [$table, $column]);

        return array_map(fn ($row) => $row->CONSTRAINT_NAME, $rows);
    }
};
