@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="login-container">

    <!-- KIRI -->
    <div class="login-left">
        <div class="brand">
            <img src="{{ asset('assets/images/viva.png') }}" alt="logo">
            <span>Viva Arena</span>
        </div>

        <h1>Masuk ke Akun Anda</h1>

        <form class="login-form" method="POST" action="{{ route('login') }}">
            @csrf

            <input 
                type="email" 
                name="email" 
                placeholder="Email" 
                required
            >

            <div class="password-wrapper">
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Password" 
                    required
                >
                <i class="fa-solid fa-eye toggle-password"></i>
            </div>

            <button type="submit" class="btn-login">Masuk</button>
        </form>

        @if (session('success'))
        <div class="toast">
            {{ session('success') }}
        </div>
        @endif


    </div>

    <!-- KANAN -->
    <div class="login-right">
        <div class="overlay">
            <h2>Baru di sini?</h2>
            <p>Daftar dan temukan<br>kesenangan baru!</p>
            <a href="{{ route('register') }}" class="btn-register">Daftar</a>
        </div>
    </div>

</div>
@endsection
