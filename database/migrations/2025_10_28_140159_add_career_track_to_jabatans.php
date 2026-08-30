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
            $table->string('career_track')->nullable();
            $table->unsignedTinyInteger('level_in_track')->default(1)->after('career_track');
        });
    }

    public function down()
    {
        Schema::table('jabatans', function (Blueprint $table) {
            $table->dropColumn(['career_track', 'level_in_track']);
        });
    }
};
