<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hanya tambahkan kolom jika belum ada
            if (!Schema::hasColumn('users', 'requested_jabatan')) {
                $table->string('requested_jabatan')->nullable();
            }
            if (!Schema::hasColumn('users', 'requested_department')) {
                $table->string('requested_department')->nullable();
            }
            
            // Pastikan kolom jabatan & department nullable (jika sudah ada)
            // Tidak perlu tambah kolom yang sudah ada
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'requested_jabatan')) {
                $table->dropColumn('requested_jabatan');
            }
            if (Schema::hasColumn('users', 'requested_department')) {
                $table->dropColumn('requested_department');
            }
        });
    }
};