@php
    $user = $user ?? null;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Akun</title>
</head>
<body>
    <p>Halo {{ $user?->name ?? 'Pengguna' }},</p>

    <p>Berikut adalah <strong>kode verifikasi</strong> untuk mengaktifkan akun Rumah Stunting Pangalengan Anda:</p>

    <h2 style="font-size: 24px; letter-spacing: 4px; text-align:center;">
        {{ $token }}
    </h2>

    <p>Kode ini berlaku selama <strong>3 menit</strong> sejak email ini dikirim.</p>

    <p>Jika Anda tidak merasa sedang melakukan proses verifikasi, abaikan email ini.</p>

    <p>Salam,<br>Rumah Stunting Pangalengan</p>
</body>
</html>
