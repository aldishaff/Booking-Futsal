@extends('layouts.app')

@section('title', 'Jadwal Lapangan')

@section('content')
<section class="jadwal-section">

    <h2 class="jadwal-title">Jadwal Lapangan</h2>

    <!-- FILTER -->
    <form method="GET" class="jadwal-filter">
        <!-- PILIH LAPANGAN -->
        <select name="lapangan_id" onchange="this.form.submit()">
            @foreach ($lapangans as $lap)
                <option value="{{ $lap->id }}"
                    {{ $lap->id == $lapangan->id ? 'selected' : '' }}>
                    {{ $lap->nama }}
                </option>
            @endforeach
        </select>

        <!-- PILIH TANGGAL -->
        <input
            type="date"
            name="tanggal"
            value="{{ $baseDate->toDateString() }}"
            onchange="this.form.submit()"
        >

        <div class="date-range">
            {{ $startDate->translatedFormat('d M Y') }}
            –
            {{ $endDate->translatedFormat('d M Y') }}
        </div>

    </form>

    <!-- TABLE -->
    <div class="jadwal-wrapper">
        <table class="jadwal-table">
            <thead>
                <tr>
                    <th>Jam</th>
                    @for ($i = 0; $i < 7; $i++)
                        <th>
                            {{ $startDate->copy()->addDays($i)->translatedFormat('D') }}<br>
                            <span>{{ $startDate->copy()->addDays($i)->translatedFormat('d M Y') }}</span>
                        </th>
                    @endfor
                </tr>
            </thead>

            <tbody>
                @for ($jam = 0; $jam <= 23; $jam++)
                <tr>
                    <td class="jam">{{ sprintf('%02d.00', $jam) }}</td>

                    @for ($i = 0; $i < 7; $i++)
                       @php
                            $tanggal = $startDate->copy()->addDays($i)->toDateString();

                            $jamDb = sprintf('%02d:00:00', $jam);

                            $slotMulai = \Carbon\Carbon::parse($tanggal.' '.$jamDb);
                            $slotSelesai = $slotMulai->copy()->addHour();

                            $now = \Carbon\Carbon::now();

                            // DEFAULT
                            $statusSlot = 'tersedia';

                            // =========================
                            // 1️⃣ ADMIN TUTUP (PRIORITAS TERTINGGI)
                            // =========================
                            if (
                                isset($adminTutup[$tanggal.' '.$jamDb]) &&
                                $adminTutup[$tanggal.' '.$jamDb]->first()->status === 'tutup'
                            ) {
                                $statusSlot = 'tutup';
                            }

                            // =========================
                            // 2️⃣ SELESAI (WAKTU)
                            // =========================
                            elseif ($slotSelesai <= $now) {
                                $statusSlot = 'selesai';
                            }

                            // =========================
                            // 3️⃣ BOOKING USER
                            // =========================
                            else {
                                foreach ($bookings as $b) {

                                    if ($b->tanggal !== $tanggal) {
                                        continue;
                                    }

                                    $bookingMulai = \Carbon\Carbon::parse(
                                        $b->tanggal.' '.$b->jam_mulai
                                    );

                                    $bookingSelesai = $bookingMulai
                                        ->copy()
                                        ->addMinutes((int) $b->durasi);

                                    if (
                                        $slotMulai >= $bookingMulai &&
                                        $slotMulai < $bookingSelesai
                                    ) {
                                        $statusSlot = 'dibooking';
                                        break;
                                    }
                                }
                            }
                        @endphp

                  
                        <td>
                            @if ($statusSlot === 'dibooking')
                                <span class="slot booked">Dibooking</span>

                            @elseif ($statusSlot === 'selesai')
                                <span class="slot finished">Selesai</span>

                            @elseif ($statusSlot === 'tutup')
                                <span class="slot closed">Tutup</span>

                            @else
                                <a
                                    href="{{ route('booking.create', $lapangan->id) }}
                                        ?tanggal={{ $tanggal }}
                                        &jam={{ sprintf('%02d:00', $jam) }}"
                                    class="slot available"
                                >
                                    Tersedia
                                </a>
                            @endif
                        </td>

                    @endfor
                </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="jadwal-footer">
        <div class="legend">
            <span><i class="dot green"></i> Tersedia</span>
            <span><i class="dot red"></i> Sudah Dibooking</span>
            <span><i class="dot dark"></i>Selesai</span>
            <span><i class="dot gray"></i> Tutup</span>
        </div>

        <a href="{{ route('home') }}#lapangan" class="btn-booking">Booking Sekarang</a>
    </div>

</section>
@endsection
