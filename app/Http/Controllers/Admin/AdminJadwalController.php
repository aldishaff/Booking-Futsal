<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Booking;
use App\Models\JadwalAdmin;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminJadwalController extends Controller
{
     public function index(Request $request)
    {
        // 🔹 AMBIL SEMUA LAPANGAN (INI YANG KURANG)
        $lapangans = Lapangan::all();

        // 🔹 PILIH LAPANGAN
        $lapanganId = $request->lapangan_id ?? $lapangans->first()->id;

        // 🔹 TANGGAL
        $tanggal = $request->tanggal ?? Carbon::today()->toDateString();

        // 🔹 JADWAL ADMIN (TUTUP / TERSEDIA)
        $adminJadwal = JadwalAdmin::where('lapangan_id', $lapanganId)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('jam_mulai');

        // 🔹 BOOKING
        $bookings = Booking::where('lapangan_id', $lapanganId)
            ->whereDate('tanggal', $tanggal)
            ->get();

        // 🔹 BUAT SLOT
        $slots = [];

        for ($jam = 0; $jam <= 23; $jam++) {

            $jamDb = sprintf('%02d:00:00', $jam);
            $label = sprintf('%02d:00 - %02d:00', $jam, ($jam + 1) %24);

            $status = 'tersedia';

            // PRIORITAS 1: DITUTUP ADMIN
            if (
                isset($adminJadwal[$jamDb]) &&
                $adminJadwal[$jamDb]->status === 'tutup'
            ) {
                $status = 'tutup';
            }

            // PRIORITAS 2: BOOKING USER
            foreach ($bookings as $b) {
                $mulai = Carbon::parse($b->tanggal . ' ' . $b->jam_mulai);
                $selesai = $mulai->copy()->addMinutes($b->durasi);

                $slotMulai = Carbon::parse($tanggal . ' ' . $jamDb);
                $slotSelesai = $slotMulai->copy()->addHour();

                if ($slotMulai < $selesai && $slotSelesai > $mulai) {
                    $status = 'dibooking';
                    break;
                }
            }

            $slots[] = [
                'jam'    => $label,
                'jam_db' => $jamDb,
                'status' => $status,
            ];
        }

        // 🔥 KIRIM SEMUA KE VIEW
        return view('admin.jadwal.index', compact(
            'lapangans',
            'lapanganId',
            'tanggal',
            'slots'
        ));
    }

    public function update(Request $request)
    {
        foreach ($request->status as $jam => $status) {
            JadwalAdmin::updateOrCreate(
                [
                    'lapangan_id' => $request->lapangan_id,
                    'tanggal'     => $request->tanggal,
                    'jam_mulai'   => $jam,
                ],
                ['status' => $status]
            );
        }

        return back()->with('success','Jadwal berhasil diperbarui');
    }
}