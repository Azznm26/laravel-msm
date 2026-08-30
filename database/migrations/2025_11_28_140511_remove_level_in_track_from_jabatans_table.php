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
        Schema::table('jabatans', function (Blueprint $table) {
            // Cek dulu apakah kolomnya ada sebelum dihapus untuk menghindari error
            if (Schema::hasColumn('jabatans', 'level_in_track')) {
                $table->dropColumn('level_in_track');
            }

            // Opsional: Hapus juga 'career_track' jika masih ada dan tidak dipakai
            if (Schema::hasColumn('jabatans', 'career_track')) {
                $table->dropColumn('career_track');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jabatans', function (Blueprint $table) {
            // Jika di-rollback, tambahkan kembali kolomnya
            $table->integer('level_in_track')->default(1)->after('nama_jabatan');
            $table->string('career_track')->nullable()->after('nama_jabatan');
        });
    }
};
