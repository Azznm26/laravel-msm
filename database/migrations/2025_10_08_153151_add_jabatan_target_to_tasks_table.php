<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('jabatan_target')->nullable()->after('judul');
            $table->foreign('jabatan_target')->references('id')->on('jabatans')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['jabatan_target']);
            $table->dropColumn('jabatan_target');
        });
    }
};