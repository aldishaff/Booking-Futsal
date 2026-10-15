<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    
    protected $fillable = [
        'nama',
        'jenis_lantai',
        'ukuran',
        'harga_per_jam',
        'gambar',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'lapangan_id');
    }
}
