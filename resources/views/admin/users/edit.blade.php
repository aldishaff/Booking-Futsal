@extends('admin.admin-layouts.app')

@section('title', 'Edit Pengguna')

@section('content')

<div class="page-header">
    <h1>Edit Pengguna</h1>
    <p>Dashboard / Pengguna / Edit</p>
</div>

<div class="card user-form-card">

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid">

            {{-- NAMA --}}
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input
                    type="text"
                    name="username"
                    value="{{ old('name', $user->username) }}"
                    required
                >

                @error('name')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div class="form-group">
                <label>Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                >

                @error('email')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            {{-- NOMOR HP --}}
            <div class="form-group">
                <label>Nomor HP</label>
                <input
                    type="text"
                    name="no_telepon"
                    value="{{ old('phone', $user->no_telepon) }}"
                >

                @error('phone')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div class="form-group">
                <label>Password Baru</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Kosongkan jika tidak diubah"
                >

                @error('password')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

            {{-- FOTO --}}
            <div class="form-group full-width">
                <label>Foto Profil</label>

                <input type="file" name="photo">

                @if($user->photo)
                    <div class="preview-image">
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto User">
                    </div>
                @endif

                @error('foto')
                    <small class="error-text">{{ $message }}</small>
                @enderror
            </div>

        </div>

        {{-- ACTION --}}
        <div class="form-action">
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                Kembali
            </a>

            <button type="submit" class="btn-primary">
                <i class="fa fa-save"></i> Update Pengguna
            </button>
        </div>

    </form>

</div>

@endsection