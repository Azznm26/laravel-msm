<?php

/**
 * resources/lang/id/help.php
 *
 * Semua teks untuk modul "Bantuan & Info" (Panduan Sistem, SOP Perusahaan, Hubungi IT Support).
 * Karena pakai ekosistem lang bawaan Laravel, view tinggal panggil __('help.xxx')
 * dan isinya otomatis mengikuti locale aktif (session) tanpa JS tambahan.
 */

return [

    'back' => 'Kembali',

    'menu' => [
        'label'   => 'Bantuan & Info',
        'guide'   => 'Panduan Sistem',
        'sop'     => 'SOP Perusahaan',
        'support' => 'Hubungi IT Support',
    ],

    'guide' => [
        'title'    => 'Panduan Sistem',
        'subtitle' => 'Semua yang perlu Anda tahu supaya lancar pakai aplikasi ini.',

        // ⚠️ STATIC/HARDCODE — gampang diganti nanti kalau mau ambil dari API/CMS.
        'sections' => [
            [
                'icon'  => 'compass',
                'title' => 'Mulai dari Mana?',
                'body'  => 'Dashboard adalah halaman utama Anda. Di sana Anda bisa melihat progres karir, jumlah tugas yang tertunda, dan (khusus supervisor) ringkasan performa tim.',
            ],
            [
                'icon'  => 'map',
                'title' => 'Cara Melihat Jalur Karir',
                'body'  => "Tekan kartu 'Jalur Karir Saya' di Dashboard, atau buka menu Karir dari navigasi. Anda akan melihat level saat ini, EXP yang terkumpul, dan syarat naik ke level berikutnya.",
            ],
            [
                'icon'  => 'check-square',
                'title' => 'Cara Mengerjakan Tugas',
                'body'  => 'Buka menu Tugas, pilih salah satu evaluasi (Pilihan Ganda, Survey, atau Upload File), lalu ikuti instruksinya. Setelah selesai, Anda akan mendapatkan EXP sesuai jenis tugasnya.',
            ],
            [
                'icon'  => 'credit-card',
                'title' => 'Kenapa Tugas Terkunci?',
                'body'  => 'Beberapa tugas mengharuskan Anda melengkapi Foto ID Badge terlebih dahulu di halaman Profil. Ini untuk memastikan identitas Anda tervalidasi sebelum mengerjakan evaluasi.',
            ],
            [
                'icon'  => 'user',
                'title' => 'Mengubah Data Profil',
                'body'  => 'Buka menu Profil untuk mengubah nama, foto, email, atau kata sandi. Perubahan akan langsung tersinkron ke seluruh aplikasi.',
            ],
        ],
    ],

    'sop' => [
        'title'    => 'SOP Perusahaan',
        'subtitle' => 'Kebijakan dan prosedur resmi yang perlu Anda ketahui.',
        'updated'  => 'Diperbarui',

        // ⚠️ STATIC/HARDCODE — ganti isi array ini kalau nanti SOP diambil dari API/CMS.
        'items' => [
            [
                'icon'       => 'clock',
                'updated_at' => '2026',
                'title'      => 'SOP Kehadiran & Presensi',
                'body'       => 'Karyawan wajib melakukan presensi masuk dan pulang melalui sistem. Keterlambatan lebih dari 15 menit tanpa keterangan akan tercatat sebagai pelanggaran ringan.',
            ],
            [
                'icon'       => 'calendar',
                'updated_at' => '2026',
                'title'      => 'SOP Pengajuan Cuti',
                'body'       => 'Pengajuan cuti tahunan dilakukan minimal 3 hari kerja sebelumnya melalui atasan langsung. Cuti mendadak (sakit/darurat) dapat diajukan di hari yang sama dengan bukti pendukung.',
            ],
            [
                'icon'       => 'shield',
                'updated_at' => '2026',
                'title'      => 'Kode Etik Karyawan',
                'body'       => 'Setiap karyawan wajib menjaga kerahasiaan data perusahaan, bersikap profesional terhadap rekan kerja, dan menghindari konflik kepentingan dalam bentuk apa pun.',
            ],
            [
                'icon'       => 'alert-triangle',
                'updated_at' => '2026',
                'title'      => 'SOP Keselamatan Kerja',
                'body'       => 'Gunakan alat pelindung diri (APD) sesuai area kerja masing-masing. Laporkan segera setiap potensi bahaya kepada supervisor atau tim K3 terdekat.',
            ],
            [
                'icon'       => 'trending-up',
                'updated_at' => '2026',
                'title'      => 'SOP Evaluasi & Kenaikan Jenjang',
                'body'       => 'Kenaikan jenjang karir ditentukan oleh akumulasi EXP dari evaluasi yang diselesaikan, ditambah penilaian dari atasan langsung setiap periode penilaian.',
            ],
        ],
    ],

    'support' => [
        'title'        => 'Hubungi IT Support',
        'subtitle'     => 'Mengalami kendala di aplikasi? Hubungi kami lewat salah satu kanal di bawah.',
        'hours_label'  => 'Jam layanan',
        'hours'        => 'Senin–Jumat, 08.00–17.00 WIB',
        'call_title'   => 'Telepon',
        'wa_desc'      => 'Respons paling cepat',
        'wa_message'   => 'Halo IT Support, saya butuh bantuan terkait aplikasi.',
        'mail_subject' => 'Permintaan Bantuan Aplikasi',
    ],

];
