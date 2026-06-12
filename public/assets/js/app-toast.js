// 1. Konfigurasi Dasar Toast MacaBae (Global)
const MacaBaeToast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000, // Disetel ke 2.5 detik agar pustakawan sempat membaca pesan sukses
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

// 2. Fungsi Helper Global Utama (Dipakai oleh Blade)
function fireToast(iconType, message) {
    MacaBaeToast.fire({
        icon: iconType, // 'success', 'error', 'warning', 'info'
        title: message
    });
}

// 3. Cadangan Helper (Biar aman kalau ada script lama yang manggil showToast)
function showToast(icon, message) {
    fireToast(icon, message);
}

document.addEventListener('DOMContentLoaded', function () {
    // Menangkap semua klik pada form yang memiliki class 'form-hapus-macabae'
    document.body.addEventListener('submit', function (e) {
        if (e.target && e.target.classList.contains('form-hapus-macabae')) {
            e.preventDefault(); // Tahan dulu proses kirim data ke server
            
            const form = e.target;
            const namaData = form.getAttribute('data-nama') || 'data ini';

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Data "${namaData}" yang dihapus tidak dapat dikembalikan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2F3951', // Warna gelap serasi dengan tema dasar MacaBae
                cancelButtonColor: '#EF4444',  // Warna merah tegas
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                borderRadius: '1rem',
                customClass: {
                    popup: 'rounded-2xl shadow-lg border border-gray-100',
                    confirmButton: 'px-5 py-2.5 rounded-xl font-semibold text-sm',
                    cancelButton: 'px-5 py-2.5 rounded-xl font-semibold text-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Jika klik "Ya", teruskan pengiriman data ke controller
                }
            });
        }
    });
});

// 4. Overriding Native browser alert() globally with premium SweetAlert2
window.alert = function (message) {
    const isDark = document.documentElement.classList.contains('dark');
    Swal.fire({
        title: 'Informasi',
        text: message,
        icon: 'info',
        confirmButtonColor: '#4D9BE2',
        confirmButtonText: 'Mengerti',
        background: isDark ? '#0f172a' : '#ffffff',
        color: isDark ? '#f1f5f9' : '#2F3951',
        customClass: {
            popup: 'rounded-[2rem] border border-gray-100 dark:border-slate-800 shadow-2xl p-6',
            title: 'text-sm font-extrabold text-[#2F3951] dark:text-slate-100 uppercase tracking-wider',
            htmlContainer: 'text-xs text-gray-500 dark:text-slate-400 mt-2 font-medium',
            confirmButton: 'px-5 py-2.5 bg-[#4D9BE2] text-white rounded-xl font-bold text-xs shadow-sm hover:opacity-90 transition active:scale-95 cursor-pointer'
        },
        buttonsStyling: false
    });
};

// 5. Global Interception for forms using native confirm()
document.addEventListener('submit', function (e) {
    const form = e.target;
    
    // Check if the form is already confirmed
    if (form.dataset.confirmed === 'true') {
        return;
    }

    const onsubmitAttr = form.getAttribute('onsubmit');
    if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
        // Prevent default submission and stop it from reaching the target form's handler (inline onsubmit)
        e.preventDefault();
        e.stopImmediatePropagation();

        // Extract the message
        let message = "Apakah Anda yakin?";
        const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
        if (match && match[1]) {
            message = match[1];
        }

        // Determine if it is a destructive action
        const isDestructive = message.toLowerCase().includes('hapus') || 
                              message.toLowerCase().includes('batal') || 
                              message.toLowerCase().includes('tolak');

        const isDark = document.documentElement.classList.contains('dark');

        const confirmButtonClass = isDestructive
            ? 'px-5 py-2.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl font-bold text-xs shadow-sm transition active:scale-95 cursor-pointer'
            : 'px-5 py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl font-bold text-xs shadow-sm transition active:scale-95 cursor-pointer';

        Swal.fire({
            title: 'Konfirmasi Aksi',
            text: message,
            icon: isDestructive ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: isDestructive ? '#EF4444' : '#4D9BE2',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: isDestructive ? 'Ya, Hapus/Batalkan' : 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            background: isDark ? '#0f172a' : '#ffffff',
            color: isDark ? '#f1f5f9' : '#2F3951',
            customClass: {
                popup: 'rounded-[2rem] border border-[#F1F5F9] dark:border-slate-800 shadow-2xl p-6',
                title: 'text-sm font-extrabold text-[#2F3951] dark:text-slate-100 uppercase tracking-wider',
                htmlContainer: 'text-xs text-gray-500 dark:text-slate-400 mt-2 font-medium',
                actions: 'flex gap-4 justify-center mt-4',
                confirmButton: confirmButtonClass,
                cancelButton: 'px-5 py-2.5 rounded-xl font-bold text-xs bg-gray-100 dark:bg-slate-800 text-gray-500 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 transition active:scale-95 cursor-pointer'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                form.dataset.confirmed = 'true';
                form.submit();
            }
        });
    }
}, true); // Use capture phase to intercept before target handler executes