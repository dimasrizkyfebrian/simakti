<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kegiatan>
 */
class KegiatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pagu = fake()->numberBetween(5000000, 20000000);
        $pengajuan = $pagu - fake()->numberBetween(0, 1000000);

        return [
            'nama_kegiatan' => 'Kegiatan ' . fake()->words(3, true),
            'sub_kegiatan' => 'Sub Kegiatan ' . fake()->sentence(4),
            'tanggal_mulai' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'tanggal_selesai' => function (array $attributes) {
                return fake()->dateTimeBetween($attributes['tanggal_mulai'], '+2 months');
            },
            'pic_id' => User::query()->inRandomOrder()->first()->id,
            'ketua_pelaksana' => fake()->name(),
            'pagu_anggaran' => $pagu,
            'total_pengajuan' => $pengajuan,
            'total_realisasi' => 0, // Awalnya selalu 0
        ];
    }
}
