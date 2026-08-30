<?php

namespace Database\Seeders;

use App\Models\CareerPath;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CareerPathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        CareerPath::insert([
            ['name' => 'Junior Developer', 'description' => 'Mulai menulis kode dan belajar dasar pengembangan.', 'order' => 1],
            ['name' => 'Mid-Level Developer', 'description' => 'Mengembangkan fitur kompleks dan bekerja dalam tim.', 'order' => 2],
            ['name' => 'Senior Developer', 'description' => 'Memimpin proyek dan mentoring junior developer.', 'order' => 3],
            ['name' => 'Tech Lead', 'description' => 'Mengambil keputusan teknis dan mengelola arsitektur sistem.', 'order' => 4],
        ]);
    }
}
