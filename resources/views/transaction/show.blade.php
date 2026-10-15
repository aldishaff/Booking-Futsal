@extends('layouts.app')

@section('title', 'Transaksi')

@push('styles')

@endpush

@section('content')

<section class="transaction-section">

    <div class="transaction-wrapper">

        <div class="transaction-header">

            <h1>Detail Transaksi</h1>

            <p>
                Informasi pembayaran dan booking Anda
            </p>

        </div>

        <div class="transaction-card">

            {{-- STATUS --}}
            @if ($transaction->metode === 'cash')

                @if($transaction->status === 'pending')

                    <div class="status-box pending">

                        <div class="status-title">
                            Menunggu Konfirmasi Admin
                        </div>

                        <div class="status-text">
                            Silakan lakukan pembayaran langsung kepada admin saat datang ke lokasi.
                        </div>

                    </div>

                @elseif($transaction->status === 'paid')

                    <div class="status-box success">

                        <div class="status-title">
                            Pembayaran Dikonfirmasi
                        </div>

                        <div class="status-text">
                            Booking Anda telah berhasil dikonfirmasi.
                        </div>

                    </div>

                @elseif($transaction->status === 'failed')

                    <div class="status-box failed">

                        <div class="status-title">
                            Pembayaran Dibatalkan
                        </div>

                        <div class="status-text">
                            Transaksi dibatalkan oleh admin.
                        </div>

                    </div>

                @endif

            @else

                <div class="status-box pending">

                    <div class="status-title">
                        Menunggu Pembayaran
                    </div>

                    <div class="status-text">
                        Silakan lanjutkan pembayaran online.
                    </div>

                </div>

            @endif

            {{-- DETAIL --}}
            <div class="transaction-info">
                <span class="transaction-label">Lapangan</span>
                <span class="transaction-value">
                    {{ $transaction->booking->lapangan->nama }}
                </span>
            </div>

            <div class="transaction-info">
                <span class="transaction-label">Tanggal</span>
                <span class="transaction-value">
                    {{ $transaction->booking->tanggal }}
                </span>
            </div>

            <div class="transaction-info">
                <span class="transaction-label">Jam Main</span>
                <span class="transaction-value">
                    {{ $transaction->booking->jam_mulai }}
                </span>
            </div>

            <div class="transaction-info">
                <span class="transaction-label">Metode</span>
                <span class="transaction-value">
                    {{ ucfirst($transaction->metode) }}
                </span>
            </div>

            <div class="total-box">

                <div class="total-label">
                    Total Pembayaran
                </div>

                <div class="total-price">
                    Rp {{ number_format(
                        $transaction->booking->total_harga,
                        0,
                        ',',
                        '.'
                    ) }}
                </div>

            </div>

            {{-- BUTTON MIDTRANS --}}
            @if($transaction->metode === 'midtrans'
                && $transaction->status === 'pending')

                <button id="pay-button"
                        class="btn-pay">

                    Bayar Sekarang

                </button>

            @endif

        </div>

    </div>

</section>

@if($transaction->metode === 'midtrans')

<script
src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ config('midtrans.clientKey') }}">
</script>

<script>

document.getElementById('pay-button')?.addEventListener(
    'click',
    function () {

        snap.pay('{{ $snapToken }}', {

            onSuccess: function(result) {

                window.location.href =
                "{{ route('home') }}?payment=success";

            },

            onPending: function(result) {

                window.location.reload();

            },

            onError: function(result) {

                alert('Pembayaran gagal');

            }

        });
    }
);

</script>

@endif

@endsection