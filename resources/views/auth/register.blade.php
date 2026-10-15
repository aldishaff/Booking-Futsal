@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="login-container register">

    <!-- KIRI -->
    <div class="login-right">
        <div class="overlay">
            <h2>Punya Akun?</h2>
            <p>Masuk dan temukan<br>kesenangan baru!</p>
            <a href="{{ route('login') }}" class="btn-register">Masuk</a>
        </div>
    </div>

    <!-- KANAN -->
    <div class="login-left">
        <div class="brand center">
            <img src="{{ asset('assets/images/viva.png') }}">
            <span>Viva Arena</span>
        </div>

        <h1>Daftar Akun Baru Anda</h1>

        {{-- Notifikasi Berhasil --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="login-form" method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <input 
                type="text" 
                name="username" 
                placeholder="Username" 
                value="{{ old('username') }}"
                class="@error('username') is-invalid @enderror"
                required
            >
            @error('username')
                <small class="error-text">{{ $message }}</small>
            @enderror

            <input 
                type="email" 
                name="email" 
                placeholder="Email" 
                value="{{ old('email') }}"
                class="@error('email') is-invalid @enderror"
                required
            >
            @error('email')
                <small class="error-text">{{ $message }}</small>
            @enderror

            <input 
                type="tel" 
                name="no_telepon" 
                placeholder="No. Telepon" 
                value="{{ old('no_telepon') }}"
                class="@error('no_telepon') is-invalid @enderror"
                required
            >
            @error('no_telepon')
                <small class="error-text">{{ $message }}</small>
            @enderror

            <div class="password-wrapper">
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Password" 
                    class="@error('password') is-invalid @enderror"
                    required
                >
                <i class="fa-solid fa-eye toggle-password"></i>
            </div>
            @error('password')
                <small class="error-text">{{ $message }}</small>
            @enderror

            <div class="password-wrapper">
                <input 
                    type="password" 
                    name="password_confirmation" 
                    placeholder="Konfirmasi Password" 
                    required
                >
                <i class="fa-solid fa-eye toggle-password"></i>
            </div>

            <button class="btn-login" type="submit">Daftar</button>
        </form>

    </div>

</div>
@endsection
