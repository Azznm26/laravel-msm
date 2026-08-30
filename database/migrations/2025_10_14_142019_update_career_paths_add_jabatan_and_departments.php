<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Hanya tambahkan jabatan_id jika belum ada
        if (!Schema::hasColumn('career_paths', 'jabatan_id')) {
            Schema::table('career_paths', function (Blueprint $table) {
                $table->foreignId('jabatan_id')
                    ->nullable()
                    ->constrained('jabatans')
                    ->onDelete('set null');
            });
        }

        // Buat tabel pivot hanya jika belum ada
        if (!Schema::hasTable('career_path_department')) {
            Schema::create('career_path_department', function (Blueprint $table) {
                $table->id();
                $table->foreignId('career_path_id')->constrained()->onDelete('cascade');
                $table->foreignId('department_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('career_path_department')) {
            Schema::dropIfExists('career_path_department');
        }

        if (Schema::hasColumn('career_paths', 'jabatan_id')) {
            Schema::table('career_paths', function (Blueprint $table) {
                $table->dropForeign(['jabatan_id']);
                $table->dropColumn('jabatan_id');
            });
        }
    }
};
