/**
 * MacaBae Register Logic
 * Handling 3-Step Registration: Identity -> OTP Verification -> Password Creation
 */

// 1. Fungsi Helper untuk Animasi Transisi antar Step
function switchStep(oldStepId, newStepId) {
    const oldStep = document.getElementById(oldStepId);
    const newStep = document.getElementById(newStepId);

    // Mulai animasi keluar (Fade out & Scale down)
    oldStep.classList.add('opacity-0', 'scale-95');
    
    setTimeout(() => {
        oldStep.classList.add('hidden'); // Sembunyikan total setelah animasi selesai
        
        // Siapkan step baru (masih tersembunyi/transparan)
        newStep.classList.remove('hidden');
        
        // Trigger animasi masuk (Fade in & Scale up)
        setTimeout(() => {
            newStep.classList.remove('opacity-0', 'scale-95');
        }, 50);
    }, 500); // Durasi 500ms sesuai durasi transisi Tailwind
}

// 2. STEP 1: Mengirim OTP ke Email
async function handleSendOtp() {
    const email = document.getElementById('email').value;
    const name = document.getElementById('name').value;
    const btn = document.getElementById('btn-otp');
    
    if(!name || !email) return alert('Nama dan Email wajib diisi!');

    // Indikator Loading
    btn.disabled = true;
    btn.innerHTML = '<span class="animate-pulse">Mengirim Kode...</span>';

    try {
        const response = await fetch("/register/send-otp", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email, name })
        });

        const data = await response.json();

        if (data.success) {
            switchStep('step-1', 'step-2');
        } else {
            alert(data.message || 'Gagal mengirim OTP. Cek apakah email sudah terdaftar.');
            btn.disabled = false;
            btn.innerText = 'Lanjut & Kirim Kode OTP';
        }
    } catch (error) {
        console.error('Error Step 1:', error);
        alert('Terjadi kesalahan koneksi ke server.');
        btn.disabled = false;
        btn.innerText = 'Lanjut & Kirim Kode OTP';
    }
}

// 3. STEP 2: Verifikasi OTP (Hanya Cek Validitas)
async function handleVerifyOtp() {
    const email = document.getElementById('email').value;
    const otp = document.getElementById('otp').value;
    
    if(otp.length < 6) return alert('Masukkan 6 digit kode OTP dengan benar.');

    try {
        const response = await fetch("/register/verify-otp", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email, otp })
        });

        const data = await response.json();

        if (data.success) {
            switchStep('step-2', 'step-3');
        } else {
            alert(data.message || 'Kode OTP salah atau sudah kedaluwarsa.');
        }
    } catch (error) {
        console.error('Error Step 2:', error);
        alert('Gagal memverifikasi kode.');
    }
}

// 4. STEP 3: Submit Pendaftaran Final
async function handleFinalRegister() {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const otp = document.getElementById('otp').value;
    const password = document.getElementById('password').value;
    const password_confirmation = document.getElementById('password_confirmation').value;

    if(!password) return alert('Password tidak boleh kosong!');
    if(password !== password_confirmation) return alert('Konfirmasi password tidak cocok.');

    try {
        const response = await fetch("/register", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                name, email, otp, password, password_confirmation 
            })
        });

        const data = await response.json();

        if (data.success) {
            // Redirect user ke dashboard
            window.location.href = data.redirect;
        } else {
            alert(data.message || 'Pendaftaran gagal. Pastikan semua data benar.');
        }
    } catch (error) {
        console.error('Error Step 3:', error);
        alert('Terjadi kesalahan saat menyimpan akun.');
    }
}