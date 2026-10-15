<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/admin/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/lapangan.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/lapangan-form.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/jadwal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/transaction.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/users-index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/users-show.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/users-edit.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/laporan-index.css') }}">
    
     @stack('styles')
</head>
<body>

<div class="admin-wrapper">

    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('assets/images/viva.png') }}" alt="Logo">
            <span>VIVA ARENA</span>
        </div>

        <nav>
            <a href="{{ route('admin.dashboard') }}" class="active">
                <i class="fa fa-home"></i> Dashboard
            </a>
            <a href="{{ route('admin.lapangan.index') }}"
                class="{{ request()->routeIs('admin.lapangan.*') ? 'active' : '' }}">
                <i class="fa fa-futbol"></i> Data Lapangan
            </a>
            <a href="{{ route('admin.jadwal.index') }}">
                <i class="fa fa-calendar"></i> Jadwal Lapangan
            </a>
            <a href="{{ route('admin.booking.index') }}">
                <i class="fa fa-book"></i> Pemesanan
                @php
                $pendingTransactions = \App\Models\Transaction::where(
                    'metode',
                    'cash'
                )
                ->where('status', 'pending')
                ->count();
                @endphp

                <a href="{{ route('admin.transaction.index') }}"
                class="{{ request()->routeIs('admin.transaction.*') ? 'active' : '' }}">

                    <i class="fa fa-money-bill"></i>

                    Pembayaran

                    @if($pendingTransactions > 0)

                        <span style="
                            background:red;
                            color:white;
                            border-radius:50%;
                            padding:2px 7px;
                            font-size:12px;
                            margin-left:5px;
                        ">
                            {{ $pendingTransactions }}
                        </span>

                    @endif

                </a>
            </a>
            <a href="{{ route('admin.users.index') }}"
                class="{{request()->routeIs('admin.users.*') ? 'active' : ''}}">
                <i class="fa fa-users"></i> Pengguna
            </a>
            <a href="{{ route('admin.laporan.index') }}">
                <i class="fa fa-file-alt"></i> Laporan
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="main-content">

        {{-- NAVBAR --}}
        <header class="navbar">
            <div></div>
            <div class="admin-profile">
                <img src="{{ asset('img/avatar.png') }}">
                <span>Hai, Admin</span>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <section class="content">
            @yield('content')
        </section>

        {{-- FOOTER --}}
        <footer class="footer">
            © 2025 Viva Arena Malang. All rights reserved.
        </footer>

    </main>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')
</body>
</html>
