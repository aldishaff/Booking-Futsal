<header class="navbar">
    <div class="nav-left">
        <img src="{{ asset('assets/images/viva.png') }}" alt="Logo">
        <span class="brand-name">Viva Arena</span>
    </div>

    <nav class="nav-menu">
        <a href="{{ route('home') }}#beranda">Beranda</a>
        <a href="{{ route('home') }}#lapangan">Lapangan</a>
        <a href="{{ route('home') }}#tentang">Tentang Kami</a>
        <a href="{{ route('home') }}#kontak">Kontak</a>

    </nav>

    <div class="nav-right">
        @auth
            <div class="user-dropdown">
                <div class="user-info" id="userDropdownBtn">
                    <img
                        src="{{ Auth::user()->photo
                            ? asset('storage/'.Auth::user()->photo)
                            : asset('img/default-avatar.png') }}"
                        alt="Foto Profil"
                        class="user-avatar"
                    >

                    <span>Halo, {{ Auth::user()->username }}</span>

                    <i class="fa-solid fa-chevron-down"></i>
                </div>

                <div class="dropdown-menu" id="userDropdownMenu">
                    <a href="{{ route('profil') }}">
                        <i class="fa-solid fa-user"></i>
                        Profil
                    </a>
                    <a href="{{ route('riwayat.pesanan') }}">Riwayat Pesanan</a>
                    <a href="{{ route('riwayat.transaksi') }}">Riwayat Transaksi</a>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn-auth">Login</a>
            <a href="{{ route('register') }}" class="btn-auth outline">Register</a>
        @endauth
    </div>

    </div>
</header>
