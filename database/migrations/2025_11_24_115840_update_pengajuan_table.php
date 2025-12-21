<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            // Tambahkan kolom baru hanya jika belum ada
            if (!Schema::hasColumn('pengajuan', 'tanggal_mulai')) {
                $table->date('tanggal_mulai')->nullable();
            }
            if (!Schema::hasColumn('pengajuan', 'tanggal_selesai')) {
                $table->date('tanggal_selesai')->nullable();
            }
            if (!Schema::hasColumn('pengajuan', 'durasi')) {
                $table->integer('durasi')->default(1);
            }

            // Ubah enum status
            $table->enum('status', ['pending','acc','ditolak'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan', 'tanggal_mulai')) {
                $table->dropColumn('tanggal_mulai');
            }
            if (Schema::hasColumn('pengajuan', 'tanggal_selesai')) {
                $table->dropColumn('tanggal_selesai');
            }
            if (Schema::hasColumn('pengajuan', 'durasi')) {
                $table->dropColumn('durasi');
            }

            $table->enum('status', ['pending','disetujui','ditolak'])->default('pending')->change();
        });
    }
};
