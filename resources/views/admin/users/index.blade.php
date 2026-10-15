@extends('admin.admin-layouts.app')

@push('styles')

@endpush

@section('title', 'Kelola Pengguna')

@section('content')

<div class="container py-4 user-index-page">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="page-title">
            Kelola Pengguna
        </h2>

        <form action="{{ route('admin.users.index') }}"
              method="GET"
              class="user-search-form">

            <input type="text"
                   name="search"
                   class="user-search-input"
                   placeholder="Cari pengguna..."
                   value="{{ request('search') }}">

            <button type='submit' class="btn-action btn-search">
                <i class="fa fa-search"></i>
                Search
            </button>

        </form>

    </div>

    <div class="user-card">

        <div class="table-responsive">

            <table class="user-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Tanggal Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($users as $user)

                        <tr>

                            <td>
                                {{ $users->firstItem() + $loop->index }}
                            </td>

                            <td>

                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   class="user-link">

                                    {{ $user->username }}

                                </a>

                            </td>

                            <td>
                                {{ $user->email }}
                            </td>

                            <td>
                                {{ $user->created_at->format('d M Y') }}
                            </td>

                            <td>

                                <div class="action-buttons">

                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       class="btn-action btn-edit">

                                        <i class="fa fa-pen"></i>
                                        Edit

                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                class="btn-action btn-delete btn-delete-user">

                                            <i class="fa fa-trash"></i>
                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="empty-data">

                                Data pengguna tidak ditemukan

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="pagination-wrapper">

            <div class="custom-pagination">

                {{-- PREVIOUS --}}
                @if ($users->onFirstPage())

                    <span class="pagination-disabled">
                        ‹
                    </span>

                @else

                    <a href="{{ $users->previousPageUrl() }}"
                    class="pagination-link">

                        ‹

                    </a>

                @endif

                {{-- PAGE NUMBER --}}
                @for ($i = 1; $i <= $users->lastPage(); $i++)

                    <a href="{{ $users->url($i) }}"
                    class="pagination-link {{ $users->currentPage() == $i ? 'active' : '' }}">

                        {{ $i }}

                    </a>

                @endfor

                {{-- NEXT --}}
                @if ($users->hasMorePages())

                    <a href="{{ $users->nextPageUrl() }}"
                    class="pagination-link">

                        ›

                    </a>

                @else

                    <span class="pagination-disabled">
                        ›
                    </span>

                @endif

            </div>

        </div>

    </div>

</div>

@push('scripts')

<script>

document.querySelectorAll('.btn-delete-user').forEach(button => {

    button.addEventListener('click', function () {

        Swal.fire({
            title: 'Yakin?',
            text: 'User akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {

            if (result.isConfirmed) {

                this.closest('form').submit();

            }

        });

    });

});

</script>

@endpush

@endsection