<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situs dalam Perbaikan</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

    <style>
        /* CSS Reset dan Pengaturan Dasar */
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.6;
        }

        /* * Kontainer Utama (Flexbox)
         * Mengatur konten agar selalu di tengah (vertikal dan horizontal)
         */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #ffffff; /* Latar belakang PUTIH bersih */
            color: #333;
            text-align: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .container {
            max-width: 600px;
            width: 100%;
        }

        /* * Logo
         * Ukuran diatur agar tidak terlalu dominan
         */
        .logo {
            max-width: 150px; /* Anda bisa sesuaikan ukuran logo di sini */
            height: auto;
            margin-bottom: 30px;
        }

        /* * Judul Utama (H1)
         * Menggunakan warna MERAH sebagai aksen utama
         */
        h1 {
            font-size: 2.2rem; /* Ukuran font besar dan jelas */
            font-weight: 600; /* Sedikit tebal, tapi tetap elegan */
            color: #E60000; /* Warna MERAH yang kuat dan bersih */
            margin-top: 0;
            margin-bottom: 20px;
        }

        /* * Garis Pemisah (Divider)
         * Aksen MERAH kecil untuk sentuhan elegan
         */
        .divider {
            width: 70px;
            height: 3px;
            background-color: #E60000; /* Warna MERAH yang sama */
            border: 0;
            margin: 0 auto 25px auto; /* Otomatis di tengah */
        }

        /* * Paragraf Penjelas
         * Warna abu-abu tua agar mudah dibaca tapi tidak sekeras hitam pekat
         */
        p {
            font-size: 1.1rem;
            color: #444;
            margin-bottom: 0;
        }

        /* * Media Query untuk Perangkat Seluler (Responsif)
         * Membuat font dan logo sedikit lebih kecil di layar HP
         */
        @media (max-width: 480px) {
            h1 {
                font-size: 1.8rem;
            }
            .logo {
                max-width: 120px;
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