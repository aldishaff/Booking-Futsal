<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class JadwalTest extends TestCase
{
    use RefreshDatabase;

    private function createUser()
    {
        $user = \App\Models\User::create([
            'username' => 'testuser',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456'),
            'no_telepon' => '08123456789'
        ]);

        $this->actingAs($user);

        return $user;
    }

    private function createLapangan()
    {
        return \App\Models\Lapangan::create([
            'nama' => 'Lapangan A',
            'jenis_lantai' => 'Sintetis',
            'ukuran' => '5x5',
            'harga_per_jam' => 100000,
            'gambar' => 'lapangan.jpg'
        ]);
    }

    /**
     * TEST 1: STATUS DIBOOKING
     */
    public function test_jadwal_dibooking()
    {
        Carbon::setTestNow('2026-01-01 08:00:00');

        $user = $this->createUser();
        $lapangan = $this->createLapangan();

        $tanggal = '2026-01-01';

        \App\Models\Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '10:00:00',
            'durasi' => 2,
            'total_harga' => 100000,
            'catatan' => '-',
            'status' => 'confirmed'
        ]);

        $response = $this->post('/cek-ketersediaan', [
            'lapangan_id' => $lapangan->id,
            'tanggal' => $tanggal,
            'jam' => '10:00'
        ]);

        $response->assertJson(['status' => 'dibooking']);
    }

    /**
     * TEST 2: STATUS TUTUP
     */
    public function test_jadwal_tutup()
    {
        Carbon::setTestNow('2026-01-01 08:00:00');

        $this->createUser();
        $lapangan = $this->createLapangan();

        $tanggal = '2026-01-01';

        \App\Models\JadwalAdmin::create([
            'lapangan_id' => $lapangan->id,
            'tanggal' => $tanggal,
            'jam_mulai' => '12:00:00',
            'status' => 'tutup'
        ]);

        $response = $this->post('/cek-ketersediaan', [
            'lapangan_id' => $lapangan->id,
            'tanggal' => $tanggal,
            'jam' => '12:00'
        ]);

        $response->assertJson(['status' => 'tutup']);
    }

    /**
     * TEST 3: STATUS TERSEDIA
     */
    public function test_jadwal_tersedia()
    {
        Carbon::setTestNow('2026-01-01 08:00:00');

        $this->createUser();
        $lapangan = $this->createLapangan();

        $tanggal = '2026-01-01';

        $response = $this->post('/cek-ketersediaan', [
            'lapangan_id' => $lapangan->id,
            'tanggal' => $tanggal,
            'jam' => '15:00'
        ]);

        $response->assertJson(['status' => 'tersedia']);
    }
}