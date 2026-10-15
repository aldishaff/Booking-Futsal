<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewLapTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_menampilkan_daftar_lapangan()
    {

        // user
        $user = \App\Models\User::create([
            'username' => 'testuser',
            'email' => 'test@gmail.com',
            'password' => bcrypt('123456'),
            'no_telepon' => '08123456789'
        ]);

        $this->actingAs($user);

        // data lapangan
        \App\Models\Lapangan::create([
            'nama' => 'Lapangan A',
            'jenis_lantai' => 'Sintetis',
            'ukuran' => '5x5',
            'harga_per_jam' => 100000,
            'gambar' => 'lapangan.jpg'
        ]);

        // akses halaman home (INI YANG BENAR)
        $response = $this->get('/');

        $response->assertStatus(200);

        // cek tampil
        $response->assertSee('Pilihan Lapangan');
        $response->assertSee('Lapangan A');
    }
}
