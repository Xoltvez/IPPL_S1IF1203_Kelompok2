<x-guest-layout>
    <div class="flex min-h-screen">
        
        <div class="w-full md:w-1/2 flex flex-col justify-between bg-white px-8 py-10 sm:p-16 md:p-20 relative z-10 min-h-screen">
            
            <div class="w-full max-w-md mx-auto">
                <a href="/" class="flex items-center gap-2 mb-20 transition hover:opacity-80">
                    <img src="{{ asset('assets/img/brand/logo-macabae.png') }}" alt="Logo MacaBae" class="h-9">
                </a>

                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-[#2F3951] tracking-tight">Daftar Akun Baru ✨</h1>
                    <p class="text-gray-500 mt-2 text-sm">Bergabunglah untuk mulai menjelajahi ribuan buku digital.</p>
                </div>

            <form id="registerForm">
                @csrf
                
                <div id="step-1" class="space-y-5 transition-all duration-500 transform">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input id="name" type="text" name="name" required class="w-full px-5 py-3 rounded-xl border border-gray-200 outline-none focus:border-[#4D9BE2]" placeholder="Nama lengkap kamu">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input id="email" type="email" name="email" required class="w-full px-5 py-3 rounded-xl border border-gray-200 outline-none focus:border-[#4D9BE2]" placeholder="email@macabae.com">
                    </div>
                    <div class="pt-4">
                        <button type="button" id="btn-otp" onclick="handleSendOtp()" class="w-full bg-[#4D9BE2] text-white py-4 rounded-xl font-bold shadow-lg transition-all">
                            Lanjut & Kirim Kode OTP
                        </button>
                    </div>
                </div>

                <div id="step-2" class="hidden space-y-5 opacity-0 scale-95 transition-all duration-500 transform">
                    <div class="text-center mb-6">
                        <h3 class="font-bold text-gray-800">Verifikasi Email 📩</h3>
                        <p class="text-xs text-gray-500">Masukkan 6 digit kode yang dikirim ke email kamu.</p>
                    </div>
                    <div>
                        <input id="otp" type="text" maxlength="6" class="w-full text-center tracking-[10px] text-2xl font-bold px-5 py-3 rounded-xl border border-gray-200 outline-none focus:border-[#4D9BE2]" placeholder="000000">
                    </div>
                    <div class="pt-4">
                        <button type="button" onclick="handleVerifyOtp()" class="w-full bg-[#2F3951] text-white py-4 rounded-xl font-bold shadow-lg">
                            Verifikasi Kode
                        </button>
                        <button type="button" onclick="location.reload()" class="w-full mt-4 text-xs text-gray-400 hover:underline">Ganti Email</button>
                    </div>
                </div>

                <div id="step-3" class="hidden space-y-5 opacity-0 scale-95 transition-all duration-500 transform">
                    <div class="bg-green-50 p-3 rounded-xl mb-4">
                        <p class="text-xs text-green-600 text-center font-medium">✓ Email berhasil diverifikasi. Buat password kamu.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Buat Password</label>
                        <input id="password" type="password" name="password" required class="w-full px-5 py-3 rounded-xl border border-gray-200 outline-none focus:border-[#4D9BE2]" placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full px-5 py-3 rounded-xl border border-gray-200 outline-none focus:border-[#4D9BE2]" placeholder="••••••••">
                    </div>
                    <div class="pt-4">
                        <button type="button" onclick="handleFinalRegister()" class="w-full bg-[#4D9BE2] text-white py-4 rounded-xl font-bold shadow-lg">
                            Selesaikan Pendaftaran
                        </button>
                    </div>
                </div>
            </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-[#4D9BE2] font-semibold hover:underline transition">
                            Masuk di sini
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <div class="hidden md:block md:w-1/2 relative bg-gray-900 overflow-hidden min-h-screen">
            <img src="{{ asset('assets/img/auth/login-kanan.png') }}" 
                 alt="Wanita Membaca di Sofa MacaBae" 
                 class="absolute inset-0 w-full h-full object-cover scale-105 hover:scale-100 transition-transform duration-1000 ease-in-out z-0">
            
            <div class="absolute bottom-10 left-16 right-16 text-white z-10 max-w-xl">
                <h2 class="text-4xl font-bold leading-tight tracking-tight">Mulai petualanganmu hari ini 🚀</h2>
                <p class="mt-2 text-regular text-gray-100 font-medium">Bergabunglah dengan ribuan pembaca lainnya dan nikmati akses tanpa batas.</p>
            </div>

            <div class="absolute top-0 right-0 w-96 h-96 bg-[#4D9BE2]/30 rounded-full -mr-32 -mt-32 blur-3xl z-0"></div>
        </div>
    </div>
    <script src="{{ asset('assets/js/register-otp.js') }}"></script>
</x-guest-layout>