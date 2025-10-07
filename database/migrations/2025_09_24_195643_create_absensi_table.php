<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Tambahan
            $table->date('tanggal'); // menyimpan tanggal absen
            $table->time('jam_masuk')->nullable();  // jam masuk
            $table->time('jam_keluar')->nullable(); // jam keluar

            $table->timestamp('waktu_absen')->nullable(); // tetap dipertahankan jika mau

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
