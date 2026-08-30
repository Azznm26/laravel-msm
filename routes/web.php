<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// 🔹 Panggil Komponen Livewire Auth
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\DepartmentIndex;
use App\Livewire\Admin\LaporanNilai;
use App\Livewire\Notification;
use App\Http\Controllers\LocaleController;

// Controllers
use App\Http\Controllers\SubordinateController;
use App\Http\Controllers\User\UserCareerPathController;
use App\Http\Controllers\User\UserTaskController;

// Admin Controllers
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\SurveyController;
use App\Http\Controllers\Admin\AdminHistoryController;
use App\Http\Middleware\AdminAccessMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==============================
// 🔹 Public Route (Landing Page)
// ==============================
Route::get('/', function () {
    return view('welcome');
})->name('landing');

// 🔹 Switch bahasa
Route::get('locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('locale.switch');

// ==============================
// 🔹 Guest Routes (Livewire Auth)
// ==============================
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// ==============================
// 🔹 Authenticated Routes (Logout, Profile)
// ==============================
Route::middleware('auth')->group(function () {

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::get('/profile', \App\Livewire\Profile\Edit::class)->name('profile');

    Route::get('/notifications', \App\Livewire\Notifications::class)->name('notifications');
});

// ==============================
// 🔹 User Routes (role: user biasa)
// ==============================
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user/dashboard', \App\Livewire\User\UserDashboard::class)->name('user.dashboard');

    Route::get('/user/career-path', \App\Livewire\User\CareerPathViewer::class)->name('user.career-path.index');

    Route::get('/tasks', \App\Livewire\User\TaskViewer::class)->name('user.tasks.index');

    // ==============================
    // 🔹 Bantuan & Info
    // ==============================
    Route::prefix('bantuan')->group(function () {
        Route::view('/panduan', 'user.help.guide')->name('user.help.guide');
        Route::view('/sop', 'user.help.sop')->name('user.help.sop');

        Route::get('/support', function () {
            return view('user.help.support', [
                'itContact' => [
                    'phone'    => '+622112345678',
                    'whatsapp' => '622812345678',
                    'email'    => 'itsupport@goldpath.co.id',
                ],
            ]);
        })->name('user.help.support');
    });

    // Subordinates — SUDAH BENAR, pakai permission (bukan role), dipertahankan apa adanya
    Route::middleware(['can:view-subordinates'])->group(function () {
        Route::get('/bawahan', [SubordinateController::class, 'index'])->name('bawahan.index');
        Route::get('/bawahan/{subordinate}', [SubordinateController::class, 'show'])->name('bawahan.show');
        Route::get('/bawahan/{subordinate}/career-progress', [SubordinateController::class, 'careerProgress'])
            ->name('bawahan.career-progress');
    });

    Route::get('/career-progress', [UserCareerPathController::class, 'showCareerProgress'])
        ->name('user.career-progress');

    Route::delete('/clear-approval-notification/{cacheKey}', function ($cacheKey) {
        if (auth()->check() && str_starts_with($cacheKey, 'upload_approved_' . auth()->id() . '_')) {
            \Cache::forget($cacheKey);
        }
        return response()->json(['success' => true]);
    })->name('user.clear-approval-notification');
});

// ==============================
// 🔹 Admin Routes
// ==============================
// Gerbang utama: AdminAccessMiddleware (versi baru, cek "punya permission
// apa pun" — bukan lagi hardcode nama role). Setelah lolos gerbang utama,
// SETIAP grup di bawah dijaga permission-nya masing-masing lewat 'can:',
// supaya siapa saja yang punya permission tertentu otomatis bisa akses
// menu terkait — tanpa perlu edit file ini lagi kalau ada role baru.
// Nama & isi permission diatur bebas lewat halaman Manage Permissions /
// Manage Roles / Assign Role.
// ==============================
Route::middleware(['auth', AdminAccessMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard — cukup lolos gerbang utama, tidak perlu permission spesifik
        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        // ==============================
        // 1 & 2. MANAGE USER + APPROVAL USER
        // ==============================
        Route::middleware('can:manage-users')->group(function () {
            Route::get('/manage-user', \App\Livewire\Admin\ManageUser::class)->name('manage-user.index');
            Route::get('/approval', \App\Livewire\Admin\ApprovalManager::class)->name('approval.index');
        });

        // ==============================
        // ROUTE HISTORY
        // ==============================
        Route::middleware('can:view-history')->group(function () {
            Route::get('/history', [AdminHistoryController::class, 'index'])->name('history.index');
            Route::get('/history/export-excel', [AdminHistoryController::class, 'exportExcel'])->name('history.export_excel');
            Route::get('/history/export-pdf', [AdminHistoryController::class, 'exportPdf'])->name('history.export_pdf');
        });

        // ==============================
        // 3 & 8. CAREER PATH & LEVEL + USER CAREER PATH ASSIGNMENT
        // ==============================
        Route::middleware('can:manage-career-path')->group(function () {
            Route::get('/career-path', \App\Livewire\Admin\CareerPathManager::class)->name('career-path.index');
            Route::get('/user-career-path', \App\Livewire\Admin\UserCareerPathManager::class)->name('user-career-path.index');
        });

        // ==============================
        // 4. MONITORING (Career Monitoring, Task Monitoring, Laporan Nilai, Gap Analysis/Competency)
        // ==============================
        Route::middleware('can:view-reports')->group(function () {
            Route::prefix('career-monitoring')->name('career-monitoring.')->group(function () {
                Route::get('/', \App\Livewire\Admin\CareerMonitoringManager::class)->name('index');
                Route::get('/{user}', \App\Livewire\Admin\CareerMonitoringManager::class)->name('show');
            });

            Route::prefix('task-monitoring')->name('task-monitoring.')->group(function () {
                Route::get('/', \App\Livewire\Admin\TaskMonitoringManager::class)->name('index');
            });

            Route::get('/laporan-nilai', LaporanNilai::class)->name('reports.index');
            Route::get('/competency', \App\Livewire\Admin\CompetencyManager::class)->name('competency.index');
        });

        // ==============================
        // 5. MANAGE TASK (Task, Assign Task, Survey, Questions)
        // ==============================
        Route::middleware('can:manage-tasks')->group(function () {
            Route::get('/task/{task}/assign', \App\Livewire\Admin\TaskAssign::class)->name('task.assign');
            Route::get('/task', \App\Livewire\Admin\TaskIndex::class)->name('task.index');
            Route::get('/task/{task}/survey/create', [SurveyController::class, 'create'])->name('survey.create');
            Route::post('/task/{task}/survey', [SurveyController::class, 'store'])->name('survey.store');
            Route::get('/task/{task}/questions', \App\Livewire\Admin\QuestionManager::class)->name('question.create');
        });

        // ==============================
        // 6 & 7. DEPARTMENTS + JABATAN
        // ==============================
        Route::middleware('can:manage-departments')->group(function () {
            Route::get('departments', DepartmentIndex::class)->name('departments.index');
            Route::get('/jabatan', \App\Livewire\Admin\JabatanIndex::class)->name('jabatan.indexDepartments');
        });

        // ==============================
        // 10. MANAJEMEN AKSES (RBAC) — permission 'manage-rbac'.
        // Secara default cuma dicentang ke role super_admin lewat UI,
        // tapi kalau nanti mau kasih ke role lain, tinggal centang
        // lewat halaman Assign Role — TANPA ubah kode ini lagi.
        // ==============================
        Route::middleware('can:manage-rbac')->group(function () {
            Route::get('/roles', \App\Livewire\Admin\RoleIndex::class)->name('roles.index');
            Route::get('/permissions', \App\Livewire\Admin\PermissionIndex::class)->name('permissions.index');
            Route::get('/user-roles', \App\Livewire\Admin\UserRoleIndex::class)->name('user-roles.index');
        });
    });
