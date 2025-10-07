<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke user
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Data pengajuan
            $table->date('tanggal'); // tanggal pengajuan
            $table->string('jenis'); // cuti, lembur, kasbon
            $table->integer('jam_lembur')->nullable(); // khusus lembur
            $table->integer('nominal')->nullable(); // khusus kasbon
            $table->string('bukti')->nullable(); // upload bukti
            $table->text('keterangan')->nullable(); // tambahan keterangan
            
            // Status pengajuan (pending, disetujui, ditolak)
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
