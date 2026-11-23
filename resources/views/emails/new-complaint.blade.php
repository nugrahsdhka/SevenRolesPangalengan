
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>Pengaduan Baru</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f5f8; font-family:Arial, Helvetica, sans-serif; color:#263445; -webkit-font-smoothing:antialiased;">

    {{-- Preheader --}}
    <div style="display:none; max-height:0; overflow:hidden; opacity:0; color:transparent;">
        Pengaduan baru {{ $complaint->ticket_code }} untuk {{ $complaint->puskesmas }}.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
        style="background-color:#f3f5f8;">
        <tr>
            <td align="center" style="padding:36px 16px;">

                {{-- Main Container --}}
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:580px; background-color:#ffffff; border:1px solid #e3e8ef; border-radius:8px;">

                    {{-- Top Accent --}}
                    <tr>
                        <td style="height:5px; background-color:#7F1D2D; font-size:0; line-height:0; border-radius:8px 8px 0 0;">
                            &nbsp;
                        </td>
                    </tr>

                    {{-- Header --}}
                    <tr>
                        <td style="padding:30px 36px 24px 36px; border-bottom:1px solid #e9edf2;">

                            <div style="font-size:11px; font-weight:700; letter-spacing:1.3px; text-transform:uppercase; color:#64748b;">
                                RUMAH STUNTING PANGALENGAN
                            </div>

                            <h1 style="margin:20px 0 8px 0; font-size:23px; line-height:1.3; font-weight:700; color:#1F3A5F;">
                                Pengaduan Baru
                            </h1>

                            <p style="margin:0; font-size:13px; line-height:1.6; color:#64748b;">
                                Pemberitahuan pengaduan masyarakat melalui sistem Rumah Stunting Pangalengan.
                            </p>

                        </td>
                    </tr>

                    {{-- Main Content --}}
                    <tr>
                        <td style="padding:28px 36px 0 36px;">

                            <p style="margin:0 0 12px 0; font-size:14px; line-height:1.7; color:#334155;">
                                Yth. Bapak/Ibu,
                            </p>

                            @if ($audience === 'puskesmas')
                                <p style="margin:0; font-size:14px; line-height:1.8; color:#475569;">
                                    Terdapat pengaduan baru dari masyarakat yang ditujukan kepada
                                    <strong style="color:#1e293b;">
                                        {{ $complaint->puskesmas }}
                                    </strong>.
                                    Mohon untuk melakukan peninjauan dan tindak lanjut melalui aplikasi.
                                </p>
                            @else
                                <p style="margin:0; font-size:14px; line-height:1.8; color:#475569;">
                                    Terdapat pengaduan baru dari masyarakat untuk
                                    <strong style="color:#1e293b;">
                                        {{ $complaint->puskesmas }}
                                    </strong>.
                                    Pemberitahuan ini disampaikan sebagai informasi untuk keperluan pemantauan.
                                </p>
                            @endif

                        </td>
                    </tr>

                    {{-- Ticket Information --}}
                    <tr>
                        <td style="padding:26px 36px 0 36px;">

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="background-color:#faf6f7; border:1px solid #eadde0; border-radius:6px;">

                                <tr>
                                    <td style="padding:16px 18px; border-left:3px solid #7F1D2D;">

                                        <div style="margin-bottom:7px; font-size:10px; font-weight:700; letter-spacing:1px; text-transform:uppercase; color:#7F1D2D;">
                                            KODE TIKET PENGADUAN
                                        </div>

                                        <div style="font-size:19px; font-weight:700; letter-spacing:0.5px; color:#1F3A5F;">
                                            {{ $complaint->ticket_code }}
                                        </div>

                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- Complaint Details --}}
                    <tr>
                        <td style="padding:28px 36px 0 36px;">

                            <div style="margin-bottom:14px; font-size:12px; font-weight:700; letter-spacing:0.6px; text-transform:uppercase; color:#1F3A5F;">
                                Informasi Pengaduan
                            </div>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">

                                {{-- Category --}}
                                <tr>
                                    <td width="34%" valign="top"
                                        style="padding:12px 0; border-top:1px solid #e9edf2; font-size:12px; color:#64748b;">
                                        Kategori
                                    </td>
                                    <td valign="top"
                                        style="padding:12px 0; border-top:1px solid #e9edf2; font-size:13px; font-weight:600; color:#334155;">
                                        {{ $complaint->kategori_label }}
                                    </td>
                                </tr>

                                {{-- Title --}}
                                <tr>
                                    <td width="34%" valign="top"
                                        style="padding:12px 0; border-top:1px solid #e9edf2; font-size:12px; color:#64748b;">
                                        Judul
                                    </td>
                                    <td valign="top"
                                        style="padding:12px 0; border-top:1px solid #e9edf2; font-size:13px; font-weight:600; color:#334155;">
                                        {{ $complaint->judul }}
                                    </td>
                                </tr>

                                {{-- Puskesmas --}}
                                <tr>
                                    <td width="34%" valign="top"
                                        style="padding:12px 0; border-top:1px solid #e9edf2; font-size:12px; color:#64748b;">
                                        Puskesmas
                                    </td>
                                    <td valign="top"
                                        style="padding:12px 0; border-top:1px solid #e9edf2; font-size:13px; color:#334155;">
                                        {{ $complaint->puskesmas }}
                                    </td>
                                </tr>

                                {{-- Location --}}
                                <tr>
                                    <td width="34%" valign="top"
                                        style="padding:12px 0; border-top:1px solid #e9edf2; font-size:12px; color:#64748b;">
                                        Lokasi
                                    </td>
                                    <td valign="top"
                                        style="padding:12px 0; border-top:1px solid #e9edf2; font-size:13px; color:#334155;">
                                        {{ $complaint->posyandu }}, {{ $complaint->desa_kelurahan }}
                                    </td>
                                </tr>

                                {{-- Received Date --}}
                                <tr>
                                    <td width="34%" valign="top"
                                        style="padding:12px 0; border-top:1px solid #e9edf2; border-bottom:1px solid #e9edf2; font-size:12px; color:#64748b;">
                                        Waktu Diterima
                                    </td>
                                    <td valign="top"
                                        style="padding:12px 0; border-top:1px solid #e9edf2; border-bottom:1px solid #e9edf2; font-size:13px; color:#334155;">
                                        {{ $complaint->created_at?->timezone(config('app.timezone'))->format('d-m-Y H:i') }}
                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    {{-- Complaint Summary --}}
                    <tr>
                        <td style="padding:26px 36px 0 36px;">

                            <div style="margin-bottom:12px; font-size:12px; font-weight:700; letter-spacing:0.6px; text-transform:uppercase; color:#1F3A5F;">
                                Ringkasan Pengaduan
                            </div>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="background-color:#f8fafc; border:1px solid #e9edf2; border-radius:6px;">

                                <tr>
                                    <td style="padding:16px 18px; border-left:3px solid #7F1D2D; font-size:13px; line-height:1.8; color:#475569;">
                                        {{ \Illuminate\Support\Str::limit($complaint->isi, 300) }}
                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    {{-- CTA Button --}}
                    <tr>
                        <td style="padding:30px 36px 0 36px;">

                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" bgcolor="#1F3A5F"
                                        style="background-color:#1F3A5F; border-radius:5px;">

                                        <a href="{{ $url }}"
                                            style="display:inline-block; padding:13px 24px; font-size:13px; font-weight:700; letter-spacing:0.2px; color:#ffffff; text-decoration:none;">
                                            Buka Pengaduan &rarr;
                                        </a>

                                    </td>
                                </tr>
                            </table>

                            <p style="margin:13px 0 0 0; font-size:11px; line-height:1.6; color:#94a3b8;">
                                Silakan login ke aplikasi untuk melihat informasi dan detail pengaduan secara lengkap.
                            </p>

                        </td>
                    </tr>

                    {{-- Closing --}}
                    <tr>
                        <td style="padding:30px 36px; font-size:13px; line-height:1.8; color:#475569;">

                            Hormat kami,<br>
                            <strong style="color:#1e293b;">
                                Rumah Stunting Pangalengan
                            </strong>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:18px 36px; background-color:#f8fafc; border-top:1px solid #e9edf2; border-radius:0 0 8px 8px;">

                            <p style="margin:0; font-size:11px; line-height:1.7; color:#94a3b8;">
                                Email ini dikirim secara otomatis oleh sistem Rumah Stunting Pangalengan.
                                Mohon untuk tidak membalas email ini.
                            </p>

                        </td>
                    </tr>

                </table>

                {{-- Copyright --}}
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:580px;">

                    <tr>
                        <td align="center" style="padding:18px 12px; font-size:10px; color:#94a3b8;">
                            &copy; {{ date('Y') }} Rumah Stunting Pangalengan
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
