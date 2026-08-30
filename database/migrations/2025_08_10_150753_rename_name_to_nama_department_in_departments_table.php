<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->renameColumn('name', 'nama_department'); // Ganti nama kolom
        });
    }

    public function down()
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->renameColumn('nama_department', 'name');
        });
    }
};