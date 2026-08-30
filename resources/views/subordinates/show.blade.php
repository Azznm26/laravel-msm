@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <a href="{{ route('bawahan.index') }}" 
                   class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 mb-6">
                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar Bawahan
                </a>

                <h2 class="text-2xl font-semibold text-gray-800 mb-6">{{ $subordinate->name }}</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subordinate->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jabatan</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($subordinate->jabatan) }}</dd>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Departemen</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $subordinate->department ?: '–' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection