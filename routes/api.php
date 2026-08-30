<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApprovalApiController;
use App\Http\Controllers\Api\DepartmentApiController;
use App\Http\Controllers\Api\AdminDashboardApiController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\DeviceTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ─── RUTE PUBLIK ──────────────────────────────────────────────────────────
// Rute ini dibiarkan terbuka tanpa middleware karena pengguna harus
// mengaksesnya untuk membuat token pertama kali.
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


// ─── RUTE TERLINDUNGI ───────────────────────────────────────────────────
// Semua rute di dalam blok ini WAJIB menyertakan token Bearer dari Sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user',          [UserController::class, 'apiShow']);
    Route::put('/user/profile',  [UserController::class, 'apiUpdateProfile']);
    Route::put('/user/password', [UserController::class, 'apiUpdatePassword']);
    Route::post('/user/photo',   [UserController::class, 'apiUpdatePhoto']);
    Route::post('/user/badge-photo', [UserController::class, 'apiUpdateBadgePhoto']);

    // Rute untuk fitur utama
    Route::get('/user/career-paths', [UserController::class, 'apiCareerPaths']);
    Route::get('/tasks', [App\Http\Controllers\Api\TaskApiController::class, 'index']);
    Route::post('/tasks/{id}/submit-quiz', [App\Http\Controllers\Api\TaskApiController::class, 'submitQuiz']);
    Route::post('/tasks/{id}/submit-survey', [App\Http\Controllers\Api\TaskApiController::class, 'submitSurvey']);
    Route::post('/tasks/{id}/submit-upload', [App\Http\Controllers\Api\TaskApiController::class, 'submitUploadFile']);

    //Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);

    Route::post('/device-token', [DeviceTokenController::class, 'register']);
    Route::delete('/device-token', [DeviceTokenController::class, 'unregister']);

    // ─── ADMIN: PERSETUJUAN USER BARU (mobile) ──────────────────────────
    // Versi API dari App\Livewire\Admin\ApprovalManager, khusus dipakai mobile.
    // Otorisasi role admin/super_admin sudah dicek di dalam ApprovalApiController.
    Route::prefix('admin/approval')->group(function () {
        Route::get('/pending-users',           [ApprovalApiController::class, 'pendingUsers']);
        Route::get('/departments',             [ApprovalApiController::class, 'departments']);
        Route::get('/jabatans',                [ApprovalApiController::class, 'jabatans']);
        Route::get('/career-paths',            [ApprovalApiController::class, 'careerPaths']);
        Route::post('/users/{userId}/approve', [ApprovalApiController::class, 'approve']);
        Route::post('/users/{userId}/reject',  [ApprovalApiController::class, 'reject']);
    });

    Route::get('/admin/dashboard/summary', [AdminDashboardApiController::class, 'summary']);

    Route::prefix('admin/departments')->group(function () {
        Route::get('/',              [DepartmentApiController::class, 'index']);
        Route::post('/',             [DepartmentApiController::class, 'store']);       // create
        Route::put('/{id}',          [DepartmentApiController::class, 'store']);       // update
        Route::delete('/delete-all', [DepartmentApiController::class, 'destroyAll']);  // ⚠️ taruh SEBELUM /{id}
        Route::delete('/{id}',       [DepartmentApiController::class, 'destroy']);
    });

    // Jangan lupa tambahkan rute logout di sini agar token bisa dihancurkan
    Route::post('/logout', [AuthController::class, 'logout']);
});
