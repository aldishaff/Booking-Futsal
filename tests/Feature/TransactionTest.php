<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TransactionTest extends TestCase
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
     * TEST 1 : PEMBAYARAN BERHASIL
     */
    public function test_pembayaran_berhasil_mengubah_status_transaksi_dan_booking()
    {
        $user = $this->createUser();
        $lapangan = $this->createLapangan();

        $booking = \App\Models\Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '10:00:00',
            'durasi' => 2,
            'total_harga' => 200000,
            'catatan' => '-',
            'status' => 'pending'
        ]);

        $transaction = \App\Models\Transaction::create([
            'booking_id' => $booking->id,
            'metode' => 'midtrans',
            'status' => 'pending',
            'midtrans_order_id' => 'TEST-001'
        ]);

        $transaction->update([
            'status' => 'paid'
        ]);

        $booking->update([
            'status' => 'confirmed'
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'paid'
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed'
        ]);
    }

    /**
     * TEST 2 : PEMBAYARAN GAGAL
     */
    public function test_pembayaran_gagal_membatalkan_booking()
    {
        $user = $this->createUser();
        $lapangan = $this->createLapangan();

        $booking = \App\Models\Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '10:00:00',
            'durasi' => 2,
            'total_harga' => 200000,
            'catatan' => '-',
            'status' => 'pending'
        ]);

        $transaction = \App\Models\Transaction::create([
            'booking_id' => $booking->id,
            'metode' => 'midtrans',
            'status' => 'pending',
            'midtrans_order_id' => 'TEST-002'
        ]);

        $transaction->update([
            'status' => 'failed'
        ]);

        $booking->update([
            'status' => 'cancelled'
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'failed'
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled'
        ]);
    }

    /**
     * TEST 3 : PEMBAYARAN KEDALUWARSA
     */
    public function test_pembayaran_kedaluwarsa_membatalkan_booking()
    {
        $user = $this->createUser();
        $lapangan = $this->createLapangan();

        $booking = \App\Models\Booking::create([
            'user_id' => $user->id,
            'lapangan_id' => $lapangan->id,
            'tanggal' => now()->toDateString(),
            'jam_mulai' => '10:00:00',
            'durasi' => 2,
            'total_harga' => 200000,
            'catatan' => '-',
            'status' => 'pending'
        ]);

        $transaction = \App\Models\Transaction::create([
            'booking_id' => $booking->id,
            'metode' => 'midtrans',
            'status' => 'pending',
            'midtrans_order_id' => 'TEST-003'
        ]);

        $transaction->update([
            'status' => 'expired'
        ]);

        $booking->update([
            'status' => 'cancelled'
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'expired'
        ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled'
        ]);
    }
}