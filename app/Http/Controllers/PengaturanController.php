<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class PengaturanController extends Controller
{
    // Tampilkan halaman pengaturan
    public function index()
    {
        $user = Auth::user();
        return view('member.pengaturan.index', compact('user'));
    }

    // Mengubah nama lengkap
    public function updateName(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Nama lengkap berhasil diperbarui.');
    }

    // Mengubah email
    public function updateEmail(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'email' => $request->email,
        ]);

        return back()->with('success', 'Alamat email berhasil diperbarui.');
    }

    // Mengubah nomor telepon
    public function updatePhone(Request $request)
    {
        $request->validate([
            'no_telp' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
        ]);

        $user = Auth::user();
        $user->update([
            'no_telp' => $request->no_telp,
        ]);

        return back()->with('success', 'Nomor telepon berhasil diperbarui.');
    }

    // Mengubah password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    // Mengupload/Mengubah foto profil
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Max 2MB
        ]);

        $user = Auth::user();

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Simpan foto baru
            $path = $request->file('foto')->store('profile-photos', 'public');
            $user->update([
                'foto' => $path,
            ]);

            return back()->with('success', 'Foto profil berhasil diperbarui.');
        }

        return back()->with('error', 'Gagal memperbarui foto profil.');
    }

    // Menghapus foto profil
    public function deleteFoto()
    {
        $user = Auth::user();

        if ($user->foto) {
            if (Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            $user->update([
                'foto' => null,
            ]);

            return back()->with('success', 'Foto profil berhasil dihapus.');
        }

        return back()->with('error', 'Anda belum mengatur foto profil.');
    }

    // Mengubah preferensi notifikasi
    public function updateNotifikasi(Request $request)
    {
        $user = Auth::user();
        
        $user->update([
            'notif_persetujuan' => $request->boolean('notif_persetujuan'),
            'notif_pengembalian' => $request->boolean('notif_pengembalian'),
            'notif_jatuh_tempo' => $request->boolean('notif_jatuh_tempo'),
            'notif_rekomendasi' => $request->boolean('notif_rekomendasi'),
        ]);

        return back()->with('success', 'Preferensi notifikasi berhasil diperbarui.');
    }

    // Mengubah bahasa aplikasi (locale)
    public function changeLanguage($locale)
    {
        if (in_array($locale, ['id', 'en'])) {
            session()->put('locale', $locale);
        }

        return back()->with('success', 'Bahasa berhasil diubah / Language changed successfully.');
    }

    // Mengubah tema aplikasi (light / dark)
    public function changeTheme($theme)
    {
        if (in_array($theme, ['light', 'dark'])) {
            session()->put('theme', $theme);
        }

        return back()->with('success', 'Tema berhasil diubah / Theme changed successfully.');
    }

    // Menandai semua notifikasi dibaca
    public function readAllNotifications()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi berhasil ditandai sebagai dibaca.');
    }
}
