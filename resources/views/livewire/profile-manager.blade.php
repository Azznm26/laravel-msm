<div class="min-h-screen px-4 sm:px-6 py-8 bg-[#f8fafc] font-sans" style="font-family: 'Inter', sans-serif; color: #1e293b;">

    <style>
        /* ---------- Material Design 3 Colors & Components ---------- */
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-tint: #eff6ff;

            --surface: #ffffff;
            --background: #f8fafc;
            --border: #e2e8f0;

            --ink: #1e293b;
            --ink-soft: #64748b;

            --danger: #ef4444;
            --danger-hover: #dc2626;
            --danger-tint: #fef2f2;

            --success: #10b981;
            --success-tint: #ecfdf5;
        }

        .md-panel {
            background: var(--surface);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.025);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-input {
            background: var(--background);
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            color: var(--ink);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-input:focus {
            background: var(--surface);
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .md-btn-primary {
            background: var(--primary);
            color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-btn-primary:hover {
            background: var(--primary-hover);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
            transform: translateY(-1px);
        }

        .md-btn-primary:active {
            transform: translateY(0);
        }
    </style>

    <div class="mb-8 max-w-5xl mx-auto">
        <button onclick="history.back()" class="flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors mb-5 w-fit">
            <i data-lucide="arrow-left" class="w-4.5 h-4.5 mr-1.5"></i> Kembali
        </button>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
            <div class="p-2 bg-blue-50 border border-blue-100 rounded-xl shadow-sm"><i data-lucide="user-circle" class="w-6 h-6 text-blue-600"></i></div>
            Pengaturan Profil
        </h1>
        <p class="text-sm font-medium text-gray-500 mt-2 ml-1">Kelola informasi data diri, foto, dan keamanan akun Anda.</p>
    </div>

    <div class="max-w-5xl mx-auto">
        @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i><span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
        @endif

        {{-- ✅ Banner peringatan — muncul jika foto ID Badge belum diisi --}}
        @if (!$new_id_badge_photo && !$user->id_badge_photo)
        <div class="mb-6 p-5 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl flex items-start gap-3 shadow-sm">
            <i data-lucide="alert-triangle" class="w-6 h-6 mt-0.5 flex-shrink-0 text-amber-500"></i>
            <div>
                <p class="text-sm font-extrabold uppercase tracking-wider mb-0.5">Perlu Tindakan</p>
                <p class="text-xs font-medium text-amber-800">Anda wajib mengunggah Foto ID Badge terlebih dahulu sebelum bisa membuka Halaman Tugas/Soal.</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

            {{-- KOLOM KIRI: INFO PROFIL READ-ONLY --}}
            <div class="lg:col-span-1">
                <div class="md-panel p-6 sm:p-8 text-center sticky top-28">
                    <div class="w-32 h-32 mx-auto rounded-full border-4 border-white shadow-md overflow-hidden bg-slate-50 mb-5 relative ring-1 ring-gray-100">
                        @if ($new_photo)
                        <img src="{{ $new_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                        @elseif ($user->photo)
                        <img src="{{ Storage::url($user->photo) }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-4xl font-black text-gray-400 bg-slate-100">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        @endif

                        <div wire:loading wire:target="new_photo" class="absolute inset-0 bg-white/70 backdrop-blur-[2px] flex items-center justify-center">
                            <i data-lucide="loader-2" class="w-6 h-6 text-blue-600 animate-spin"></i>
                        </div>
                    </div>

                    <h3 class="text-xl font-extrabold text-gray-900 leading-tight">{{ $user->name }}</h3>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest mt-3 inline-block px-3 py-1.5 rounded-lg shadow-sm border {{ in_array($user->role, ['admin', 'super_admin']) ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-slate-50 text-slate-600 border-slate-200' }}">
                        {{ str_replace('_', ' ', $user->role) }}
                    </span>

                    {{-- ✅ Info Pekerjaan (read-only) --}}
                    <div class="mt-8 pt-6 border-t border-gray-100 text-left space-y-4">
                        <div>
                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">ID Badge</p>
                            <p class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                <i data-lucide="badge-check" class="w-4 h-4 text-blue-500"></i>
                                {{ $user->id_badge ?? '—' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Departemen</p>
                            <p class="text-sm font-bold text-gray-800">{{ $user->department->nama_department ?? 'Belum diatur' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Jabatan</p>
                            <p class="text-sm font-bold text-gray-800">{{ $user->jabatan->nama_jabatan ?? 'Belum diatur' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Jenjang Karier</p>
                            <p class="text-sm font-bold text-gray-800">{{ $user->careerPath->name ?? 'Belum diatur' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-wider mb-1">Status Akun</p>
                            <p class="text-sm font-bold flex items-center gap-2 {{ (int) $user->status === 1 ? 'text-emerald-600' : 'text-rose-600' }}">
                                <span class="w-2.5 h-2.5 rounded-full {{ (int) $user->status === 1 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                {{ (int) $user->status === 1 ? 'Aktif' : 'Nonaktif' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: FORM UPDATE --}}
            <div class="lg:col-span-2">
                <form wire:submit.prevent="updateProfile" class="md-panel overflow-hidden flex flex-col h-full">
                    <div class="p-6 sm:p-8 space-y-8 flex-1">

                        {{-- Section: Informasi Dasar --}}
                        <div>
                            <h4 class="text-sm font-extrabold text-blue-800 uppercase tracking-wider border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                                <i data-lucide="contact" class="w-4.5 h-4.5"></i> Informasi Dasar
                            </h4>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                    <input type="text" wire:model="name" class="w-full px-4 py-3 md-input text-sm font-medium" required>
                                    @error('name') <span class="text-xs font-bold text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Email</label>
                                    <input type="email" wire:model="email" class="w-full px-4 py-3 md-input text-sm font-medium" required>
                                    @error('email') <span class="text-xs font-bold text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Ubah Foto Profil</label>
                                    <input type="file" wire:model="new_photo" accept="image/*" class="w-full px-3 py-2.5 md-input text-sm font-medium file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border file:border-blue-200 file:text-xs file:font-extrabold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer transition-all shadow-sm">
                                    @error('new_photo') <span class="text-xs font-bold text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ✅ Section: Foto ID Badge (wajib) --}}
                        <div>
                            <h4 class="text-sm font-extrabold text-blue-800 uppercase tracking-wider border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                                <i data-lucide="credit-card" class="w-4.5 h-4.5"></i> Foto ID Badge
                                <span class="text-[9px] font-black text-white bg-rose-500 px-2 py-0.5 rounded shadow-sm tracking-wider ml-1">WAJIB</span>
                            </h4>
                            <p class="text-xs font-medium text-gray-500 mb-5 leading-relaxed">Unggah foto khusus yang digunakan untuk kartu ID Badge Anda. Ini berbeda dari foto profil di atas.</p>

                            <div class="flex items-center gap-5 p-5 bg-slate-50 rounded-2xl border {{ (!$new_id_badge_photo && !$user->id_badge_photo) ? 'border-amber-300 border-dashed bg-amber-50/30' : 'border-slate-200 shadow-inner' }}">
                                <div class="w-20 h-24 rounded-xl overflow-hidden bg-white border border-gray-200 shadow-sm flex-shrink-0 flex items-center justify-center relative group">
                                    @if ($new_id_badge_photo)
                                    <img src="{{ $new_id_badge_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                    @elseif ($user->id_badge_photo)
                                    <img src="{{ Storage::url($user->id_badge_photo) }}" class="w-full h-full object-cover">
                                    @else
                                    <i data-lucide="image" class="w-6 h-6 text-gray-300"></i>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <label class="inline-flex items-center justify-center gap-2 px-5 py-2.5 md-btn-primary text-xs font-bold cursor-pointer w-full sm:w-auto">
                                        <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                                        {{ $user->id_badge_photo ? 'Ganti Foto Badge' : 'Pilih Foto Badge' }}
                                        <input type="file" wire:model="new_id_badge_photo" accept="image/*" class="hidden">
                                    </label>

                                    @if ($new_id_badge_photo)
                                    <p class="text-xs text-emerald-600 font-bold mt-2.5 flex items-center gap-1.5">
                                        <i data-lucide="check-circle-2" class="w-4 h-4"></i> Foto badge baru siap disimpan
                                    </p>
                                    @elseif ($user->id_badge_photo)
                                    <p class="text-xs text-emerald-600 font-bold mt-2.5 flex items-center gap-1.5">
                                        <i data-lucide="check-circle-2" class="w-4 h-4"></i> Foto badge sudah tersimpan
                                    </p>
                                    @endif

                                    <div wire:loading wire:target="new_id_badge_photo" class="text-xs font-bold text-blue-600 mt-2.5 flex items-center gap-1.5">
                                        <i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> Mengunggah...
                                    </div>
                                </div>
                            </div>
                            @error('new_id_badge_photo') <span class="text-xs font-bold text-red-500 mt-2 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Section: Keamanan Kata Sandi --}}
                        <div>
                            <h4 class="text-sm font-extrabold text-blue-800 uppercase tracking-wider border-b border-gray-100 pb-3 mb-4 flex items-center gap-2">
                                <i data-lucide="shield-check" class="w-4.5 h-4.5"></i> Keamanan Kata Sandi
                            </h4>
                            <p class="text-[11px] font-medium text-gray-500 mb-5 bg-blue-50/50 p-3 rounded-lg border border-blue-100/50"><span class="font-bold text-blue-700">Catatan:</span> Kosongkan seluruh kolom di bawah ini jika Anda tidak ingin mengubah kata sandi.</p>

                            <div class="space-y-5">
                                @if(auth()->user()->role === 'user')
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kata Sandi Saat Ini</label>
                                    <input type="password" wire:model="current_password" class="w-full px-4 py-3 md-input text-sm font-medium" placeholder="Wajib diisi jika ganti password">
                                    @error('current_password') <span class="text-xs font-bold text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
                                </div>
                                @endif

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kata Sandi Baru</label>
                                        <input type="password" wire:model="new_password" class="w-full px-4 py-3 md-input text-sm font-medium" placeholder="Minimal 8 karakter">
                                        @error('new_password') <span class="text-xs font-bold text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Konfirmasi Kata Sandi</label>
                                        <input type="password" wire:model="new_password_confirmation" class="w-full px-4 py-3 md-input text-sm font-medium" placeholder="Ulangi sandi baru">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Form Actions --}}
                    <div class="p-6 sm:p-8 border-t border-gray-100 bg-white flex justify-end">
                        <button type="submit" class="px-8 py-3.5 md-btn-primary text-sm font-bold flex items-center justify-center gap-2 w-full sm:w-auto" wire:loading.attr="disabled">
                            <i data-lucide="save" class="w-4.5 h-4.5" wire:loading.remove wire:target="updateProfile"></i>
                            <span wire:loading.remove wire:target="updateProfile">Simpan Perubahan</span>
                            <span wire:loading wire:target="updateProfile" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', () => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    </script>
</div>