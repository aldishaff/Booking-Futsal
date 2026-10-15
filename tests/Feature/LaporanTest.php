<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_melihat_halaman_laporan()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.laporan.index'));

        $response->assertStatus(200);
    }

    public function test_admin_dapat_filter_laporan()
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
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.laporan.index', [
                'status' => 'confirmed',
                'lapangan_id' => $lapangan->id,
            ]));

        $response->assertStatus(200);
        $response->assertSee('Lapangan A');
    }

    public function test_admin_dapat_export_pdf()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.laporan.pdf'));

        $response->assertStatus(200);
    }

    public function test_admin_dapat_export_excel()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.laporan.excel'));

        $response->assertStatus(200);
    }

    public function test_admin_dapat_export_csv()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.laporan.csv'));

        $response->assertStatus(200);
    }

    public function test_admin_dapat_export_word()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.laporan.word'));

        $response->assertStatus(200);
    }
}