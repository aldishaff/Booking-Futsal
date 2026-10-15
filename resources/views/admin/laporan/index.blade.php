@extends('admin.admin-layouts.app')

@section('content')

<div class="laporan-container">

    {{-- HEADER --}}
    <div class="laporan-header">

        <div>
            <h2 class="page-title">
                Laporan Booking
            </h2>

            <p class="page-subtitle">
                Rekap data booking dan income futsal
            </p>
        </div>

    </div>

    {{-- FILTER --}}
    <div class="filter-card">

        <form method="GET">

            <div class="filter-grid">

                {{-- START DATE --}}
                <div class="form-group">
                    <label>Tanggal Mulai</label>

                    <input
                        type="date"
                        name="start_date"
                        value="{{ request('start_date') }}"
                    >
                </div>

                {{-- END DATE --}}
                <div class="form-group">
                    <label>Tanggal Selesai</label>

                    <input
                        type="date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                    >
                </div>

                {{-- STATUS --}}
                <div class="form-group">
                    <label>Status</label>

                    <select name="status">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="pending"
                            {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option value="success"
                            {{ request('status') == 'success' ? 'selected' : '' }}>
                            Success
                        </option>

                        <option value="cancel"
                            {{ request('status') == 'cancel' ? 'selected' : '' }}>
                            Cancel
                        </option>

                    </select>
                </div>

                {{-- LAPANGAN --}}
                <div class="form-group">
                    <label>Lapangan</label>

                    <select name="lapangan_id">

                        <option value="">
                            Semua Lapangan
                        </option>

                        @foreach($lapangans as $lapangan)

                            <option
                                value="{{ $lapangan->id }}"
                                {{ request('lapangan_id') == $lapangan->id ? 'selected' : '' }}
                            >
                                {{ $lapangan->nama }}
                            </option>

                        @endforeach

                    </select>
                </div>

            </div>

            <div class="filter-button-group">

                <button type="submit" class="btn-filter">
                    Filter
                </button>

                <a href="{{ route('admin.laporan.index') }}"
                   class="btn-reset">
                    Reset
                </a>

            </div>

        </form>

    </div>

    {{-- STATISTIK --}}
    <div class="stats-grid">

        <div class="stats-card">
            <h4>Total Booking</h4>
            <h2>{{ $totalBooking }}</h2>
        </div>

        <div class="stats-card income">
            <h4>Total Income</h4>
            <h2>
                Rp {{ number_format($totalIncome) }}
            </h2>
        </div>

        <div class="stats-card success">
            <h4>Booking Success</h4>
            <h2>{{ $bookingSuccess }}</h2>
        </div>

        <div class="stats-card pending">
            <h4>Booking Pending</h4>
            <h2>{{ $bookingPending }}</h2>
        </div>

    </div>

    {{-- GRAFIK --}}
    <div class="chart-grid">

        {{-- TOP LAPANGAN --}}
        <div class="chart-card">

            <div class="card-header">
                <h3>Lapangan</h3>
            </div>

            <canvas
                id="lapanganChart"
                data-labels='@json($topLapangan->pluck("lapangan.nama")->toArray())'
                data-totals='@json($topLapangan->pluck("total")->toArray())'>
            </canvas>

        </div>

        {{-- INCOME --}}
        <div class="chart-card">

            <div class="card-header">
                <h3>Income Harian</h3>
            </div>

            <canvas
                id="incomeChart"
                data-labels='@json($incomeChart->pluck("tanggal")->toArray())'
                data-totals='@json($incomeChart->pluck("total_income")->toArray())'>
            </canvas>

        </div>

    </div>

    {{-- EXPORT --}}
    <div class="export-group">

        <a href="{{ route('admin.laporan.pdf', request()->query()) }}"
           class="btn-export pdf">
            PDF
        </a>

        <a href="{{ route('admin.laporan.excel', request()->query()) }}"
           class="btn-export excel">
            Excel
        </a>

        <a href="{{ route('admin.laporan.csv', request()->query()) }}"
           class="btn-export csv">
            CSV
        </a>

        <a href="{{ route('admin.laporan.word', request()->query()) }}"
           class="btn-export word">
            Word
        </a>

    </div>

    {{-- TABLE --}}
    <div class="table-card">

        <table>

            <thead>

                <tr>
                    <th>No</th>
                    <th>Customer</th>
                    <th>Lapangan</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>

            </thead>

            <tbody>

                @forelse($bookings as $booking)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $booking->user->username ?? '-' }}
                        </td>

                        <td>
                            {{ $booking->lapangan->nama ?? '-' }}
                        </td>

                        <td>
                            {{ $booking->tanggal }}
                        </td>

                        <td>

                            <span class="status {{ $booking->status }}">

                                {{ ucfirst($booking->status) }}

                            </span>

                        </td>

                        <td>
                            Rp {{ number_format($booking->total_harga) }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="empty-data">
                            Tidak ada data laporan
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

        {{-- PAGINATION --}}
        <div class="pagination-wrapper">

            @if ($bookings->hasPages())

            <div class="pagination-container">

                {{-- INFO --}}
                <div class="pagination-info">

                    Showing

                    {{ $bookings->firstItem() }}

                    to

                    {{ $bookings->lastItem() }}

                    of

                    {{ $bookings->total() }}

                    results

                </div>

                {{-- PAGINATION --}}
                <div class="custom-pagination">

                    {{-- PREVIOUS --}}
                    @if ($bookings->onFirstPage())

                        <span class="pagination-disabled">
                            ‹
                        </span>

                    @else

                        <a href="{{ $bookings->previousPageUrl() }}"
                        class="pagination-btn">
                            ‹
                        </a>

                    @endif

                    {{-- PAGE --}}
                    @foreach ($bookings->getUrlRange(1, $bookings->lastPage()) as $page => $url)

                        @if ($page == $bookings->currentPage())

                            <span class="pagination-active">
                                {{ $page }}
                            </span>

                        @else

                            <a href="{{ $url }}"
                            class="pagination-btn">
                                {{ $page }}
                            </a>

                        @endif

                    @endforeach

                    {{-- NEXT --}}
                    @if ($bookings->hasMorePages())

                        <a href="{{ $bookings->nextPageUrl() }}"
                        class="pagination-btn">
                            ›
                        </a>

                    @else

                        <span class="pagination-disabled">
                            ›
                        </span>

                    @endif

                </div>

            </div>

            @endif

        </div>

    </div>

</div>

{{-- CHART --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | TOP LAPANGAN
    |--------------------------------------------------------------------------
    */
    const lapanganElement =
        document.getElementById('lapanganChart');

    if (lapanganElement) {

        const labels =
            JSON.parse(lapanganElement.dataset.labels);

        const totals =
            JSON.parse(lapanganElement.dataset.totals);

        new Chart(lapanganElement, {

            type: 'doughnut',

            data: {

                labels: labels,

                datasets: [{

                    data: totals,

                    backgroundColor: [
                        '#2563eb',
                        '#16a34a',
                        '#f59e0b'
                    ],

                    borderWidth: 0
                }]
            },

            options: {

                responsive: true,

                plugins: {

                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | INCOME CHART
    |--------------------------------------------------------------------------
    */
    const incomeElement =
        document.getElementById('incomeChart');

    if (incomeElement) {

        const incomeLabels =
            JSON.parse(incomeElement.dataset.labels);

        const incomeTotals =
            JSON.parse(incomeElement.dataset.totals);

        new Chart(incomeElement, {

            type: 'line',

            data: {

                labels: incomeLabels,

                datasets: [{

                    label: 'Income',

                    data: incomeTotals,

                    borderColor: '#16a34a',

                    backgroundColor: 'rgba(22,163,74,0.1)',

                    tension: 0.4,

                    fill: true
                }]
            },

            options: {

                responsive: true,

                plugins: {

                    legend: {
                        display: false
                    }
                }
            }
        });
    }

});

</script>

</script>

@endsection