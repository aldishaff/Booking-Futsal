<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create(Request $request, Lapangan $lapangan)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();

        // semua jam (24 jam)
        $allHours = collect(range(0, 23))->map(function ($h) {
            return sprintf('%02d:00', $h);
        });

        // booking AKTIF saja
        $bookings = Booking::where('lapangan_id', $lapangan->id)
            ->where('tanggal', $tanggal)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        // hitung jam terpakai BERDASARKAN DURASI
        $bookedHours = collect();

        foreach ($bookings as $b) {
            $mulai   = Carbon::createFromFormat('H:i:s', $b->jam_mulai);
            $selesai = $mulai->copy()->addHours((int) $b->durasi);

            for ($h = (int) $mulai->format('H'); $h < (int) $selesai->format('H'); $h++) {
                $bookedHours->push(sprintf('%02d:00', $h));
            }
        }

        // jam tersedia
        $availableHours = $allHours->diff($bookedHours)->values();

        return view('booking.create', [
            'lapangan'       => $lapangan,
            'tanggal'        => $tanggal,
            'jam'            => $request->jam,
            'availableHours' => $availableHours,
            'fromJadwal'     => $request->has('jam'),
        ]);
    }

    public function riwayat(Request $request)
    {
        $query = Booking::with(['lapangan', 'transaction'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->from && $request->to) {
            $query->whereBetween('tanggal', [$request->from, $request->to]);
        }

        $bookings = $query->paginate(5);

        return view('user.riwayat-pesanan', compact('bookings'));
    }

    public function detail(Booking $booking)
    {
        // Security: pastikan booking milik user login
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['lapangan', 'transaction']);

        return view('user.riwayat-detail', compact('booking'));
    }
}
