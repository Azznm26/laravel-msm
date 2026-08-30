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
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropColumn(['career_track', 'level_in_track']);
        });

        Schema::table('career_paths', function (Blueprint $table) {
            $table->dropForeign(['jabatan_id']);
            $table->dropColumn('jabatan_id');
        });
    }

    public function down()
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->string('career_track')->nullable();
            $table->tinyInteger('level_in_track')->unsigned()->default(1);
        });

        Schema::table('career_paths', function (Blueprint $table) {
            $table->unsignedBigInteger('jabatan_id')->nullable();
            $table->foreign('jabatan_id')->references('id')->on('jabatans')->onDelete('set null');
        });
    }
};
