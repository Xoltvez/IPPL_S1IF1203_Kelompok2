<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;

class ChatbotController extends Controller
{
    public function respond(Request $request)
    {
        $message = strtolower(trim($request->input('message', '')));
        $user    = Auth::user();

        if (empty($message)) {
            return response()->json(['reply' => 'Ketik pesanmu ya! 😊']);
        }

        // ── Sapa ──────────────────────────────────────────────────────────────
        if ($this->matches($message, ['halo', 'hai', 'hello', 'hi', 'hei', 'selamat', 'pagi', 'siang', 'sore', 'malam', 'hey'])) {
            $nama = $user ? explode(' ', $user->name)[0] : 'Kak';
            return response()->json([
                'reply' =>
                "Halo, {$nama}! 👋 Aku <b>MacaBot</b>, asisten perpustakaan MacaBae.\n\n" .
                    "Aku bisa bantu kamu:\n" .
                    "📚 <b>Cari buku</b> — ketik: <i>cari [judul/pengarang]</i>\n" .
                    "📋 <b>Pinjaman aktif</b> — ketik: <i>pinjaman saya</i>\n" .
                    "💸 <b>Info denda</b> — ketik: <i>denda saya</i>\n" .
                    "❓ <b>Panduan</b> — ketik: <i>cara pinjam</i>\n\n" .
                    "Ada yang bisa dibantu? 😊"
            ]);
        }

        // ── Cari Buku ─────────────────────────────────────────────────────────
        if ($this->matches($message, ['cari', 'search', 'find', 'cari buku', 'ada buku'])) {
            $keyword = preg_replace('/^(cari buku|cari|search|find|ada buku)\s*/i', '', $request->input('message'));
            $keyword = trim($keyword);

            if (strlen($keyword) < 2) {
                return response()->json([
                    'reply' =>
                    "Silakan ketik judul atau nama pengarang setelah kata \"cari\".\n" .
                        "Contoh: <i>cari Atomic Habits</i> 🔍"
                ]);
            }

            $books = Buku::with('kategori')
                ->where(function ($q) use ($keyword) {
                    $q->where('judul', 'like', "%{$keyword}%")
                        ->orWhere('pengarang', 'like', "%{$keyword}%")
                        ->orWhere('isbn', 'like', "%{$keyword}%");
                })
                ->where('status', 'aktif')
                ->limit(5)
                ->get();

            if ($books->isEmpty()) {
                return response()->json([
                    'reply' =>
                    "Hmm, buku \"<b>{$keyword}</b>\" tidak ditemukan di katalog kami. 😔\n\n" .
                        "Coba cari dengan kata kunci lain, atau lihat <a href='/katalog' class='chatbot-link'>Katalog Lengkap</a>."
                ]);
            }

            $reply = "Ditemukan <b>{$books->count()} buku</b> untuk \"<b>{$keyword}</b>\":\n\n";
            foreach ($books as $b) {
                $stok  = $b->stok > 0 ? "✅ Tersedia ({$b->stok})" : "❌ Habis";
                $reply .= "📖 <b>{$b->judul}</b>\n";
                $reply .= "   ✍️ {$b->pengarang} | {$stok}\n\n";
            }
            $reply .= "<a href='/katalog?search=" . urlencode($keyword) . "' class='chatbot-link'>Lihat di Katalog →</a>";

            return response()->json(['reply' => $reply]);
        }

        // ── Pinjaman Aktif ────────────────────────────────────────────────────
        if ($this->matches($message, ['pinjaman saya', 'buku pinjam', 'sedang pinjam', 'dipinjam', 'pinjaman aktif', 'buku saya', 'pinjam saya'])) {
            $loans = Peminjaman::with('buku')
                ->where('user_id', $user->id)
                ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
                ->orderBy('tanggal_kembali')
                ->get();

            if ($loans->isEmpty()) {
                return response()->json([
                    'reply' =>
                    "Kamu belum punya peminjaman aktif saat ini. 📭\n\n" .
                        "Yuk <a href='/katalog' class='chatbot-link'>cari buku</a> yang menarik!"
                ]);
            }

            $reply = "Kamu punya <b>{$loans->count()} peminjaman</b> aktif:\n\n";
            foreach ($loans as $p) {
                $status = $p->status === 'menunggu_konfirmasi' ? '⏳ Menunggu Konfirmasi' : '📗 Dipinjam';
                $due    = \Carbon\Carbon::parse($p->tanggal_kembali)->translatedFormat('d M Y');
                $reply .= "{$status}\n";
                $reply .= "📖 <b>{$p->buku->judul}</b>\n";
                $reply .= "   📅 Kembali: {$due}\n\n";
            }
            $reply .= "<a href='/peminjaman' class='chatbot-link'>Lihat detail →</a>";

            return response()->json(['reply' => $reply]);
        }

        // ── Info Denda ────────────────────────────────────────────────────────
        if ($this->matches($message, ['denda', 'bayar denda', 'denda saya', 'keterlambatan', 'terlambat', 'biaya'])) {
            $dendas = Denda::whereHas('peminjaman', fn($q) => $q->where('user_id', $user->id))
                ->where('status_pembayaran', 'belum_lunas')
                ->with('peminjaman.buku')
                ->get();

            if ($dendas->isEmpty()) {
                return response()->json([
                    'reply' =>
                    "Yeay! 🎉 Kamu tidak punya denda yang belum dibayar.\n\n" .
                        "Ingat ya, denda keterlambatan <b>Rp 1.000/hari</b>. Selalu kembalikan tepat waktu! ⏰"
                ]);
            }

            $total = $dendas->sum('jumlah_denda');
            $reply = "⚠️ Kamu punya <b>{$dendas->count()} denda</b> belum lunas:\n\n";
            foreach ($dendas as $d) {
                $judul  = $d->peminjaman->buku->judul ?? 'Buku';
                $amount = number_format($d->jumlah_denda, 0, ',', '.');
                $reply .= "📖 <b>{$judul}</b>\n";
                $reply .= "   💰 Rp {$amount}\n\n";
            }
            $totalFmt = number_format($total, 0, ',', '.');
            $reply   .= "Total: <b>Rp {$totalFmt}</b>\n";
            $reply   .= "Hubungi pustakawan untuk pembayaran. 🏛️";

            return response()->json(['reply' => $reply]);
        }

        // ── Cara Pinjam ───────────────────────────────────────────────────────
        if ($this->matches($message, ['cara pinjam', 'gimana pinjam', 'bagaimana pinjam', 'prosedur', 'alur pinjam', 'langkah'])) {
            return response()->json([
                'reply' =>
                "📚 <b>Cara Meminjam Buku di MacaBae:</b>\n\n" .
                    "1️⃣ Buka <a href='/katalog' class='chatbot-link'>Katalog Buku</a>\n" .
                    "2️⃣ Pilih buku yang ingin dipinjam\n" .
                    "3️⃣ Klik <b>\"Pinjam Buku\"</b> & pilih durasi (1–7 hari)\n" .
                    "4️⃣ Tunggu <b>konfirmasi pustakawan</b>\n" .
                    "5️⃣ Setelah disetujui, ambil buku di perpustakaan 🏛️\n\n" .
                    "Ada pertanyaan lain? 😊"
            ]);
        }

        // ── Aturan / Peraturan ────────────────────────────────────────────────
        if ($this->matches($message, ['aturan', 'peraturan', 'kebijakan', 'rules', 'syarat'])) {
            return response()->json([
                'reply' =>
                "📜 <b>Peraturan Perpustakaan MacaBae:</b>\n\n" .
                    "📅 Durasi pinjam: <b>1–7 hari</b> per buku\n" .
                    "💸 Denda keterlambatan: <b>Rp 1.000/hari</b>\n" .
                    "📦 Maks. pinjaman: <b>5 buku</b> sekaligus\n" .
                    "🔄 Perpanjangan: hubungi pustakawan\n" .
                    "🚫 Buku rusak/hilang: dikenakan ganti rugi\n\n" .
                    "Patuhi aturan agar semua member nyaman! 🙏"
            ]);
        }

        // ── Jam Buka ──────────────────────────────────────────────────────────
        if ($this->matches($message, ['jam', 'buka', 'tutup', 'operasional', 'jam buka', 'open', 'waktu'])) {
            return response()->json([
                'reply' =>
                "🕐 <b>Jam Operasional Perpustakaan:</b>\n\n" .
                    "📅 Senin – Jumat: <b>08.00 – 16.00</b>\n" .
                    "📅 Sabtu: <b>09.00 – 13.00</b>\n" .
                    "🚫 Minggu & hari libur nasional: <b>Tutup</b>\n\n" .
                    "Peminjaman digital bisa dilakukan 24 jam melalui MacaBae! 💻"
            ]);
        }

        // ── Kontak ────────────────────────────────────────────────────────────
        if ($this->matches($message, ['kontak', 'contact', 'hubungi', 'email', 'telepon', 'cs', 'customer service'])) {
            return response()->json([
                'reply' =>
                "📞 <b>Hubungi Kami:</b>\n\n" .
                    "📧 Email: <b>macabae@library.id</b>\n" .
                    "📱 WhatsApp: <b>0812-3456-7890</b>\n" .
                    "🏛️ Alamat: Perpustakaan MacaBae, Gedung A Lt.2\n\n" .
                    "Atau langsung datang ke perpustakaan saat jam buka! 😊"
            ]);
        }

        // ── Reservasi ─────────────────────────────────────────────────────────
        if ($this->matches($message, ['reservasi', 'reserve', 'antri', 'antrian', 'booking'])) {
            return response()->json([
                'reply' =>
                "🔖 <b>Fitur Reservasi Buku:</b>\n\n" .
                    "Jika buku yang kamu inginkan sedang habis stok, kamu bisa menekan tombol <b>\"Reservasi\"</b> pada halaman detail buku.\n\n" .
                    "Kamu akan otomatis mendapat notifikasi ketika buku tersedia! 🔔\n\n" .
                    "<a href='/reservasi' class='chatbot-link'>Lihat Reservasi Saya →</a>"
            ]);
        }

        // ── Riwayat ───────────────────────────────────────────────────────────
        if ($this->matches($message, ['riwayat', 'history', 'histori', 'pinjaman lama', 'sudah dikembalikan'])) {
            return response()->json([
                'reply' =>
                "📜 Lihat semua riwayat peminjaman kamu di:\n\n" .
                    "<a href='/riwayat' class='chatbot-link'>Halaman Riwayat Pinjaman →</a>\n\n" .
                    "Di sana kamu bisa melihat semua buku yang pernah dipinjam beserta statusnya. 📚"
            ]);
        }

        // ── Terima kasih ──────────────────────────────────────────────────────
        if ($this->matches($message, ['terima kasih', 'makasih', 'thanks', 'thank you', 'thx', 'mantap', 'oke', 'ok', 'siap'])) {
            return response()->json([
                'reply' =>
                "Sama-sama! 😊 Senang bisa membantu.\n\n" .
                    "Jika ada pertanyaan lain, jangan ragu untuk tanya ya! 🤖✨"
            ]);
        }

        // ── Panduan ───────────────────────────────────────────────────────────
        if ($this->matches($message, ['panduan', 'help', 'bantuan', 'tolong', 'bingung', 'apa yang bisa'])) {
            return response()->json([
                'reply' =>
                "🤖 <b>Aku MacaBot, asisten MacaBae!</b>\n\n" .
                    "Ini yang bisa aku bantu:\n\n" .
                    "🔍 <b>cari [judul/pengarang]</b> — cari buku\n" .
                    "📋 <b>pinjaman saya</b> — buku yang sedang dipinjam\n" .
                    "💸 <b>denda saya</b> — info denda\n" .
                    "📖 <b>cara pinjam</b> — panduan peminjaman\n" .
                    "📜 <b>aturan</b> — peraturan perpustakaan\n" .
                    "🕐 <b>jam buka</b> — jam operasional\n" .
                    "🔖 <b>reservasi</b> — info reservasi buku\n" .
                    "📞 <b>kontak</b> — hubungi perpustakaan"
            ]);
        }

        // ── Default ───────────────────────────────────────────────────────────
        return response()->json([
            'reply' =>
            "Hmm, aku belum mengerti maksudnya. 🤔\n\n" .
                "Coba ketik:\n" .
                "• <b>cari [judul buku]</b>\n" .
                "• <b>pinjaman saya</b>\n" .
                "• <b>denda saya</b>\n" .
                "• <b>panduan</b> — lihat semua perintah\n\n" .
                "atau <b>halo</b> untuk memulai! 😊"
        ]);
    }

    /**
     * Cek apakah pesan mengandung salah satu keyword.
     */
    private function matches(string $message, array $keywords): bool
    {
        foreach ($keywords as $kw) {
            if (str_contains($message, $kw)) {
                return true;
            }
        }
        return false;
    }
}
