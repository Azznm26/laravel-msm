<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_career_progress', function (Blueprint $table) {
            $table->unsignedInteger('total_exp')->default(0)->after('current_level');
        });
    }
};
