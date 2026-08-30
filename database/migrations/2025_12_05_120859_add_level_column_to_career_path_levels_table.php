<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('career_path_levels', function (Blueprint $table) {
            // Menambahkan kolom 'level' yang hilang
            // Kolom ini digunakan oleh Controller Anda untuk sorting
            $table->unsignedSmallInteger('level')->nullable()->after('jabatan_id');
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('career_path_levels', function (Blueprint $table) {
            // Menghapus kolom 'level' saat rollback
            $table->dropColumn('level');
        });
    }
};
