<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_career_path_jabatans_table.php
    public function up()
    {
        Schema::create('career_path_jabatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_path_id')->constrained()->onDelete('cascade');
            $table->foreignId('jabatan_id')->constrained()->onDelete('cascade');
            $table->unique(['career_path_id', 'jabatan_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('career_path_jabatans');
    }
};
