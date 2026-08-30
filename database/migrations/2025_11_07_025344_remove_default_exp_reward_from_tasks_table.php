<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDefaultExpRewardFromTasksTable extends Migration
{
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Hapus default value dari exp_reward
            $table->integer('exp_reward')->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Kembalikan ke default 10 (jika perlu rollback)
            $table->integer('exp_reward')->default(10)->change();
        });
    }
}
