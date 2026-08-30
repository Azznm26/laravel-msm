<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Ubah kolom menjadi nullable
            $table->foreignId('jabatan_target')->nullable()->change();
            $table->foreignId('department_target')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Kembalikan ke NOT NULL (opsional)
            $table->foreignId('jabatan_target')->nullable(false)->change();
            $table->foreignId('department_target')->nullable(false)->change();
        });
    }
};