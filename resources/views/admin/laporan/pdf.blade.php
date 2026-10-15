<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>
        Laporan Booking Futsal
    </title>

    <style>

        body{
            font-family:sans-serif;
            font-size:12px;
            color:#333;
        }

        .header{
            text-align:center;
            margin-bottom:25px;
        }

        .header h2{
            margin:0;
            font-size:22px;
        }

        .header p{
            margin-top:5px;
            color:#666;
        }

        .info{
            margin-bottom:20px;
        }

        .info table{
            width:100%;
        }

        .info td{
            padding:4px 0;
        }

        .summary{
            margin-bottom:20px;
        }

        .summary-box{
            display:inline-block;
            width:23%;
            padding:12px;
            border:1px solid #ddd;
            margin-right:1%;
            text-align:center;
        }

        .summary-box h4{
            margin:0;
            font-size:13px;
            color:#777;
        }

        .summary-box h2{
            margin-top:8px;
            font-size:18px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th{
            background:#f3f4f6;
            padding:10px;
            border:1px solid #ddd;
            text-align:left;
        }

        table td{
            padding:10px;
            border:1px solid #ddd;
        }

        .status{
            padding:4px 8px;
            border-radius:4px;
            color:white;
            font-size:11px;
        }

        .confirmed{
            background:#16a34a;
        }

        .pending{
            background:#f59e0b;
        }

        .cancelled{
            background:#dc2626;
        }

        .footer{
            margin-top:30px;
            text-align:right;
        }

    </style>

</head>

<body>

    {{-- HEADER --}}
    <div class="header">

        <h1 style="
            margin:0;
            font-size:28px;
            color:#111827;
        ">

            VIVA ARENA

        </h1>

        <p style="
            margin:8px 0 4px;
            font-size:13px;
            color:#4b5563;
        ">

            Jl. Bunga Andong, Jatimulyo, Kec. Lowokwaru, 
            Kota Malang, Jawa Timur 65141

        </p>

        <p style="
            margin:0;
            font-size:13px;
            color:#4b5563;
        ">

            Telepon: 0813-3362-1111

        </p>

        <hr style="
            margin-top:20px;
            border:0;
            border-top:2px solid #111827;
        ">

        <h2 style="
            margin-top:20px;
            font-size:20px;
            letter-spacing:1px;
        ">

            LAPORAN BOOKING LAPANGAN FUTSAL

        </h2>

    </div>

    {{-- FILTER INFO --}}
    <div class="info">

        <table>

            <tr>

                <td>
                    <strong>Tanggal Cetak:</strong>
                    {{ now()->format('d M Y') }}
                </td>

                <td align="right">

                    <strong>Total Data:</strong>
                    {{ $bookings->count() }}

                </td>

            </tr>

        </table>

    </div>

    {{-- SUMMARY --}}
    <div class="summary">

        <div class="summary-box">

            <h4>Total Booking</h4>

            <h2>
                {{ $bookings->count() }}
            </h2>

        </div>

        <div class="summary-box">

            <h4>Total Income</h4>

            <h2>
                Rp {{ number_format($totalIncome) }}
            </h2>

        </div>

    </div>

    {{-- TABLE --}}
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

                        <span class="status {{ strtolower($booking->status) }}">

                            {{ ucfirst($booking->status) }}

                        </span>

                    </td>

                    <td>

                        Rp {{ number_format($booking->total_harga) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" align="center">

                        Tidak ada data laporan

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

    {{-- FOOTER --}}
    <div class="footer">

        <p>
            Dicetak pada:
            {{ now()->format('d M Y H:i') }}
        </p>

    </div>

</body>

</html>