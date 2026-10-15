@extends('admin.admin-layouts.app')

@section('title','Pembayaran')

@section('content')

<div class="page-header">
    <h1>Pembayaran Cash</h1>
    <p>Dashboard / Pembayaran</p>
</div>

<div class="card">

    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Lapangan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            @forelse($transactions as $transaction)

            <tr>

                <td>
                    {{ $transaction->booking->user->username }}
                </td>

                <td>
                    {{ $transaction->booking->lapangan->nama }}
                </td>

                <td>
                    Rp {{ number_format(
                        $transaction->booking->total_harga,
                        0,
                        ',',
                        '.'
                    ) }}
                </td>

                <td>

                    @if($transaction->status == 'pending')

                        <span class="badge warning">
                            Pending
                        </span>

                    @elseif($transaction->status == 'paid')

                        <span class="badge success">
                            Paid
                        </span>

                    @elseif($transaction->status == 'failed')

                        <span class="badge danger">
                            Failed
                        </span>

                    @endif

                </td>

                <td>

                    @if($transaction->status == 'pending')

                        <div class="action-group">

                            {{-- KONFIRMASI --}}
                            <form id="confirm-form-{{ $transaction->id }}"
                                method="POST"
                                action="{{ route(
                                    'admin.transaction.konfirmasi',
                                    $transaction
                                ) }}">

                                @csrf

                                <button type="button"
                                        class="action-btn confirm-btn"
                                        onclick="confirmPayment(this)">

                                    <i class="fa fa-check"></i>

                                </button>

                            </form>

                            {{-- BATALKAN --}}
                            <form id="cancel-form-{{ $transaction->id }}"
                                method="POST"
                                action="{{ route(
                                    'admin.transaction.batalkan',
                                    $transaction
                                ) }}">

                                @csrf

                                <button type="button"
                                        class="action-btn cancel-btn"
                                        onclick="cancelPayment(this)">

                                    <i class="fa fa-times"></i>

                                </button>

                            </form>

                        </div>

                    @elseif($transaction->status == 'paid')

                        <span class="status-chip paid">
                            Paid
                        </span>

                    @elseif($transaction->status == 'failed')

                        <span class="status-chip failed">
                            Failed
                        </span>

                    @endif

                </td>

            </tr>

            @empty

            <tr>
                <td colspan="5">
                    Tidak ada transaksi
                </td>
            </tr>

            @endforelse

        </tbody>
    </table>

    <div style="margin-top:20px">
        {{ $transactions->links() }}
    </div>

</div>

@endsection

@push('scripts')

<script>

function confirmPayment(button)
{
    let id = button.dataset.id;

    Swal.fire({

        title: 'Konfirmasi Pembayaran?',
        text: 'Pembayaran akan dinyatakan berhasil.',
        icon: 'question',

        showCancelButton: true,

        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#6b7280',

        confirmButtonText: 'Ya, Konfirmasi',
        cancelButtonText: 'Batal'

    }).then((result) => {

        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}

function cancelPayment(button)
{
    let id = button.dataset.id;

    Swal.fire({

        title: 'Batalkan Pembayaran?',
        text: 'Transaksi akan dibatalkan.',
        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',

        confirmButtonText: 'Ya, Batalkan',
        cancelButtonText: 'Kembali'

    }).then((result) => {

        if (result.isConfirmed) {
            button.closest('form').submit()
        }
    });
}

</script>

@endpush