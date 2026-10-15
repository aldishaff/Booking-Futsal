<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lapangan;

class LapanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lapangan::create([
            'nama' => 'Lapangan 1',
            'jenis_lantai' => 'Interlock',
            'ukuran' => '25m x 15m',
            'harga_per_jam' => 70000,
            'gambar' => 'lap-futsal.png',
        ]);

        Lapangan::create([
            'nama' => 'Lapangan VIP',
            'jenis_lantai' => 'Vinyl',
            'ukuran' => '30m x 16m',
            'harga_per_jam' => 90000,
            'gambar' => 'lap-vip.png',
        ]);
    }
}
