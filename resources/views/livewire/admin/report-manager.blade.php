<div class="min-h-screen px-4 sm:px-6 py-8" style="background: #f8fafc; font-family: 'Inter', sans-serif; color: #1e293b;">

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

        .md-btn-success-solid {
            background: var(--success);
            color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .md-btn-success-solid:hover {
            background: #059669;
            /* Emerald 600 */
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
            transform: translateY(-1px);
        }

        .md-btn-danger-solid {
            background: var(--danger);
            color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }

        .md-btn-danger-solid:hover {
            background: var(--danger-hover);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
            transform: translateY(-1px);
        }
    </style>

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight flex items-center gap-3">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl shadow-sm border border-blue-100">
                    <i data-lucide="file-check" class="w-6 h-6"></i>
                </div>
                Laporan Hasil Task
            </h1>
            <p class="text-sm mt-2 ml-1" style="color:var(--ink-soft);">Rekapitulasi nilai Kuis dan Survei berdasarkan masing-masing karyawan.</p>
        </div>

        <div class="flex flex-wrap gap-3 w-full lg:w-auto mt-2 lg:mt-0">
            <button wire:click="exportExcel" wire:loading.attr="disabled" class="flex items-center justify-center gap-2 px-5 py-2.5 md-btn-success-solid font-bold text-sm disabled:opacity-60 flex-1 sm:flex-none">
                <i data-lucide="file-spreadsheet" class="w-4.5 h-4.5"></i>
                <span wire:loading.remove wire:target="exportExcel">Export Excel</span>
                <span wire:loading wire:target="exportExcel" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Mengunduh...
                </span>
            </button>

            <a href="{{ route('admin.reports.export.pdf') }}" target="_blank" class="flex items-center justify-center gap-2 px-5 py-2.5 md-btn-danger-solid font-bold text-sm flex-1 sm:flex-none">
                <i data-lucide="file-text" class="w-4.5 h-4.5"></i> Export PDF
            </a>
        </div>
    </div>

    {{-- SEARCH BAR --}}
    <div class="mb-6">
        <div class="relative max-w-md">
            <i data-lucide="search" class="absolute left-4 top-1/2 transform -translate-y-1/2 w-4.5 h-4.5 text-gray-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama karyawan atau judul task..."
                class="w-full pl-11 pr-4 py-3 md-input text-sm font-medium shadow-sm">
        </div>
    </div>

    {{-- ACCORDION LIST --}}
    <div x-data="{ expanded: null }" class="space-y-4">
        @forelse($groupedResults as $userName => $results)
        <div class="md-panel overflow-hidden transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 border border-slate-200">

            <button @click="expanded === '{{ Str::slug($userName) }}' ? expanded = null : expanded = '{{ Str::slug($userName) }}'"
                class="w-full px-6 py-4.5 flex justify-between items-center bg-white hover:bg-blue-50/50 transition-colors focus:outline-none">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-white font-extrabold text-lg shadow-inner shrink-0 border border-blue-300">
                        {{ strtoupper(substr($userName, 0, 1)) }}
                    </div>
                    <div class="text-left">
                        <h3 class="text-lg font-extrabold text-gray-900 mb-0.5">{{ $userName }}</h3>
                        <p class="text-xs text-gray-500 font-medium">
                            <span class="font-extrabold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">{{ $results->count() }}</span> Task Diselesaikan
                        </p>
                    </div>
                </div>
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-slate-50 border border-slate-100 transition-colors" :class="expanded === '{{ Str::slug($userName) }}' ? 'bg-blue-50 border-blue-100 text-blue-600' : 'text-gray-400'">
                    <i data-lucide="chevron-down" class="w-5 h-5 transition-transform duration-300" :class="expanded === '{{ Str::slug($userName) }}' ? 'rotate-180' : ''"></i>
                </div>
            </button>

            <div x-show="expanded === '{{ Str::slug($userName) }}'" x-collapse x-cloak>
                <div class="p-6 border-t border-gray-100 bg-slate-50/50">
                    <div class="overflow-x-auto bg-white rounded-xl border border-gray-200 shadow-sm">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead class="text-xs text-gray-500 uppercase tracking-wider bg-slate-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 font-extrabold">Judul Task</th>
                                    <th class="px-6 py-4 font-extrabold text-center">Tipe Task</th>
                                    <th class="px-6 py-4 font-extrabold text-center">Skor / Jawaban Benar</th>
                                    <th class="px-6 py-4 font-extrabold text-right">Tanggal Selesai</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($results as $res)
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-gray-800">{{ $res->task->judul ?? 'Task Dihapus' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if(($res->task->jenis_task ?? '') == 'survey')
                                        <span class="px-2.5 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-[10px] font-extrabold uppercase tracking-wider shadow-sm">Survei</span>
                                        @else
                                        <span class="px-2.5 py-1.5 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg text-[10px] font-extrabold uppercase tracking-wider shadow-sm">Kuis</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if(($res->task->jenis_task ?? '') == 'survey')
                                        <span class="text-xs font-bold text-gray-500"><i data-lucide="check" class="w-3.5 h-3.5 inline mr-1 text-emerald-500"></i> Telah Mengisi</span>
                                        @else
                                        @php
                                        $totalSoal = max($res->task->questions_count ?? 1, 1);
                                        $persentase = round(($res->total_skor / $totalSoal) * 100, 1);
                                        @endphp
                                        <span class="px-3 py-1.5 rounded-lg text-xs font-extrabold shadow-sm border {{ $persentase >= 80 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                            {{ $res->total_skor }} / {{ $totalSoal }} ({{ $persentase }}%)
                                        </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right text-xs font-semibold text-gray-500">
                                        {{ $res->created_at->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 text-center md-panel flex flex-col items-center justify-center">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                <i data-lucide="folder-search" class="w-8 h-8 text-slate-400"></i>
            </div>
            <h3 class="text-lg font-extrabold text-gray-800">Tidak Ada Data</h3>
            <p class="text-sm mt-1.5 font-medium text-gray-500">Belum ada laporan hasil task yang bisa ditampilkan atau tidak sesuai dengan pencarian Anda.</p>
        </div>
        @endforelse
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', () => {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    </script>
</div>