@extends('admin.admin-layouts.app')

@push('styles')

@endpush

@section('title', 'Detail Pengguna')

@section('content')

<div class="container py-4 user-show-page">

    {{-- HEADER --}}
    <div class="show-header">

        <h2 class="page-title">
            Detail Pengguna
        </h2>

        <a href="{{ route('admin.users.index') }}"
           class="btn-back">

            <i class="fa fa-arrow-left"></i>
            Kembali

        </a>

    </div>

    {{-- TOP SECTION --}}
    <div class="top-section">

        {{-- PROFILE --}}
        <div class="profile-card">

            <div class="profile-wrapper">

                <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('img/avatar.png') }}"
                     class="profile-avatar">

                <div class="profile-info">

                    <h4>
                        {{ $user->username }}
                    </h4>

                    <p>
                        <i class="fa fa-envelope"></i>
                        {{ $user->email }}
                    </p>

                    <p>
                        <i class="fa fa-phone"></i>
                        {{ $user->no_telepon }}
                    </p>

                    <p>
                        <i class="fa fa-calendar"></i>
                        Bergabung:
                        {{ $user->created_at->format('d M Y') }}
                    </p>

                </div>

            </div>

        </div>

        {{-- STATISTICS --}}
        <div class="stat-grid">

            <div class="stat-card">

                <h3>
                    {{ $totalBooking }}
                </h3>

                <p>
                    Total Booking
                </p>

            </div>

            <div class="stat-card">

                <h3>
                    {{ $bookingSelesai }}
                </h3>

                <p>
                    Booking Selesai
                </p>

            </div>

            <div class="stat-card">

                <h3>
                    {{ $bookingPending }}
                </h3>

                <p>
                    Booking Pending
                </p>

            </div>

            <div class="stat-card">

                <h3>
                    {{ $transaksiBerhasil }}
                </h3>

                <p>
                    Transaksi Berhasil
                </p>

            </div>

        </div>

    </div>

    {{-- HISTORY --}}
    <div class="history-card">

        <div class="history-header">

            <h5>
                Riwayat Booking
            </h5>

        </div>

        <div class="table-responsive">

            <table class="history-table">

                <thead>

                    <tr>
                        <th>Lapangan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Status Booking</th>
                        <th>Total Harga</th>
                        <th>Status Pembayaran</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($user->bookings as $booking)

                        <tr>

                            <td>
                                {{ $booking->lapangan->nama ?? '-' }}
                            </td>

                            <td>
                                {{ $booking->tanggal }}
                            </td>

                            <td>
                                {{ $booking->jam_mulai }}
                            </td>

                            <td>

                                <span class="
                                    status-badge

                                    @if($booking->status == 'selesai' || $booking->status == 'confirmed')
                                        status-success
                                    @elseif($booking->status == 'pending')
                                        status-warning
                                    @else
                                        status-danger
                                    @endif
                                ">

                                    {{ ucfirst($booking->status) }}

                                </span>

                            </td>

                            <td>
                                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                            </td>

                            <td>

                                @if($booking->transaction)

                                    <span class="
                                        status-badge

                                        @if($booking->transaction->status == 'paid')
                                            status-success
                                        @elseif($booking->transaction->status == 'pending')
                                            status-warning
                                        @else
                                            status-danger
                                        @endif
                                    ">

                                        {{ ucfirst($booking->transaction->status) }}

                                    </span>

                                @else

                                    <span class="status-badge status-danger">
                                        Tidak Ada
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="empty-data">

                                Pengguna belum pernah melakukan booking

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection