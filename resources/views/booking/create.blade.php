@extends('layouts.app')

@section('title', 'Booking Lapangan')

@section('content')
<section class="booking-section">
    <h2>Booking Lapangan</h2>

    <div class="booking-card">

        <!-- KIRI : NOMINAL LAPANGAN -->
        <div class="booking-left">
            <h4>Nominal Lapangan</h4>

            <div class="lapangan-preview">
                <img src="{{ asset('storage/' . $lapangan->gambar) }}">
                <h5>{{ $lapangan->nama }}</h5>
                <p>Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }} per jam</p>
            </div>
        </div>

        <!-- TENGAH : FORM -->
        <div class="booking-middle">
            <form id="bookingForm" method="POST" action="{{ route('transaction.preview') }}">
                @csrf

                <input type="hidden" id="hargaPerJam" value="{{ $lapangan->harga_per_jam }}">

                <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">

                <label>Lapangan</label>
                <input type="text" value="{{ $lapangan->nama }}" disabled>

                <label>Tanggal Booking</label>
                <input type="date" name="tanggal" value="{{ $tanggal ?? now()->toDateString() }}" required>

                <div class="booking-row">
                    <div>
                        <label>Jam Mulai</label>

                        @if ($fromJadwal)
                            {{-- Datang dari jadwal → jam terkunci --}}
                            <input type="time" name="jam_mulai" value="{{ $jam }}" readonly>
                        @else
                            {{-- Booking manual → hanya jam tersedia --}}
                            <select name="jam_mulai" required>
                                @foreach ($availableHours as $hour)
                                    <option value="{{ $hour }}">{{ $hour }}</option>
                                @endforeach
                            </select>
                        @endif

                    </div>
                    <div>
                        <label>Durasi Bermain</label>
                        <select name="durasi" id="durasi">
                            <option value="1">1 Jam</option>
                            <option value="2">2 Jam</option>
                            <option value="3">3 Jam</option>
                        </select>
                    </div>
                </div>

                <label>Metode Pembayaran</label>

                <select name="metode" required>

                    <option value="cash">
                        Bayar di Tempat
                    </option>

                    <option value="midtrans">
                        Online Payment (QRIS / E-Wallet / VA)
                    </option>

                </select>


                <label>Catatan (Opsional)</label>
                <textarea name="catatan" placeholder="Masukkan catatan tambahan..."></textarea>
            </form>
        </div>

        <!-- KANAN : RINGKASAN -->
        <div class="booking-right">
            <h4>Ringkasan Pemesanan</h4>

            <ul>
                <li><span>Lapangan</span><span>{{ $lapangan->nama }}</span></li>
                <li><span>Harga</span><span>Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}/jam</span></li>
                <li>
                    <span>Durasi</span>
                    <span id="durasiText">1 Jam</span>
                </li>

                <li class="total">
                    <span>Total Harga</span>
                    <span id="totalHarga">
                        Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}
                    </span>
                </li>
            </ul>

            <button class="btn-primary" type="submit" form="bookingForm">
                Pesan Sekarang
            </button>
        </div>

    </div>
</section>
@endsection
