@extends('admin.admin-layouts.app')

@section('title','Kelola Jadwal Lapangan')

@section('content')

<div class="page-header">
    <h1>Kelola Jadwal Lapangan</h1>
    <p>Dashboard / Jadwal Lapangan</p>
</div>

<div class="card">

    {{-- FILTER --}}
    <form method="GET" class="jadwal-filter">
        <select name="lapangan_id" onchange="this.form.submit()">
            @foreach($lapangans as $lap)
                <option value="{{ $lap->id }}"
                    {{ $lap->id == $lapanganId ? 'selected' : '' }}>
                    {{ $lap->nama }}
                </option>
            @endforeach
        </select>

        <input
            type="date"
            name="tanggal"
            value="{{ $tanggal }}"
            onchange="this.form.submit()"
        >
    </form>

    {{-- FORM SIMPAN --}}
    <form method="POST" action="{{ route('admin.jadwal.update') }}">
        @csrf

        <input type="hidden" name="lapangan_id" value="{{ $lapanganId }}">
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">

        <div class="table-wrapper">
            <table class="jadwal-admin-table">
                <thead>
                    <tr>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($slots as $slot)
                    <tr>
                        <td class="jam">{{ $slot['jam'] }}</td>

                        <td>
                            <span class="badge {{ $slot['status'] }}">
                                {{ ucfirst($slot['status']) }}
                            </span>
                        </td>

                        <td>
                            <div class="radio-group">

                                {{-- TERSEDIA --}}
                                <label class="radio tersedia">
                                    <input
                                        type="radio"
                                        name="status[{{ $slot['jam_db'] }}]"
                                        value="tersedia"
                                        {{ $slot['status'] === 'tersedia' ? 'checked' : '' }}
                                    >
                                    <span>Tersedia</span>
                                </label>

                                {{-- TUTUP --}}
                                <label class="radio tutup">
                                    <input
                                        type="radio"
                                        name="status[{{ $slot['jam_db'] }}]"
                                        value="tutup"
                                        {{ $slot['status'] === 'tutup' ? 'checked' : '' }}
                                    >
                                    <span>Tutup</span>
                                </label>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ACTION --}}
        <div class="form-action">
            <button type="submit" class="btn-primary">
                <i class="fa fa-save"></i> Simpan Jadwal
            </button>
        </div>
    </form>

</div>

@endsection
