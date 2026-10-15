<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_melihat_halaman_booking()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.booking.index'));

        $response->assertStatus(200);
    }

    public function test_admin_dapat_melihat_detail_booking()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $user = User::create([
            'username' => 'user',
            'email' => 'user@gmail.com',
            'no_telepon' => '08111111111',
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
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.booking.show', $booking->id));

        $response->assertStatus(200);
    }

    public function test_admin_dapat_mengubah_status_booking()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $user = User::create([
            'username' => 'user',
            'email' => 'user@gmail.com',
            'no_telepon' => '08111111111',
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
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.booking.updateStatus', $booking->id), [
                'status' => 'completed',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'completed',
        ]);
    }
}