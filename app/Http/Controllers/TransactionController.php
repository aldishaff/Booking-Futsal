<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Transaction;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * =========================
     * PREVIEW TRANSAKSI
     * (sebelum simpan ke DB)
     * =========================
     */
    public function preview(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required|date_format:H:i',
            'durasi'      => 'required|integer|min:1',
            'metode' => 'required|in:cash,midtrans',
        ]);

        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $total = $lapangan->harga_per_jam * $request->durasi;

        return view('transaction.preview', [
            'lapangan' => $lapangan,
            'data'     => $request->all(),
            'total'    => $total,
        ]);
    }

    /**
     * =========================
     * SIMPAN BOOKING + TRANSAKSI
     * =========================
     */
    public function store(Request $request)
    {
        $request->validate([
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal'     => 'required|date',
            'jam_mulai'   => 'required|date_format:H:i',
            'durasi'      => 'required|integer|min:1',
            'metode' => 'required|in:cash,midtrans',
        ]);

        $lapangan = Lapangan::findOrFail($request->lapangan_id);
        $durasi = (int) $request->durasi;

        // 🔒 CEK BENTROK JAM (SEBELUM INSERT)
        $jamMulaiBaru   = Carbon::createFromFormat('H:i', $request->jam_mulai);
        $jamSelesaiBaru = $jamMulaiBaru->copy()->addHours((int) $request->durasi);

        $exists = Booking::where('lapangan_id', $lapangan->id)
            ->where('tanggal', $request->tanggal)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->contains(function ($b) use ($jamMulaiBaru, $jamSelesaiBaru) {

                $jamMulaiLama   = Carbon::createFromFormat('H:i:s', $b->jam_mulai);
                $jamSelesaiLama = $jamMulaiLama->copy()->addHours((int) $b->durasi);

                // 🔑 LOGIKA BENTROK WAKTU
                return $jamMulaiBaru < $jamSelesaiLama
                    && $jamSelesaiBaru > $jamMulaiLama;
            });

        if ($exists) {
            return back()
                ->withErrors(['jam_mulai' => 'Jam sudah dibooking, silakan pilih jam lain'])
                ->withInput();
        }

        $total = $lapangan->harga_per_jam * $request->durasi;

        // 1️⃣ SIMPAN BOOKING
        $booking = Booking::create([
            'user_id'      => Auth::id(),
            'lapangan_id'  => $lapangan->id,
            'tanggal'      => $request->tanggal,
            'jam_mulai'    => $request->jam_mulai,
            'durasi'       => $durasi,
            'total_harga'  => $total,
            'status'       => 'pending',
        ]);

        // 2️⃣ SIMPAN TRANSAKSI
        $transaction = Transaction::create([
            'booking_id' => $booking->id,
            'metode'     => $request->metode,
            'status' => 'pending',
            'midtrans_order_id' => 'BOOK-' . time(),
        ]);

        // Jika cash
        // if ($request->metode === 'cash') {
        //     $booking->update([
        //         'status' => 'pending'
        //     ]);
        // }

        // 4️⃣ REDIRECT KE DETAIL TRANSAKSI
        return redirect()->route('transaction.show', $transaction->id);
    }

    /**
     * =========================
     * RIWAYAT TRANSAKSI USER
     * =========================
     */
    public function index(Request $request)
    {
        $transactions = Transaction::with(['booking.lapangan'])
            ->whereHas('booking', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('user.riwayat-transaksi', compact('transactions'));
    }
    /**
     * =========================
     * DETAIL TRANSAKSI
     * =========================
     */
    public function show(Transaction $transaction)
    {
        // 🔒 proteksi user
        if ($transaction->booking->user_id !== Auth::id()) {
            abort(403);
        }

        // ⏱️ timeout 10 menit untuk pending
        $response = $this->handleExpiredTransaction($transaction);

        if ($response) {
            return $response;
        }

        // if (
        //     $transaction->metode === 'midtrans' &&
        //     $transaction->status === 'pending' &&
        //     $transaction->created_at->addMinutes(10)->isPast()
        // ) {
        //     $transaction->update(['status' => 'expired']);
        //     $transaction->booking->update(['status' => 'cancelled']);

        //     return redirect()
        //         ->route('jadwal.lapangan', [
        //             'lapangan_id' => $transaction->booking->lapangan_id,
        //             'tanggal'     => $transaction->booking->tanggal,
        //         ])
        //         ->with('error', 'Waktu pembayaran habis, silakan booking ulang');
        // }

        // ✅ JIKA TRANSAKSI SUDAH CONFIRMED → LANGSUNG KE JADWAL
        if ($transaction->status === 'paid') {
            return $this->redirectToSchedule(
                $transaction,
                'success',
                'Booking berhasil, jadwal telah diperbarui'
            );
        }
        // if ($transaction->status === 'paid') {
        //     return redirect()
        //         ->route('jadwal.lapangan', [
        //             'lapangan_id' => $transaction->booking->lapangan_id,
        //             'tanggal'     => $transaction->booking->tanggal,
        //         ])
        //         ->with('success', 'Booking berhasil, jadwal telah diperbarui');
        // }

        // $snapToken = null;
        $snapToken = $this->generateSnapToken($transaction);

        // if ($transaction->metode === 'midtrans') {

        //     Config::$serverKey = config('midtrans.serverKey');
        //     Config::$isProduction = config('midtrans.isProduction');
        //     Config::$isSanitized = true;
        //     Config::$is3ds = true;

        //     if (!$transaction->snap_token) {

        //         $params = [
        //             'transaction_details' => [
        //                 'order_id' => $transaction->midtrans_order_id,
        //                 'gross_amount' => $transaction->booking->total_harga,
        //             ],

        //             'customer_details' => [
        //                 'first_name' => Auth::user()->name,
        //                 'email' => Auth::user()->email,
        //             ]
        //         ];
      
        //         $snapToken = Snap::getSnapToken($params);

        //         $transaction->update([
        //             'snap_token' => $snapToken,
        //         ]);

        //     } else {

        //         $snapToken = $transaction->snap_token;
        //     }
        // }

        return view('transaction.show', compact(
            'transaction',
            'snapToken'
        ));
    }

    public function callback(Request $request)
    {
        Log::info('=== CALLBACK MASUK ===');

        Config::$serverKey = config('midtrans.serverKey');

        $notification = new Notification();

        $transaction = Transaction::where(
            'midtrans_order_id',
            $notification->order_id
        )->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found'
            ], 404);
        }

        $statusMap = [
            'settlement' => ['paid', 'confirmed'],
            'capture'    => ['paid', 'confirmed'],
            'cancel'     => ['failed', 'cancelled'],
            'deny'       => ['failed', 'cancelled'],
            'expire'     => ['expired', 'cancelled'],
        ];

        if (isset($statusMap[$notification->transaction_status])) {

            [$transactionStatus, $bookingStatus] =
                $statusMap[$notification->transaction_status];

            $transaction->update([
                'status' => $transactionStatus
            ]);

            $transaction->booking->update([
                'status' => $bookingStatus
            ]);
        }

        return response()->json([
            'success' => true
        ]);
    }

    private function handleExpiredTransaction(Transaction $transaction)
    {
        if (
            $transaction->metode !== 'midtrans' ||
            $transaction->status !== 'pending' ||
            !$transaction->created_at->addMinutes(10)->isPast()
        ) {
            return null;
        }

        $transaction->update([
            'status' => 'expired'
        ]);

        $transaction->booking->update([
            'status' => 'cancelled'
        ]);

        return redirect()
            ->route('jadwal.lapangan', [
                'lapangan_id' => $transaction->booking->lapangan_id,
                'tanggal' => $transaction->booking->tanggal,
            ])
            ->with('error', 'Waktu pembayaran habis, silakan booking ulang');
    }

    private function redirectToSchedule(
        Transaction $transaction,
        string $type,
        string $message
    ) {
        return redirect()
            ->route('jadwal.lapangan', [
                'lapangan_id' => $transaction->booking->lapangan_id,
                'tanggal' => $transaction->booking->tanggal,
            ])
            ->with($type, $message);
    }

    private function generateSnapToken(Transaction $transaction)
    {
        if ($transaction->metode !== 'midtrans') {
            return null;
        }

        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        if ($transaction->snap_token) {
            return $transaction->snap_token;
        }

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->midtrans_order_id,
                'gross_amount' => $transaction->booking->total_harga,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        $transaction->update([
            'snap_token' => $snapToken
        ]);

        return $snapToken;
    }
}
