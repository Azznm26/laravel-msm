<?php

return [

    // ==========================
    // Shared / common
    // ==========================
    'actions'          => 'Actions',
    'save'             => 'Save',
    'cancel'           => 'Cancel',
    'id'               => 'ID',

    // ==========================
    // Assign Role to User
    // ==========================
    'assign_role' => [
        'title'                => 'Assign Role to User',
        'subtitle'             => 'Manage access rights and roles for each user.',
        'search_placeholder'   => 'Search by name or email...',
        'col_user'             => 'User',
        'col_current_role'     => 'Current Role',
        'col_actions'          => 'Actions',
        'no_role'              => 'No role assigned',
        'manage_role_button'   => 'Manage Role',
        'not_found'            => 'No users found.',
        'modal_title'          => 'Manage Role:',
        'modal_description'    => 'Select the role(s) to assign to this user:',
        'empty_role_warning'   => 'No roles are available yet. Please create a role in the Manage Roles menu first.',
        'submit'               => 'Update Role',
        'cancel'               => 'Cancel',
    ],

    // ==========================
    // Manage Roles
    // ==========================
    'roles' => [
        'title'                 => 'Manage Roles',
        'subtitle'              => 'Manage the roles available in the system.',
        'add_button'            => 'Add Role',
        'col_id'                => 'ID',
        'col_name'              => 'Role Name',
        'col_actions'           => 'Actions',
        'confirm_delete'        => 'Are you sure you want to delete this role?',
        'not_found'             => 'No role data has been added yet.',
        'modal_title_add'       => 'Add New Role',
        'modal_title_edit'      => 'Edit Role',
        'field_name'            => 'Role Name',
        'field_name_placeholder' => 'e.g. manager, staff, admin',
        'submit'                => 'Save',
        'cancel'                => 'Cancel',
    ],

    // ==========================
    // Manage Permissions
    // ==========================
    'permissions' => [
        'title'                 => 'Manage Permissions',
        'subtitle'              => 'Manage specific access permissions for system features.',
        'add_button'            => 'Add Permission',
        'col_id'                => 'ID',
        'col_name'              => 'Permission Name',
        'col_actions'           => 'Actions',
        'confirm_delete'        => 'Are you sure you want to delete this permission?',
        'not_found'             => 'No permission data has been added yet.',
        'modal_title_add'       => 'Add New Permission',
        'modal_title_edit'      => 'Edit Permission',
        'field_name'            => 'Permission Name',
        'field_name_placeholder' => 'e.g. view-subordinates, edit-tasks',
        'submit'                => 'Save',
        'cancel'                => 'Cancel',
    ],

];
