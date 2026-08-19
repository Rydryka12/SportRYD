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
                        <div class="theme-toggle d-flex gap-2 align-items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                                role="img" class="iconify iconify--system-uicons" width="20" height="20"
                                preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path>
                                    <g transform="translate(-210 -1)">
                                        <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                        <circle cx="220.5" cy="11.5" r="4"></circle>
                                        <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                                    </g>
                                </g>
                            </svg>
                            <div class="form-check form-switch fs-6">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                                role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet"
                                viewBox="0 0 24 24">
                                <path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z"></path>
                            </svg>
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

                        <li class="sidebar-item {{ request()->routeIs('kasir.laporan.index') ? 'active' : '' }}">
                            <a href="{{ route('kasir.laporan.index') }}" class='sidebar-link'>
                                <i class="bi bi-bar-chart-fill"></i>
                                <span>Laporan Harian</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('kasir.reschedule.index') ? 'active' : '' }}">
                            <a href="{{ route('kasir.reschedule.index') }}" class='sidebar-link'>
                                <i class="bi bi-arrow-repeat"></i>
                                <span>Reschedule</span>
                            </a>
                        </li>

                        <li class="sidebar-title">Akun</li>

                        <li class="sidebar-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="sidebar-link border-0 bg-transparent w-100 text-start" style="cursor:pointer;">
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
        /* ===== LIGHT MODE ===== */
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
        #sidebar .sidebar-menu .sidebar-link:hover i { color: #12244a !important; }
        #sidebar .sidebar-menu .sidebar-item.active > .sidebar-link {
            background: #ef7d2d !important; color: #ffffff !important; border-radius: 12px !important;
            margin-left: 16px !important; margin-right: 16px !important;
        }
        #sidebar .sidebar-menu .sidebar-item.active > .sidebar-link i { color: #ffffff !important; }

        /* ===== DARK MODE ===== */
        html[data-bs-theme="dark"] #sidebar,
        html[data-bs-theme="dark"] #sidebar .sidebar-wrapper,
        html[data-bs-theme="dark"] #sidebar .sidebar-header,
        html[data-bs-theme="dark"] #sidebar .sidebar-menu,
        html[data-bs-theme="dark"] #sidebar .submenu { background: #12244a !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-header { border-bottom: 1px solid rgba(255,255,255,0.12) !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-header .logo a { color: #ffffff !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-header .logo h1 { color: #ffffff !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-header .logo h1 span { color: #ef7d2d !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-title { color: rgba(255,255,255,0.55) !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-menu .sidebar-link { color: #d9deea !important; background: transparent !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-menu .sidebar-link i { color: #d9deea !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-menu .sidebar-link:hover { background: rgba(255,255,255,0.08) !important; color: #ffffff !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-menu .sidebar-link:hover i { color: #ffffff !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-menu .sidebar-item.active > .sidebar-link { background: #ef7d2d !important; color: #ffffff !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-menu .sidebar-item.active > .sidebar-link i { color: #ffffff !important; }

        /* ===== TOGGLE SWITCH ===== */
        #toggle-dark {
            cursor: pointer !important;
            width: 48px !important; height: 24px !important;
            background-color: #e9ecef !important; border: 2px solid #e9ecef !important;
            box-shadow: none !important; transition: all 0.2s ease-in-out !important;
        }
        #toggle-dark:checked { background-color: #ef7d2d !important; border-color: #ef7d2d !important; }
        #toggle-dark:focus { border-color: #ef7d2d !important; box-shadow: 0 0 0 0.2rem rgba(239,125,45,0.25) !important; }
        #toggle-dark:hover { border-color: #ef7d2d !important; }
        .theme-toggle svg { color: #ef7d2d !important; }
        html[data-bs-theme="dark"] .theme-toggle svg { color: #ef7d2d !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>