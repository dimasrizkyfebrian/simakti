<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kegiatan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Panggil seeder untuk membuat user admin kita yang utama
        $this->call([
            AdminUserSeeder::class,
        ]);

        // 2. Buat 10 user dummy dengan role acak
        User::factory(19)->create();

        // 3. Buat 25 kegiatan dummy. PIC akan dipilih dari user yang sudah dibuat.
        Kegiatan::factory(25)->create();
    }
}
