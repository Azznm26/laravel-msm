<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('task_results', function (Blueprint $table) {
            // Tambahkan kolom status setelah persentase_benar
            $table->string('status')->nullable()->after('persentase_benar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_results', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
