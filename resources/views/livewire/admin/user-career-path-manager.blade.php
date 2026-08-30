<div class="min-h-screen bg-[#f8fafc] px-4 sm:px-6 py-8">

    @if (session()->has('success'))
    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
        <i data-lucide="check-circle" class="w-5 h-5"></i>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center gap-3">
        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
        <span class="font-medium text-sm">{{ session('error') }}</span>
    </div>
    @endif

    {{-- ======================================================== --}}
    {{-- VIEW MODE: INDEX (DAFTAR KARYAWAN) --}}
    {{-- ======================================================== --}}
    @if($viewMode === 'index')
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 tracking-tight flex items-center gap-3">
                <div class="p-2 bg-amber-100 rounded-xl">
                    <i data-lucide="users" class="w-6 h-6 text-[#C9A84C]"></i>
                </div>
                Plotting Jalur Karir
            </h1>
            <p class="text-sm text-gray-500 mt-2">Tentukan dan kelola arah pengembangan karir setiap karyawan.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama karyawan..." class="pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:ring-[#C9A84C] focus:border-transparent w-full sm:w-64">
            </div>
            <select wire:model.live="filter_department_id" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:ring-[#C9A84C] focus:border-transparent">
                <option value="">Semua Departemen</option>
                @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->nama_department }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-[#fdfcf8] text-gray-500 text-xs uppercase tracking-wider border-b border-[#e0d5b0]">
                    <tr>
                        <th class="px-6 py-4 font-bold">Karyawan</th>
                        <th class="px-6 py-4 font-bold">Departemen & Jabatan</th>
                        <th class="px-6 py-4 font-bold">Jalur Karir Utama</th>
                        <th class="px-6 py-4 font-bold text-center">Jalur Sekunder</th>
                        <th class="px-6 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors" wire:key="user-{{ $user->id }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 overflow-hidden">
                                    @if($user->photo)
                                    <img src="{{ Storage::url($user->photo) }}" class="w-full h-full object-cover">
                                    @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-700">{{ $user->department->nama_department ?? '-' }}</p>
                            <p class="text-xs text-gray-500">{{ $user->jabatan->nama_jabatan ?? 'Belum ada jabatan' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->careerPath)
                            <span class="px-3 py-1 rounded-lg bg-amber-50 text-amber-700 text-xs font-bold uppercase tracking-wider border border-amber-200">
                                {{ $user->careerPath->name }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400 italic">Belum diplot</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php $secondaryCount = $user->careerPaths->where('id', '!=', $user->career_path_id)->count(); @endphp
                            @if($secondaryCount > 0)
                            <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-600 text-xs font-bold">
                                +{{ $secondaryCount }} Jalur
                            </span>
                            @else
                            <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="edit({{ $user->id }})" class="px-3 py-1.5 text-xs font-bold text-[#C9A84C] bg-[#fdf8ec] hover:bg-[#fcf3d9] rounded-lg transition-colors border border-[#e0d5b0]">
                                    Atur Jalur
                                </button>
                                @if($user->career_path_id || $user->careerPaths->count() > 0)
                                <button type="button" @click="$dispatch('open-reset-modal', { id: {{ $user->id }}, name: '{{ addslashes($user->name) }}' })"
                                    class="p-1.5 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors" title="Reset/Hapus Jalur">
                                    <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i data-lucide="users" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                            Tidak ada data karyawan ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    {{-- MODAL RESET ALPINE --}}
    <div x-data="{ open: false, uid: null, uname: '' }"
        @open-reset-modal.window="open = true; uid = $event.detail.id; uname = $event.detail.name"
        x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-4">
        <div x-show="open" @click.outside="open = false" x-transition class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-rose-100 mb-4">
                    <i data-lucide="rotate-ccw" class="h-6 w-6 text-rose-600"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Reset Jalur Karir?</h3>
                <p class="text-sm text-gray-500 mt-2">Semua jalur (utama dan sekunder) milik <span class="font-bold text-gray-800" x-text="uname"></span> akan dihapus dari sistem.</p>
            </div>
            <div class="px-6 pb-6 flex gap-3">
                <button type="button" @click="open = false" class="flex-1 py-2 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition-colors">Batal</button>
                <button type="button" @click="$wire.confirmReset(uid); $wire.resetCareerPath(); open = false" class="flex-1 py-2 text-sm font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition-colors">Ya, Reset</button>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- VIEW MODE: FORM (EDIT JALUR KARIR) --}}
    {{-- ======================================================== --}}
    @elseif($viewMode === 'form')
    <div class="mb-8">
        <button wire:click="backToIndex" class="flex items-center text-sm font-semibold text-gray-500 hover:text-[#C9A84C] transition-colors mb-3">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i> Kembali ke Daftar Karyawan
        </button>
        <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Atur Jalur Karir</h1>
        <p class="text-sm text-gray-500 mt-1">Karyawan: <span class="font-bold text-[#C9A84C]">{{ $userName }}</span></p>
    </div>

    <form wire:submit.prevent="store" class="space-y-6 max-w-4xl">
        {{-- Card Jalur Utama --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 md:p-8">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4 mb-6">
                <div class="p-2 bg-amber-50 rounded-lg"><i data-lucide="git-merge" class="w-5 h-5 text-[#C9A84C]"></i></div>
                <div>
                    <h3 class="font-bold text-gray-800">Jalur Karir Utama (Primary) <span class="text-rose-500">*</span></h3>
                    <p class="text-xs text-gray-500">Menentukan departemen dan jabatan utama Level 1 karyawan tersebut.</p>
                </div>
            </div>

            <select wire:model="primary_career_path_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-[#C9A84C] font-semibold text-gray-700" required>
                <option value="">-- Pilih Jalur Karir Utama --</option>
                @foreach($careerPaths as $path)
                <option value="{{ $path->id }}">{{ $path->name }}</option>
                @endforeach
            </select>
            @error('primary_career_path_id') <span class="text-xs text-rose-500 mt-2 block font-medium">{{ $message }}</span> @enderror
        </div>

        {{-- Card Jalur Sekunder --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6 md:p-8">
            <div class="flex items-center gap-3 border-b border-gray-100 pb-4 mb-6">
                <div class="p-2 bg-blue-50 rounded-lg"><i data-lucide="network" class="w-5 h-5 text-blue-500"></i></div>
                <div>
                    <h3 class="font-bold text-gray-800">Jalur Karir Ekstra (Secondary)</h3>
                    <p class="text-xs text-gray-500">Izinkan karyawan mengumpulkan EXP dari jalur lain tanpa mengubah jabatan utamanya.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($careerPaths as $path)
                <label class="flex items-start gap-3 p-4 rounded-xl border border-gray-100 cursor-pointer hover:bg-gray-50 transition-colors {{ in_array($path->id, $secondary_career_path_ids) ? 'bg-[#fdf8ec] border-[#e0d5b0]' : '' }}">
                    <input type="checkbox" wire:model="secondary_career_path_ids" value="{{ $path->id }}"
                        class="mt-0.5 w-4 h-4 text-[#C9A84C] bg-white border-gray-300 rounded focus:ring-[#C9A84C]">
                    <div>
                        <span class="block text-sm font-bold text-gray-800">{{ $path->name }}</span>
                        <span class="block text-xs text-gray-500 mt-0.5">Tambahkan ke portofolio</span>
                    </div>
                </label>
                @endforeach
            </div>
            <p class="text-[10px] text-amber-600 font-bold mt-4 bg-amber-50 p-2 rounded-lg inline-block">
                *Peringatan: Menghapus centang pada jalur yang sudah berjalan TIDAK AKAN mengembalikan data poin (EXP) mereka jika dicentang kembali.
            </p>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="px-8 py-3 text-sm font-bold text-white bg-[#C9A84C] hover:bg-[#A8862C] rounded-xl flex items-center gap-2 shadow-lg shadow-amber-500/20 transition-all" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="store">Simpan Konfigurasi</span>
                <span wire:loading wire:target="store">Menyimpan...</span>
            </button>
        </div>
    </form>
    @endif

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', () => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    </script>
</div>