<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('slip_gajis', function (Blueprint $table) {
    $table->id();

    $table->foreignId('karyawan_id')
        ->constrained('karyawans')
        ->cascadeOnDelete();

    $table->integer('bulan');
    $table->integer('tahun');
    $table->integer('total_lembur_jam')->default(0);
    $table->integer('total_telat_jam')->default(0);
    $table->bigInteger('potongan')->default(0);
    $table->bigInteger('total_gaji');
    $table->string('status')->default('draft');
    $table->timestamps();
});

}

public function down()
{
    Schema::dropIfExists('slip_gajis');
}

};
