<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'lapangan'])
            ->orderByDesc('tanggal')
            ->orderByDesc('jam_mulai');

        // 🔍 FILTER STATUS
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // 🔍 FILTER TANGGAL
        if ($request->tanggal) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $bookings = $query->paginate(10)->withQueryString();

        return view('admin.booking.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        return view('admin.booking.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:completed,cancelled',
        ]);

        $booking->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status pemesanan berhasil diperbarui');
    }
}
