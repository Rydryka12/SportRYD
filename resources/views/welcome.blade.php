<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SportRYD — Booking Lapangan Olahraga</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        * { font-family: 'Nunito', sans-serif; box-sizing: border-box; }
        html { font-size: 13px; }
        body { background-color: #f4f6fb; color: #1e2a45; font-size: 1rem; }
        [x-cloak] { display: none !important; }

        /* ── Navbar ── */
        .navbar-landing {
            background-color: #12244a;
            padding: 10px 0;
            box-shadow: 0 3px 12px rgba(18,36,74,.15);
        }
        .nav-btn-login {
            border: 1.5px solid rgba(255,255,255,.45);
            color: white;
            border-radius: 8px;
            padding: .35rem 1rem;
            font-weight: 700;
            font-size: 0.846rem;
            transition: all .2s;
            background: transparent;
        }
        .nav-btn-login:hover {
            background: rgba(255,255,255,.1);
            border-color: white;
            color: white;
        }
        .nav-btn-register {
            background-color: #ef7d2d;
            color: white;
            border: none;
            border-radius: 8px;
            padding: .35rem 1rem;
            font-weight: 700;
            font-size: 0.846rem;
            transition: all .2s;
        }
        .nav-btn-register:hover {
            background-color: #d8691b;
            color: white;
            transform: translateY(-1px);
        }

        /* ── Hero ── */
        .hero-section {
            background: radial-gradient(circle at center top, #1e293b 0%, #0f172a 100%);
            padding: 4.5rem 1.5rem 4rem;
            text-align: center;
        }
        .hero-badge {
            display: inline-block;
            background-color: rgba(239,125,45,.12);
            color: #ef7d2d;
            border: 1px solid rgba(239,125,45,.25);
            border-radius: 50px;
            padding: .3rem 1rem;
            font-size: .769rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
        }
        .hero-title {
            font-size: clamp(1.6rem, 4vw, 2.6rem);
            font-weight: 900;
            color: white;
            line-height: 1.2;
            margin-bottom: .85rem;
        }
        .hero-subtitle {
            color: #94a3b8;
            font-size: 0.923rem;
            max-width: 560px;
            margin: 0 auto 2rem;
            line-height: 1.7;
        }
        .hero-cta-group { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; }
        .btn-cta-primary {
            background-color: #ef7d2d;
            color: white;
            border: none;
            border-radius: 9px;
            padding: .6rem 1.75rem;
            font-weight: 800;
            font-size: 0.923rem;
            transition: all .2s;
        }
        .btn-cta-primary:hover {
            background-color: #d8691b;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(239,125,45,.4);
        }
        .btn-cta-secondary {
            background: rgba(255,255,255,.08);
            color: white;
            border: 1.5px solid rgba(255,255,255,.3);
            border-radius: 9px;
            padding: .6rem 1.75rem;
            font-weight: 700;
            font-size: 0.923rem;
            transition: all .2s;
        }
        .btn-cta-secondary:hover {
            background: rgba(255,255,255,.15);
            color: white;
            border-color: white;
        }

        /* ── Stats bar ── */
        .stats-bar { background: white; border-bottom: 1px solid #e9ecef; padding: 1.25rem 0; }
        .stat-item { text-align: center; }
        .stat-number { font-size: 1.4rem; font-weight: 900; color: #12244a; line-height: 1.1; }
        .stat-label { font-size: .692rem; color: #6b7280; font-weight: 600; }

        /* ── Fitur ── */
        .fitur-card {
            background: white;
            border-radius: 10px;
            border: 1px solid #eef0f2;
            padding: 1.25rem;
            height: 100%;
            transition: transform .2s, box-shadow .2s;
        }
        .fitur-card:hover { transform: translateY(-3px); box-shadow: 0 8px 18px rgba(18,36,74,.08); }
        .fitur-icon {
            width: 44px; height: 44px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            margin-bottom: .75rem;
        }
        .fitur-card h6 { font-size: .923rem !important; }
        .fitur-card p  { font-size: .846rem !important; }

        /* ── Olahraga cards ── */
        .sport-card {
            background: white;
            border-radius: 10px;
            border: 1px solid #eef0f2;
            padding: 1.25rem .85rem;
            text-align: center;
            transition: transform .2s, box-shadow .2s;
        }
        .sport-card:hover { transform: translateY(-2px); box-shadow: 0 6px 14px rgba(18,36,74,.08); }
        .sport-emoji { font-size: 2rem; margin-bottom: .4rem; }
        .sport-card .fw-bold { font-size: .923rem !important; }
        .sport-card .text-muted { font-size: .769rem !important; }

        /* ── CTA bottom ── */
        .cta-bottom {
            background: linear-gradient(135deg, #12244a 0%, #1b3060 100%);
            border-radius: 12px;
            padding: 3rem 1.5rem;
            text-align: center;
        }

        /* ── Section ── */
        .section-title { font-size: 1.5rem; font-weight: 900; color: #12244a; }
        .section-subtitle { color: #6b7280; font-size: .923rem; max-width: 500px; margin: 0 auto; }
        section { padding-top: 3rem !important; padding-bottom: 3rem !important; }
    </style>
</head>
<body>

    {{-- ══════════════════════════════════════
         NAVBAR
    ══════════════════════════════════════ --}}
    <nav class="navbar navbar-landing navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <div style="width:28px;height:28px;border-radius:50%;background:#ef7d2d;display:flex;align-items:center;justify-content:center;font-weight:900;color:white;font-size:.8rem;">S</div>
                <span class="fw-bold" style="font-size:1.077rem;">Sport<span style="color:#ef7d2d;">RYD</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#landingNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="landingNav">
                <ul class="navbar-nav me-auto ms-3 gap-1">
                    <li class="nav-item">
                        <a href="#fitur" class="nav-link text-white-50 fw-semibold" style="font-size:.846rem;">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a href="#olahraga" class="nav-link text-white-50 fw-semibold" style="font-size:.846rem;">Olahraga</a>
                    </li>
                </ul>
                <div class="d-flex gap-2 mt-3 mt-lg-0">
                    <a href="{{ route('login') }}" class="nav-btn-login text-decoration-none">Masuk</a>
                    <a href="{{ route('register') }}" class="nav-btn-register text-decoration-none">Daftar</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ══════════════════════════════════════
         HERO
    ══════════════════════════════════════ --}}
    <section class="hero-section">
        <div class="container">
            <span class="hero-badge"><i class="bi bi-lightning-charge-fill me-1"></i>Anti Bentrok Jadwal</span>
            <h1 class="hero-title">
                Booking Lapangan Olahraga<br>
                <span style="color:#ef7d2d;">Cepat &amp; Transparan</span>
            </h1>
            <p class="hero-subtitle">
                Lihat ketersediaan lapangan real-time, pilih jam langsung dari grid, dan pesan tanpa antri.
                Futsal, Badminton, Basket, dan Tenis dalam satu platform.
            </p>
            <div class="hero-cta-group">
                <a href="{{ route('register') }}" class="btn-cta-primary text-decoration-none">
                    <i class="bi bi-play-circle me-2"></i>Mulai Booking Sekarang
                </a>
                <a href="{{ route('login') }}" class="btn-cta-secondary text-decoration-none">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sudah Punya Akun
                </a>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         STATS BAR
    ══════════════════════════════════════ --}}
    <div class="stats-bar">
        <div class="container">
            <div class="row g-3 justify-content-center">
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-number">4+</div>
                    <div class="stat-label">Jenis Olahraga</div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-number">Real-time</div>
                    <div class="stat-label">Cek Ketersediaan Slot</div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-number">0</div>
                    <div class="stat-label">Bentrok Jadwal</div>
                </div>
                <div class="col-6 col-md-3 stat-item">
                    <div class="stat-number">2 Cara</div>
                    <div class="stat-label">Booking Fleksibel</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         FITUR
    ══════════════════════════════════════ --}}
    <section id="fitur" class="py-5 mt-2">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Semua yang Kamu Butuhkan</h2>
                <p class="section-subtitle mt-2">Dari booking sekali main sampai paket bulanan, semuanya ada di sini.</p>
            </div>
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="fitur-card">
                        <div class="fitur-icon" style="background:#e8f0fe;">
                            <i class="bi bi-grid-3x3-gap" style="color:#2563eb;"></i>
                        </div>
                        <h6 class="fw-bold mb-2" style="color:#12244a;">Grid Slot Real-time</h6>
                        <p class="text-muted small mb-0">Pilih jam yang tersedia langsung dari grid visual. Slot terisi otomatis dikunci, tidak bisa double booking.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="fitur-card">
                        <div class="fitur-icon" style="background:#fcece3;">
                            <i class="bi bi-box-seam" style="color:#ef7d2d;"></i>
                        </div>
                        <h6 class="fw-bold mb-2" style="color:#12244a;">Paket Langganan</h6>
                        <p class="text-muted small mb-0">Hemat lebih banyak dengan paket kuota atau jadwal tetap mingguan. Cocok untuk yang rutin olahraga.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="fitur-card">
                        <div class="fitur-icon" style="background:#dcfce7;">
                            <i class="bi bi-gift" style="color:#16a34a;"></i>
                        </div>
                        <h6 class="fw-bold mb-2" style="color:#12244a;">Poin &amp; Voucher</h6>
                        <p class="text-muted small mb-0">Dapatkan poin otomatis setiap kali main. Tukar jadi voucher diskon atau tambahan kuota sesi.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="fitur-card">
                        <div class="fitur-icon" style="background:#f3e8ff;">
                            <i class="bi bi-arrow-repeat" style="color:#7e22ce;"></i>
                        </div>
                        <h6 class="fw-bold mb-2" style="color:#12244a;">Reschedule Mudah</h6>
                        <p class="text-muted small mb-0">Ada halangan? Ajukan reschedule langsung dari akun kamu. Admin tinggal setujui, jadwal otomatis berubah.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="fitur-card">
                        <div class="fitur-icon" style="background:#fef3e2;">
                            <i class="bi bi-shield-check" style="color:#b45309;"></i>
                        </div>
                        <h6 class="fw-bold mb-2" style="color:#12244a;">Approval Transparan</h6>
                        <p class="text-muted small mb-0">Booking kamu langsung masuk ke kasir untuk dikonfirmasi. Status selalu update di riwayat pemesanan.</p>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="fitur-card">
                        <div class="fitur-icon" style="background:#e0f2fe;">
                            <i class="bi bi-phone" style="color:#0284c7;"></i>
                        </div>
                        <h6 class="fw-bold mb-2" style="color:#12244a;">Mobile Friendly</h6>
                        <p class="text-muted small mb-0">Tampilan responsif di semua perangkat. Booking dari HP semudah dari laptop.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         OLAHRAGA TERSEDIA
    ══════════════════════════════════════ --}}
    <section id="olahraga" class="py-5" style="background:white;border-top:1px solid #eef0f2;border-bottom:1px solid #eef0f2;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Olahraga Tersedia</h2>
                <p class="section-subtitle mt-2">Semua dalam satu platform booking.</p>
            </div>
            <div class="row g-3 justify-content-center">
                <div class="col-6 col-md-3">
                    <div class="sport-card">
                        <div class="sport-emoji">⚽</div>
                        <div class="fw-bold" style="color:#12244a;">Futsal</div>
                        <div class="text-muted small">Indoor & Outdoor</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="sport-card">
                        <div class="sport-emoji">🏸</div>
                        <div class="fw-bold" style="color:#12244a;">Badminton</div>
                        <div class="text-muted small">Berbagai kelas</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="sport-card">
                        <div class="sport-emoji">🏀</div>
                        <div class="fw-bold" style="color:#12244a;">Basket</div>
                        <div class="text-muted small">Half & Full court</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="sport-card">
                        <div class="sport-emoji">🎾</div>
                        <div class="fw-bold" style="color:#12244a;">Tenis</div>
                        <div class="text-muted small">Hard & Clay court</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         CTA BOTTOM
    ══════════════════════════════════════ --}}
    <section class="py-5">
        <div class="container">
            <div class="cta-bottom">
                <h2 class="fw-bold text-white mb-2" style="font-size:1.9rem;">Siap Mulai Olahraga?</h2>
                <p class="mb-4" style="color:#94a3b8;font-size:1rem;">Daftar gratis, booking dalam hitungan detik.</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('register') }}" class="btn-cta-primary text-decoration-none">
                        <i class="bi bi-person-plus me-2"></i>Daftar Gratis
                    </a>
                    <a href="{{ route('login') }}" class="btn-cta-secondary text-decoration-none">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════════════════════════════
         FOOTER
    ══════════════════════════════════════ --}}
    <footer style="background:#0f172a;color:#475569;padding:2rem 0;text-align:center;">
        <div class="container">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                <div style="width:28px;height:28px;border-radius:50%;background:#ef7d2d;display:flex;align-items:center;justify-content:center;font-weight:900;color:white;font-size:.8rem;">S</div>
                <span class="fw-bold text-white">Sport<span style="color:#ef7d2d;">RYD</span></span>
            </div>
            <p class="mb-0 small">&copy; {{ date('Y') }} SportRYD. Platform Booking Lapangan Olahraga Online.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
