<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jabatans', function (Blueprint $table) {
            // Pastikan kolom level_order belum ada
            if (!Schema::hasColumn('jabatans', 'level_order')) {
                $table->integer('level_order')->default(1)->after('nama_jabatan');
            }
        });
    }

    public function down()
    {
        Schema::table('jabatans', function (Blueprint $table) {
            if (Schema::hasColumn('jabatans', 'level_order')) {
                $table->dropColumn('level_order');
            }
        });
    }
};