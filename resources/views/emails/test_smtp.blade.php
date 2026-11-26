<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tes Konfigurasi Email SKMS</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F8FAFC;
            margin: 0;
            padding: 24px;
            color: #1E293B;
        }
        .container {
            max-width: 580px;
            margin: 0 auto;
            background: #FFFFFF;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: #8B0D18;
            color: #FFFFFF;
            padding: 24px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }
        .header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            opacity: 0.9;
        }
        .body {
            padding: 28px 30px;
            line-height: 1.6;
            font-size: 14px;
        }
        .success-box {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 20px;
            color: #15803D;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 13px;
        }
        .details-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #F1F5F9;
        }
        .details-table td:first-child {
            font-weight: 600;
            color: #64748B;
            width: 140px;
        }
        .footer {
            background: #F8FAFC;
            padding: 18px 30px;
            border-top: 1px solid #E2E8F0;
            font-size: 12px;
            color: #94A3B8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Student Knowledge Management System</h1>
            <p>Konfigurasi Layanan Pengiriman Email (Gmail SMTP)</p>
        </div>

        <div class="body">
            <div class="success-box">
                <strong>✓ Berhasil!</strong> Layanan email SMTP Gmail terhubung dengan baik ke sistem SKMS.
            </div>

            <p>Halo,</p>
            <p>Jika Anda menerima email ini, artinya konfigurasi <strong>Gmail SMTP</strong> dengan <strong>Google App Password</strong> pada aplikasi <strong>SKMS (Laravel)</strong> telah berhasil terpasang dan berfungsi dengan sempurna.</p>

            <table class="details-table">
                <tr>
                    <td>Penerima</td>
                    <td><strong>{{ $recipientEmail ?: 'Tujuan Tes' }}</strong></td>
                </tr>
                <tr>
                    <td>Waktu Pengiriman</td>
                    <td>{{ $sentAt }}</td>
                </tr>
                <tr>
                    <td>Driver Transport</td>
                    <td><code>smtp.gmail.com</code> (Port {{ config('mail.mailers.smtp.port') }})</td>
                </tr>
                <tr>
                    <td>Alamat Pengirim</td>
                    <td>{{ config('mail.from.address') }} ({{ config('mail.from.name') }})</td>
                </tr>
            </table>

            <p style="margin-bottom: 0; font-size: 13px; color: #64748B;">
                Email ini dikirim secara otomatis sebagai pengujian sistem. Tidak ada tindakan lebih lanjut yang diperlukan.
            </p>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} SKMS - Student Knowledge Management System.
        </div>
    </div>
</body>
</html>
