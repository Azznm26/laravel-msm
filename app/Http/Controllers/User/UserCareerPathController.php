<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CareerPath;
use App\Models\CareerPathLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCareerPathController extends Controller
{
    /**
     * Menampilkan Dashboard Career Path.
     * Menampilkan LIST semua jalur karir yang dimiliki user (Primary & Secondary).
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil semua career path user dari tabel pivot
        // Kita eager load 'levels' untuk efisiensi query
        $myPaths = $user->careerPaths()
            ->with(['levels' => function ($q) {
                $q->orderBy('level', 'asc');
            }])
            ->get();

        if ($myPaths->isEmpty()) {
            return view('user.career-path.index', [
                'myPaths' => collect(), // Collection kosong
                'error' => 'Anda belum memiliki Career Path. Hubungi admin.'
            ]);
        }

        // PROSES KALKULASI PROGRESS (Looping setiap path)
        // Kita "suntikkan" data progress ke dalam object path agar mudah dipanggil di View
        $myPaths->transform(function ($path) {

            // 1. Ambil Data dari Pivot
            $currentLvlNum = $path->pivot->current_level;
            $currentExp    = $path->pivot->total_exp;

            // 2. Cari Object Level Saat Ini & Level Berikutnya
            // (Kita cari dari relation yang sudah di-eager load tadi)
            $currentLevelModel = $path->levels->firstWhere('level', $currentLvlNum);
            $nextLevelModel    = $path->levels->firstWhere('level', $currentLvlNum + 1);

            // 3. Hitung Target & Persentase
            $expRequired = $nextLevelModel ? $nextLevelModel->required_exp : 0;

            // Rumus Persentase:
            // Jika Max Level (tidak ada next level), progress 100%
            if (!$nextLevelModel) {
                $percentage = 100;
                $status = 'Max Level';
            } else {
                // Hindari pembagian dengan nol
                $percentage = ($expRequired > 0)
                    ? min(100, round(($currentExp / $expRequired) * 100))
                    : 0;
                $status = 'In Progress';
            }

            // 4. Attach data hasil hitungan ke object path (Temporary Attribute)
            $path->calculated_current_level = $currentLevelModel;
            $path->calculated_next_level    = $nextLevelModel;
            $path->progress_percentage      = $percentage;
            $path->status_label             = $status;

            // Cek apakah ini Primary Path? (Berdasarkan ID di tabel user)
            $path->is_primary = (auth()->user()->career_path_id == $path->id);

            return $path;
        });

        return view('user.career-path.index', compact('myPaths'));
    }

    /**
     * Menampilkan Detail Satu Career Path.
     * URL: /user/career-path/{careerPath}
     */
    public function show(CareerPath $careerPath)
    {
        $user = Auth::user();

        // 1. Validasi Keamanan:
        // Cek apakah user benar-benar memiliki path ini di pivot table?
        $pivotData = $user->careerPaths()
            ->where('career_path_id', $careerPath->id)
            ->first();

        if (!$pivotData) {
            return redirect()->route('user.career-path.index')
                ->with('error', 'Anda tidak terdaftar di jalur karir ini.');
        }

        // 2. Load Levels
        $careerPath->load(['levels.jabatan']);
        $levels = $careerPath->levels->sortBy('level');

        // 3. Ambil Data Progress Spesifik Path Ini
        $currentLvlNum = $pivotData->pivot->current_level;
        $totalExp      = $pivotData->pivot->total_exp;

        $currentLevelModel = $levels->firstWhere('level', $currentLvlNum);
        $nextLevelModel    = $levels->firstWhere('level', $currentLvlNum + 1);

        // Hitung Persentase (sama seperti index, tapi khusus satu path)
        $expRequired = $nextLevelModel ? $nextLevelModel->required_exp : 0;
        $percentage  = (!$nextLevelModel)
            ? 100
            : (($expRequired > 0) ? min(100, round(($totalExp / $expRequired) * 100)) : 0);

        return view('user.career-path.show', compact(
            'careerPath',
            'levels',
            'currentLevelModel',
            'nextLevelModel',
            'totalExp',
            'percentage',
            'pivotData' // Untuk akses start_date, dll
        ));
    }

    // Helper jika ingin melihat history progress (Opsional/Masa Depan)
    public function showCareerProgress()
    {
        return redirect()->route('user.career-path.index');
    }
}
