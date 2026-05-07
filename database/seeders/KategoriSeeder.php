<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Informatika'],
            ['nama_kategori' => 'Sains'],
            ['nama_kategori' => 'Novel'],
            ['nama_kategori' => 'Sejarah'],
        ];

        foreach ($kategori as $k) {
            \App\Models\Kategori::create($k);
        }
    }
}
