@extends(in_array(auth()->user()->role, ['admin', 'super_admin']) ? 'layouts.admin' : 'layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">

    {{-- Card Container dengan Style Amber --}}
    <div class="bg-white rounded-xl shadow-lg border-t-4 border-amber-500 p-4 md:p-8">

        <div class="flex items-center gap-3 mb-6 pb-3 md:pb-4 border-b border-gray-100">
            <div class="p-2 bg-amber-50 rounded-lg text-amber-600 shrink-0">
                <i data-lucide="user-cog" class="w-6 h-6"></i>
            </div>
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-800">Profil Pengguna</h2>
                <p class="text-xs md:text-sm text-gray-500">Kelola informasi akun dan keamanan Anda.</p>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- 1. FOTO PROFIL --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-3">Foto Profil</label>
                <div class="flex flex-col sm:flex-row items-center gap-4 md:gap-6">
                    {{-- Preview Foto --}}
                    <div class="flex-shrink-0 relative">
                        @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" id="photo-preview-img"
                            alt="Foto Profil"
                            class="h-24 w-24 md:h-28 md:w-28 rounded-full object-cover border-4 border-amber-100 shadow-md">
                        @else
                        <div id="photo-placeholder" class="h-24 w-24 md:h-28 md:w-28 rounded-full bg-amber-50 flex items-center justify-center border-4 border-amber-100 shadow-sm text-amber-300">
                            <i data-lucide="user" class="w-10 h-10 md:w-12 md:h-12"></i>
                        </div>
                        <img id="photo-preview-img" class="h-24 w-24 md:h-28 md:w-28 rounded-full object-cover border-4 border-amber-100 shadow-md hidden">
                        @endif

                        {{-- Tombol Edit Kecil di Foto --}}
                        <label for="photo" class="absolute bottom-0 right-0 bg-white rounded-full p-1.5 md:p-2 shadow-md border border-gray-200 cursor-pointer hover:bg-amber-50 text-gray-600 hover:text-amber-600 transition">
                            <i data-lucide="camera" class="w-3.5 h-3.5 md:w-4 md:h-4"></i>
                            <input type="file" name="photo" id="photo" accept="image/*" class="hidden">
                        </label>
                    </div>

                    {{-- Info File --}}
                    <div class="flex-grow text-center sm:text-left">
                        <p class="text-sm font-medium text-gray-900">Ubah Foto Profil</p>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                        @error('photo')
                        <p class="mt-2 text-xs text-red-600 flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 2. INFORMASI DASAR (Stack di mobile, Grid di desktop) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="user" class="h-4 w-4 text-gray-400"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="pl-10 block w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500 sm:text-sm transition">
                    </div>
                    @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700">Email Address</label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-4 w-4 text-gray-400"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="pl-10 block w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500 sm:text-sm transition">
                    </div>
                    @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- 3. INFORMASI ROLE & JABATAN (Updated for Multiple Career Paths) --}}
            <div class="bg-gray-50 rounded-lg p-4 grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 border border-gray-200">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide">Role Sistem</label>
                    <div class="mt-1 flex items-center gap-2 text-gray-700 font-medium text-sm">
                        <i data-lucide="shield" class="w-4 h-4 text-amber-500"></i>
                        {{
                            match($user->role) {
                                'super_admin' => 'Super Admin (HRD Pusat)',
                                'admin' => 'Admin Departemen',
                                'user' => 'Karyawan Biasa',
                                default => 'Tidak Diketahui'
                            }
                        }}
                    </div>
                </div>

                @if($user->role !== 'super_admin')
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide">Departemen</label>
                    <div class="mt-1 flex items-center gap-2 text-gray-700 font-medium text-sm">
                        <i data-lucide="building-2" class="w-4 h-4 text-amber-500"></i>
                        {{ $user->department?->nama_department ?? '-' }}
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Career Paths / Jabatan</label>

                    @if(isset($user->careerPaths) && $user->careerPaths->count() > 0)
                    {{-- TAMPILAN UNTUK BANYAK CAREER PATH --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($user->careerPaths as $path)
                        <div class="flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-2 bg-amber-50 rounded-md text-amber-600 shrink-0">
                                <i data-lucide="git-fork" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $path->name ?? $path->judul_career_path ?? 'Nama Path Tidak Tersedia' }}</p>
                                <p class="text-xs text-gray-500">Level: {{ $path->level ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    {{-- TAMPILAN DEFAULT (SINGLE JABATAN) --}}
                    <div class="flex items-center gap-2 text-gray-700 font-medium text-sm bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                        <i data-lucide="briefcase" class="w-4 h-4 text-amber-500"></i>
                        {{ $user->jabatan?->nama_jabatan ?? 'Belum ditentukan' }}
                    </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- 4. ID BADGE --}}
            <div class="border-t border-gray-100 pt-6">
                <label class="block text-sm font-bold text-gray-700">
                    Foto ID Badge <span class="text-red-500">*</span>
                </label>
                <div class="mt-2 flex flex-col sm:flex-row items-start gap-4 md:gap-6">

                    {{-- Preview Badge --}}
                    <div class="flex-shrink-0">
                        @if($user->id_badge)
                        <img src="{{ asset('storage/' . $user->id_badge) }}" id="badge-preview-img"
                            alt="ID Badge"
                            class="h-24 w-40 sm:h-32 sm:w-52 rounded-lg object-cover border-2 border-gray-200 shadow-sm">
                        @else
                        <div id="badge-placeholder" class="h-24 w-40 sm:h-32 sm:w-52 rounded-lg bg-gray-100 flex items-center justify-center border-2 border-dashed border-gray-300 text-gray-400">
                            <div class="text-center">
                                <i data-lucide="credit-card" class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-1"></i>
                                <span class="text-xs">No Image</span>
                            </div>
                        </div>
                        <img id="badge-preview-img" class="h-24 w-40 sm:h-32 sm:w-52 rounded-lg object-cover border-2 border-gray-200 shadow-sm hidden">
                        @endif
                    </div>

                    {{-- Upload Control --}}
                    <div class="flex-grow space-y-3">
                        <label for="id_badge" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-amber-50 hover:text-amber-700 hover:border-amber-300 focus:outline-none cursor-pointer transition">
                            <i data-lucide="upload" class="w-4 h-4 mr-2"></i>
                            Upload ID Badge Baru
                        </label>
                        <input type="file" name="id_badge" id="id_badge" accept="image/*" class="hidden">

                        <p class="text-xs text-gray-500">
                            Wajib untuk verifikasi assessment. Pastikan nama dan foto terlihat jelas.
                        </p>
                        @error('id_badge')
                        <p class="text-xs text-red-600 flex items-center gap-1">
                            <i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- 5. KEAMANAN (GANTI PASSWORD) --}}
            <div class="border-t border-gray-100 pt-6">
                <h3 class="text-base md:text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="lock" class="w-5 h-5 text-amber-500"></i> Keamanan
                </h3>

                <div class="space-y-4 max-w-2xl">
                    {{-- Current Password --}}
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm transition">
                        @error('current_password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Passwords (Grid 2 Kolom di desktop, Stack di mobile) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <input type="password" name="new_password" id="new_password"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm transition">
                            @error('new_password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm transition">
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER TOMBOL AKSI --}}
            <div class="pt-6 flex items-center justify-between gap-3 border-t border-gray-100">
                @php
                $backRoute = in_array(auth()->user()->role, ['admin', 'super_admin']) ? route('admin.dashboard') : route('user.tasks.index');
                @endphp

                <a href="{{ $backRoute }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Kembali
                </a>

                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Inisialisasi Lucide Icons
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });

    // Preview Foto Profil
    document.getElementById('photo').addEventListener('change', function(e) {
        const [file] = e.target.files;
        if (file) {
            const previewImg = document.getElementById('photo-preview-img');
            const placeholder = document.getElementById('photo-placeholder');

            previewImg.src = URL.createObjectURL(file);
            previewImg.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        }
    });

    // Preview ID Badge
    document.getElementById('id_badge').addEventListener('change', function(e) {
        const [file] = e.target.files;
        if (file) {
            const previewImg = document.getElementById('badge-preview-img');
            const placeholder = document.getElementById('badge-placeholder');

            previewImg.src = URL.createObjectURL(file);
            previewImg.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        }
    });
</script>
@endpush