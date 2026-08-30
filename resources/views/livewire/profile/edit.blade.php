<div class="min-h-screen px-4 sm:px-6 py-8 bg-[#f8fafc] dark:bg-slate-900 font-sans text-slate-900 dark:text-slate-100" style="font-family: 'Inter', sans-serif;">

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
        }

        /* Dark Mode Variables */
        :is(.dark) {
            --surface: #1e293b;
            /* slate-800 */
            --background: #0f172a;
            /* slate-900 */
            --border: #334155;
            /* slate-700 */

            --ink: #f8fafc;
            /* slate-50 */
            --ink-soft: #94a3b8;
            /* slate-400 */
        }

        .md-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.025);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-input {
            background: var(--background);
            border: 1px solid var(--border);
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

    <div class="mb-8 max-w-7xl mx-auto">
        <button onclick="history.back()" class="flex items-center text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors mb-4 w-fit">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> {{ __('user_profile.back') }}
        </button>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 dark:text-white tracking-tight flex items-center gap-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-900/30 border border-blue-100 dark:border-blue-800/50 rounded-xl shadow-sm">
                <i data-lucide="user-circle" class="w-6 h-6 text-blue-600 dark:text-blue-400"></i>
            </div>
            {{ __('user_profile.title') }}
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 ml-1">{{ __('user_profile.subtitle') }}</p>
    </div>

    <div class="max-w-7xl mx-auto">
        @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 rounded-xl flex items-center gap-3 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
        @endif

        {{-- ✅ Banner peringatan — muncul jika foto ID Badge belum diisi --}}
        @if (!$new_id_badge_photo && !$user->id_badge_photo)
        <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-300 dark:border-amber-800/50 text-amber-700 dark:text-amber-400 rounded-xl flex items-start gap-3 shadow-sm">
            <i data-lucide="alert-triangle" class="w-5 h-5 mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="text-sm font-bold">{{ __('user_profile.action_required') }}</p>
                <p class="text-xs mt-1">{{ __('user_profile.id_badge_warning') }}</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Kolom Kiri --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="md-panel p-6 text-center overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-blue-500 to-blue-700 dark:from-blue-600 dark:to-blue-800"></div>

                    <div class="relative mt-8 mb-4">
                        <div class="w-32 h-32 mx-auto rounded-full border-4 border-white dark:border-slate-800 shadow-lg overflow-hidden bg-gray-50 dark:bg-slate-700">
                            @if ($new_photo)
                            <img src="{{ $new_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                            @elseif ($user->photo)
                            <img src="{{ Storage::url($user->photo) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-4xl font-black text-blue-600 dark:text-blue-400">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 dark:text-white">{{ $user->name }}</h3>
                    <p class="text-sm font-semibold text-blue-600 dark:text-blue-400 uppercase tracking-wider mt-1">
                        {{ $user->role === 'user' ? __('user_profile.employee') : __('user_profile.admin') }}
                    </p>

                    <div class="mt-6 space-y-3 text-left bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-3">
                            <i data-lucide="building" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $user->department->nama_department ?? __('user_profile.no_department') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i data-lucide="briefcase" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $user->jabatan->nama_jabatan ?? __('user_profile.no_position') }}</span>
                        </div>
                        {{-- ✅ ID Badge (read-only) --}}
                        <div class="flex items-center gap-3">
                            <i data-lucide="lock" class="w-4 h-4 text-gray-400 dark:text-gray-500"></i>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $user->id_badge ?? __('user_profile.not_set') }}</span>
                        </div>
                    </div>
                </div>

                {{-- ✅ Kartu preview Foto ID Badge --}}
                <div class="md-panel p-6">
                    <h4 class="text-sm font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                        <i data-lucide="badge-check" class="w-4 h-4 text-blue-600 dark:text-blue-400"></i> {{ __('user_profile.id_badge_photo') }}
                        @if (!$new_id_badge_photo && !$user->id_badge_photo)
                        <span class="text-[9px] font-bold text-white bg-amber-500 px-2 py-0.5 rounded-md tracking-wider">{{ __('user_profile.required') }}</span>
                        @endif
                    </h4>
                    <div class="w-full h-48 bg-gray-50 dark:bg-slate-900/50 rounded-xl border-2 border-dashed border-gray-200 dark:border-slate-700 flex items-center justify-center overflow-hidden relative group">
                        @if ($new_id_badge_photo)
                        <img src="{{ $new_id_badge_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                        @elseif ($user->id_badge_photo)
                        <img src="{{ Storage::url($user->id_badge_photo) }}" class="w-full h-full object-cover">
                        @else
                        <div class="text-center p-4">
                            <i data-lucide="image-off" class="w-8 h-8 text-gray-300 dark:text-gray-600 mx-auto mb-2"></i>
                            <p class="text-xs text-gray-400 dark:text-gray-500 font-medium">{{ __('user_profile.no_id_badge') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan (Form) --}}
            <div class="lg:col-span-2">
                <form wire:submit.prevent="updateProfile" class="md-panel overflow-hidden">
                    <div class="p-6 sm:p-8 space-y-8">

                        <div>
                            <h4 class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider border-b border-gray-100 dark:border-slate-700 pb-2 mb-4">{{ __('user_profile.basic_info') }}</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('user_profile.full_name') }}</label>
                                    <input type="text" wire:model="name" class="w-full px-4 py-2.5 md-input text-sm" required>
                                    @error('name') <span class="text-xs text-rose-500 dark:text-rose-400 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('user_profile.email_address') }}</label>
                                    <input type="email" wire:model="email" class="w-full px-4 py-2.5 md-input text-sm" required>
                                    @error('email') <span class="text-xs text-rose-500 dark:text-rose-400 mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider border-b border-gray-100 dark:border-slate-700 pb-2 mb-4">{{ __('user_profile.file_updates') }}</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('user_profile.change_photo') }}</label>
                                    <input type="file" wire:model="new_photo" accept="image/*" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 dark:file:bg-blue-900/30 file:text-blue-700 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/50 cursor-pointer transition-colors">
                                    <div wire:loading wire:target="new_photo" class="text-[10px] text-blue-600 dark:text-blue-400 mt-1 font-bold">{{ __('user_profile.uploading_preview') }}</div>
                                    @error('new_photo') <span class="text-xs text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('user_profile.update_id_badge') }}</label>
                                    <input type="file" wire:model="new_id_badge_photo" accept="image/*" class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm file:mr-4 file:py-1 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 dark:file:bg-blue-900/30 file:text-blue-700 dark:file:text-blue-400 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/50 cursor-pointer transition-colors">
                                    <div wire:loading wire:target="new_id_badge_photo" class="text-[10px] text-blue-600 dark:text-blue-400 mt-1 font-bold">{{ __('user_profile.uploading_preview') }}</div>
                                    @error('new_id_badge_photo') <span class="text-xs text-rose-500 dark:text-rose-400 mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider border-b border-gray-100 dark:border-slate-700 pb-2 mb-4">{{ __('user_profile.account_security') }}</h4>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">{{ __('user_profile.password_leave_blank') }}</p>
                            <div class="space-y-4">
                                @if($user->role === 'user')
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('user_profile.current_password') }}</label>
                                    <input type="password" wire:model="current_password" class="w-full px-4 py-2.5 md-input text-sm" placeholder="••••••••">
                                    @error('current_password') <span class="text-xs text-rose-500 dark:text-rose-400 mt-1">{{ $message }}</span> @enderror
                                </div>
                                @endif
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('user_profile.new_password') }}</label>
                                        <input type="password" wire:model="new_password" class="w-full px-4 py-2.5 md-input text-sm" placeholder="••••••••">
                                        @error('new_password') <span class="text-xs text-rose-500 dark:text-rose-400 mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('user_profile.confirm_new_password') }}</label>
                                        <input type="password" wire:model="new_password_confirmation" class="w-full px-4 py-2.5 md-input text-sm" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="p-6 border-t border-gray-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80 flex justify-end">
                        <button type="submit" class="px-8 py-3 text-sm font-semibold md-btn-primary flex items-center gap-2 transition-all" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="updateProfile">{{ __('user_profile.save_updates') }}</span>
                            <span wire:loading wire:target="updateProfile">
                                <i data-lucide="loader-2" class="w-4 h-4 animate-spin inline-block mr-1"></i> {{ __('user_profile.processing') }}
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