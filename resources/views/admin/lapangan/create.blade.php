@extends('admin.admin-layouts.app')

@section('title','Tambah Lapangan')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/admin/lapangan-form.css') }}">
@endsection

@section('content')

{{-- HEADER --}}
<div class="page-header">
    <h1>Tambah Lapangan</h1>
    <p>Tambah Lapangan</p>
</div>

<div class="card">

    <div class="card-header space-between">
        <h3>Form Tambah Lapangan</h3>
    </div>

    <form
        action="{{ route('admin.lapangan.store') }}"
        method="POST"
        enctype="multipart/form-data"
        class="form-grid"
    >
        @csrf

        {{-- NAMA --}}
        <div class="form-group full">
            <label>Nama Lapangan</label>
            <input type="text" name="nama" placeholder="Lapangan X" required>
        </div>

        {{-- FOTO --}}
        <div class="form-group full">
            <label>Unggah Foto Lapangan</label>
            <input type="file" name="gambar" accept=".jpg,.jpeg,.png" required>
            <small>Max 2MB (.jpg, .jpeg, .png)</small>
        </div>

        {{-- JENIS LANTAI --}}
        <div class="form-group">
            <label>Jenis Lantai</label>
            <select name="jenis_lantai" required>
                <option value="">Pilihan Lantai</option>
                <option value="Sintetis">Sintetis</option>
                <option value="Vinyl">Vinyl</option>
                <option value="Parquette">Parquette</option>
                <option value="Wodit">Wodit</option>
                <option value="Interlock">Interlock</option>
            </select>
        </div>

        {{-- HARGA --}}
        <div class="form-group">
            <label>Harga / Jam (Rp)</label>
            <input type="number" name="harga_per_jam" placeholder="Masukkan harga per jam..." required>
        </div>

        {{-- UKURAN --}}
        <div class="form-group full">
            <label>Ukuran Lapangan</label>
            <input type="text" name="ukuran" placeholder="Contoh: 20 x 40 m" required>
        </div>

        {{-- ACTION --}}
        <div class="form-action">
            <button type="submit" class="btn">Simpan</button>
            <a href="{{ route('admin.lapangan.index') }}" class="btn-secondary">Batal</a>
        </div>

    </form>

</div>

@endsection
