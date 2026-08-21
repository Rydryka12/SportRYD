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
                                <h1>Sport<span>RYD</span></h1>
                            </a>
                        </div>
                        <div class="theme-toggle d-flex gap-2 align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="15" height="15" viewBox="0 0 21 21" class="d-none d-xl-inline-block">
                                <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2" opacity=".3"></path>
                                    <g transform="translate(-210 -1)"><path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path><circle cx="220.5" cy="11.5" r="4"></circle><path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path></g>
                                </g>
                            </svg>
                            <div class="form-check form-switch mb-0" style="padding:0;margin:0;display:flex;align-items:center;">
                                <input class="form-check-input me-0" type="checkbox" id="toggle-dark" style="cursor:pointer;margin:0;float:none;vertical-align:middle;">
                                <label class="form-check-label"></label>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="15" height="15" viewBox="0 0 24 24" class="d-none d-xl-inline-block">
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
                        <li class="sidebar-item {{ request()->routeIs('kasir.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('kasir.dashboard') }}" class="sidebar-link">
                                <i class="bi bi-speedometer2"></i><span>Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('kasir.booking.*') ? 'active' : '' }}">
                            <a href="{{ route('kasir.booking.index') }}" class="sidebar-link">
                                <i class="bi bi-cash-coin"></i><span>Booking & Pembayaran</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('kasir.laporan.index') ? 'active' : '' }}">
                            <a href="{{ route('kasir.laporan.index') }}" class="sidebar-link">
                                <i class="bi bi-bar-chart-fill"></i><span>Laporan Harian</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ request()->routeIs('kasir.reschedule.*') ? 'active' : '' }}">
                            <a href="{{ route('kasir.reschedule.index') }}" class="sidebar-link">
                                <i class="bi bi-arrow-repeat"></i><span>Reschedule</span>
                            </a>
                        </li>
                    </ul>

                    <div class="sidebar-account">
                        <div class="account-info">
                            <div class="account-avatar">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="account-details">
                                <div class="account-name">{{ auth()->user()->nama ?? auth()->user()->name }}</div>
                                <div class="account-role">Kasir</div>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <i class="bi bi-box-arrow-right"></i><span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="main">
            <header class="mb-0" style="min-height:0;padding:0;background:transparent;border:none;">
                <a href="#" class="burger-btn d-block d-xl-none" style="padding:.6rem .9rem;">
                    <i class="bi bi-justify fs-4"></i>
                </a>
            </header>

            @yield('content')

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start"><p>SportRYD &copy; {{ date('Y') }}</p></div>
                </div>
            </footer>
        </div>
    </div>

    <script src="{{ asset('Mazer/assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('Mazer/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('Mazer/assets/compiled/js/app.js') }}"></script>

    <style>
        /* Kasir sidebar — identik token dengan admin */
        :root {
            --sr-primary: #ef7d2d; --sr-navy: #12244a;
            --sr-border: #e9ecef; --sr-hover: rgba(18,36,74,0.07);
            --sr-bg: #f4f6fb; --sr-radius: 8px; --sr-radius-lg: 12px; --sr-radius-sm: 6px;
            --sr-surface-dk: #12244a; --sr-bg-dk: #0e1a33;
            --sr-text-dk: #d4daea; --sr-border-dk: rgba(255,255,255,0.11);
            --sr-hover-dk: rgba(255,255,255,0.07);
        }
        html { font-size: 13px; }
        body { font-size: 1rem !important; background: var(--sr-bg) !important; }

        .btn, button.btn, a.btn { border-radius: var(--sr-radius) !important; font-size: 0.923rem !important; }
        .btn-sm  { padding: .3rem .65rem !important; font-size: 0.846rem !important; }
        .form-control, .form-select, textarea { border-radius: var(--sr-radius) !important; font-size: 0.923rem !important; }
        .card { border-radius: var(--sr-radius-lg) !important; }
        .badge { border-radius: var(--sr-radius-sm) !important; padding: .28rem .55rem !important; font-size: 0.769rem !important; font-weight: 600 !important; display: inline-flex !important; align-items: center !important; }
        .modal-content { border-radius: var(--sr-radius-lg) !important; }
        .alert { border-radius: var(--sr-radius) !important; }
        .page-link { border-radius: var(--sr-radius-sm) !important; }
        .table { font-size: 0.923rem !important; }
        .card-body { padding: 1rem !important; }
        .card-header { padding: .7rem 1rem !important; background: transparent !important; border-bottom: 1px solid var(--sr-border) !important; }
        .bi { display: inline-flex !important; align-items: center !important; justify-content: center !important; line-height: 1 !important; }

        #main > header { min-height: 0 !important; padding: 0 !important; background: transparent !important; border: none !important; box-shadow: none !important; margin-bottom: 0 !important; }
        @media (min-width: 1200px) { #main > header { display: none !important; } }

        #sidebar, #sidebar .sidebar-wrapper, #sidebar .sidebar-header, #sidebar .sidebar-menu { background: #fff !important; }
        #sidebar .sidebar-wrapper { display: flex; flex-direction: column; height: 100%; }
        #sidebar .sidebar-header { border-bottom: 1px solid var(--sr-border) !important; padding: .55rem .85rem !important; }
        #sidebar .sidebar-header .d-flex { flex-wrap: nowrap !important; gap: .4rem !important; align-items: center !important; }
        #sidebar .sidebar-header .logo { flex-shrink: 0; }
        #sidebar .sidebar-header .logo a { text-decoration: none !important; }
        #sidebar .sidebar-header .logo h1 { margin: 0 !important; padding: 0 !important; font-size: 1rem !important; font-weight: 700 !important; line-height: 1 !important; color: var(--sr-navy) !important; }
        #sidebar .sidebar-header .logo h1 span { color: var(--sr-primary) !important; }

        .theme-toggle { display: flex !important; align-items: center !important; gap: .4rem !important; flex-shrink: 0 !important; margin-right: auto !important; }
        @media (min-width: 1200px) { .theme-toggle { margin-right: 0 !important; } }
        .theme-toggle svg { width: 16px !important; height: 16px !important; color: var(--sr-primary) !important; }
        #toggle-dark { width: 40px !important; height: 22px !important; cursor: pointer !important; background-color: #ced4da !important; border: 2px solid #ced4da !important; box-shadow: none !important; transition: all .2s !important; flex-shrink: 0 !important; border-radius: 22px !important; }
        #toggle-dark:checked { background-color: var(--sr-primary) !important; border-color: var(--sr-primary) !important; }
        #toggle-dark:focus   { border-color: var(--sr-primary) !important; box-shadow: 0 0 0 .15rem rgba(239,125,45,.25) !important; outline: none !important; }
        .sidebar-toggler.x { flex-shrink: 0 !important; margin-left: .2rem !important; }
        .sidebar-toggler.x .sidebar-hide { display: flex !important; align-items: center !important; justify-content: center !important; width: 34px !important; height: 34px !important; border-radius: 8px !important; font-size: 1.3rem !important; line-height: 1 !important; color: #6b7280 !important; background: rgba(0,0,0,0.05) !important; transition: background .15s !important; }
        .sidebar-toggler.x .sidebar-hide:hover { background: rgba(0,0,0,0.1) !important; color: #374151 !important; }

        #sidebar .sidebar-menu { flex: 1 !important; overflow-y: auto !important; display: flex !important; flex-direction: column !important; }
        #sidebar .sidebar-menu > ul { flex: 1 !important; }
        #sidebar .sidebar-title { color: #8a93a8 !important; font-size: .65rem !important; text-transform: uppercase !important; letter-spacing: .5px !important; padding: .7rem 1rem .2rem !important; }
        #sidebar .sidebar-menu .sidebar-link { color: var(--sr-navy) !important; background: transparent !important; font-size: .823rem !important; padding: .5rem .8rem !important; border-radius: var(--sr-radius) !important; margin: 0 10px 2px 10px !important; transition: background .15s !important; }
        #sidebar .sidebar-menu .sidebar-link i { color: var(--sr-navy) !important; font-size: .85rem !important; }
        #sidebar .sidebar-menu .sidebar-link:hover { background: var(--sr-hover) !important; }
        #sidebar .sidebar-menu .sidebar-item.active > .sidebar-link { background: var(--sr-primary) !important; color: #fff !important; }
        #sidebar .sidebar-menu .sidebar-item.active > .sidebar-link i { color: #fff !important; }

        .sidebar-account { padding: .65rem !important; border-top: 1px solid var(--sr-border) !important; flex-shrink: 0 !important; }
        .account-info { display: flex !important; align-items: center !important; gap: .55rem !important; padding: .55rem .65rem !important; background: rgba(239,125,45,.08) !important; border-radius: var(--sr-radius) !important; }
        .account-avatar { width: 30px !important; height: 30px !important; min-width: 30px !important; display: flex !important; align-items: center !important; justify-content: center !important; background: var(--sr-primary) !important; border-radius: 50% !important; flex-shrink: 0 !important; overflow: hidden !important; }
        .account-avatar i { font-size: .95rem !important; color: #fff !important; line-height: 1 !important; }
        .account-details { flex: 1 !important; min-width: 0 !important; }
        .account-name { font-size: .8rem !important; font-weight: 600 !important; color: var(--sr-navy) !important; white-space: nowrap !important; overflow: hidden !important; text-overflow: ellipsis !important; line-height: 1.2 !important; }
        .account-role { font-size: .65rem !important; color: #8a93a8 !important; line-height: 1.2 !important; }
        .btn-logout { width: 100% !important; margin-top: .4rem !important; padding: .4rem !important; background: transparent !important; border: 1px solid var(--sr-border) !important; border-radius: var(--sr-radius) !important; color: var(--sr-navy) !important; font-size: .8rem !important; display: flex !important; align-items: center !important; justify-content: center !important; gap: .35rem !important; cursor: pointer !important; transition: all .2s !important; }
        .btn-logout:hover { background: rgba(220,53,69,.1) !important; border-color: #dc3545 !important; color: #dc3545 !important; }

        /* DARK MODE */
        html[data-bs-theme="dark"] body, html[data-bs-theme="dark"] #main { background: var(--sr-bg-dk) !important; }
        html[data-bs-theme="dark"] #sidebar,
        html[data-bs-theme="dark"] #sidebar .sidebar-wrapper,
        html[data-bs-theme="dark"] #sidebar .sidebar-header,
        html[data-bs-theme="dark"] #sidebar .sidebar-menu { background: var(--sr-surface-dk) !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-header { border-bottom-color: var(--sr-border-dk) !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-header .logo h1 { color: #fff !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-title { color: rgba(255,255,255,.42) !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-menu .sidebar-link { color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-menu .sidebar-link i { color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] #sidebar .sidebar-menu .sidebar-link:hover { background: var(--sr-hover-dk) !important; color: #fff !important; }
        html[data-bs-theme="dark"] .sidebar-toggler.x .sidebar-hide { color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] .sidebar-account { border-top-color: var(--sr-border-dk) !important; }
        html[data-bs-theme="dark"] .account-info { background: rgba(239,125,45,.13) !important; }
        html[data-bs-theme="dark"] .account-name { color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] .account-role { color: rgba(212,218,234,.55) !important; }
        html[data-bs-theme="dark"] .btn-logout { border-color: var(--sr-border-dk) !important; color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] .btn-logout:hover { background: rgba(220,53,69,.15) !important; border-color: #dc3545 !important; color: #dc3545 !important; }
        html[data-bs-theme="dark"] h1,html[data-bs-theme="dark"] h2,html[data-bs-theme="dark"] h3,
        html[data-bs-theme="dark"] h4,html[data-bs-theme="dark"] h5,html[data-bs-theme="dark"] h6 { color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] p, html[data-bs-theme="dark"] label,
        html[data-bs-theme="dark"] td, html[data-bs-theme="dark"] th { color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] .text-muted { color: rgba(212,218,234,.55) !important; }
        html[data-bs-theme="dark"] .card { background: var(--sr-surface-dk) !important; border-color: var(--sr-border-dk) !important; color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] .card-header { border-bottom-color: var(--sr-border-dk) !important; color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] .table { color: var(--sr-text-dk) !important; border-color: var(--sr-border-dk) !important; }
        html[data-bs-theme="dark"] .table thead th { color: var(--sr-text-dk) !important; border-color: var(--sr-border-dk) !important; background: transparent !important; }
        html[data-bs-theme="dark"] .table tbody td { border-color: var(--sr-border-dk) !important; }
        html[data-bs-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > * { background: rgba(255,255,255,.04) !important; color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] .form-control,
        html[data-bs-theme="dark"] .form-select,
        html[data-bs-theme="dark"] textarea { background: rgba(255,255,255,.05) !important; border-color: var(--sr-border-dk) !important; color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] .form-control:focus,
        html[data-bs-theme="dark"] .form-select:focus { background: rgba(255,255,255,.08) !important; border-color: var(--sr-primary) !important; color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] .form-control::placeholder { color: rgba(212,218,234,.4) !important; }
        html[data-bs-theme="dark"] .form-label { color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] .alert-success  { background: rgba(25,135,84,.2) !important;  border-color: rgba(25,135,84,.4) !important;  color: #75dba8 !important; }
        html[data-bs-theme="dark"] .alert-danger   { background: rgba(220,53,69,.2) !important;   border-color: rgba(220,53,69,.4) !important;   color: #f08090 !important; }
        html[data-bs-theme="dark"] .alert-warning  { background: rgba(255,193,7,.15) !important;  border-color: rgba(255,193,7,.35) !important;  color: #ffc107 !important; }
        html[data-bs-theme="dark"] .modal-content  { background: var(--sr-surface-dk) !important; border-color: var(--sr-border-dk) !important; color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] .modal-header,
        html[data-bs-theme="dark"] .modal-footer   { border-color: var(--sr-border-dk) !important; }
        html[data-bs-theme="dark"] .btn-close { filter: invert(1) !important; }
        html[data-bs-theme="dark"] .page-link { background: var(--sr-surface-dk) !important; border-color: var(--sr-border-dk) !important; color: var(--sr-text-dk) !important; }
        html[data-bs-theme="dark"] .page-item.active .page-link { background: var(--sr-primary) !important; border-color: var(--sr-primary) !important; color: #fff !important; }
        html[data-bs-theme="dark"] hr { border-color: var(--sr-border-dk) !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
