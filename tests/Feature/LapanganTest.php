<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Lapangan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LapanganTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_menambahkan_data_lapangan()
    {
        Storage::fake('public');

        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.lapangan.store'), [
                'nama' => 'Lapangan A',
                'gambar' => UploadedFile::fake()->create('lapangan.jpg', 100),
                'jenis_lantai' => 'Vinyl',
                'harga_per_jam' => 120000,
                'ukuran' => '25x15',
            ]);

        $response->assertRedirect(route('admin.lapangan.index'));

        $this->assertDatabaseHas('lapangans', [
            'nama' => 'Lapangan A',
            'jenis_lantai' => 'Vinyl',
            'harga_per_jam' => 120000,
            'ukuran' => '25x15',
        ]);
    }

    public function test_validasi_gagal_jika_data_kosong()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.lapangan.store'), []);

        $response->assertSessionHasErrors([
            'nama',
            'gambar',
            'jenis_lantai',
            'harga_per_jam',
            'ukuran',
        ]);
    }

    public function test_admin_dapat_mengubah_data_lapangan()
    {
        Storage::fake('public');

        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $lapangan = Lapangan::create([
            'nama' => 'Lapangan Lama',
            'gambar' => 'lapangan/test.jpg',
            'jenis_lantai' => 'Vinyl',
            'harga_per_jam' => 100000,
            'ukuran' => '25x15',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.lapangan.update', $lapangan->id), [
                'nama' => 'Lapangan Baru',
                'jenis_lantai' => 'Rumput Sintetis',
                'harga_per_jam' => 150000,
                'ukuran' => '30x20',
                'gambar' => UploadedFile::fake()->create('baru.jpg', 100),
            ]);

        $response->assertRedirect(route('admin.lapangan.index'));

        $this->assertDatabaseHas('lapangans', [
            'nama' => 'Lapangan Baru',
            'jenis_lantai' => 'Rumput Sintetis',
            'harga_per_jam' => 150000,
        ]);
    }

    public function test_admin_dapat_menghapus_data_lapangan()
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
            ->delete(route('admin.lapangan.destroy', $lapangan->id));

        $response->assertRedirect(route('admin.lapangan.index'));

        $this->assertDatabaseMissing('lapangans', [
            'id' => $lapangan->id,
        ]);
    }
}