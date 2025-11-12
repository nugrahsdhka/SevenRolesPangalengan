<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Reset Password - Rumah Stunting Pangalengan</title>
</head>

<body
    style="margin:0;padding:0;background:#f3f4f6;font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:24px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:600px;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,0.06);">
                    {{-- Header merah --}}
                    <tr>
                        <td
                            style="background:linear-gradient(135deg,#b91c1c,#dc2626,#ef4444);padding:20px 24px;color:#fff;">
                            <h1 style="margin:0;font-size:20px;font-weight:700;">
                                Rumah Stunting Pangalengan
                            </h1>
                            <p style="margin:4px 0 0;font-size:12px;opacity:0.9;">
                                Permintaan reset password akun admin
                            </p>
                        </td>
                    </tr>

                    {{-- Isi utama --}}
                    <tr>
                        <td style="padding:24px 24px 8px;color:#111827;font-size:14px;line-height:1.6;">
                            <p style="margin:0 0 8px;">Halo, <strong>{{ $user->name }}</strong> 👋</p>
                            <p style="margin:0 0 8px;">
                                Kami menerima permintaan untuk mengatur ulang password akun admin
                                <strong>Rumah Stunting Pangalengan</strong> yang terdaftar dengan email:
                                <strong>{{ $user->email }}</strong>.
                            </p>
                            <p style="margin:0 0 16px;">
                                Jika benar ini Anda, silakan klik tombol di bawah untuk mengatur ulang password:
                            </p>

                            {{-- Tombol CTA --}}
                            <p style="margin:0 0 24px;text-align:center;">
                                <a href="{{ $url }}"
                                    style="display:inline-block;padding:10px 22px;border-radius:999px;background:#dc2626;color:#fff;text-decoration:none;font-size:14px;font-weight:600;">
                                    Atur Ulang Password
                                </a>
                            </p>

                            <p style="margin:0 0 8px;font-size:12px;color:#4b5563;">
                                Tautan ini hanya berlaku selama <strong>60 menit</strong> sejak email ini dikirim.
                            </p>
                            <p style="margin:0 0 8px;font-size:12px;color:#6b7280;">
                                Jika Anda tidak merasa meminta reset password, Anda dapat mengabaikan email ini dan
                                password Anda akan tetap aman.
                            </p>

                            {{-- URL plain (fallback) --}}
                            <p style="margin:16px 0 0;font-size:11px;color:#9ca3af;">
                                Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:
                                <br>
                                <span style="word-break:break-all;color:#dc2626;">{{ $url }}</span>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:16px 24px 20px;border-top:1px solid #e5e7eb;background:#f9fafb;">
                            <p style="margin:0;font-size:11px;color:#9ca3af;text-align:center;">
                                © {{ date('Y') }} Rumah Stunting Pangalengan. Semua hak dilindungi.
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>

</html>
