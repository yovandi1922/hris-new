<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('nama');
            $table->string('jabatan');
            $table->bigInteger('gaji_pokok')->default(3000000);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('karyawans');
    }
};
