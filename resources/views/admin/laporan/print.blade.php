<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Sirkulasi MacaBae Perpustakaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header-container {
            display: flex;
            align-items: center;
            border-bottom: 3px double #333;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header-logo {
            width: 80px;
            margin-right: 20px;
        }
        .header-text {
            flex-grow: 1;
            text-align: center;
        }
        .header-text h1 {
            font-size: 20px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header-text p {
            margin: 2px 0;
            color: #666;
        }
        .doc-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
            text-decoration: underline;
        }
        .meta-info {
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-info td {
            padding: 3px 0;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .stats-table th, .stats-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .stats-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .data-table th, .data-table td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #eaeaea;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center !important;
        }
        .text-right {
            text-align: right !important;
        }
        .signature-container {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-box {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-space {
            height: 75px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none;
            }
            button {
                display: none;
            }
        }
    </style>
</head>
<body>

    {{-- PRINT FLOATING ACTION --}}
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 16px; background-color: #4D9BE2; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            Cetak Dokumen
        </button>
    </div>

    {{-- KOP SURAT --}}
    <div class="header-container">
        <div class="header-text">
            <h1>Perpustakaan MacaBae</h1>
            <p>Sistem Layanan Literasi & Pustaka Digital Modern</p>
            <p style="font-size: 10px;">Gedung Kuliah Umum, Universitas Telkom, Bandung. Telp: (022) 123456 | Email: info@macabae.com</p>
        </div>
    </div>

    {{-- DOKUMEN JUDUL --}}
    <div class="doc-title">
        Laporan Sirkulasi Buku & Keuangan Denda
    </div>

    {{-- META INFO LAPORAN --}}
    <table class="meta-info">
        <tr>
            <td style="width: 15%; font-weight: bold;">Periode Laporan</td>
            <td style="width: 2%;">:</td>
            <td style="width: 33%;">{{ \Carbon\Carbon::parse($start)->format('d F Y') }} s.d {{ \Carbon\Carbon::parse($end)->format('d F Y') }}</td>
            <td style="width: 15%; font-weight: bold;">Dicetak Pada</td>
            <td style="width: 2%;">:</td>
            <td style="width: 33%;">{{ now()->translatedFormat('d F Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Status Filter</td>
            <td>:</td>
            <td>{{ ucfirst($status) }}</td>
            <td style="font-weight: bold;">Petugas Cetak</td>
            <td>:</td>
            <td>{{ Auth::user()->name }} (Admin)</td>
        </tr>
    </table>

    {{-- RINGKASAN REKAPITULASI --}}
    <h3 style="margin-bottom: 10px;">I. Rekapitulasi Data</h3>
    <table class="stats-table">
        <thead>
            <tr>
                <th>Total Peminjaman</th>
                <th>Total Pengembalian</th>
                <th>Total Denda Lunas</th>
                <th>Total Denda Tertunggak</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="font-size: 14px; font-weight: bold;">{{ $totalPeminjaman }}</td>
                <td style="font-size: 14px; font-weight: bold;">{{ $totalPengembalian }}</td>
                <td style="font-size: 14px; font-weight: bold; color: #10B981;">Rp {{ number_format($totalDendaLunas, 0, ',', '.') }}</td>
                <td style="font-size: 14px; font-weight: bold; color: #EF4444;">Rp {{ number_format($totalDendaBelumLunas, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    {{-- RINCIAN TRANSAKSI --}}
    <h3 style="margin-bottom: 10px;">II. Rincian Transaksi Sirkulasi</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">ID Transaksi</th>
                <th style="width: 20%;">Anggota / Member</th>
                <th style="width: 28%;">Buku yang Dipinjam</th>
                <th style="width: 11%;">Tgl Pinjam</th>
                <th style="width: 11%;">Tgl Kembali</th>
                <th style="width: 14%;">Denda (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $t)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">TRX-{{ str_pad($t->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        {{ $t->user->name ?? '-' }}
                        <div style="font-size: 9px; color: #666;">ID: #MBR-{{ str_pad($t->user_id, 4, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td>
                        {{ $t->buku->judul ?? '-' }}
                        <div style="font-size: 9px; color: #666;">ISBN: {{ $t->buku->isbn ?? '-' }}</div>
                    </td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($t->tanggal_kembali)->format('d M Y') }}</td>
                    <td class="text-center">
                        @if($t->denda)
                            Rp {{ number_format($t->denda->jumlah_denda, 0, ',', '.') }}
                            <div style="font-size: 9px; color: {{ $t->denda->status_pembayaran === 'lunas' ? '#10B981' : '#EF4444' }}; font-weight: bold;">
                                ({{ $t->denda->status_pembayaran === 'lunas' ? 'Lunas' : 'Belum Lunas' }})
                            </div>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px;">Tidak ada data transaksi ditemukan pada rentang ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- KOTAK TANDA TANGAN --}}
    <div class="signature-container">
        <div class="signature-box">
            <p>Bandung, {{ now()->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <p style="font-weight: bold; margin-bottom: 0;">Staff Administrator MacaBae</p>
            <div class="signature-space"></div>
            <p class="signature-name">{{ Auth::user()->name }}</p>
            <p style="margin-top: 0; font-size: 10px; color: #555;">NIP. STAFF-{{ str_pad(Auth::user()->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    {{-- AUTO PRINT TRIGGER --}}
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
