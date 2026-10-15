@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('page-class', 'page-riwayat')

@section('content')
<div class="container riwayat-container">

    <div class="page-header">
        <h2>Riwayat Pesanan</h2>
        <p>Lihat semua histori pemesanan lapangan futsal Anda</p>
    </div>

    {{-- Filter --}}
    <form method="GET" class="filter-card">
        <select name="status">
            <option value="">Semua Status</option>
            <option value="pending">Menunggu Pembayaran</option>
            <option value="confirmed">Berhasil</option>
            <option value="cancelled">Dibatalkan</option>
        </select>

        <input type="date" name="from">
        <input type="date" name="to">

        <button class="btn-primary">Filter</button>
        <a href="{{ route('riwayat.pesanan') }}" class="btn-reset">Reset</a>
    </form>

    {{-- List --}}
    @forelse ($bookings as $booking)
        <div class="booking-card">

            <div class="booking-header">
                <h4>{{ $booking->lapangan->nama }}</h4>

                <span class="status {{ $booking->status }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>

            <div class="booking-body">
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}</p>
                <p><strong>Jam:</strong> {{ $booking->jam_mulai }} ({{ $booking->durasi }} jam)</p>
                <p><strong>Total:</strong> Rp {{ number_format($booking->total_harga,0,',','.') }}</p>
            </div>

            <div class="booking-footer">
                <a href="{{ route('riwayat.pesanan.detail', $booking->id) }}"
                class="btn-outline">
                Detail
                </a>

                @if($booking->transaction)
                    <a href="{{ route('riwayat.transaksi') }}" class="btn-primary">Lihat Transaksi</a>
                @endif
            </div>

        </div>
    @empty
        <div class="empty-state">
            <p>Anda belum memiliki riwayat pesanan.</p>
            <a href="{{ url('/') }}" class="btn-primary">Pesan Lapangan</a>
        </div>
    @endforelse

    <div class="pagination-wrapper">
        <div class="simple-pagination">
        <span class="info">
            Showing {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} of {{ $bookings->total() }}
        </span>

        {{ $bookings->onEachSide(1)->links() }}
    </div>

</div>
@endsection
