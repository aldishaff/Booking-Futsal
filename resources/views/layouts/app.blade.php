<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Viva Arena')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/booking.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/jadwal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/riwayat-pesanan.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/riwayat-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profil.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/transaction-preview.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/transaction-show.css') }}">

</head>
<body class="@yield('page-class')">

    {{-- NAVBAR --}}
    @include('partials.navbar')

    {{-- CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.about-card .info-slider img');
            let current = 0;

            setInterval(() => {
                slides[current].classList.remove('opacity-100');
                slides[current].classList.add('opacity-0');

                current = (current + 1) % slides.length;

                slides[current].classList.remove('opacity-0');
                slides[current].classList.add('opacity-100');
            }, 3000); // ganti gambar tiap 3 detik
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const backToTop = document.querySelector('.back-to-top');

            if (!backToTop) return;

            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    backToTop.classList.add('show');
                } else {
                    backToTop.classList.remove('show');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                item.addEventListener('click', () => {
                    faqItems.forEach(i => {
                        if (i !== item) i.classList.remove('active');
                    });
                    item.classList.toggle('active');
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const btn = document.getElementById("userDropdownBtn");
            const menu = document.getElementById("userDropdownMenu");

            btn.addEventListener("click", function (e) {
                e.stopPropagation();
                menu.style.display = menu.style.display === "flex" ? "none" : "flex";
            });

            document.addEventListener("click", function () {
                menu.style.display = "none";
            });
        });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const durasiSelect = document.getElementById("durasi");
        const hargaPerJam = document.getElementById("hargaPerJam").value;

        const durasiText = document.getElementById("durasiText");
        const totalHarga = document.getElementById("totalHarga");

        function formatRupiah(number) {
            return "Rp " + number.toLocaleString("id-ID");
        }

        durasiSelect.addEventListener("change", function () {
            const durasi = parseInt(this.value);
            const total = durasi * hargaPerJam;

            durasiText.textContent = durasi + " Jam";
            totalHarga.textContent = formatRupiah(total);
        });
    });
    </script>


    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
    document.getElementById('cekKetersediaan').addEventListener('click', function () {

        const lapangan = document.getElementById('lapangan_id').value;
        const tanggal  = document.getElementById('tanggal').value;
        const jam      = document.getElementById('jam').value;

        if (!lapangan || !tanggal || !jam) {
            alert('Lengkapi lapangan, tanggal, dan jam');
            return;
        }

        fetch("{{ route('jadwal.cek') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },
            body: JSON.stringify({
                lapangan_id: lapangan,
                tanggal: tanggal,
                jam: jam
            })
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP error ' + res.status);
            return res.json();
        })
        .then(data => {
        if (data.status === 'tersedia') {
            Swal.fire({
                icon: 'success',
                title: 'Tersedia',
                text: data.message,
                confirmButtonText: 'OK'
            });
        } 
        else if (data.status === 'dibooking') {
            Swal.fire({
                icon: 'error',
                title: 'Sudah Dibooking',
                text: data.message,
                confirmButtonText: 'OK'
            });
        } 
        else if (data.status === 'tutup') {
            Swal.fire({
                icon: 'warning',
                title: 'Lapangan Tutup',
                text: data.message,
                confirmButtonText: 'OK'
            });
        }
    })

        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan, coba lagi');
        });
    });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11">

    </script>

    <script>
    setTimeout(() => {
        const toast = document.getElementById('toastSuccess');
        if (toast) toast.remove();
    }, 3500);
    </script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @if(request('payment') == 'success')
    <script>

    Swal.fire({
        icon: 'success',
        title: 'Pembayaran Berhasil',
        text: 'Booking berhasil dikonfirmasi',
        timer: 2500,
        showConfirmButton: false
    });

    </script>
    @endif


</body>
</html>
