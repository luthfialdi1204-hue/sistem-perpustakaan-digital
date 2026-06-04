<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('buku') || ! Schema::hasColumn('buku', 'nomor_panggil')) {
            return;
        }

        foreach ($this->indexNames('buku', 'nomor_panggil') as $index) {
            try {
                DB::statement("ALTER TABLE `buku` DROP INDEX `{$index}`");
            } catch (\Throwable) {
            }
        }
    }

    public function down(): void
    {
        // Sengaja tidak membuat ulang indeks — nomor panggil bukan kunci di database.
    }

    /** @return list<string> */
    private function indexNames(string $table, string $column): array
    {
        $rows = DB::select('SHOW INDEX FROM `'.$table.'` WHERE Column_name = ?', [$column]);

        return array_values(array_unique(array_map(fn ($row) => $row->Key_name, $rows)));
    }
};
