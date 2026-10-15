@extends('admin.admin-layouts.app')

@section('title','Data Pemesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/booking.css') }}">
@endpush

@section('content')

<div class="page-header">
    <h1>Data Pemesanan</h1>
    <p>Dashboard / Pemesanan</p>
</div>

<div class="card">

    {{-- FILTER --}}
    <form method="GET" class="jadwal-filter">
        <input type="date" name="tanggal" value="{{ request('tanggal') }}">

        <select name="status">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="confirmed" {{ request('status')=='confirmed'?'selected':'' }}>Dikonfirmasi</option>
            <!-- <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option> -->
            <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Dibatalkan</option>
        </select>

        <button class="btn-primary">Filter</button>
    </form>

    {{-- TABLE --}}
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Lapangan</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td>{{ $b->user->username }}</td>
                    <td>{{ $b->lapangan->nama }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }}</td>
                    <td>{{ substr($b->jam_mulai,0,5) }}</td>
                    <td>{{ $b->durasi }} jam</td>
                    <td>
                        <span class="badge {{ $b->status }}">
                            {{ ucfirst($b->status) }}
                        </span>
                    </td>
                    <td class="aksi">
                        <a href="{{ route('admin.booking.show',$b) }}" class="btn-edit">
                            <i class="fa fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center">Belum ada pemesanan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        {{ $bookings->links('vendor.pagination.admin') }}
    </div>

</div>

@endsection
