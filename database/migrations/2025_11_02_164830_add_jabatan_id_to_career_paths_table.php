<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJabatanIdToCareerPathsTable extends Migration
{
    public function up()
    {
        Schema::table('career_paths', function (Blueprint $table) {
            $table->foreignId('jabatan_id')->nullable()->after('id');
            $table->foreign('jabatan_id')->references('id')->on('jabatans')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('career_paths', function (Blueprint $table) {
            $table->dropForeign(['jabatan_id']);
            $table->dropColumn('jabatan_id');
        });
    }
}
