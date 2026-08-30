<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_uploaded_files', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('file_path');
            $table->timestamp('approved_at')->nullable()->after('is_approved');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
        });
    }

    public function down()
    {
        Schema::table('user_uploaded_files', function (Blueprint $table) {
            $table->dropColumn(['is_approved', 'approved_at', 'approved_by']);
        });
    }
};
