@extends('admin.admin-layouts.app')

@section('title','Edit Lapangan')

@section('content')

<div class="page-header">
    <h1>Edit Lapangan</h1>
    <p>Edit Data Lapangan</p>
</div>

<div class="card">

    <form
        action="{{ route('admin.lapangan.update', $lapangan) }}"
        method="POST"
        enctype="multipart/form-data"
        class="form-grid"
    >
        @csrf
        @method('PUT')

        <div class="form-group full">
            <label>Nama Lapangan</label>
            <input type="text" name="nama" value="{{ $lapangan->nama }}" required>
        </div>

        <div class="form-group full">
            <label>Foto Lapangan</label>
            <input type="file" name="gambar">
            <small>Biarkan kosong jika tidak diganti</small>
        </div>

        <div class="form-group">
            <label>Jenis Lantai</label>
            <select name="jenis_lantai" required>
                <option value="">Pilihan Lantai</option>

                @foreach (['Sintetis','Vinyl','Parquette','Wodit','Interlock'] as $lantai)
                    <option value="{{ $lantai }}"
                        {{ old('jenis_lantai', $lapangan->jenis_lantai ?? '') == $lantai ? 'selected' : '' }}>
                        {{ $lantai }}
                    </option>
                @endforeach
            </select>

        </div>

        <div class="form-group">
            <label>Harga / Jam</label>
            <input type="number" name="harga_per_jam"
                   value="{{ $lapangan->harga_per_jam }}" required>
        </div>

        <div class="form-group full">
            <label>Ukuran</label>
            <input type="text" name="ukuran"
                   value="{{ $lapangan->ukuran }}" required>
        </div>

        <div class="form-action">
            <button type="submit" class="btn">Simpan</button>
            <a href="{{ route('admin.lapangan.index') }}" class="btn-secondary">
                Batal
            </a>
        </div>

    </form>

</div>

@endsection
