<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Diubah: Link CSS Bootstrap 4 diganti dengan Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Ditambahkan: Link untuk Bootstrap Icons (karena Anda menggunakannya) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css?family=Montserrat:500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2e7d32;
            --primary-light: #a5d6a7;
            --primary-dark: #1b5e20;
            --bg-light: #f4fdf6;
            --text-main: #2f4f2f;
            --white: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            display: flex;
            /* Menggunakan flexbox untuk layout sidebar */
        }

        /* Sidebar */
        .sidebar {
            height: 100vh;
            background: #2c3e50;
            color: var(--white);
            width: 250px;
            /* Lebar sidebar yang tetap */
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.1);
            padding-top: 30px;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li a {
            display: flex;
            /* Diubah ke flex untuk alignment ikon */
            align-items: center;
            gap: 12px;
            /* Jarak antara ikon dan teks */
            padding: 12px 24px;
            color: var(--white);
            font-weight: 500;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background-color: #34495e;
            border-left-color: #81c784;
        }

        .sidebar-footer {
            margin-top: auto;
            /* Mendorong footer ke bawah */
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            /* Memberi ruang untuk sidebar */
            padding: 20px;
            width: calc(100% - 250px);
            /* Lebar konten utama */
            min-height: 100vh;
        }

        .topbar {
            background-color: var(--white);
            padding: 15px 25px;
            margin-bottom: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(76, 175, 80, 0.12);
        }

        /* Responsive */
        @media (max-width: 992px) {
            body {
                display: block;
                /* Kembalikan ke block di layar kecil */
            }

            .sidebar {
                position: static;
                width: 100%;
                height: auto;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar/Navbar -->
        @include('layouts.navbar')

        <!-- Konten Halaman Dinamis -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Diubah: Script Bootstrap 4 diganti dengan Bootstrap 5 Bundle -->
    <!-- Bundle ini sudah termasuk Popper.js, jadi lebih ringkas -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Dihapus: jQuery dan Popper.js lama (tidak diperlukan oleh Bootstrap 5) -->

    <!-- Script untuk Chart.js (jika diperlukan) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Stack untuk script kustom dari halaman lain -->
    @stack('scripts')
</body>

</html>