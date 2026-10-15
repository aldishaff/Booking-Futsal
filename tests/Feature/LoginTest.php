<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_login_dapat_diakses()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_user_dapat_login()
    {
        $user = User::create([
            'username' => 'aldi',
            'email' => 'aldi@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'aldi@gmail.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_dapat_login()
    {
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'admin@gmail.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_gagal_jika_password_salah()
    {
        User::create([
            'username' => 'aldi',
            'email' => 'aldi@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->from(route('login'))
            ->post(route('login.post'), [
                'email' => 'aldi@gmail.com',
                'password' => 'salah',
            ]);

        $response->assertRedirect(route('login'));

        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_validasi_login_gagal_jika_kosong()
    {
        $response = $this->from(route('login'))
            ->post(route('login.post'), []);

        $response->assertSessionHasErrors([
            'email',
            'password',
        ]);
    }

    public function test_user_dapat_logout()
    {
        $user = User::create([
            'username' => 'aldi',
            'email' => 'aldi@gmail.com',
            'no_telepon' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)
            ->post(route('logout'));

        $response->assertRedirect(route('login'));

        $this->assertGuest();
    }
}