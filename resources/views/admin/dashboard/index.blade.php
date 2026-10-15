@extends('admin.admin-layouts.app')

@section('title','Dashboard Admin')

@section('content')

<div class="dashboard-header">
    <h1>Dashboard Admin</h1>
    <p>Aplikasi Booking Lapangan Futsal – Viva Arena</p>
</div>

{{-- STAT --}}
<div class="stat-grid">
    <div class="stat-card blue">
        <i class="fa fa-futbol"></i>
        <div>
            <span>Total Lapangan</span>
            <h3>{{ $totalLapangan }}</h3>
        </div>
    </div>

    <div class="stat-card orange">
        <i class="fa fa-calendar-day"></i>
        <div>
            <span>Total Pemesanan Hari Ini</span>
            <h3>{{ $bookingHariIni }}</h3>
        </div>
    </div>

    <div class="stat-card green">
        <i class="fa fa-check-circle"></i>
        <div>
            <span>Pemesanan Aktif</span>
            <h3>{{ $bookingAktif }}</h3>
        </div>
    </div>

    <div class="stat-card dark">
        <i class="fa fa-flag-checkered"></i>
        <div>
            <span>Pemesanan Selesai</span>
            <h3>{{ $bookingSelesai }}</h3>
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="dashboard-grid">

    {{-- TABLE --}}
    <div class="card">
        <h4>Pemesanan Terbaru</h4>

        <table class="table">
            <thead>
                <tr>
                    <th>Nama Pengguna</th>
                    <th>Lapangan</th>
                    <th>Tanggal & Jam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookingTerbaru as $b)
                <tr>
                    <td>{{ $b->user->username }}</td>
                    <td>{{ $b->lapangan->nama }}</td>
                    <td>{{ $b->tanggal }} | {{ $b->jam_mulai }}</td>
                    <td>
                        <span class="badge {{ $b->status }}">
                            {{ ucfirst($b->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center;color:#6b7280">
                        Belum ada pemesanan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="card-footer right">
            <a href="{{ route('admin.booking.index') }}" class="btn">Lihat Semua</a>
        </div>
    </div>

    {{-- JADWAL HARI INI (REALTIME) --}}
    <div class="card">
        <h4>Jadwal Hari Ini</h4>

        <ul class="schedule">
            @php
                $startJam = max(0, now()->hour - 2);
                $endJam   = min(23, now()->hour + 4);
            @endphp

            @for($jam = $startJam; $jam <= $endJam; $jam++)
                @php
                    $slotMulai   = today()->setHour($jam)->setMinute(0);
                    $slotSelesai = $slotMulai->copy()->addHour();

                    $status = 'tersedia';

                  

                    // 2️⃣ TUTUP ADMIN
                    foreach ($jadwalTutupHariIni as $tutup) {
                        $mulai = \Carbon\Carbon::parse($tutup->tanggal.' '.$tutup->jam_mulai);
                        $selesai = $mulai->copy()->addHour();

                        if ($slotMulai >= $mulai && $slotMulai < $selesai) {
                            $status = 'tutup';
                            break;
                        }
                    }

                    // 3️⃣ DIBOOKING
                    if ($status === 'tersedia') {
                        foreach ($jadwalHariIni as $b) {
                            $mulai = \Carbon\Carbon::parse($b->tanggal.' '.$b->jam_mulai);
                            $selesai = $mulai->copy()->addMinutes((int)$b->durasi);

                            if ($slotMulai < $selesai && $slotSelesai > $mulai) {
                                $status = 'dibooking';
                                break;
                            }
                        }
                    }
                @endphp

                <li>
                    <span>{{ sprintf('%02d:00', $jam) }}</span>
                    <span class="badge {{ $status }}">
                        {{ ucfirst($status) }}
                    </span>
                </li>
            @endfor
        </ul>

        <div class="card-footer right">
            <a href="{{ route('admin.jadwal.index') }}" class="btn">
                Lihat Jadwal Lengkap
            </a>
        </div>
    </div>


</div>

@endsection
