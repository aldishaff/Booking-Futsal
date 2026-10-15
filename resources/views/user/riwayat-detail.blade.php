@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('page-class', 'page-riwayat-detail')

@section('content')
<div class="container detail-container">

    <a href="{{ route('riwayat.pesanan') }}" class="back-link">
        ← Kembali ke Riwayat Pesanan
    </a>

    <div class="detail-card">

        <div class="detail-header">
            <h3>Detail Pesanan</h3>

            <span class="status {{ $booking->status }}">
                {{ ucfirst($booking->status) }}
            </span>
        </div>

        <div class="detail-section">
            <h4>Informasi Lapangan</h4>
            <p><strong>Nama Lapangan:</strong> {{ $booking->lapangan->nama }}</p>
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}</p>
            <p><strong>Jam Mulai:</strong> {{ $booking->jam_mulai }}</p>
            <p><strong>Durasi:</strong> {{ $booking->durasi }} jam</p>
        </div>

        <div class="detail-section">
            <h4>Informasi Pembayaran</h4>

            <p><strong>Total Harga:</strong>
                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
            </p>

            @if($booking->transaction)
                <p><strong>Metode:</strong> {{ ucfirst($booking->transaction->metode) }}</p>
                <p><strong>Status Pembayaran:</strong>
                    {{ ucfirst($booking->transaction->status) }}
                </p>

                @if($booking->transaction->bukti_transfer)
                    <p>
                        <strong>Bukti Transfer:</strong><br>
                        <a href="{{ asset('storage/'.$booking->transaction->bukti_transfer) }}"
                           target="_blank">
                           Lihat Bukti
                        </a>
                    </p>
                @endif
            @else
                <p>Status pembayaran belum tersedia.</p>
            @endif
        </div>

        @if($booking->catatan)
            <div class="detail-section">
                <h4>Catatan</h4>
                <p>{{ $booking->catatan }}</p>
            </div>
        @endif

        <div class="detail-section total">
            <p>
                <strong>Total Harga</strong>
                Rp {{ number_format($booking->total_harga,0,',','.') }}
            </p>
        </div>


    </div>
</div>
@endsection
