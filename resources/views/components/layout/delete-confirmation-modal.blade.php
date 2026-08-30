<div
    x-data="{
        show: false,
        title: 'Konfirmasi Hapus',
        message: 'Apakah Anda yakin?',
        deleteUrl: '',
        confirmText: 'Hapus'
    }"
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center"
    x-transition
    @keydown.escape.window="show = false"
    @open-delete-modal.window="
        show = true;
        title = $event.detail.title;
        message = $event.detail.message;
        deleteUrl = $event.detail.actionUrl;
        if ($event.detail.confirmText) confirmText = $event.detail.confirmText;
    "
    @click.away="show = false">

    {{-- Container Modal --}}
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">

        {{-- Form DELETE (Tersembunyi) --}}
        <form method="POST" x-ref="deleteForm" :action="deleteUrl" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <div class="p-6 text-center">
            <img
                src="{{ asset('images/icons/warning.png') }}"
                alt="Peringatan"
                class="mx-auto h-16 w-16">

            <h3 class="mt-4 text-lg font-semibold text-red-600" x-text="title"></h3>
            <p class="mt-2 text-gray-700" x-text="message"></p>
        </div>

        <div class="px-6 pb-6 flex justify-end gap-2 border-t pt-4">
            {{-- Tombol Batal --}}
            <button type="button"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                @click="show = false">
                Batal
            </button>

            {{-- Tombol Hapus - Pemicu Submit Form --}}
            <button type="button"
                @click="$refs.deleteForm.submit()"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
                x-text="confirmText">

            </button>
        </div>
    </div>
</div>