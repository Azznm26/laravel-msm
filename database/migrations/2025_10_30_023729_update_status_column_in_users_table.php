<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Jika kolom 'status' sudah ada sebagai string, drop dulu (opsional)
            // $table->dropColumn('status');

            // Tambahkan kolom status sebagai tinyInteger
            $table->tinyInteger('status')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Kembalikan ke tipe sebelumnya jika perlu (misalnya string)
            // Atau cukup drop kolom jika tidak dibutuhkan rollback
            $table->string('status')->nullable()->change();
        });
    }
};