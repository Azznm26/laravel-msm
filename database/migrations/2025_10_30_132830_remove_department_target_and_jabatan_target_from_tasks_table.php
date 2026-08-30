<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['department_target']);
            $table->dropForeign(['jabatan_target']);
            $table->dropColumn(['department_target', 'jabatan_target']);
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('department_target')->nullable();
            $table->unsignedBigInteger('jabatan_target')->nullable();
            $table->foreign('department_target')->references('id')->on('departments');
            $table->foreign('jabatan_target')->references('id')->on('jabatans');
        });
    }
};
