@extends('layouts.admin')

@section('title', 'Edit Career Path User')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-6">Edit Career Path untuk {{ $user->name }}</h2>

        <form method="POST" action="{{ route('admin.user-career-path.update', $assignment) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">User</label>
                <div class="p-2 bg-gray-100 rounded">{{ $user->name }} ({{ $user->email }})</div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                <div class="p-2 bg-gray-100 rounded">{{ $currentJabatan->nama_jabatan ?? 'N/A' }}</div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Career Path</label>
                <select name="career_path_id" class="w-full mt-1 rounded border-gray-300" required>
                    <option value="">Pilih Career Path</option>
                    @foreach($currentJabatan->careerPaths as $cp)
                        <option value="{{ $cp->id }}" {{ $assignment->career_path_id == $cp->id ? 'selected' : '' }}>
                            {{ $cp->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('admin.user-career-path.index') }}" class="px-4 py-2 bg-gray-200 rounded">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Update Career Path
                </button>
            </div>
        </form>
    </div>
</div>
@endsection