<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_user_memesan_lapangan()
    {

        // buat user manual (biar ga error name)
        $user = \App\Models\User::create([
            'username' => 'testuser',
            'email' => 'test@gmail.com',
            'password' => bcrypt('12345678'),
            'no_telepon' => '08123456789'
        ]);

        $this->actingAs($user);

        // buat lapangan
        $lapangan = \App\Models\Lapangan::create([
            'nama' => 'Lapangan 1',
            'jenis_lantai' => 'Sintetis',
            'ukuran' => '5x5',
            'harga_per_jam' => 70000,
            'gambar' => 'lapangan.jpg'
        ]);

        // buat booking (ISI SEMUA FIELD PENTING)
        \App\Models\Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '10:00:00',
            'durasi' => 2,
            'total_harga' => 100000,
            'catatan' => '-',
            'status' => 'confirmed'
        ]);

        // akses route
        $response = $this->get("/booking/{$lapangan->id}");

        $response->assertStatus(200);

        $availableHours = $response->viewData('availableHours');

        // jam terpakai
        $this->assertFalse($availableHours->contains('10:00'));
        $this->assertFalse($availableHours->contains('11:00'));

        // jam masih ada
        $this->assertTrue($availableHours->contains('12:00'));
    }
}
