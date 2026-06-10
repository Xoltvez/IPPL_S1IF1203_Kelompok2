<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Persetujuan Peminjaman Buku</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #F3F7FB; padding: 30px; margin: 0; color: #2F3951;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 24px; overflow: hidden; border: 1px border-gray-100; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <!-- Header -->
        <tr>
            <td style="background: linear-gradient(135deg, #4D9BE2, #3D8BCF); padding: 40px 30px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: bold; letter-spacing: -0.5px;">MacaBae Perpustakaan</h1>
                <p style="color: rgba(255, 255, 255, 0.85); margin: 5px 0 0 0; font-size: 14px;">Status Pengajuan Peminjaman Buku</p>
            </td>
        </tr>
        
        <!-- Content -->
        <tr>
            <td style="padding: 40px 30px;">
                <p style="font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">Halo <strong>{{ $peminjaman->user->name }}</strong>,</p>
                
                @if($statusText === 'disetujui')
                    <p style="font-size: 15px; line-height: 1.6; margin: 0 0 25px 0;">Kabar gembira! Pengajuan peminjaman buku Anda telah <strong>disetujui</strong> oleh Pustakawan MacaBae. Berikut rincian peminjaman aktif Anda:</p>
                    
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
                            <td style="font-size: 13px; color: #94A3B8; font-weight: bold; text-transform: uppercase; padding: 6px 0;">Tanggal Pinjam</td>
                            <td style="font-size: 14px; color: #2F3951; font-weight: bold; padding: 6px 0;">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; color: #94A3B8; font-weight: bold; text-transform: uppercase; padding: 6px 0;">Jatuh Tempo</td>
                            <td style="font-size: 14px; color: #E11D48; font-weight: bold; padding: 6px 0;">{{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') }}</td>
                        </tr>
                    </table>

                    <p style="font-size: 14px; line-height: 1.6; color: #64748B; margin: 0 0 20px 0;">Harap kembalikan buku tepat waktu sebelum tanggal jatuh tempo untuk menghindari denda keterlambatan sebesar Rp 1.000 per hari.</p>
                @else
                    <p style="font-size: 15px; line-height: 1.6; margin: 0 0 25px 0;">Mohon maaf, pengajuan peminjaman buku Anda telah <strong>ditolak</strong> oleh Pustakawan MacaBae:</p>

                    <table width="100%" style="background-color: #FFF1F2; border-radius: 16px; padding: 20px; margin-bottom: 25px; border: 1px solid #FFE4E6;">
                        <tr>
                            <td width="30%" style="font-size: 13px; color: #FDA4AF; font-weight: bold; text-transform: uppercase; padding: 6px 0;">Judul Buku</td>
                            <td style="font-size: 14px; font-weight: bold; color: #9F1239; padding: 6px 0;">{{ $peminjaman->buku->judul }}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; color: #FDA4AF; font-weight: bold; text-transform: uppercase; padding: 6px 0;">Pengarang</td>
                            <td style="font-size: 14px; color: #9F1239; padding: 6px 0;">{{ $peminjaman->buku->pengarang }}</td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; color: #FDA4AF; font-weight: bold; text-transform: uppercase; padding: 6px 0;">Status</td>
                            <td style="font-size: 14px; font-weight: bold; color: #E11D48; padding: 6px 0;">Ditolak / Pembatalan Pengajuan</td>
                        </tr>
                    </table>

                    <p style="font-size: 14px; line-height: 1.6; color: #64748B; margin: 0 0 20px 0;">Anda dapat mencoba mengajukan peminjaman buku lainnya di katalog kami.</p>
                @endif

                <div style="text-align: center; margin-top: 30px;">
                    <a href="{{ url('/peminjaman') }}" style="display: inline-block; background-color: #4D9BE2; color: #ffffff; text-decoration: none; padding: 12px 30px; font-size: 14px; font-weight: bold; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(77, 155, 226, 0.2);">Lihat Peminjaman Saya</a>
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
