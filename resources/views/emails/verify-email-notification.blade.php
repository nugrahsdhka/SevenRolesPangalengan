<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Token Verifikasi Email - Rumah Stunting Pangalengan</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:24px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0"
                       style="max-width:600px;background:#ffffff;border-radius:16px;overflow:hidden;
                              box-shadow:0 10px 40px rgba(0,0,0,0.06);">

                    {{-- Header merah --}}
                    <tr>
                        <td style="background:linear-gradient(135deg,#b91c1c,#dc2626,#ef4444);
                                   padding:20px 24px;color:#fff;">
                            <h1 style="margin:0;font-size:20px;font-weight:700;">
                                Rumah Stunting Pangalengan
                            </h1>
                            <p style="margin:4px 0 0;font-size:12px;opacity:0.9;">
                                Token verifikasi email akun Anda
                            </p>
                        </td>
                    </tr>

                    {{-- Isi utama --}}
                    <tr>
                        <td style="padding:24px 24px 8px;color:#111827;font-size:14px;line-height:1.6;">
                            <p style="margin:0 0 8px;">Halo, <strong>{{ $user->name }}</strong> 👋</p>
                            <p style="margin:0 0 8px;">
                                Terima kasih telah mendaftar di
                                <strong>Rumah Stunting Pangalengan</strong>.
                            </p>
                            <p style="margin:0 0 12px;">
                                Untuk menyelesaikan proses pendaftaran, silakan masukkan
                                <strong>kode verifikasi</strong> berikut pada halaman verifikasi di website:
                            </p>

                            {{-- BOX TOKEN --}}
                            <div style="margin:16px 0 20px;text-align:center;">
                                <div style="display:inline-block;padding:12px 24px;border-radius:999px;
                                            background:#fef2f2;border:1px dashed #fecaca;">
                                    <span style="letter-spacing:4px;font-size:22px;font-weight:700;color:#b91c1c;">
                                        {{ $token }}
                                    </span>
                                </div>
                            </div>

                            <p style="margin:0 0 8px;font-size:12px;color:#4b5563;">
                                Kode ini hanya berlaku selama 3 menit.
                            </p>
                            <p style="margin:0 0 8px;font-size:12px;color:#6b7280;">
                                Jika Anda tidak merasa mendaftar, abaikan email ini.
                                Akun Anda tidak akan aktif tanpa memasukkan kode verifikasi tersebut.
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
