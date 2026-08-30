<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('career_path_id')->nullable()->after('jabatan_id');
            $table->foreign('career_path_id')->references('id')->on('career_paths')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['career_path_id']);
            $table->dropColumn('career_path_id');
        });
    }
};
