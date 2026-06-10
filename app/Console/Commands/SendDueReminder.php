<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use App\Mail\JatuhTempoMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendDueReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peminjaman:remind-due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email pengingat H-1 sebelum jatuh tempo peminjaman buku';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        
        $peminjamans = Peminjaman::with('user', 'buku')
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', $tomorrow)
            ->get();

        $this->info("Menemukan {$peminjamans->count()} peminjaman yang akan jatuh tempo besok ($tomorrow).");

        $sentCount = 0;

        foreach ($peminjamans as $peminjaman) {
            $user = $peminjaman->user;
            if ($user && $user->notif_jatuh_tempo) {
                try {
                    Mail::to($user->email)->send(new JatuhTempoMail($peminjaman, 1));
                    $this->line("Email pengingat dikirim ke: {$user->email} untuk buku: {$peminjaman->buku->judul}");
                    $sentCount++;
                } catch (\Exception $e) {
                    $this->error("Gagal mengirim email ke {$user->email}: " . $e->getMessage());
                }
            } else {
                $this->line("User {$user->email} menonaktifkan notifikasi jatuh tempo.");
            }
        }

        $this->info("Selesai. Berhasil mengirim {$sentCount} email pengingat.");
    }
}
