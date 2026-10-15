<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_register_dapat_diakses()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    public function test_user_dapat_registrasi()
    {
        $response = $this->post(route('register.store'), [
            'username' => 'aldi',
            'email' => 'aldi@gmail.com',
            'no_telepon' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'username' => 'aldi',
            'email' => 'aldi@gmail.com',
        ]);
    }

    public function test_registrasi_gagal_jika_email_sudah_digunakan()
    {
        User::create([
            'username' => 'aldi',
            'email' => 'aldi@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->from(route('register'))
            ->post(route('register.store'), [
                'username' => 'aldi2',
                'email' => 'aldi@gmail.com',
                'no_telepon' => '08111111111',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_registrasi_gagal_jika_password_tidak_sama()
    {
        $response = $this->from(route('register'))
            ->post(route('register.store'), [
                'username' => 'aldi',
                'email' => 'aldi@gmail.com',
                'no_telepon' => '08123456789',
                'password' => 'password123',
                'password_confirmation' => 'password456',
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_validasi_register_gagal_jika_data_kosong()
    {
        $response = $this->from(route('register'))
            ->post(route('register.store'), []);

        $response->assertSessionHasErrors([
            'username',
            'email',
            'no_telepon',
            'password',
        ]);
    }
}