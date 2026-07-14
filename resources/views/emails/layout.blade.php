<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Cendekia LMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1F2937;
            background-color: #F3F4F6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .email-wrapper {
            background-color: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #002B6B 0%, #001A40 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .email-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .email-header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 24px;
            font-weight: 500;
        }
        .content-section {
            margin-bottom: 28px;
        }
        .content-section h2 {
            font-size: 18px;
            font-weight: 600;
            color: #002B6B;
            margin-bottom: 12px;
        }
        .info-box {
            background-color: #F9FAFB;
            border-left: 4px solid #002B6B;
            padding: 16px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .info-box p {
            margin: 8px 0;
            font-size: 14px;
        }
        .info-box strong {
            color: #002B6B;
        }
        .cta-button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(90deg, #002B6B 0%, #001A40 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: filter 0.2s;
            margin-top: 16px;
        }
        .cta-button:hover {
            filter: brightness(1.1);
        }
        .section-divider {
            border-top: 1px solid #E5E7EB;
            margin: 32px 0;
        }
        .footer-section {
            background-color: #F9FAFB;
            padding: 24px 30px;
            text-align: center;
            font-size: 12px;
            color: #6B7280;
            border-top: 1px solid #E5E7EB;
        }
        .footer-section p {
            margin: 6px 0;
        }
        .footer-logo {
            font-size: 24px;
            font-weight: 800;
            color: #002B6B;
            margin-bottom: 8px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            background-color: #DDD6FE;
            color: #4F46E5;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 12px;
        }
        .highlight {
            background-color: #FEF08A;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }
        table th {
            background-color: #F3F4F6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #E5E7EB;
            font-size: 13px;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 14px;
        }
        .text-center {
            text-align: center;
        }
        .text-muted {
            color: #6B7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            @yield('content')
            
            <div class="footer-section">
                <div class="footer-logo">Cendekia</div>
                <p>Platform Pembelajaran Digital</p>
                <p class="text-muted" style="margin-top: 12px;">
                    Anda menerima email ini karena terdaftar sebagai {{ $role ?? 'pengguna' }} di Cendekia LMS.
                </p>
                <p class="text-muted" style="font-size: 11px; margin-top: 8px;">
                    © {{ date('Y') }} Cendekia LMS. Semua hak dilindungi.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
