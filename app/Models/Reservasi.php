<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Notifications\SirkulasiNotification;
use Carbon\Carbon;

class Reservasi extends Model
{
    protected $table = 'reservasis';

    protected $fillable = [
        'user_id',
        'buku_id',
        'durasi',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    /**
     * Memeriksa antrean reservasi dan memprosesnya jika stok tersedia.
     * Stok tersedia = stok saat ini - jumlah pengajuan peminjaman aktif (menunggu_konfirmasi)
     *
     * @param int $bukuId
     * @return void
     */
    public static function checkAndProcessReservations($bukuId)
    {
        $buku = Buku::findOrFail($bukuId);

        // Hitung pengajuan peminjaman aktif yang belum disetujui (menunggu_konfirmasi)
        $activeRequestsCount = Peminjaman::where('buku_id', $bukuId)
            ->where('status', 'menunggu_konfirmasi')
            ->count();

        // Stok riil yang tersedia untuk reservasi berikutnya
        $availableStock = $buku->stok - $activeRequestsCount;

        while ($availableStock > 0) {
            // Ambil antrean reservasi terlama
            $reservasi = self::where('buku_id', $bukuId)
                ->where('status', 'menunggu')
                ->orderBy('created_at', 'asc')
                ->first();

            if (!$reservasi) {
                break;
            }

            // Tandai reservasi telah diproses
            $reservasi->update([
                'status' => 'proses'
            ]);

            // Buat pengajuan peminjaman otomatis
            Peminjaman::create([
                'user_id' => $reservasi->user_id,
                'buku_id' => $bukuId,
                'tanggal_pinjam' => Carbon::now()->format('Y-m-d'),
                'tanggal_kembali' => Carbon::now()->addDays($reservasi->durasi ?? 7)->format('Y-m-d'),
                'status' => 'menunggu_konfirmasi',
            ]);

            // Kirim notifikasi in-app ke member
            if ($reservasi->user) {
                try {
                    $reservasi->user->notify(new SirkulasiNotification(
                        'Reservasi Buku Siap Diproses',
                        "Buku yang Anda reservasi '{$buku->judul}' kini tersedia. Pengajuan peminjaman otomatis telah diajukan.",
                        'success'
                    ));
                } catch (\Exception $e) {
                    // Tetap berjalan jika ada kendala notifikasi
                }
            }

            // Kirim notifikasi in-app ke Pustakawan & Admin
            $adminsAndLibrarians = User::whereIn('role', ['admin', 'pustakawan'])->get();
            foreach ($adminsAndLibrarians as $staff) {
                try {
                    $staff->notify(new SirkulasiNotification(
                        'Pengajuan Peminjaman Baru (Reservasi)',
                        "Member {$reservasi->user->name} mengajukan peminjaman buku '{$buku->judul}' (melalui antrean reservasi).",
                        'info'
                    ));
                } catch (\Exception $e) {
                    // Tetap berjalan jika ada kendala
                }
            }

            // Setelah membuat pengajuan baru, sisa slot berkurang
            $availableStock--;
        }
    }
}
