<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class AdminTransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with([
            'booking.user',
            'booking.lapangan'
        ])
        ->where('metode', 'cash')
        ->where('status', 'pending')
        ->latest()
        ->paginate(10);

        return view(
            'admin.transaction.index',
            compact('transactions')
        );
    }

    public function konfirmasi(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'paid'
        ]);

        $transaction->booking->update([
            'status' => 'confirmed'
        ]);

        return back()->with(
            'success',
            'Pembayaran berhasil dikonfirmasi'
        );
    }

    public function batalkan(Transaction $transaction)
    {
        $transaction->update([
            'status' => 'failed'
        ]);

        $transaction->booking->update([
            'status' => 'cancelled'
        ]);

        return back()->with(
            'success',
            'Pembayaran dibatalkan'
        );
    }
}