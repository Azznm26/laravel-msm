{{-- Modal Notifikasi Upload File Disetujui --}}
@if(isset($approvalNotification) && $approvalNotification)
<div x-data="{ showModal: true }" x-show="showModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="showModal"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
            @click="showModal = false">
        </div>
        <div x-show="showModal"
            class="inline-block align-bottom bg-white rounded-2xl shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 6v-4m0 4h-4m4 4h4v-4h-4v4z" />
                    </svg>
                </div>
                <h3 class="mt-4 text-lg font-bold text-gray-900">Upload File Disetujui!</h3>
                <div class="mt-2 px-2 text-sm text-gray-600">
                    <p>
                        File untuk task <strong>"{{ $approvalNotification['task_title'] }}"</strong> telah disetujui.
                    </p>
                    <p class="mt-1 text-green-600 font-medium">
                        Anda mendapatkan <strong>+{{ $approvalNotification['exp_reward'] }} EXP</strong>!
                    </p>
                    <p class="mt-2 text-xs text-gray-500">
                        Disetujui pada: {{ $approvalNotification['approved_at'] }}
                    </p>
                </div>
            </div>
            <div class="mt-6">
                <button @click="showModal = false; $dispatch('clear-approval-notification')"
                    class="w-full px-4 py-2.5 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Script untuk hapus notifikasi dari cache --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.on('clear-approval-notification', () => {
            const cacheKey = "{{ $cacheKey ?? '' }}";
            if (cacheKey) {
                fetch("{{ route('user.clear-approval-notification', ['cacheKey' => 'DUMMY']) }}".replace('DUMMY', cacheKey), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }).then(() => {
                    console.log('Notifikasi approval dihapus dari cache');
                }).catch(err => {
                    console.error('Gagal hapus notifikasi:', err);
                });
            }
        });
    });
</script>
@endif