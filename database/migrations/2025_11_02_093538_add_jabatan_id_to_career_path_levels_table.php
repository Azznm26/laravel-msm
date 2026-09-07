<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('career_path_levels', function (Blueprint $table) {
            $table->foreignId('jabatan_id')->nullable()->after('id')
                ->constrained('jabatans')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('career_path_levels', function (Blueprint $table) {
            $table->dropForeign(['jabatan_id']);
            $table->dropColumn('jabatan_id');
        });
    }
};
