@if(session('registration_success'))
<div id="approvalModal" class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6">

    {{-- Backdrop Blur --}}
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
        onclick="closeModal()"></div>

    {{-- Modal Content --}}
    <div class="bg-white rounded-2xl shadow-2xl transform transition-all sm:max-w-md w-full p-6 text-center relative z-10 border-t-4 border-primary-600 scale-100 opacity-100">

        {{-- Icon Animasi --}}
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-primary-50 mb-6 animate-bounce">
            <i data-lucide="clock" class="w-8 h-8 text-primary-600"></i>
        </div>

        <h3 class="font-display text-xl font-extrabold text-gray-900 mb-2">Pendaftaran Berhasil!</h3>

        <div class="text-gray-500 text-sm mb-6 leading-relaxed">
            <p class="mb-2">Akun Anda telah berhasil dibuat.</p>
            <p>
                Demi keamanan data perusahaan, status akun Anda saat ini adalah:
            </p>
            <div class="mt-3 mb-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-primary-50 text-primary-700 border border-primary-200">
                    <i data-lucide="loader-2" class="w-3 h-3 mr-1.5 animate-spin"></i>
                    Pending Approval
                </span>
            </div>
            <p>
                Mohon tunggu Admin/HRD memverifikasi data Anda. Anda dapat mencoba login kembali secara berkala.
            </p>
        </div>

        <button onclick="closeModal()"
            class="btn-material w-full inline-flex justify-center items-center px-4 py-3 border border-transparent rounded-xl font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 shadow-lg shadow-primary-600/30 transition-all duration-200 group">
            <span class="mr-2">Mengerti &amp; Tutup</span>
            <i data-lucide="check" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
        </button>
    </div>

    {{-- Script Sederhana untuk menutup modal --}}
    <script>
        function closeModal() {
            const modal = document.getElementById('approvalModal');
            if (modal) {
                modal.style.opacity = '0';
                modal.style.transition = 'opacity 0.3s ease';
                setTimeout(() => modal.remove(), 300);
            }
        }
    </script>
</div>
@endif