<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan', 'bukti_nama_asli')) {
                $table->string('bukti_nama_asli')->nullable()->after('bukti');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan', 'bukti_nama_asli')) {
                $table->dropColumn('bukti_nama_asli');
            }
        });
    }
};