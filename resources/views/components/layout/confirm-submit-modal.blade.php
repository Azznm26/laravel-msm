<div x-show="showConfirmModal" x-cloak tabindex="-1"
    {{-- PERBAIKAN: Tambahkan 'flex items-center justify-center' di container terluar --}}
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 overflow-y-auto"
    aria-modal="true" role="dialog" aria-labelledby="modal-title">

    {{-- Layer Overlay (Tetap di sini) --}}
    <div x-show="showConfirmModal"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
        @click="showConfirmModal = false">
    </div>

    {{-- Modal Content (Disesuaikan untuk pemusatan yang benar) --}}
    <div x-show="showConfirmModal"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"

        {{-- Hapus align-bottom dan ganti dengan z-index dan margin-auto --}}
        class="relative inline-block bg-white rounded-2xl shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6 z-50">

        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100">
                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <h3 class="mt-4 text-lg font-bold text-gray-900" id="modal-title">Konfirmasi Pengiriman</h3>

            <div class="mt-2 px-2">
                <p class="text-sm text-gray-600">
                    Apakah Anda yakin ingin mengirim?
                    <br>
                    <span class="font-medium text-gray-800">Anda tidak bisa mengubah setelah dikirim.</span>
                </p>
            </div>
        </div>

        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <button type="button" @click="showConfirmModal = false"
                class="flex-1 px-4 py-2.5 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition">
                Batalkan
            </button>

            {{-- Tombol KIRIM memicu submission form luar --}}
            <button type="button"
                @click="document.getElementById('taskSubmitForm').submit(); showConfirmModal = false;"
                class="flex-1 px-4 py-2.5 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium transition">
                Kirim
            </button>
        </div>
    </div>
</div>