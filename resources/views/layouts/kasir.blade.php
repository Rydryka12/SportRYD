<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kasir - SportRYD</title>

    <link rel="stylesheet" crossorigin href="{{ asset('Mazer/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('Mazer/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('Mazer/assets/compiled/css/iconly.css') }}">
</head>

<body>
    <script src="{{ asset('Mazer/assets/static/js/initTheme.js') }}"></script>
    <div id="app">
        <div id="sidebar">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header position-relative">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="logo">
                            <a href="{{ route('kasir.booking.index') }}">
                                <h1 class="">Sport<span>RYD</span></h1>
                            </a>
                        </div>
                        <div class="sidebar-toggler x">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu Kasir</li>

                        <li class="sidebar-item {{ request()->routeIs('kasir.booking.index') ? 'active' : '' }}">
                            <a href="{{ route('kasir.booking.index') }}" class='sidebar-link'>
                                <i class="bi bi-cash-coin"></i>
                                <span>Booking & Pembayaran</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-arrow-repeat"></i>
                                <span>Reschedule</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <form method="POST" action="#">
                                @csrf
                                <button type="submit" class="sidebar-link border-0 bg-transparent w-100 text-start">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="main">
            <header class="mb-3 d-flex justify-content-between align-items-center">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
                <span class="ms-3 fw-semibold">{{ auth()->user()->name }} <span class="badge bg-secondary">Kasir</span></span>
            </header>

            @yield('content')

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>SportRYD &copy; {{ date('Y') }}</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="{{ asset('Mazer/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('Mazer/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('Mazer/assets/compiled/js/app.js') }}"></script>

    <style>
        #sidebar { background: #ffffff !important; }
        #sidebar .sidebar-wrapper,
        #sidebar .sidebar-header,
        #sidebar .sidebar-menu,
        #sidebar .sidebar-menu .submenu { background: #ffffff !important; }
        #sidebar .sidebar-header { border-bottom: 1px solid #e9ecef !important; }
        #sidebar .sidebar-header .logo a { color: #12244a !important; text-decoration: none; }
        #sidebar .sidebar-header .logo h1 {
            margin: 0 !important; padding: 0 !important; font-size: 20px !important;
            line-height: 1 !important; font-weight: 600 !important; letter-spacing: -0.5px; color: #12244a !important;
        }
        #sidebar .sidebar-header .logo h1 span { font-size: inherit !important; font-weight: 700 !important; color: #ef7d2d !important; }
        #sidebar .sidebar-title { color: #7c8495 !important; }
        #sidebar .sidebar-menu .sidebar-link { color: #12244a !important; background: transparent !important; }
        #sidebar .sidebar-menu .sidebar-link i { color: #12244a !important; }
        #sidebar .sidebar-menu .sidebar-link:hover { background: rgba(18, 36, 74, 0.08) !important; color: #12244a !important; }
        #sidebar .sidebar-menu .sidebar-item.active > .sidebar-link {
            background: #ef7d2d !important; color: #ffffff !important; border-radius: 12px !important;
            margin-left: 16px !important; margin-right: 16px !important;
        }
        #sidebar .sidebar-menu .sidebar-item.active > .sidebar-link i { color: #ffffff !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>