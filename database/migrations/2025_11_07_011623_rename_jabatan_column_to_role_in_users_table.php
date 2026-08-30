<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Hanya rename jika kolom 'jabatan' ada
            if (Schema::hasColumn('users', 'jabatan')) {
                $table->renameColumn('jabatan', 'role');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->renameColumn('role', 'jabatan');
            }
        });
    }
};
