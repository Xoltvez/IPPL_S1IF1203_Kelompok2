<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use App\Mail\SendOtpMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Carbon\Carbon;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * STEP 1: Kirim OTP via AJAX
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        ]);

        $otpCode = rand(100000, 999999);

        // Simpan atau update OTP di database
        DB::table('otps')->updateOrInsert(
            ['email' => $request->email],
            [
                'otp' => $otpCode,
                'expires_at' => Carbon::now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Kirim Email
        Mail::to($request->email)->send(new SendOtpMail($otpCode));

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP berhasil dikirim ke email kamu!'
        ]);
    }

    /**
     * STEP 2: Validasi OTP & Simpan User
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'otp' => ['required', 'string', 'size:6'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Cek validitas OTP
        $otpData = DB::table('otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpData) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP salah atau sudah kedaluwarsa.'
            ], 422);
        }

        // Jika OTP valid, buat User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'member', // Default role untuk registrasi baru
        ]);

        // Hapus OTP setelah digunakan
        DB::table('otps')->where('email', $request->email)->delete();

        event(new Registered($user));
        Auth::login($user);

        return response()->json([
            'success' => true,
            'redirect' => route('dashboard')
        ]);
    }

    public function verifyOnly(Request $request)
    {
        $otpData = DB::table('otps')
            ->where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpData) {
            return response()->json(['success' => false, 'message' => 'Kode OTP salah atau expired.'], 422);
        }

        return response()->json(['success' => true]);
    }
}