<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function statusEnum(): array
    {
        return [
            'mengajukan',
            'dipinjam',
            'terlambat',
            'dikembalikan',
            'sudah_lunas',
            'ditolak',
            'dibatalkan',
        ];
    }

    public function up(): void
    {
        if (! Schema::hasTable('detail_peminjaman')) {
            Schema::create('detail_peminjaman', function (Blueprint $table) {
                $table->bigIncrements('kode_detail');
                $table->unsignedBigInteger('kode_peminjaman');
                $table->unsignedBigInteger('id_user');
                $table->unsignedBigInteger('kode_buku');
                $table->enum('status_transaksi', $this->statusEnum())->default('mengajukan');
                $table->dateTime('tgl_pinjam')->nullable();
                $table->dateTime('tgl_jatuh_tempo')->nullable();
                $table->dateTime('tgl_kembali')->nullable();
                $table->decimal('total_denda', 15, 2)->default(0);
                $table->integer('jumlah_buku')->default(1);
                $table->timestamp('diajukan_pada')->useCurrent();

                $table->foreign('kode_peminjaman')->references('kode_peminjaman')->on('peminjaman')->cascadeOnDelete();
                $table->foreign('id_user')->references('id_user')->on('user')->cascadeOnDelete();
                $table->foreign('kode_buku')->references('kode_buku')->on('buku')->restrictOnDelete();
                $table->index(['status_transaksi', 'id_user']);
            });

            $this->backfillFromPeminjaman();

            return;
        }

        Schema::table('detail_peminjaman', function (Blueprint $table) {
            if (! Schema::hasColumn('detail_peminjaman', 'status_transaksi')) {
                $table->enum('status_transaksi', $this->statusEnum())->default('dikembalikan')->after('kode_buku');
            }
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
            if (! Schema::hasColumn('detail_peminjaman', 'total_denda') && Schema::hasColumn('detail_peminjaman', 'subtotal')) {
                $table->decimal('total_denda', 15, 2)->default(0)->after('tgl_kembali');
            } elseif (! Schema::hasColumn('detail_peminjaman', 'total_denda')) {
                $table->decimal('total_denda', 15, 2)->default(0)->after('tgl_kembali');
            }
            if (! Schema::hasColumn('detail_peminjaman', 'diajukan_pada')) {
                $table->timestamp('diajukan_pada')->nullable()->after('total_denda');
            }
        });

        $this->backfillLegacyDetailRows();
    }

    public function down(): void
    {
        if (! Schema::hasTable('detail_peminjaman')) {
            return;
        }

        Schema::table('detail_peminjaman', function (Blueprint $table) {
            foreach (['diajukan_pada', 'total_denda', 'tgl_kembali', 'tgl_jatuh_tempo', 'tgl_pinjam', 'id_user', 'status_transaksi'] as $col) {
                if (Schema::hasColumn('detail_peminjaman', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    private function backfillFromPeminjaman(): void
    {
        if (! Schema::hasTable('peminjaman')) {
            return;
        }

        $rows = DB::table('peminjaman')->orderBy('kode_peminjaman')->get();
        foreach ($rows as $row) {
            if (DB::table('detail_peminjaman')->where('kode_peminjaman', $row->kode_peminjaman)->exists()) {
                continue;
            }

            DB::table('detail_peminjaman')->insert([
                'kode_peminjaman' => $row->kode_peminjaman,
                'id_user' => $row->id_user,
                'kode_buku' => $row->kode_buku,
                'status_transaksi' => 'dikembalikan',
                'tgl_pinjam' => $row->tgl_Peminjaman,
                'tgl_jatuh_tempo' => $row->tgl_pengembalian,
                'tgl_kembali' => $row->tgl_pengembalian,
                'total_denda' => $row->total_denda ?? 0,
                'jumlah_buku' => 1,
                'diajukan_pada' => $row->tgl_Peminjaman,
            ]);
        }
    }

    private function backfillLegacyDetailRows(): void
    {
        $details = DB::table('detail_peminjaman')->orderBy('kode_detail')->get();

        foreach ($details as $detail) {
            $header = DB::table('peminjaman')->where('kode_peminjaman', $detail->kode_peminjaman)->first();
            $updates = [];

            if (Schema::hasColumn('detail_peminjaman', 'id_user') && empty($detail->id_user) && $header) {
                $updates['id_user'] = $header->id_user;
            }

            if (Schema::hasColumn('detail_peminjaman', 'tgl_pinjam') && empty($detail->tgl_pinjam)) {
                $updates['tgl_pinjam'] = $detail->tgl_Peminjaman ?? $header?->tgl_Peminjaman;
            }
            if (Schema::hasColumn('detail_peminjaman', 'tgl_jatuh_tempo') && empty($detail->tgl_jatuh_tempo)) {
                $updates['tgl_jatuh_tempo'] = $detail->tgl_pengembalian ?? $header?->tgl_pengembalian;
            }
            if (Schema::hasColumn('detail_peminjaman', 'tgl_kembali') && empty($detail->tgl_kembali)) {
                $updates['tgl_kembali'] = $detail->tgl_pengembalian ?? null;
            }

            if (Schema::hasColumn('detail_peminjaman', 'total_denda')) {
                $current = $detail->total_denda ?? null;
                if ($current === null || (float) $current === 0.0) {
                    $updates['total_denda'] = $detail->subtotal ?? $header?->total_denda ?? 0;
                }
            }

            if (Schema::hasColumn('detail_peminjaman', 'status_transaksi') && empty($detail->status_transaksi)) {
                $updates['status_transaksi'] = 'dikembalikan';
            }

            if (Schema::hasColumn('detail_peminjaman', 'diajukan_pada') && empty($detail->diajukan_pada)) {
                $updates['diajukan_pada'] = $detail->tgl_Peminjaman ?? $header?->tgl_Peminjaman ?? now();
            }

            if ($updates !== []) {
                DB::table('detail_peminjaman')->where('kode_detail', $detail->kode_detail)->update($updates);
            }
        }
    }
};
