<!DOCTYPE html>
<html lang="tg">
<head>
    <meta charset="utf-8">
    <title>Сертификати Biyomuz Academy — {{ $certificate->certificate_code }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 40px;
            background-color: #f8fafc;
            color: #1e293b;
        }
        .cert-container {
            border: 10px solid #00875A;
            border-radius: 16px;
            padding: 40px 60px;
            background: #ffffff;
            box-sizing: border-box;
            height: 90%;
            position: relative;
            text-align: center;
        }
        .cert-inner-border {
            border: 2px dashed #10B981;
            padding: 30px;
            height: 88%;
            border-radius: 10px;
        }
        .logo-title {
            font-size: 32px;
            font-weight: bold;
            color: #00875A;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .logo-subtitle {
            font-size: 13px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-bottom: 25px;
        }
        .cert-heading {
            font-size: 26px;
            font-weight: 800;
            color: #064E3B;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .cert-text {
            font-size: 15px;
            color: #64748b;
            margin-bottom: 15px;
        }
        .student-name {
            font-size: 32px;
            font-weight: bold;
            color: #1e293b;
            border-bottom: 2px solid #00875A;
            display: inline-block;
            padding: 0 40px 8px 40px;
            margin-bottom: 20px;
        }
        .course-title {
            font-size: 22px;
            font-weight: bold;
            color: #00875A;
            margin-bottom: 25px;
        }
        .footer-table {
            width: 100%;
            margin-top: 35px;
        }
        .footer-table td {
            vertical-align: bottom;
            text-align: center;
        }
        .sig-line {
            border-top: 1px solid #94a3b8;
            margin: 0 auto;
            width: 200px;
            padding-top: 5px;
            font-size: 12px;
            color: #475569;
        }
        .badge {
            background-color: #ECFDF5;
            border: 1px solid #10B981;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 11px;
            color: #064E3B;
            display: inline-block;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="cert-container">
        <div class="cert-inner-border">
            <div class="logo-title">BIYOMUZ ACADEMY</div>
            <div class="logo-subtitle">Платформаи миллии курсҳои онлайн</div>

            <div class="cert-heading">ШАҲОДАТНОМАИ ХАТМИ КУРС</div>
            <div class="cert-text">Ин ҳуҷҷат тасдиқ менамояд, ки донишҷӯ:</div>

            <div class="student-name">{{ $enrollment->user->name }}</div>

            <div class="cert-text">бомуваффақият тамоми барномаи таълимии курси зеринро хатм намуд:</div>
            <div class="course-title">«{{ $enrollment->course->title }}»</div>

            <div>
                <span class="badge">Рамзи шаҳодатнома: {{ $certificate->certificate_code }}</span>
            </div>

            <table class="footer-table">
                <tr>
                    <td style="width: 33%;">
                        <div class="sig-line">
                            Санаи супурдан<br>
                            <strong>{{ $certificate->issued_at ? $certificate->issued_at->format('d.m.Y') : date('d.m.Y') }}</strong>
                        </div>
                    </td>
                    <td style="width: 33%;">
                        <div style="font-size: 24px; color: #00875A;">★ ★ ★</div>
                        <div style="font-size: 11px; color: #64748b;">МӮҲРИ РАҚАМӢ</div>
                    </td>
                    <td style="width: 33%;">
                        <div class="sig-line">
                            Муаллифи курс<br>
                            <strong>{{ $enrollment->course->author->name ?? 'Biyomuz Academy' }}</strong>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
