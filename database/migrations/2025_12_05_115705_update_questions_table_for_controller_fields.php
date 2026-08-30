<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // 1. Mengubah nama kolom 'question_text' menjadi 'pertanyaan'
            // Ini akan menyelesaikan error 'Unknown column pertanyaan'
            $table->renameColumn('question_text', 'pertanyaan');

            // 2. Menambahkan kolom-kolom pilihan ganda
            // Kolom-kolom ini dibutuhkan oleh QuestionController Anda
            $table->string('pilihan_a')->after('pertanyaan');
            $table->string('pilihan_b')->after('pilihan_a');
            $table->string('pilihan_c')->after('pilihan_b');
            $table->string('pilihan_d')->after('pilihan_c');
            $table->string('jawaban_benar', 1)->after('pilihan_d'); // Menyimpan A, B, C, atau D (kecil)
            $table->unsignedSmallInteger('skor')->after('jawaban_benar');

            // Kolom 'image' sudah ada, jadi tidak perlu diubah.
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Rollback: Hapus kolom-kolom yang baru ditambahkan
            $table->dropColumn(['pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'jawaban_benar', 'skor']);

            // Rollback: Mengembalikan nama kolom dari 'pertanyaan' ke 'question_text'
            $table->renameColumn('pertanyaan', 'question_text');
        });
    }
};
