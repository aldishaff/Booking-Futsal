@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container riwayat-container">

    <div class="page-header">
        <h2>Riwayat Transaksi</h2>
        <p>Histori pembayaran pemesanan lapangan futsal Anda</p>
    </div>

    @forelse ($transactions as $trx)
        <div class="booking-card">

            <div class="booking-header">
                <h4>{{ $trx->booking->lapangan->nama }}</h4>

                <span class="status {{ $trx->status }}">
                    {{ ucfirst($trx->status) }}
                </span>
            </div>

            <div class="booking-body">
                <p><strong>Tanggal:</strong>
                    {{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y') }}
                </p>
                <p><strong>Metode:</strong> {{ ucfirst($trx->metode) }}</p>
                <p><strong>Total:</strong>
                    Rp {{ number_format($trx->booking->total_harga,0,',','.') }}
                </p>
            </div>

            <div class="booking-footer">
                <a href="{{ route('riwayat.pesanan.detail', $trx->booking->id) }}"
                   class="btn-outline">
                   Detail Pesanan
                </a>

                @if($trx->bukti_transfer)
                    <a href="{{ asset('storage/'.$trx->bukti_transfer) }}"
                       target="_blank"
                       class="btn-primary">
                       Bukti Transfer
                    </a>
                @endif
            </div>

        </div>
    @empty
        <div class="empty-state">
            <p>Belum ada riwayat transaksi.</p>
        </div>
    @endforelse

    {{-- SIMPLE PAGINATION --}}
    <div class="simple-pagination">
        <span class="info">
            Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }}
            of {{ $transactions->total() }}
        </span>

        {{ $transactions->onEachSide(1)->links() }}
    </div>

</div>
@endsection
