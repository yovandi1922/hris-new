<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan kolom tanggal_mulai ada di tabel pengajuan
        if (Schema::hasTable('pengajuan')) {
            Schema::table('pengajuan', function (Blueprint $table) {
                if (!Schema::hasColumn('pengajuan', 'tanggal_mulai')) {
                    $table->date('tanggal_mulai')->nullable()->after('jenis');
                }
                if (!Schema::hasColumn('pengajuan', 'tanggal_selesai')) {
                    $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
                }
                if (!Schema::hasColumn('pengajuan', 'durasi')) {
                    $table->integer('durasi')->default(1)->after('tanggal_selesai');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pengajuan')) {
            Schema::table('pengajuan', function (Blueprint $table) {
                // Tidak perlu drop, biarkan data tetap
            });
        }
    }
};
