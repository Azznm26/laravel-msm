@extends('layouts.admin')

@section('title', 'Atur Career Path User')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
    <!-- Form Assign Baru -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-bold mb-6">Atur Career Path Baru</h2>

        <form method="POST" action="{{ route('admin.user-career-path.store') }}">
            @csrf

            <!-- Department -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Department</label>
                <select id="department" class="w-full mt-1 rounded border-gray-300" required>
                    <option value="">Pilih Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->nama_department }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Jabatan -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                <select name="jabatan_id" id="jabatan" class="w-full mt-1 rounded border-gray-300" required disabled>
                    <option value="">Pilih department terlebih dahulu</option>
                </select>
            </div>

            <!-- User -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">User</label>
                <select name="user_id" id="user" class="w-full mt-1 rounded border-gray-300" required disabled>
                    <option value="">Pilih jabatan terlebih dahulu</option>
                </select>
            </div>

            <!-- Career Path -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Career Path</label>
                <select name="career_path_id" id="career_path" class="w-full mt-1 rounded border-gray-300" required disabled>
                    <option value="">Pilih jabatan terlebih dahulu</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Simpan Career Path
            </button>
        </form>
    </div>

    <!-- Daftar Career Path yang Sudah Diatur -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-bold mb-6">Daftar Career Path User</h2>

        @if($assignedPaths->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Career Path</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($assignedPaths as $assignment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->careerPath->jabatan->nama_jabatan ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $assignment->careerPath->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">Level {{ $assignment->current_level }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                <a href="{{ route('admin.user-career-path.edit', $assignment) }}" 
                                   class="text-blue-600 hover:text-blue-900">Edit</a>
                                <form action="{{ route('admin.user-career-path.destroy', $assignment) }}" 
                                      method="POST" class="inline" 
                                      onsubmit="return confirm('Yakin ingin menghapus career path ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $assignedPaths->links() }}
            </div>
        @else
            <p class="text-gray-500">Belum ada career path yang diatur.</p>
        @endif
    </div>
</div>

@push('scripts')
<script>
// ... (script AJAX sama seperti sebelumnya) ...
document.addEventListener('DOMContentLoaded', function () {
    const departmentSelect = document.getElementById('department');
    const jabatanSelect = document.getElementById('jabatan');
    const userSelect = document.getElementById('user');
    const careerPathSelect = document.getElementById('career_path');

    departmentSelect.addEventListener('change', function () {
        const deptId = this.value;
        if (!deptId) {
            jabatanSelect.innerHTML = '<option value="">Pilih department terlebih dahulu</option>';
            jabatanSelect.disabled = true;
            userSelect.disabled = true;
            careerPathSelect.disabled = true;
            return;
        }

        fetch("{{ route('admin.jabatan.by-department', ['department' => '__DEPT__']) }}".replace('__DEPT__', deptId))
            .then(response => response.json())
            .then(data => {
                jabatanSelect.innerHTML = '<option value="">Pilih Jabatan</option>';
                data.forEach(jabatan => {
                    const option = document.createElement('option');
                    option.value = jabatan.id;
                    option.textContent = jabatan.nama_jabatan;
                    jabatanSelect.appendChild(option);
                });
                jabatanSelect.disabled = false;
            })
            .catch(() => {
                jabatanSelect.innerHTML = '<option value="">Gagal memuat jabatan</option>';
                jabatanSelect.disabled = true;
            });
    });

    jabatanSelect.addEventListener('change', function () {
        const jabatanId = this.value;
        if (!jabatanId) {
            userSelect.disabled = true;
            careerPathSelect.disabled = true;
            return;
        }

        fetch("{{ route('admin.user-career-path.get-users') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ jabatan_id: jabatanId })
        })
        .then(response => response.json())
        .then(users => {
            userSelect.innerHTML = '<option value="">Pilih User</option>';
            users.forEach(user => {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = `${user.name} (${user.email})`;
                userSelect.appendChild(option);
            });
            userSelect.disabled = false;
        });

        fetch("{{ route('admin.user-career-path.get-career-paths') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ jabatan_id: jabatanId })
        })
        .then(response => response.json())
        .then(careerPaths => {
            careerPathSelect.innerHTML = '<option value="">Pilih Career Path</option>';
            careerPaths.forEach(cp => {
                const option = document.createElement('option');
                option.value = cp.id;
                option.textContent = cp.name;
                careerPathSelect.appendChild(option);
            });
            careerPathSelect.disabled = false;
        });
    });
});
</script>
@endpush