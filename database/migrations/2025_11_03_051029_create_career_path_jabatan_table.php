<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('career_path_jabatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_path_id')->constrained()->onDelete('cascade');
            $table->foreignId('jabatan_id')->constrained()->onDelete('cascade');
            $table->integer('level_order')->default(0); // urutan jabatan dalam track
            $table->boolean('is_highest_level')->default(false); // apakah level tertinggi?
            $table->timestamps();

            $table->unique(['career_path_id', 'jabatan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_path_jabatan');
    }
};
