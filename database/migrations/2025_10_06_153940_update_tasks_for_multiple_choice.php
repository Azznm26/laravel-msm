<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'jabatan_target')) {
                $table->dropColumn('jabatan_target');
            }
            if (Schema::hasColumn('tasks', 'pertanyaan')) {
                $table->dropColumn('pertanyaan');
            }
            if (Schema::hasColumn('tasks', 'file_path')) {
                $table->dropColumn('file_path');
            }
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'jabatan_target')) {
                $table->string('jabatan_target')->nullable();
            }
            if (!Schema::hasColumn('tasks', 'pertanyaan')) {
                $table->text('pertanyaan')->nullable();
            }
            if (!Schema::hasColumn('tasks', 'file_path')) {
                $table->string('file_path')->nullable();
            }
        });
    }
};