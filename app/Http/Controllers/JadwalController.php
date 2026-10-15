<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\JadwalAdmin;


class JadwalController extends Controller
{

    public function index(Request $request)
    {
        $lapangans = Lapangan::all();

        $lapangan = $request->lapangan_id
            ? Lapangan::findOrFail($request->lapangan_id)
            : Lapangan::first();

        // ⬇️ tanggal acuan (default: hari ini)
        $baseDate = $request->tanggal
            ? Carbon::parse($request->tanggal)
            : Carbon::now();

        // minggu dari tanggal terpilih
        $startDate = $baseDate->copy();
        $endDate   = $startDate->copy()->addDays(6);

        $bookings = Booking::where('lapangan_id', $lapangan->id)
        ->whereBetween('tanggal', [$startDate, $endDate])
        ->whereIn('status', ['pending', 'confirmed'])
        ->get();

        // JADWAL TUTUP DARI ADMIN
        $adminTutup = JadwalAdmin::where('lapangan_id', $lapangan->id)
            ->whereBetween('tanggal', [
                $startDate->toDateString(),
                $endDate->toDateString()
            ])
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal . ' ' . $item->jam_mulai;
            });

        return view('jadwal.index', compact(
            'lapangans',
            'lapangan',
            'bookings',
            'adminTutup',
            'startDate',
            'endDate',
            'baseDate'
        ));
    }

    public function cekKetersediaan(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal'     => 'required|date',
            'jam'         => 'required|date_format:H:i',
        ]);

        $tanggal    = Carbon::parse($request->tanggal)->startOfDay();
        $jamMulai   = Carbon::parse($request->tanggal.' '.$request->jam);
        $jamSelesai = $jamMulai->copy()->addHour();
        $now        = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ SELESAI — TANGGAL MASA LALU (KEMARIN & SEBELUMNYA)
        |--------------------------------------------------------------------------
        */
        if ($tanggal->isBefore(Carbon::today())) {
            return response()->json([
                'status'  => 'selesai',
                'message' => 'Tanggal tersebut sudah lewat',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ SELESAI — HARI INI & JAM SUDAH LEWAT
        |--------------------------------------------------------------------------
        */
        if ($tanggal->isToday() && $jamSelesai <= $now) {
            return response()->json([
                'status'  => 'selesai',
                'message' => 'Jam tersebut sudah selesai',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ TUTUP (ADMIN) — RANGE
        |--------------------------------------------------------------------------
        */
        $tutup = JadwalAdmin::where('lapangan_id', $request->lapangan_id)
            ->whereDate('tanggal', $tanggal->toDateString())
            ->where('status', 'tutup')
            ->get()
            ->contains(function ($j) use ($jamMulai) {
                $mulai   = Carbon::parse($j->tanggal.' '.$j->jam_mulai);
                $selesai = $mulai->copy()->addHour();
                return $jamMulai >= $mulai && $jamMulai < $selesai;
            });

        if ($tutup) {
            return response()->json([
                'status'  => 'tutup',
                'message' => 'Lapangan ditutup pada jam tersebut',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 4️⃣ DIBOOKING (USER) — RANGE
        |--------------------------------------------------------------------------
        */
        $dibooking = Booking::where('lapangan_id', $request->lapangan_id)
            ->whereDate('tanggal', $tanggal->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->contains(function ($b) use ($jamMulai, $jamSelesai) {
                $mulai   = Carbon::parse($b->tanggal.' '.$b->jam_mulai);
                $selesai = $mulai->copy()->addMinutes((int) $b->durasi);
                return $jamMulai < $selesai && $jamSelesai > $mulai;
            });

        if ($dibooking) {
            return response()->json([
                'status'  => 'dibooking',
                'message' => 'Jam tersebut sudah dibooking',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 5️⃣ TERSEDIA
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'status'  => 'tersedia',
            'message' => 'Jam tersedia, silakan booking',
        ]);
    }


}
