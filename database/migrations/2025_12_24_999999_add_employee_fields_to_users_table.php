<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'nip')) {
                $table->string('nip')->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('users', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('users', 'work_status')) {
                $table->string('work_status')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'nip')) {
                $table->dropColumn('nip');
            }
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('users', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('users', 'work_status')) {
                $table->dropColumn('work_status');
            }
        });
    }
};
