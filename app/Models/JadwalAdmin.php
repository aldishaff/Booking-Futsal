<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JadwalAdmin extends Model
{
    protected $table = 'jadwal_admin';

    protected $fillable = [
        'lapangan_id',
        'tanggal',
        'jam_mulai',
        'status'
    ];
}
