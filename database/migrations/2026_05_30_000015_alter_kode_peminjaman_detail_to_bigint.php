<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('peminjaman') || ! Schema::hasColumn('peminjaman', 'kode_peminjaman')) {
            return;
        }

        $this->dropForeignKeys('detail_peminjaman', 'kode_peminjaman');

        if (! $this->isBigInt('peminjaman', 'kode_peminjaman')) {
            DB::statement('ALTER TABLE `peminjaman` MODIFY `kode_peminjaman` BIGINT NOT NULL AUTO_INCREMENT');
        }

        if (Schema::hasTable('detail_peminjaman')) {
            if (Schema::hasColumn('detail_peminjaman', 'kode_detail') && ! $this->isBigInt('detail_peminjaman', 'kode_detail')) {
                DB::statement('ALTER TABLE `detail_peminjaman` MODIFY `kode_detail` BIGINT NOT NULL AUTO_INCREMENT');
            }

            if (Schema::hasColumn('detail_peminjaman', 'kode_peminjaman') && ! $this->isBigInt('detail_peminjaman', 'kode_peminjaman')) {
                DB::statement('ALTER TABLE `detail_peminjaman` MODIFY `kode_peminjaman` BIGINT NOT NULL');
            }
        }

        $this->ensureForeignKey(
            'detail_peminjaman',
            'kode_peminjaman',
            'detail_peminjaman_kode_peminjaman_foreign',
            'CASCADE'
        );
    }

    public function down(): void
    {
        if (! Schema::hasTable('peminjaman') || ! Schema::hasColumn('peminjaman', 'kode_peminjaman')) {
            return;
        }

        $this->dropForeignKeys('detail_peminjaman', 'kode_peminjaman');

        if (Schema::hasTable('detail_peminjaman')) {
            if (Schema::hasColumn('detail_peminjaman', 'kode_peminjaman') && $this->isBigInt('detail_peminjaman', 'kode_peminjaman')) {
                DB::statement('ALTER TABLE `detail_peminjaman` MODIFY `kode_peminjaman` INT UNSIGNED NOT NULL');
            }

            if (Schema::hasColumn('detail_peminjaman', 'kode_detail') && $this->isBigInt('detail_peminjaman', 'kode_detail')) {
                DB::statement('ALTER TABLE `detail_peminjaman` MODIFY `kode_detail` INT UNSIGNED NOT NULL AUTO_INCREMENT');
            }
        }

        if ($this->isBigInt('peminjaman', 'kode_peminjaman')) {
            DB::statement('ALTER TABLE `peminjaman` MODIFY `kode_peminjaman` INT NOT NULL AUTO_INCREMENT');
        }

        $this->ensureForeignKey(
            'detail_peminjaman',
            'kode_peminjaman',
            'detail_peminjaman_kode_peminjaman_foreign',
            'CASCADE'
        );
    }

    private function isBigInt(string $table, string $column): bool
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return false;
        }

        $row = DB::selectOne("SHOW COLUMNS FROM `{$table}` WHERE Field = ?", [$column]);
        $type = $row->Type ?? null;

        return $type !== null && str_contains(strtolower($type), 'bigint');
    }

    private function dropForeignKeys(string $table, string $column): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return;
        }

        foreach ($this->foreignKeyNames($table, $column) as $fk) {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fk}`");
        }
    }

    private function ensureForeignKey(string $table, string $column, string $name, string $onDelete): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return;
        }

        if ($this->foreignKeyNames($table, $column) !== []) {
            return;
        }

        DB::statement("ALTER TABLE `{$table}`
            ADD CONSTRAINT `{$name}`
            FOREIGN KEY (`{$column}`) REFERENCES `peminjaman` (`kode_peminjaman`) ON DELETE {$onDelete}");
    }

    /** @return list<string> */
    private function foreignKeyNames(string $table, string $column): array
    {
        if (! Schema::hasTable($table)) {
            return [];
        }

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
