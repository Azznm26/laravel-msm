<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('judul'); // Judul tugas
            $table->text('deskripsi'); // deskripsi umum
            $table->text('pertanyaan')->nullable(); // Isi essai (tambahan)
            $table->string('file_path')->nullable(); // Lokasi file lampiran (tambahan)
            $table->string('jabatan_target');
            $table->enum('status', ['draft', 'published', 'completed'])->default('draft');
            $table->timestamp('deadline')->nullable();
            $table->integer('total_skor')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
