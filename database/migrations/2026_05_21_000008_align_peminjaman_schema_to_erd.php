<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('detail_peminjaman')) {
            return;
        }

        $this->migrateDetailDataToErdColumns();

        Schema::table('detail_peminjaman', function (Blueprint $table) {
            foreach (['id_user', 'tgl_pinjam', 'tgl_jatuh_tempo', 'tgl_kembali', 'total_denda', 'diajukan_pada'] as $col) {
                if (Schema::hasColumn('detail_peminjaman', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        if (Schema::hasTable('peminjaman') && Schema::hasColumn('peminjaman', 'id_user')) {
            $this->ensurePeminjamanUserForeignKey();
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('detail_peminjaman')) {
            return;
        }

        Schema::table('detail_peminjaman', function (Blueprint $table) {
            if (! Schema::hasColumn('detail_peminjaman', 'id_user')) {
                $table->unsignedBigInteger('id_user')->nullable()->after('kode_peminjaman');
            }
            if (! Schema::hasColumn('detail_peminjaman', 'tgl_pinjam')) {
                $table->dateTime('tgl_pinjam')->nullable()->after('status_transaksi');
            }
            if (! Schema::hasColumn('detail_peminjaman', 'tgl_jatuh_tempo')) {
                $table->dateTime('tgl_jatuh_tempo')->nullable()->after('tgl_pinjam');
            }
            if (! Schema::hasColumn('detail_peminjaman', 'tgl_kembali')) {
                $table->dateTime('tgl_kembali')->nullable()->after('tgl_jatuh_tempo');
            }
            if (! Schema::hasColumn('detail_peminjaman', 'total_denda')) {
                $table->decimal('total_denda', 15, 2)->default(0)->after('tgl_kembali');
            }
            if (! Schema::hasColumn('detail_peminjaman', 'diajukan_pada')) {
                $table->timestamp('diajukan_pada')->nullable()->after('total_denda');
            }
        });
    }

    private function migrateDetailDataToErdColumns(): void
    {
        $details = DB::table('detail_peminjaman')->orderBy('kode_detail')->get();

        foreach ($details as $detail) {
            $updates = [];

            if (Schema::hasColumn('detail_peminjaman', 'tgl_pinjam') && ! empty($detail->tgl_pinjam)) {
                if (empty($detail->tgl_Peminjaman) || $detail->status_transaksi !== 'mengajukan') {
                    $updates['tgl_Peminjaman'] = $detail->tgl_pinjam;
                }
            }

            if (Schema::hasColumn('detail_peminjaman', 'tgl_jatuh_tempo') && ! empty($detail->tgl_jatuh_tempo)) {
                $updates['tgl_pengembalian'] = $detail->tgl_jatuh_tempo;
            } elseif (Schema::hasColumn('detail_peminjaman', 'tgl_kembali') && ! empty($detail->tgl_kembali)) {
                $updates['tgl_pengembalian'] = $detail->tgl_kembali;
            }

            if (Schema::hasColumn('detail_peminjaman', 'total_denda')) {
                $total = (float) ($detail->total_denda ?? 0);
                if ($total > 0) {
                    $updates['subtotal'] = $total;
                }
            }

            if ($updates !== []) {
                DB::table('detail_peminjaman')->where('kode_detail', $detail->kode_detail)->update($updates);
            }
        }
    }

    private function ensurePeminjamanUserForeignKey(): void
    {
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'peminjaman'
              AND COLUMN_NAME = 'id_user'
              AND REFERENCED_TABLE_NAME = 'user'
            LIMIT 1
        ");

        if ($fk) {
            return;
        }

        try {
            Schema::table('peminjaman', function (Blueprint $table) {
                $table->foreign('id_user')->references('id_user')->on('user')->restrictOnDelete();
            });
        } catch (\Throwable) {
            // Abaikan jika FK sudah ada dengan nama berbeda atau data tidak konsisten.
        }
    }
};
