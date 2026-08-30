@extends('layouts.admin')

@section('title', 'Laporan Nilai')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-800 flex items-center gap-3">
                    <i data-lucide="clipboard-list" class="w-7 h-7 text-amber-600"></i>
                    Laporan Nilai Akhir
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Ringkasan hasil penilaian akhir dan persentase kelulusan tugas yang telah diselesaikan.
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-3">
                <a href="{{ route('admin.reports.export.excel') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg border border-green-400 text-green-700 bg-green-50 hover:bg-green-100 transition shadow-sm text-sm font-medium">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i> Export Excel
                </a>
                <a href="{{ route('admin.reports.export.pdf') }}" target="_blank"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg border border-red-400 text-red-700 bg-red-50 hover:bg-red-100 transition shadow-sm text-sm font-medium">
                    <i data-lucide="file-text" class="w-4 h-4"></i> Export PDF
                </a>
            </div>
        </div>

        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Total Peserta --}}
            <div class="bg-white rounded-xl shadow-lg p-5 border-l-4 border-amber-500 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Total Peserta Uji</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ count($results) }}</p>
                </div>
                <div class="p-3 bg-amber-50 rounded-full text-amber-600"><i data-lucide="users" class="w-7 h-7"></i></div>
            </div>

            @php
            $flattened = collect($results)->flatten();
            $avgScore = $flattened->avg('persentase_benar');
            // Hitung jumlah task yang lulus (status passed ATAU nilai >= 80)
            $passCount = $flattened->filter(function($item) {
            return $item->status == 'passed' || $item->persentase_benar >= 80;
            })->count();
            @endphp

            {{-- Rata-rata Nilai --}}
            <div class="bg-white rounded-xl shadow-lg p-5 border-l-4 border-blue-500 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Rata-rata Nilai Task</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($avgScore, 1) }}%</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-full text-blue-600"><i data-lucide="percent" class="w-7 h-7"></i></div>
            </div>

            {{-- Total Lulus --}}
            <div class="bg-white rounded-xl shadow-lg p-5 border-l-4 border-emerald-500 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase">Task Lulus Total</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $passCount }} <span class="text-sm text-gray-400 font-medium">Tasks</span></p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-full text-emerald-600"><i data-lucide="check-circle" class="w-7 h-7"></i></div>
            </div>
        </div>

        {{-- Main Table --}}
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-amber-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-amber-800 uppercase tracking-wider w-[20%]">Peserta</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-amber-800 uppercase tracking-wider w-[30%]">Task / Judul</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-amber-800 uppercase tracking-wider w-[15%]">Status Lulus</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-amber-800 uppercase tracking-wider w-[15%]">Nilai Akhir</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-amber-800 uppercase tracking-wider w-[20%]">Waktu Submit</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($results as $name => $taskResults)

                        @foreach($taskResults as $index => $res)
                        <tr class="hover:bg-amber-50/30 transition-colors">

                            {{-- Kolom Peserta (Merge/Tampilkan hanya di baris pertama) --}}
                            <td class="px-6 py-4 whitespace-nowrap align-top">
                                @if($index === 0)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-10 w-10 mt-1">
                                        {{-- Tampilkan inisial atau foto --}}
                                        <div class="h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-700 font-bold border-2 border-amber-200 shadow-sm text-sm overflow-hidden">
                                            @if($res->user->photo)
                                            <img src="{{ asset('storage/' . $res->user->photo) }}" class="h-full w-full object-cover">
                                            @else
                                            {{ strtoupper(substr($name, 0, 1)) }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-3 mt-1">
                                        <div class="text-sm font-bold text-gray-900">{{ $name }}</div>
                                        @if($res->user->id_badge)
                                        <div class="mt-1">
                                            <a href="{{ asset('storage/' . $res->user->id_badge) }}" target="_blank" class="flex items-center gap-1.5 w-fit text-xs text-blue-600 hover:underline">
                                                <i data-lucide="credit-card" class="w-3 h-3"></i> ID: {{ $res->user->id_badge }}
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </td>

                            {{-- Judul Task --}}
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $res->task->judul }}</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-100 text-gray-700">
                                        {{ str_replace('_', ' ', $res->task->jenis_task) }}
                                    </span>
                                </div>
                            </td>

                            {{-- Status (Lulus/Gagal berdasarkan nilai >= 80%) --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                $isPassed = ($res->status == 'passed') || ($res->persentase_benar >= 80);
                                @endphp

                                @if($isPassed)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 shadow-sm">
                                    <i data-lucide="award" class="w-3.5 h-3.5 mr-1.5"></i> LULUS
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 shadow-sm">
                                    <i data-lucide="x-circle" class="w-3.5 h-3.5 mr-1.5"></i> GAGAL
                                </span>
                                @endif
                            </td>

                            {{-- Nilai Akhir --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($res->task->jenis_task == 'pilihan_ganda')
                                <span class="text-2xl font-extrabold {{ $res->persentase_benar >= 80 ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ number_format($res->persentase_benar, 1) }}%
                                </span>
                                <div class="text-[10px] text-gray-500 mt-0.5">
                                    ({{ $res->total_skor }} / {{ $res->jumlah_soal }} Benar)
                                </div>
                                @else
                                <span class="text-sm text-gray-400 italic">N/A</span>
                                @endif
                            </td>

                            {{-- Waktu --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs text-gray-500">
                                <div class="font-medium">{{ $res->created_at->format('d M Y') }}</div>
                                <div>{{ $res->created_at->format('H:i') }} WIB</div>
                            </td>
                        </tr>
                        @endforeach

                        {{-- Pemisah antar Peserta --}}
                        @if(!$loop->last)
                        <tr class="bg-gray-100/50">
                            <td colspan="5" class="py-2"></td>
                        </tr>
                        @endif

                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="file-x" class="w-12 h-12 text-gray-300 mb-3"></i>
                                    <p class="text-lg font-medium text-gray-900">Belum ada hasil penilaian</p>
                                    <p class="text-gray-500 text-sm mt-1">Tidak ada task yang sudah diselesaikan dan dinilai.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });
</script>
@endpush