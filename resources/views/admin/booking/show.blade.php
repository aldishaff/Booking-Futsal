@extends('admin.admin-layouts.app')

@section('title','Detail Pemesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/booking-detail.css') }}">
@endpush

@section('content')

<div class="page-header">
    <h1>Detail Pemesanan</h1>
    <p>Dashboard / Pemesanan / Detail</p>
</div>

<div class="card">

    <table class="detail-table">
        <tr><th>Pengguna</th><td>{{ $booking->user->username }}</td></tr>
        <tr><th>Lapangan</th><td>{{ $booking->lapangan->nama }}</td></tr>
        <tr><th>Tanggal</th><td>{{ $booking->tanggal }}</td></tr>
        <tr><th>Jam Mulai</th><td>{{ $booking->jam_mulai }}</td></tr>
        <tr><th>Durasi</th><td>{{ $booking->durasi }} jam</td></tr>
        <tr><th>Total Harga</th><td>Rp {{ number_format($booking->total_harga,0,',','.') }}</td></tr>
        <tr><th>Status</th>
            <td>
                <span class="badge {{ $booking->status }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </td>
        </tr>
    </table>

    <form method="POST" action="{{ route('admin.booking.updateStatus',$booking) }}" class="form-action">
        @csrf

        <select name="status">
            <option value="completed">Selesai</option>
            <option value="cancelled">Dibatalkan</option>
        </select>

        <button class="btn-primary">
            <i class="fa fa-save"></i> Update Status
        </button>
    </form>

</div>

@endsection
