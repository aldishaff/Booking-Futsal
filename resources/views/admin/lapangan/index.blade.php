@extends('admin.admin-layouts.app')

@section('title','Data Lapangan')

@section('content')

{{-- HEADER --}}
<div class="page-header">
    <h1>Kelola Data Lapangan</h1>
    <p>Dashboard / Data Lapangan</p>
</div>

{{-- CARD --}}
<div class="card">

    <div class="card-header space-between">
        <h3>Tabel data lapangan</h3>

        <a href="{{ route('admin.lapangan.create') }}" class="btn">
            <i class="fa fa-plus"></i> Tambah Lapangan
        </a>
    </div>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Lapangan</th>
                    <th>Foto</th>
                    <th>Jenis Lantai</th>
                    <th>Harga / Jam</th>
                    <th>Ukuran</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @foreach($lapangans as $lap)
                <tr>
                    <td class="bold">{{ $lap->nama }}</td>

                    <td>
                        <img
                            src="{{ asset('storage/'.$lap->gambar) }}"
                            class="lapangan-img"
                        >
                    </td>

                    <td>{{ $lap->jenis_lantai }}</td>

                    <td>Rp {{ number_format($lap->harga_per_jam,0,',','.') }} / jam</td>

                    <td>{{ $lap->ukuran }}</td>

                    <td class="aksi">
                        <a href="{{ route('admin.lapangan.edit', $lap) }}" class="btn-edit">
                            <i class="fa fa-pen"></i> Edit
                        </a>
                        <form
                            action="{{ route('admin.lapangan.destroy', $lap) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus lapangan ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn-delete">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- FOOTER --}}
    <div class="table-footer">
        <span>
            Menampilkan {{ $lapangans->count() }}
            data dari {{ $lapangans->total() }} lapangan
        </span>

        {{ $lapangans->links() }}
    </div>

</div>

@endsection
