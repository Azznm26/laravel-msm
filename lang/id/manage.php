<?php

return [

    // ==========================
    // Umum / dipakai bersama
    // ==========================
    'actions'          => 'Aksi',
    'save'             => 'Simpan Data',
    'cancel'           => 'Batal',
    'id'               => 'ID',

    // ==========================
    // Assign Role ke User
    // ==========================
    'assign_role' => [
        'title'                => 'Assign Role ke User',
        'subtitle'             => 'Kelola hak akses dan peran (role) untuk masing-masing pengguna.',
        'search_placeholder'   => 'Cari nama atau email...',
        'col_user'             => 'User',
        'col_current_role'     => 'Role Saat Ini',
        'col_actions'          => 'Aksi',
        'no_role'              => 'Belum ada role',
        'manage_role_button'   => 'Kelola Role',
        'not_found'            => 'Tidak ada user yang ditemukan.',
        'modal_title'          => 'Kelola Role:',
        'modal_description'    => 'Pilih role yang ingin diberikan kepada pengguna ini:',
        'empty_role_warning'   => 'Belum ada role yang tersedia. Silakan buat role di menu Manage Roles terlebih dahulu.',
        'submit'               => 'Update Role',
        'cancel'               => 'Batal',
    ],

    // ==========================
    // Manage Roles
    // ==========================
    'roles' => [
        'title'                 => 'Manage Roles',
        'subtitle'              => 'Kelola peran (role) yang tersedia di dalam sistem.',
        'add_button'            => 'Tambah Role',
        'col_id'                => 'ID',
        'col_name'              => 'Nama Role',
        'col_actions'           => 'Aksi',
        'confirm_delete'        => 'Yakin ingin menghapus role ini?',
        'not_found'             => 'Belum ada data role yang ditambahkan.',
        'modal_title_add'       => 'Tambah Role Baru',
        'modal_title_edit'      => 'Edit Role',
        'field_name'            => 'Nama Role',
        'field_name_placeholder' => 'contoh: manager, staff, admin',
        'submit'                => 'Simpan Data',
        'cancel'                => 'Batal',
    ],

    // ==========================
    // Manage Permissions
    // ==========================
    'permissions' => [
        'title'                 => 'Manage Permissions',
        'subtitle'              => 'Kelola hak akses (permission) spesifik untuk fitur-fitur sistem.',
        'add_button'            => 'Tambah Permission',
        'col_id'                => 'ID',
        'col_name'              => 'Nama Permission',
        'col_actions'           => 'Aksi',
        'confirm_delete'        => 'Yakin ingin menghapus permission ini?',
        'not_found'             => 'Belum ada data permission yang ditambahkan.',
        'modal_title_add'       => 'Tambah Permission Baru',
        'modal_title_edit'      => 'Edit Permission',
        'field_name'            => 'Nama Permission',
        'field_name_placeholder' => 'contoh: view-subordinates, edit-tasks',
        'submit'                => 'Simpan Data',
        'cancel'                => 'Batal',
    ],

];
