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
        // 1. Perubahan pada tabel 'tasks'
        if (Schema::hasTable('tasks')) {
            Schema::table('tasks', function (Blueprint $table) {
                
                // Hapus Foreign Keys yang terikat pada Career Path/Jabatan target lama
                if (Schema::hasColumn('tasks', 'career_path_id')) {
                    $table->dropConstrainedForeignId('career_path_id');
                }
                if (Schema::hasColumn('tasks', 'jabatan_target')) {
                    $table->dropForeign(['jabatan_target']);
                }

                // Hapus kolom yang tidak relevan lagi
                if (Schema::hasColumn('tasks', 'target_level')) {
                    $table->dropColumn('target_level');
                }
                if (Schema::hasColumn('tasks', 'level')) {
                    $table->dropColumn('level');
                }
                if (Schema::hasColumn('tasks', 'career_track')) {
                    $table->dropColumn('career_track');
                }
                if (Schema::hasColumn('tasks', 'jabatan_target')) {
                    $table->dropColumn('jabatan_target');
                }
                
                // Kolom 'jabatan_id' (FK ke jabatans) DIPERTAHANKAN
            });
        }

        // 2. Hapus pivot table 'career_path_level_tasks'
        Schema::dropIfExists('career_path_level_tasks');
    }

    /**
     * Reverse the migrations.
     * (Untuk Rollback, hanya tambahkan kolom dan tabelnya kembali)
     */
    public function down(): void
    {
        // 1. Buat kembali pivot table (jika rollback diperlukan)
        Schema::create('career_path_level_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_path_level_id')->constrained('career_path_levels')->onDelete('cascade');
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->integer('urutan')->nullable();
            $table->integer('required_score')->default(80);
            $table->timestamps();
            $table->unique(['career_path_level_id', 'task_id']);
        });
        
        // 2. Tambahkan kolom ke tabel 'tasks' (Jika rollback diperlukan)
        if (Schema::hasTable('tasks')) {
            Schema::table('tasks', function (Blueprint $table) {
                // Tambahkan kolom yang dihapus
                $table->tinyInteger('level')->unsigned()->default(1);
                $table->string('career_track')->nullable();
                $table->tinyInteger('target_level')->unsigned()->nullable();
                $table->unsignedBigInteger('jabatan_target')->nullable();
                $table->unsignedBigInteger('career_path_id')->nullable();
                
                // Tambahkan kembali Foreign Keys
                $table->foreign('jabatan_target')->references('id')->on('jabatans')->onDelete('set null');
                $table->foreign('career_path_id')->references('id')->on('career_paths')->onDelete('set null');
            });
        }
    }
};