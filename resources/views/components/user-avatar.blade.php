@props(['user', 'size' => 'md'])

@php
// Setting Ukuran (Width & Height)
$sizes = [
'sm' => 'w-8 h-8 text-xs', // Untuk Tabel / List Kecil
'md' => 'w-10 h-10 text-sm', // Untuk Navbar
'lg' => 'w-16 h-16 text-lg', // Untuk Card Dashboard
'xl' => 'w-24 h-24 text-2xl', // Untuk Halaman Profil
'2xl' => 'w-32 h-32 text-4xl', // Untuk Halaman Profil Besar
];

$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div class="relative inline-block">
    @if($user && $user->photo && file_exists(public_path('storage/' . $user->photo)))
    {{-- Jika Ada Foto --}}
    <img src="{{ asset('storage/' . $user->photo) }}"
        alt="{{ $user->name }}"
        class="{{ $sizeClass }} rounded-full object-cover border-2 border-amber-100 shadow-sm">
    @else
    {{-- Jika Tidak Ada Foto (Tampilkan Inisial) --}}
    <div class="{{ $sizeClass }} rounded-full bg-amber-100 flex items-center justify-center text-amber-700 font-bold border-2 border-amber-200 shadow-sm">
        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
    </div>
    @endif

    {{-- Indikator Status (Opsional, muncul jika user aktif baru-baru ini) --}}
    @if(isset($user->last_seen) && \Carbon\Carbon::parse($user->last_seen)->diffInMinutes(now()) < 5)
        <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-white bg-green-400"></span>
        @endif
</div>