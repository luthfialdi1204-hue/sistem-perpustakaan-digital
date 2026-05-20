<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel legacy `user` memakai INT untuk nim/nip, tidak cukup untuk 10 digit.
        // Contoh NIM: 3312501077 (butuh BIGINT).
        DB::statement('ALTER TABLE `user` MODIFY `nim` BIGINT NOT NULL');
        DB::statement('ALTER TABLE `user` MODIFY `nip` BIGINT NOT NULL');
    }

    public function down(): void
    {
        // Kembalikan seperti semula (berisiko overflow bila ada data 10 digit).
        DB::statement('ALTER TABLE `user` MODIFY `nim` INT NOT NULL');
        DB::statement('ALTER TABLE `user` MODIFY `nip` INT NOT NULL');
    }
};

