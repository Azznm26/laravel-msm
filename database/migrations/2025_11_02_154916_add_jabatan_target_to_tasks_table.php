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
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('jabatan_target')->nullable()->constrained('jabatans')->onDelete('set null');
            // atau jika hanya butuh ID tanpa FK:
            // $table->unsignedBigInteger('jabatan_target')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('jabatan_target');
        });
    }
};
