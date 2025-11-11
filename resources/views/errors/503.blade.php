<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situs dalam Perbaikan</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* CSS Reset dan Pengaturan Dasar */
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
            overflow: hidden; /* Mencegah scrollbar saat animasi */
        }

        /* * Kontainer Utama (Flexbox) */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #ffffff;
            color: #333;
            text-align: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        /* * Pengaturan Awal Elemen Animasi (BARU)
         * Semua elemen disembunyikan dulu (opacity: 0)
         * dan akan dianimasikan menggunakan 'fadeInUp'
         */
        .logo, .pre-title, h1, .divider, p {
            opacity: 0; /* Mulai dari tidak terlihat */
            animation: fadeInUp 0.7s ease-out forwards; /* Terapkan animasi */
        }

        /* * Logo */
        .logo {
            max-width: 130px;
            height: auto;
            margin-bottom: 25px;
            animation-delay: 0.2s; /* Jeda animasi #1 */
        }

        /* * Pre-Title */
        .pre-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: #E60000;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
            animation-delay: 0.4s; /* Jeda animasi #2 */
        }

        /* * Judul Utama (H1) */
        h1 {
            font-size: 2.3rem;
            font-weight: 700;
            color: #222222;
            margin-top: 0;
            margin-bottom: 20px;
            animation-delay: 0.6s; /* Jeda animasi #3 */
        }

        /* * Garis Pemisah (Divider) */
        .divider {
            width: 70px;
            height: 3px;
            background-color: #E60000;
            border: 0;
            margin: 0 auto 25px auto;
            animation-delay: 0.8s; /* Jeda animasi #4 */
        }

        /* * Paragraf Penjelas */
        p {
            font-size: 1.1rem;
            color: #444;
            margin-bottom: 0;
            animation-delay: 1.0s; /* Jeda animasi #5 */
        }

        /* * Keyframes Animasi 'fadeInUp' (BARU)
         * Bergerak dari bawah (20px) dan transparan (0)
         * ke posisi asli (0px) dan terlihat (1)
         */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* * Media Query untuk Perangkat Seluler (Responsif) */
        @media (max-width: 480px) {
            .pre-title {
                font-size: 0.8rem;
            }
            h1 {
                font-size: 1.9rem;
            }
            .logo {
                max-width: 110px;
            }
            p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        
        <img src="img/logo-kab-bandung.png" alt="Logo Kabupaten Bandung" class="logo">
        
        <p class="pre-title">Rumah Stunting Pangalengan</p>

        <h1>Situs Sedang Dalam Perbaikan</h1>

        <hr class="divider">

        <p>
            Kami sedang melakukan pembaruan untuk meningkatkan layanan.
            <br>
            Mohon maaf atas ketidaknyamanannya.
        </p>

    </div>

</body>
</html>