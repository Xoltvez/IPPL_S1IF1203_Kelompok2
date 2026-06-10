<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pengembalian Buku Sukses</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #F3F7FB; padding: 30px; margin: 0; color: #2F3951;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 24px; overflow: hidden; border: 1px border-gray-100; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #10B981, #059669); padding: 40px 30px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: bold; letter-spacing: -0.5px;">MacaBae Perpustakaan</h1>
                <p style="color: rgba(255, 255, 255, 0.85); margin: 5px 0 0 0; font-size: 14px;">Tanda Terima Pengembalian Buku</p>
            </td>
        </tr>
        
        <!-- Content -->
        <tr>
            <td style="padding: 40px 30px;">
                <p style="font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">Halo <strong>{{ $peminjaman->user->name }}</strong>,</p>
                <p style="font-size: 15px; line-height: 1.6; margin: 0 0 25px 0;">Terima kasih, Anda telah berhasil mengembalikan buku perpustakaan MacaBae. Berikut adalah detail sirkulasi pengembalian Anda:</p>
                
                <table width="100%" style="background-color: #F8FAFC; border-radius: 16px; padding: 20px; margin-bottom: 25px; border: 1px solid #F1F5F9;">
                    <tr>
                        <td width="30%" style="font-size: 13px; color: #94A3B8; font-weight: bold; text-transform: uppercase; padding: 6px 0;">Judul Buku</td>
                        <td style="font-size: 14px; font-weight: bold; color: #2F3951; padding: 6px 0;">{{ $peminjaman->buku->judul }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #94A3B8; font-weight: bold; text-transform: uppercase; padding: 6px 0;">Pengarang</td>
                        <td style="font-size: 14px; color: #475569; padding: 6px 0;">{{ $peminjaman->buku->pengarang }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #94A3B8; font-weight: bold; text-transform: uppercase; padding: 6px 0;">Batas Kembali</td>
                        <td style="font-size: 14px; color: #475569; padding: 6px 0;">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #94A3B8; font-weight: bold; text-transform: uppercase; padding: 6px 0;">Tanggal Kembali</td>
                        <td style="font-size: 14px; color: #2F3951; font-weight: bold; padding: 6px 0;">{{ \Carbon\Carbon::now()->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #94A3B8; font-weight: bold; text-transform: uppercase; padding: 6px 0;">Status Denda</td>
                        <td style="font-size: 14px; padding: 6px 0;">
                            @if($dendaAmount > 0)
                                <span style="color: #E11D48; font-weight: bold;">Terlambat (Denda: Rp {{ number_format($dendaAmount, 0, ',', '.') }})</span>
                            @else
                                <span style="color: #10B981; font-weight: bold;">Tepat Waktu (Bebas Denda)</span>
                            @endif
                        </td>
                    </tr>
                </table>

                @if($dendaAmount > 0)
                    <p style="font-size: 14px; line-height: 1.6; color: #64748B; margin: 0 0 20px 0;">Anda memiliki denda keterlambatan belum lunas sebesar <strong>Rp {{ number_format($dendaAmount, 0, ',', '.') }}</strong>. Silakan selesaikan pembayaran denda ke Pustakawan di meja sirkulasi perpustakaan.</p>
                @else
                    <p style="font-size: 14px; line-height: 1.6; color: #64748B; margin: 0 0 20px 0;">Kami sangat mengapresiasi kedisiplinan Anda dalam mengembalikan buku tepat waktu!</p>
                @endif

                <div style="text-align: center; margin-top: 30px;">
                    <a href="{{ url('/riwayat') }}" style="display: inline-block; background-color: #10B981; color: #ffffff; text-decoration: none; padding: 12px 30px; font-size: 14px; font-weight: bold; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);">Lihat Riwayat Transaksi</a>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #F8FAFC; padding: 30px; text-align: center; font-size: 12px; color: #94A3B8; border-top: 1px solid #F1F5F9;">
                MacaBae Perpustakaan Digital &bull; Email otomatis, mohon tidak membalas email ini.
            </td>
        </tr>
    </table>
</body>
</html>
