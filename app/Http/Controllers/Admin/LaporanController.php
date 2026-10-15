<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    private function filteredBookings(Request $request)
    {
        $query = Booking::with(['user', 'lapangan']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [
                $request->start_date,
                $request->end_date
            ]);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->lapangan_id) {
            $query->where('lapangan_id', $request->lapangan_id);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->filteredBookings($request);

        $filteredQuery = clone $query;

        /*
        |--------------------------------------------------------------------------
        | DATA BOOKING
        |--------------------------------------------------------------------------
        */
        $bookings = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | CARD STATISTIK
        |--------------------------------------------------------------------------
        */
        $totalBooking = (clone $filteredQuery)->count();

        $bookingSuccess = (clone $filteredQuery)
            ->where('status', 'confirmed')
            ->count();

        $bookingPending = (clone $filteredQuery)
            ->where('status', 'pending')
            ->count();

        $bookingCancel = (clone $filteredQuery)
            ->whereIn('status', ['cancel', 'canceled'])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL INCOME
        |--------------------------------------------------------------------------
        */
        $totalIncome = (clone $filteredQuery)
            ->where('status', 'confirmed')
            ->sum('total_harga');

        /*
        |--------------------------------------------------------------------------
        | TOP 3 LAPANGAN
        |--------------------------------------------------------------------------
        */
        $topLapangan = (clone $filteredQuery)
            ->selectRaw('lapangan_id, COUNT(*) as total')
            ->with('lapangan')
            ->groupBy('lapangan_id')
            ->orderByDesc('total')
            ->take(3)
            ->get();
        
        /*
        |--------------------------------------------------------------------------
        | INCOME HARIAN
        |--------------------------------------------------------------------------
        */
        $incomeChart = (clone $filteredQuery)
            ->selectRaw('tanggal, SUM(total_harga) as total_income')
            ->where('status', 'confirmed')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DATA LAPANGAN UNTUK FILTER
        |--------------------------------------------------------------------------
        */
        $lapangans = Lapangan::all();

        return view('admin.laporan.index', compact(
            'bookings',
            'totalBooking',
            'bookingSuccess',
            'bookingPending',
            'bookingCancel',
            'totalIncome',
            'topLapangan',
            'lapangans',
            'incomeChart'
        ));
    }
}

