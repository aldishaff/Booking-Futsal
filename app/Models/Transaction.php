<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'booking_id',
        'metode',
        'status',
        'bukti_transfer',
        'midtrans_order_id',
        'snap_token',
    ];

    /**
     * Relasi ke Booking
     * 1 transaksi dimiliki oleh 1 booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
