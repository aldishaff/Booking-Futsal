@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- HERO -->
<section id="beranda" class="hero">
    <h1>Selamat Datang di<br>Viva Arena Malang</h1>
    <p>Pesan lapangan futsal dengan mudah dan cepat</p>
    <a href="#lapangan" class="btn-primary">Pesan Sekarang</a>
</section>

<!-- PILIHAN LAPANGAN -->
<section id="lapangan" class="section">
    <h2 class="section-title">Pilihan Lapangan</h2>

    <div class="lapangan-grid">
    @foreach ($lapangans as $lapangan)
        <div class="lapangan-card">
            <img src="{{ asset('storage/'.$lapangan->gambar) }}" alt="Lapangan">

            <div class="lapangan-body">
                <h4>{{ $lapangan->nama }}</h4>

                <span>
                    <i class="fa-solid fa-circle-check"></i>
                    Lantai {{ $lapangan->jenis_lantai }}
                </span><br>

                <span>
                    <i class="fa-solid fa-circle-check"></i>
                    Ukuran: {{ $lapangan->ukuran }}
                </span><br><br>

                <span style="font-weight:600; color:#1b7a3c;">
                    Rp {{ number_format($lapangan->harga_per_jam, 0, ',', '.') }} / jam
                </span>

                <a href="{{ route('booking.create', $lapangan->id) }}">
                    <button>Pesan Sekarang</button>
                </a>
            </div>
        </div>
    @endforeach
    </div>

</section>

<!-- INFO -->
<section id="tentang" class="section info-grid">
    <!-- Tentang Kami -->
    <div class="info-card about-card flex">
        <!-- Slider Kiri -->
        <div class="info-slider w-1/2 h-full relative">
            <img src="{{ asset('assets/images/lap-futsal.png') }}" class="opacity-100">
            <img src="{{ asset('assets/images/futsalLogin.jpg') }}" class="opacity-0">
            <img src="{{ asset('assets/images/bgUser.png') }}" class="opacity-0">
        </div>

        <!-- Teks Kanan -->
        <div class="info-text w-1/2 p-4">
            <h3>Tentang Kami</h3>
            <p>
                Viva Arena Malang menyediakan layanan penyewaan lapangan futsal dengan fasilitas yang nyaman. 
                Melalui website ini, pengguna dapat melihat jadwal lapangan dan melakukan booking secara online dengan mudah.
            </p>
        </div>
    </div>

    <!-- Jadwal Lapangan (tetap sama) -->
    <div class="info-card schedule-card">
        <div class="schedule-header">
            <h3>Jadwal Lapangan</h3>

            <!-- Arrow ke kanan -->
            <a href="{{ route('jadwal.lapangan', [
                'lapangan_id' => $lapangans->first()->id,
                'tanggal'     => now()->toDateString(),
            ]) }}"
            class="schedule-arrow"
            aria-label="Lihat jadwal">
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <select id="lapangan_id" name="lapangan_id" required>
            <option value="">Pilih Lapangan</option>

            @foreach ($lapangans as $lapangan)
                <option value="{{ $lapangan->id }}">
                    {{ $lapangan->nama }}
                </option>
            @endforeach
        </select>

        <input type="date" id="tanggal" name="tanggal">

        <input type="time" id="jam" name="jam">
        <button type="button" id="cekKetersediaan">
            Cek Ketersediaan
        </button>
    </div>

</section>

<section  id="kontak" class="contact-section">
    <div class="contact-box">
        <h2>Hubungi Kami</h2>

        <div class="contact-container">
            <!-- KIRI -->
            <div class="contact-left">
                <div class="contact-row">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>Jl. Bunga Andong, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141</span>
                </div>

                <div class="contact-row">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Senin - Minggu</span>
                    <strong>06.00 - 23.00 WIB</strong>
                </div>
            </div>

            <!-- GARIS -->
            <div class="contact-divider"></div>

            <!-- KANAN -->
            <div class="contact-right">
                <div class="contact-row">
                    <i class="fa-solid fa-phone"></i>
                    <span>Telp : 0341-484377   &  WA : 08113771772</span>
                </div>

                <div class="contact-row">
                    <i class="fa-solid fa-envelope"></i>
                    <span>@vivafutsal@gmail.com</span>
                </div>

                <div class="contact-row">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Senin - Minggu</span>
                    <strong>07.00 - 21.00 WIB</strong>
                </div>
            </div>
        </div>
    </div>
</section>



<section class="faq-section">
    <h2>Pertanyaan Umum</h2>

    <div class="faq-container">
        <div class="faq-item">
            <div class="faq-question">
                <h4>Bagaimana cara booking lapangan?</h4>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Silakan login atau register, lalu pilih lapangan dan waktu yang tersedia.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <h4>Apakah bisa membatalkan pesanan?</h4>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Bisa, selama status pemesanan masih pending dan sesuai kebijakan.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                <h4>Metode pembayaran apa saja yang tersedia?</h4>
                <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <p>Pembayaran dapat dilakukan secara transfer atau langsung di lokasi.</p>
            </div>
        </div>
    </div>
</section>


<!-- MAP -->
<section class="map-section">
    <h2>Lokasi Kami</h2>

    <div class="map-container">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.489195757017!2d112.61769799999999!3d-7.948292!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7882766622f811%3A0x6409486a80faed58!2sVIVA%20ARENA!5e0!3m2!1sid!2sid!4v1780308832366!5m2!1sid!2sid"
            width="100%"
            height="350"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</section>

@if (session('success'))
<div class="toast toast-success" id="toastSuccess">
    {{ session('success') }}
</div>
@endif

<!-- Back to Top -->
<a href="#beranda" class="back-to-top" aria-label="Kembali ke atas">
    <i class="fa-solid fa-arrow-up"></i>
</a>


@endsection
