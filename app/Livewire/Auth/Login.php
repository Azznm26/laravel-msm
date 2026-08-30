<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;


class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    protected function throttleKey()
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
    public function authenticate()
    {
        $this->validate();

        // 1. Cek apakah user sudah salah memasukkan password sebanyak 5 kali
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey());

            $this->addError('email', 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $seconds . ' detik.');
            return;
        }

        // 2. Percobaan login
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {

            // Jika login sukses, bersihkan riwayat kegagalan
            RateLimiter::clear($this->throttleKey());

            session()->regenerate();

            $user = Auth::user();

            // Cek Admin & Super Admin
            if (in_array($user->role, ['admin', 'super_admin'])) {
                return redirect()->route('admin.dashboard');
            }

            // Cek User Biasa
            if ($user->role === 'user') {
                if ($user->status == 1) {
                    return redirect()->route('user.tasks.index');
                }

                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();

                $this->addError('email', 'Akun Anda sedang menunggu persetujuan Admin. Harap tunggu notifikasi selanjutnya.');
                return;
            }
        }

        // 3. Jika login gagal, rekam kegagalan ini (kunci selama 60 detik jika limit tercapai)
        RateLimiter::hit($this->throttleKey(), 60);

        $this->addError('email', 'Email atau kata sandi salah.');
        $this->reset('password');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}
