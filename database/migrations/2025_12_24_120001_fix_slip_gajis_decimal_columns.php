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
        if (Schema::hasTable('slip_gajis')) {
            Schema::table('slip_gajis', function (Blueprint $table) {
                $table->decimal('total_lembur_jam', 8, 2)->change();
                $table->decimal('total_telat_jam', 8, 2)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slip_gajis', function (Blueprint $table) {
            $table->integer('total_lembur_jam')->change();
            $table->integer('total_telat_jam')->change();
        });
    }
};
