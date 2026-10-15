<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Booking;
use Carbon\Carbon;
use App\Models\JadwalAdmin;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        $bookings = Booking::all();

        $bookingAktif = 0;
        $bookingSelesai = 0;

        foreach ($bookings as $b) {
            $mulai = Carbon::parse($b->tanggal . ' ' . $b->jam_mulai);

            // ⛔ SESUAIKAN INI ⛔
            // JIKA durasi dalam MENIT:
            $selesai = $mulai->copy()->addMinutes((int) $b->durasi);

            // JIKA durasi dalam JAM, ganti ke:
            // $selesai = $mulai->copy()->addHours((int) $b->durasi);

            if ($now >= $selesai) {
                $bookingSelesai++;
            } else {
                $bookingAktif++;
            }
        }

        $today = Carbon::today();
        $now   = Carbon::now();

        // ambil booking hari ini
        $bookingHariIniDetail = Booking::whereDate('tanggal', $today)
            ->whereIn('status', ['pending','confirmed'])
            ->get();

        // ambil jadwal tutup admin hari ini
        $jadwalTutupHariIni = JadwalAdmin::whereDate('tanggal', $today)
            ->where('status','tutup')
            ->get();

        return view('admin.dashboard.index', [
            'totalLapangan'   => Lapangan::count(),
            'bookingHariIni'  => Booking::whereDate('tanggal', Carbon::today())->count(),
            'bookingAktif'    => $bookingAktif,
            'bookingSelesai'  => $bookingSelesai,
            'bookingTerbaru'  => Booking::with(['user','lapangan'])
                                        ->latest()
                                        ->limit(5)
                                        ->get(),
            'jadwalHariIni'        => $bookingHariIniDetail,
            'jadwalTutupHariIni'   => $jadwalTutupHariIni,
            'now'                  => $now,
        ]);
    }
}