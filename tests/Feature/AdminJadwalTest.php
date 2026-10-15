<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\JadwalAdmin;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminJadwalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_melihat_halaman_jadwal()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $lapangan = Lapangan::create([
            'nama' => 'Lapangan A',
            'gambar' => 'lapangan/test.jpg',
            'jenis_lantai' => 'Vinyl',
            'harga_per_jam' => 100000,
            'ukuran' => '25x15',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.jadwal.index', [
                'lapangan_id' => $lapangan->id,
                'tanggal' => now()->toDateString(),
            ]));

        $response->assertStatus(200);
        $response->assertSee('Lapangan A');
    }

    public function test_jadwal_dapat_ditutup_oleh_admin()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $lapangan = Lapangan::create([
            'nama' => 'Lapangan A',
            'gambar' => 'lapangan/test.jpg',
            'jenis_lantai' => 'Vinyl',
            'harga_per_jam' => 100000,
            'ukuran' => '25x15',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.jadwal.update'), [
                'lapangan_id' => $lapangan->id,
                'tanggal' => now()->toDateString(),
                'status' => [
                    '10:00:00' => 'tutup',
                ],
            ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('jadwal_admin', [
            'lapangan_id' => $lapangan->id,
            'jam_mulai' => '10:00:00',
            'status' => 'tutup',
        ]);
    }

    public function test_jadwal_dapat_diubah_menjadi_tersedia()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $lapangan = Lapangan::create([
            'nama' => 'Lapangan A',
            'gambar' => 'lapangan/test.jpg',
            'jenis_lantai' => 'Vinyl',
            'harga_per_jam' => 100000,
            'ukuran' => '25x15',
        ]);

        JadwalAdmin::create([
            'lapangan_id' => $lapangan->id,
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '10:00:00',
            'status' => 'tutup',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.jadwal.update'), [
                'lapangan_id' => $lapangan->id,
                'tanggal' => now()->toDateString(),
                'status' => [
                    '10:00:00' => 'tersedia',
                ],
            ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('jadwal_admin', [
            'lapangan_id' => $lapangan->id,
            'jam_mulai' => '10:00:00',
            'status' => 'tersedia',
        ]);
    }

    public function test_jadwal_yang_sudah_dibooking_berstatus_dibooking()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $lapangan = Lapangan::create([
            'nama' => 'Lapangan A',
            'gambar' => 'lapangan/test.jpg',
            'jenis_lantai' => 'Vinyl',
            'harga_per_jam' => 100000,
            'ukuran' => '25x15',
        ]);

        Booking::create([
            'user_id' => $admin->id,
            'lapangan_id' => $lapangan->id,
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '10:00:00',
            'durasi' => 60,
            'total_harga' => 100000,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.jadwal.index', [
                'lapangan_id' => $lapangan->id,
                'tanggal' => now()->toDateString(),
            ]));

        $response->assertStatus(200);
    }
}