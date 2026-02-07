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
            $table->date('tanggal_mulai'); // tanggal mulai cuti/izin
            $table->date('tanggal_selesai'); // tanggal selesai cuti/izin
            $table->string('jenis'); // Cuti Tahunan, Cuti Pribadi, Izin Sakit, dll
            $table->integer('durasi')->default(1); // durasi hari
            $table->string('bukti')->nullable(); // upload bukti
            $table->text('keterangan')->nullable(); // tambahan keterangan
            
            // Status pengajuan (pending, acc, ditolak, batal)
            $table->enum('status', ['pending', 'acc', 'ditolak', 'batal'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
