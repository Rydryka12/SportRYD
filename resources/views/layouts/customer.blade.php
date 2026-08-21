<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SportRYD')</title>

    <!-- Font Mazer bawaan -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <!--
        CSS Mazer (asset lokal via Laravel).
        Kalau file ini 404 / belum di-build, browser fallback ke CDN di bawah
        supaya halaman TETAP ke-style walaupun asset lokal belum beres.
        Cek: php artisan storage:link / npm run build / path public/assets kamu.
    -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

    <!-- Fallback CDN (aman dobel load, browser cache duplikat class) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Vite (Jika masih butuh JS custom) -->
    @vite(['resources/js/app.js'])

    <style>
        :root {
            --navy: #12244a; --navy-soft: #1b3060;
            --orange: #ef7d2d; --orange-dark: #d8691b;
            --bg: #f4f6fb; --text-muted: #6b7a99; --radius: 10px;
        }
        html { font-size: 13px; }
        * { font-family: 'Nunito', sans-serif; box-sizing: border-box; }
        [x-cloak] { display: none !important; }
        body { background-color: var(--bg); color: #1e2a45; font-size: 1rem; }
        a { text-decoration: none; }

        /* Navbar */
        .navbar-custom { background-color: var(--navy); padding: 10px 0; box-shadow: 0 3px 12px rgba(18,36,74,0.15); }
        .text-orange { color: var(--orange) !important; }
        .bg-orange { background-color: var(--orange) !important; color: #fff; }
        .nav-link-custom { color: rgba(255,255,255,0.7) !important; font-weight: 600; padding: .4rem .85rem !important; border-radius: 7px; transition: all 0.2s ease; font-size: .923rem; }
        .nav-link-custom:hover { color: #fff !important; background-color: rgba(255,255,255,0.07); }
        .nav-link-custom.active { color: var(--orange) !important; background-color: rgba(255,255,255,0.1); }
        .user-badge { background-color: rgba(255,255,255,0.08); border-radius: 50px; padding: 3px 12px 3px 4px; }
        .avatar-circle { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0; font-size: .85rem; }

        /* Content */
        .content-wrapper { padding-bottom: 2.5rem; }
        .page-content h1,.page-content h2,.page-content h3 { color: var(--navy); font-weight: 800; }

        /* Filter pills */
        .filter-pills { display: flex; flex-wrap: wrap; gap: .4rem; margin: 1rem 0 1.5rem; }
        .filter-pill { border: 1px solid #dfe6f5; background: #fff; color: var(--navy); font-weight: 700; font-size: .846rem; padding: .35rem .9rem; border-radius: 7px; transition: all 0.2s ease; display: inline-block; cursor: pointer; }
        .filter-pill:hover { border-color: var(--orange); color: var(--orange); }
        .filter-pill.active { background-color: var(--navy); border-color: var(--navy); color: #fff; }

        /* Lapangan card */
        .lapangan-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1rem; }
        .card-lapangan { background: #fff; border-radius: var(--radius); border: 1px solid #eef1f8; box-shadow: 0 2px 8px rgba(18,36,74,0.06); overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .card-lapangan:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(18,36,74,0.11); }
        .card-lapangan-thumb { height: 140px; background: linear-gradient(135deg, var(--navy), var(--navy-soft)); display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.85); font-size: 1.8rem; }
        .card-lapangan-body { padding: .9rem 1rem 1.1rem; flex: 1; display: flex; flex-direction: column; }
        .card-lapangan-title { font-weight: 800; color: var(--navy); font-size: .923rem; margin-bottom: .1rem; }
        .badge-tipe { display: inline-block; background: rgba(239,125,45,0.12); color: var(--orange-dark); font-weight: 700; font-size: .692rem; padding: .18rem .5rem; border-radius: 6px; margin-bottom: .5rem; width: fit-content; }
        .card-lapangan-tarif-label { font-size: .72rem; color: var(--text-muted); margin-bottom: .08rem; }
        .card-lapangan-tarif { font-weight: 800; color: var(--navy); font-size: 1rem; margin-bottom: .75rem; }
        .btn-booking { margin-top: auto; background-color: var(--orange); color: #fff; border: none; font-weight: 700; padding: .45rem .85rem; border-radius: 8px; text-align: center; transition: background-color 0.2s ease; font-size: .846rem; }
        .btn-booking:hover { background-color: var(--orange-dark); color: #fff; }

        /* Alerts */
        .alert { border-radius: var(--radius); border: none; font-size: .923rem; }
        .alert-success { background-color: #e6f7ee; color: #1a7f4b; }
        .alert-danger  { background-color: #fdecec; color: #c0392b; }

        footer { color: var(--text-muted); font-size: .846rem; }
    </style>
</head>

<body>
    <div id="app">
        <!-- Menggunakan format Layout Horizontal Mazer -->
        <div id="main" class="layout-horizontal p-0">

            <!-- Navbar -->
            <header class="mb-4">
                <nav class="navbar navbar-expand-lg navbar-custom navbar-dark">
                    <div class="container max-w-6xl">
                        <!-- Logo -->
                        <a class="navbar-brand d-flex align-items-center gap-2 m-0" href="{{ route('customer.beranda') }}">
                            <div class="avatar-circle bg-orange">S</div>
                            <span class="fw-bold fs-4">Sport<span class="text-orange">RYD</span></span>
                        </a>

                        <!-- Tombol Toggle untuk Mobile -->
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <!-- Menu & Profile (Collapsible) -->
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <!-- Link Halaman -->
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-1">
                                <li class="nav-item">
                                    <a class="nav-link nav-link-custom {{ request()->routeIs('customer.beranda') ? 'active' : '' }}" href="{{ route('customer.beranda') }}">
                                        <i class="bi bi-house-door-fill me-1"></i> Beranda
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link nav-link-custom {{ request()->routeIs('customer.riwayat') ? 'active' : '' }}" href="{{ route('customer.riwayat') }}">
                                        <i class="bi bi-clock-history me-1"></i> Riwayat & Poin
                                    </a>
                                </li>
                            </ul>

                            <!-- Bagian Kanan: User Info & Logout -->
                            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                                @auth
                                <div class="user-badge d-flex align-items-center gap-2 text-white">
                                    <div class="avatar-circle bg-orange fs-6">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                    <div class="d-flex flex-column lh-1">
                                        <span class="fs-6 fw-bold">{{ auth()->user()->name }}</span>
                                        <small class="text-orange mt-1" style="font-size: 0.75rem; font-weight: bold;">{{ auth()->user()->role }}</small>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-link text-white text-decoration-none p-0" title="Keluar">
                                        <i class="bi bi-box-arrow-right fs-4"></i>
                                    </button>
                                </form>
                                @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light fw-bold">Masuk</a>
                                <a href="{{ route('register') }}" class="btn btn-sm fw-bold" style="background:#ef7d2d;color:white;border:none;">Daftar</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            <!-- Main Content -->
            <div class="content-wrapper container max-w-6xl">
                <div class="page-content">

                    <!-- Alert Notifikasi ala Mazer -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible show fade">
                            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible show fade">
                            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Konten Dinamis -->
                    @yield('content')

                </div>
            </div>

            <!-- Footer -->
            <footer class="mt-5">
                <div class="container text-center py-4">
                    <p class="mb-0" style="font-size: 0.85rem;">&copy; {{ date('Y') }} SportRYD. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>