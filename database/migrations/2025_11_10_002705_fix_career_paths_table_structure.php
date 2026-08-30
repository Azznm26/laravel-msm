<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Kolom jabatan_id sudah dihapus manual — tidak perlu action
        // Hanya untuk menandai bahwa struktur sudah benar
    }

    public function down()
    {
        // Tidak perlu rollback
    }
};
