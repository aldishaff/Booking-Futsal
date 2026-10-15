<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;

class LapanganController extends Controller{
    public function show(Lapangan $lapangan)
    {
        $tanggal = request('tanggal');
        $jam     = request('jam');

        return view('lapangan.show', compact('lapangan', 'tanggal', 'jam'));
    }
}

