<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('user') || ! Schema::hasColumn('user', 'id_user')) {
            return;
        }

        $this->dropForeignKeys('peminjaman', 'id_user');
        $this->dropForeignKeys('detail_peminjaman', 'id_user');

        if (! $this->isBigInt('user', 'id_user')) {
            DB::statement('ALTER TABLE `user` MODIFY `id_user` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        if (Schema::hasTable('peminjaman') && Schema::hasColumn('peminjaman', 'id_user') && ! $this->isBigInt('peminjaman', 'id_user')) {
            DB::statement('ALTER TABLE `peminjaman` MODIFY `id_user` BIGINT UNSIGNED NOT NULL');
        }

        if (Schema::hasTable('detail_peminjaman') && Schema::hasColumn('detail_peminjaman', 'id_user') && ! $this->isBigInt('detail_peminjaman', 'id_user')) {
            $nullable = $this->columnNull('detail_peminjaman', 'id_user') ? 'NULL' : 'NOT NULL';
            DB::statement("ALTER TABLE `detail_peminjaman` MODIFY `id_user` BIGINT UNSIGNED {$nullable}");
        }

        $this->ensureForeignKey('peminjaman', 'id_user', 'peminjaman_id_user_foreign', 'RESTRICT');
        $this->ensureForeignKey('detail_peminjaman', 'id_user', 'detail_peminjaman_id_user_foreign', 'CASCADE');
    }

    public function down(): void
    {
        if (! Schema::hasTable('user') || ! Schema::hasColumn('user', 'id_user')) {
            return;
        }

        $this->dropForeignKeys('peminjaman', 'id_user');
        $this->dropForeignKeys('detail_peminjaman', 'id_user');

        if (Schema::hasTable('detail_peminjaman') && Schema::hasColumn('detail_peminjaman', 'id_user') && $this->isBigInt('detail_peminjaman', 'id_user')) {
            $nullable = $this->columnNull('detail_peminjaman', 'id_user') ? 'NULL' : 'NOT NULL';
            DB::statement("ALTER TABLE `detail_peminjaman` MODIFY `id_user` INT UNSIGNED {$nullable}");
        }

        if (Schema::hasTable('peminjaman') && Schema::hasColumn('peminjaman', 'id_user') && $this->isBigInt('peminjaman', 'id_user')) {
            DB::statement('ALTER TABLE `peminjaman` MODIFY `id_user` INT NOT NULL');
        }

        if ($this->isBigInt('user', 'id_user')) {
            DB::statement('ALTER TABLE `user` MODIFY `id_user` INT NOT NULL AUTO_INCREMENT');
        }

        $this->ensureForeignKey('peminjaman', 'id_user', 'peminjaman_id_user_foreign', 'RESTRICT');
    }

    private function isBigInt(string $table, string $column): bool
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return false;
        }

        $type = $this->columnType($table, $column);

        return $type !== null && str_contains(strtolower($type), 'bigint');
    }

    private function columnNull(string $table, string $column): bool
    {
        $row = DB::selectOne("SHOW COLUMNS FROM `{$table}` WHERE Field = ?", [$column]);

        return ($row->Null ?? 'NO') === 'YES';
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
            FOREIGN KEY (`{$column}`) REFERENCES `user` (`id_user`) ON DELETE {$onDelete}");
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
