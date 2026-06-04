<?php

use App\Models\Buku;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('buku') || ! Schema::hasColumn('buku', 'kategori_buku')) {
            return;
        }

        $allowed = Buku::kategoriList();
        $default = Buku::KATEGORI_PENDIDIKAN;
        $enum = "'".implode("','", $allowed)."'";

        DB::table('buku')
            ->whereNotIn('kategori_buku', $allowed)
            ->update(['kategori_buku' => $default]);

        DB::statement("ALTER TABLE `buku` MODIFY `kategori_buku` ENUM({$enum}) NOT NULL DEFAULT '{$default}'");
    }

    public function down(): void
    {
        if (! Schema::hasTable('buku') || ! Schema::hasColumn('buku', 'kategori_buku')) {
            return;
        }

        DB::statement('ALTER TABLE `buku` MODIFY `kategori_buku` VARCHAR(50) NOT NULL');
    }
};
