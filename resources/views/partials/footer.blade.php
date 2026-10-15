<footer class="footer">
    <div class="footer-content">
        <div class="footer-brand">
            <i class="fa-solid fa-futbol"></i>
            <span>Viva Arena</span>
            <p>Viva Arena Malang — Sistem Booking Lapangan Futsal Online</p>
            <p>Booking Lapangan Futsal Mudah & Cepat | Viva Arena Malang</p>
        </div>

        <div class="footer-links">
            <h4>Navigasi</h4>
            <a href="{{ route('home') }}#beranda">Beranda</a>
            <a href="{{ route('home') }}#lapangan">Lapangan</a>
            <a href="{{ route('home') }}#tentang">Tentang Kami</a>
            <a href="{{ route('home') }}#kontak">Kontak</a>
        </div>

        <div class="footer-contact">
            <h4>Kontak</h4>

            <!-- Telepon -->
            <p>
                <i class="fa-solid fa-phone"></i>
                Admin  : 0811-3771-772
            </p>

            <!-- Email -->
            <p>
                <i class="fa-solid fa-envelope"></i>
                info@vivafutsal.com
            </p>

            <!-- Sosial Media -->
            <div class="footer-social">
                <a href="https://www.instagram.com/viva.arena/" target="_blank" aria-label="Instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>

                <a href="https://wa.me/628113771772" target="_blank" aria-label="WhatsApp">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            </div>
        </div>

    </div>

    <small>
        © {{ date('Y') }} Viva Arena. All rights reserved.
    </small>
</footer>
