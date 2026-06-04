<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('buku') || ! Schema::hasColumn('buku', 'kode_buku')) {
            return;
        }

        $this->dropForeignKeys('peminjaman', 'kode_buku');
        $this->dropForeignKeys('detail_peminjaman', 'kode_buku');

        if (! $this->isBigInt('buku', 'kode_buku')) {
            DB::statement('ALTER TABLE `buku` MODIFY `kode_buku` BIGINT NOT NULL AUTO_INCREMENT');
        }

        if (Schema::hasTable('peminjaman') && Schema::hasColumn('peminjaman', 'kode_buku') && ! $this->isBigInt('peminjaman', 'kode_buku')) {
            DB::statement('ALTER TABLE `peminjaman` MODIFY `kode_buku` BIGINT NOT NULL');
        }

        if (Schema::hasTable('detail_peminjaman') && Schema::hasColumn('detail_peminjaman', 'kode_buku') && ! $this->isBigInt('detail_peminjaman', 'kode_buku')) {
            DB::statement('ALTER TABLE `detail_peminjaman` MODIFY `kode_buku` BIGINT NOT NULL');
        }

        $this->ensureForeignKey('peminjaman', 'kode_buku', 'peminjaman_kode_buku_foreign', 'RESTRICT');
        $this->ensureForeignKey('detail_peminjaman', 'kode_buku', 'detail_peminjaman_kode_buku_foreign', 'RESTRICT');
    }

    public function down(): void
    {
        if (! Schema::hasTable('buku') || ! Schema::hasColumn('buku', 'kode_buku')) {
            return;
        }

        $this->dropForeignKeys('peminjaman', 'kode_buku');
        $this->dropForeignKeys('detail_peminjaman', 'kode_buku');

        if (Schema::hasTable('detail_peminjaman') && Schema::hasColumn('detail_peminjaman', 'kode_buku') && $this->isBigInt('detail_peminjaman', 'kode_buku')) {
            DB::statement('ALTER TABLE `detail_peminjaman` MODIFY `kode_buku` INT NOT NULL');
        }

        if (Schema::hasTable('peminjaman') && Schema::hasColumn('peminjaman', 'kode_buku') && $this->isBigInt('peminjaman', 'kode_buku')) {
            DB::statement('ALTER TABLE `peminjaman` MODIFY `kode_buku` INT NOT NULL');
        }

        if ($this->isBigInt('buku', 'kode_buku')) {
            DB::statement('ALTER TABLE `buku` MODIFY `kode_buku` INT NOT NULL AUTO_INCREMENT');
        }

        $this->ensureForeignKey('peminjaman', 'kode_buku', 'peminjaman_kode_buku_foreign', 'RESTRICT');
        $this->ensureForeignKey('detail_peminjaman', 'kode_buku', 'detail_peminjaman_kode_buku_foreign', 'RESTRICT');
    }

    private function isBigInt(string $table, string $column): bool
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return false;
        }

        $type = $this->columnType($table, $column);

        return $type !== null && str_contains(strtolower($type), 'bigint');
    }

    private function columnType(string $table, string $column): ?string
    {
        $row = DB::selectOne("SHOW COLUMNS FROM `{$table}` WHERE Field = ?", [$column]);

        return $row->Type ?? null;
    }

    private function dropForeignKeys(string $table, string $column): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return;
        }

        foreach ($this->foreignKeys($table, $column) as $fk) {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fk}`");
        }
    }

    private function ensureForeignKey(string $table, string $column, string $name, string $onDelete): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return;
        }

        if ($this->foreignKeys($table, $column) !== []) {
            return;
        }

        DB::statement("ALTER TABLE `{$table}`
            ADD CONSTRAINT `{$name}`
            FOREIGN KEY (`{$column}`) REFERENCES `buku` (`kode_buku`) ON DELETE {$onDelete}");
    }

    /** @return list<string> */
    private function foreignKeys(string $table, string $column): array
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
