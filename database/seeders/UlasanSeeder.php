<?php

namespace Database\Seeders;

use App\Models\Buku;
use App\Models\User;
use App\Models\Ulasan;
use Illuminate\Database\Seeder;

class UlasanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Dapatkan atau buat user reviewer sesuai mockup
        $user1 = User::firstOrCreate(
            ['email' => 'rizky@macabae.com'],
            [
                'name' => 'Rizky Pratama',
                'password' => bcrypt('password123'),
                'role' => 'member',
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'ahmad@macabae.com'],
            [
                'name' => 'Ahmad Fauzi',
                'password' => bcrypt('password123'),
                'role' => 'member',
            ]
        );

        // 2. Berikan ulasan untuk semua buku yang ada
        $bukus = Buku::all();

        foreach ($bukus as $buku) {
            // Ulasan 1
            Ulasan::updateOrCreate(
                [
                    'user_id' => $user1->id,
                    'buku_id' => $buku->id,
                ],
                [
                    'rating' => 5,
                    'komentar' => 'Buku yang sangat mudah dipahami dan aplikatif. Cocok untuk membangun kebiasaan sehari-hari.',
                ]
            );

            // Ulasan 2
            Ulasan::updateOrCreate(
                [
                    'user_id' => $user2->id,
                    'buku_id' => $buku->id,
                ],
                [
                    'rating' => 4,
                    'komentar' => 'Buku yang sangat bagus untuk memotivasi diri. Penjelasannya runut dan logis.',
                ]
            );
        }
    }
}
