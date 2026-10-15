<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RiwayatBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_dapat_melihat_halaman_riwayat_pemesanan()
    {
        $user = User::create([
            'username' => 'aldi',
            'email' => 'aldi@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)
            ->get(route('riwayat.pesanan'));

        $response->assertStatus(200);
    }

    public function test_user_dapat_melihat_detail_riwayat()
    {
        $user = User::create([
            'username' => 'aldi',
            'email' => 'aldi@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $lapangan = Lapangan::create([
            'nama' => 'Lapangan A',
            'gambar' => 'lapangan/test.jpg',
            'jenis_lantai' => 'Vinyl',
            'harga_per_jam' => 100000,
            'ukuran' => '25x15',
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '10:00:00',
            'durasi' => 60,
            'total_harga' => 100000,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($user)
            ->get(route('riwayat.pesanan.detail', $booking->id));

        $response->assertStatus(200);
    }

    public function test_user_dapat_filter_riwayat_berdasarkan_status()
    {
        $user = User::create([
            'username' => 'aldi',
            'email' => 'aldi@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)
            ->get(route('riwayat.pesanan', [
                'status' => 'confirmed'
            ]));

        $response->assertStatus(200);
    }
}