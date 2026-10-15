@extends('layouts.app')

@section('title', 'Profil Saya')

@section('page-class', 'page-profile')

@section('content')
<div class="profile-container">

    <h2 class="profile-title">Profil Saya</h2>

    <div class="profile-grid">

        {{-- KOLOM KIRI --}}
        <div class="profile-card">
            <h3>Informasi Akun</h3>

            <div class="profile-photo">
                <img src="{{ Auth::user()->photo
                    ? asset('storage/'.Auth::user()->photo)
                    : asset('img/default-avatar.png') }}">
            </div>

            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label>Username</label>
                <input type="text" name="username" value="{{ Auth::user()->username }}">

                <label>Email</label>
                <input type="email" value="{{ Auth::user()->email }}" disabled>

                <label>No Telepon</label>
                <input type="text" name="no_telepon" value="{{ Auth::user()->no_telepon }}">

                <label>Foto Profil</label>
                <input type="file" name="photo">

                <button class="btn-primary">Simpan Perubahan</button>
            </form>
        </div>

        {{-- KOLOM KANAN --}}
        <div class="profile-card password-card">
            <h3>Keamanan Akun</h3>

            <form action="{{ route('profil.password') }}" method="POST" class="password-form">
                @csrf

                <label>Password Lama</label>
                <input type="password" name="current_password">

                <label>Password Baru</label>
                <input type="password" name="password">

                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation">

                <button class="btn-primary btn-password">
                    Ubah Password
                </button>
            </form>
        </div>


    </div>
</div>

@endsection
