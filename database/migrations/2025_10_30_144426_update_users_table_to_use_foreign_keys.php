<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom foreign key
            $table->unsignedBigInteger('department_id')->nullable()->after('email');
            $table->unsignedBigInteger('jabatan_id')->nullable()->after('department_id');

            // Buat foreign key constraint
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('jabatan_id')->references('id')->on('jabatans')->onDelete('set null');

            // Hapus kolom lama (opsional — bisa dipertahankan sementara untuk migrasi data)
            // $table->dropColumn(['department', 'jabatan']);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['jabatan_id']);
            $table->dropColumn(['department_id', 'jabatan_id']);
        });
    }
};