<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    public function respond(Request $request)
    {
        $message = trim($request->input('message', ''));
        $user    = Auth::user();

        if (empty($message)) {
            return response()->json(['reply' => 'Ketik pesanmu ya! 😊']);
        }

        // 1. Gather Context (Data Real-time)
        $context = $this->gatherContext($user);

        // 2. System Instruction (Prompt Utama AI)
        $systemPrompt = "Kamu adalah MacaBot 🤖, asisten AI dari perpustakaan digital MacaBae. " .
            "Jawab pertanyaan dengan ramah, santai, dan profesional. Gunakan Markdown standard (**tebal**, *miring*) jika perlu. " .
            "Gunakan enter biasa untuk baris baru. JANGAN pernah menggunakan tag HTML apa pun. " .
            "Berikut adalah informasi real-time tentang pengguna yang berbicara denganmu:\n\n" . 
            $context . "\n\n" .
            "Tugas Utama: Jawab pertanyaan pengguna HANYA berdasarkan data di atas. " .
            "Jika pengguna bertanya tentang buku, rekomendasikan atau cari dari 'KATALOG BUKU TERSEDIA'. Jika stok 0, beritahu bahwa stok habis dan pengguna bisa melakukan 'Reservasi' di detail buku. " .
            "Jika pengguna bertanya tentang pinjamannya, bacakan 'STATUS PINJAMAN AKTIF'. " .
            "Jika pengguna bertanya tentang denda, bacakan 'STATUS DENDA'. " .
            "Aturan perpustakaan: Peminjaman 1-7 hari, denda keterlambatan Rp 1.000/hari, maksimal meminjam 5 buku sekaligus. " .
            "Jam operasional (jika ditanya datang langsung): Senin-Jumat (08.00-16.00), Sabtu (09.00-13.00), Minggu tutup. " .
            "Fitur website: sarankan membuka halaman katalog buku di '/katalog', riwayat lengkap di '/riwayat', atau profil di '/pengaturan'. " .
            "JANGAN memberikan informasi palsu atau merekomendasikan buku yang tidak ada dalam daftar katalog di atas.";

        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return response()->json(['reply' => 'Maaf, kunci API Gemini belum dikonfigurasi di file .env. Hubungi administrator.']);
        }

        // 3. Panggil API Gemini Flash (Alias Dinamis Terbaru)
        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'system_instruction' => [
                    'parts' => [
                        ['text' => $systemPrompt]
                    ]
                ],
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $message]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $replyText = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, aku tidak bisa memproses balasan saat ini. 😔';
                
                // 1. Konversi markdown bold dan italic
                $replyText = preg_replace('/\*\*(.*?)\*\*/s', '<b>$1</b>', $replyText);
                $replyText = preg_replace('/(?<!\w)\*(?!\s)(.*?)(?<!\s)\*(?!\w)/s', '<i>$1</i>', $replyText);
                
                // 2. Konversi bullet points * menjadi •
                $replyText = preg_replace('/^\s*\*\s/m', '• ', $replyText);
                
                // 3. Ubah literal text '\n' menjadi newline asli
                $replyText = str_replace(['\n', '\r'], "\n", $replyText);
                
                // 4. Escape semua karakter HTML untuk mencegah tag error di browser (menghilangkan teks)
                $replyText = htmlspecialchars($replyText, ENT_NOQUOTES, 'UTF-8');
                
                // 5. Kembalikan tag <b> dan <i> yang sudah kita buat
                $replyText = str_replace(['&lt;b&gt;', '&lt;/b&gt;', '&lt;i&gt;', '&lt;/i&gt;'], ['<b>', '</b>', '<i>', '</i>'], $replyText);
                
                // 6. Hapus spasi paragraf berlebih (maksimal 2 newline)
                $replyText = preg_replace("/\n{3,}/", "\n\n", $replyText);
                
                return response()->json(['reply' => trim($replyText)]);
            } else {
                $errorMsg = $response->json()['error']['message'] ?? 'Unknown Error';
                return response()->json(['reply' => "Sistem AI Google sedang menolak permintaan. Kode: {$response->status()} - {$errorMsg} 😔"]);
            }
        } catch (\Exception $e) {
            return response()->json(['reply' => 'Waduh, koneksi ke otak AI-ku terputus. Coba periksa koneksi internet server atau API key. 🔌']);
        }
    }

    private function gatherContext($user)
    {
        $context = "--- DATA PENGGUNA ---\n";
        $context .= "Nama Pengguna: " . ($user ? $user->name : "Tamu") . "\n";
        $context .= "Waktu Sekarang: " . Carbon::now()->translatedFormat('l, d F Y H:i') . "\n\n";

        if ($user) {
            // Pinjaman
            $loans = Peminjaman::with('buku')
                ->where('user_id', $user->id)
                ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
                ->get();

            $context .= "--- STATUS PINJAMAN AKTIF PENGGUNA ---\n";
            if ($loans->isEmpty()) {
                $context .= "Pengguna TIDAK memiliki peminjaman buku aktif saat ini.\n";
            } else {
                foreach ($loans as $loan) {
                    $status = $loan->status == 'dipinjam' ? 'Sedang Dipinjam' : 'Menunggu Konfirmasi Pustakawan';
                    $context .= "Judul: '{$loan->buku->judul}' | Status: {$status} | Tanggal Harus Kembali: {$loan->tanggal_kembali}\n";
                }
            }

            // Denda
            $dendas = Denda::whereHas('peminjaman', fn($q) => $q->where('user_id', $user->id))
                ->where('status_pembayaran', 'belum_lunas')
                ->with('peminjaman.buku')
                ->get();
            
            $context .= "\n--- STATUS DENDA BELUM LUNAS ---\n";
            if ($dendas->isEmpty()) {
                $context .= "Bagus! Pengguna tidak memiliki denda tunggakan keterlambatan (Rp 0).\n";
            } else {
                $total = 0;
                foreach ($dendas as $d) {
                    $context .= "Buku: '{$d->peminjaman->buku->judul}' | Jumlah Denda: Rp " . number_format($d->jumlah_denda, 0, ',', '.') . "\n";
                    $total += $d->jumlah_denda;
                }
                $context .= "Total Denda Keseluruhan: Rp " . number_format($total, 0, ',', '.') . "\n";
            }
        }

        // Katalog Buku
        $context .= "\n--- KATALOG BUKU TERSEDIA DI MACABAE ---\n";
        $books = Buku::where('status', 'aktif')->get();
        foreach ($books as $buku) {
            $statusStok = $buku->stok > 0 ? "Stok Tersedia ({$buku->stok} buah)" : "Stok Habis (0)";
            $context .= "Judul: '{$buku->judul}' | Karya: {$buku->pengarang} | Status: {$statusStok}\n";
        }

        return $context;
    }
}
