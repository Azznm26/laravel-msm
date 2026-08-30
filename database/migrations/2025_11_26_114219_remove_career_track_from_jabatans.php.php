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
        // Hapus kolom 'career_track' dari tabel 'jabatans' karena redundan
        if (Schema::hasTable('jabatans') && Schema::hasColumn('jabatans', 'career_track')) {
            Schema::table('jabatans', function (Blueprint $table) {
                $table->dropColumn('career_track');
            });
        }
    }

    /**
     * Reverse the migrations.
     * (Hanya tambahkan kolom kembali, data akan hilang)
     */
    public function down(): void
    {
        if (Schema::hasTable('jabatans') && !Schema::hasColumn('jabatans', 'career_track')) {
            Schema::table('jabatans', function (Blueprint $table) {
                // Tambahkan kembali kolom, pastikan tipe data sesuai skema lama
                $table->string('career_track', 255)->nullable()->after('department_id');
            });
        }
    }
};
