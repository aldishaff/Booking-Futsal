@extends('layouts.app')

@section('title', 'Preview Transaksi')

@push('styles')

@endpush

@section('content')

<section class="transaction-section">

    <div class="transaction-wrapper">

        <h1 class="transaction-title">
            Konfirmasi Booking
        </h1>

        <p class="transaction-subtitle">
            Periksa kembali detail pemesanan Anda
        </p>

        <div class="transaction-card">

            <div class="transaction-info">
                <span class="transaction-label">Lapangan</span>
                <span class="transaction-value">
                    {{ $lapangan->nama }}
                </span>
            </div>

            <div class="transaction-info">
                <span class="transaction-label">Tanggal</span>
                <span class="transaction-value">
                    {{ $data['tanggal'] }}
                </span>
            </div>

            <div class="transaction-info">
                <span class="transaction-label">Jam Main</span>
                <span class="transaction-value">
                    {{ $data['jam_mulai'] }}
                </span>
            </div>

            <div class="transaction-info">
                <span class="transaction-label">Durasi</span>
                <span class="transaction-value">
                    {{ $data['durasi'] }} Jam
                </span>
            </div>

            <div class="transaction-info">
                <span class="transaction-label">Metode</span>

                <span class="method-badge {{ $data['metode'] }}">
                    {{ ucfirst($data['metode']) }}
                </span>
            </div>

            <div class="total-box">

                <div class="total-label">
                    Total Pembayaran
                </div>

                <div class="total-price">
                    Rp {{ number_format($total,0,',','.') }}
                </div>

            </div>

            <form method="POST"
                  action="{{ route('transaction.store') }}">

                @csrf

                @foreach ($data as $key => $value)

                    <input type="hidden"
                           name="{{ $key }}"
                           value="{{ $value }}">

                @endforeach

                <button class="btn-confirm">

                    Konfirmasi & Lanjutkan

                </button>

            </form>

        </div>

    </div>

</section>

@endsection