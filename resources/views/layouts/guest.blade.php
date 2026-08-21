<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SportRYD') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Livewire styles -->
    @livewireStyles

    <style>
        * { font-family: 'Nunito', sans-serif; box-sizing: border-box; }
        html { font-size: 13px; }

        body {
            background: radial-gradient(circle at center top, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-size: 1rem;
        }

        /* ── Navbar ── */
        .auth-navbar {
            background: rgba(255,255,255,0.04);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            padding: 10px 0;
        }
        .btn-back-home {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            color: rgba(255,255,255,0.7);
            font-size: .846rem;
            font-weight: 600;
            text-decoration: none;
            padding: .35rem .75rem;
            border-radius: 7px;
            border: 1px solid rgba(255,255,255,0.15);
            transition: all .2s;
        }
        .btn-back-home:hover {
            color: #fff;
            border-color: rgba(255,255,255,0.35);
            background: rgba(255,255,255,0.07);
        }

        /* ── Card ── */
        .auth-card {
            background: white;
            border-radius: 12px;
            padding: 1.75rem 1.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
        }

        /* ── Inputs ── */
        .auth-label {
            font-size: 0.846rem;
            font-weight: 700;
            color: #12244a;
            margin-bottom: 0.3rem;
        }
        .auth-input {
            width: 100%;
            border: 1.5px solid #dee2e6;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            font-size: 0.923rem;
            font-family: 'Nunito', sans-serif;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            outline: none;
            color: #1e2a45;
        }
        .auth-input:focus {
            border-color: #12244a;
            box-shadow: 0 0 0 2px rgba(18,36,74,0.1);
        }
        .auth-input.is-invalid { border-color: #dc3545; }

        /* ── Error text ── */
        .auth-error {
            font-size: 0.769rem;
            color: #dc3545;
            margin-top: 0.25rem;
        }

        /* ── Button ── */
        .btn-auth {
            width: 100%;
            background-color: #ef7d2d;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.6rem;
            font-weight: 800;
            font-size: 0.923rem;
            font-family: 'Nunito', sans-serif;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .btn-auth:hover {
            background-color: #d8691b;
            transform: translateY(-1px);
            box-shadow: 0 5px 14px rgba(239,125,45,0.35);
        }
        .btn-auth:active { transform: translateY(0); }

        /* ── Link ── */
        .auth-link { color: #ef7d2d; font-weight: 700; text-decoration: none; }
        .auth-link:hover { color: #d8691b; text-decoration: underline; }

        /* ── Divider ── */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: #9ca3af;
            font-size: 0.769rem;
            margin: 1rem 0;
        }
        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* ── Remember checkbox ── */
        .auth-check {
            width: 15px; height: 15px;
            accent-color: #12244a;
            cursor: pointer;
        }

        /* ── Alert session ── */
        .auth-alert {
            background: #e6f7ee;
            color: #1a7f4b;
            border-radius: 8px;
            padding: 0.6rem 0.85rem;
            font-size: 0.846rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    {{-- Navbar minimal --}}
    <nav class="auth-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                <div style="width:28px;height:28px;border-radius:50%;background:#ef7d2d;display:flex;align-items:center;justify-content:center;font-weight:900;color:white;font-size:.8rem;">S</div>
                <span class="fw-bold text-white" style="font-size:1.077rem;">Sport<span style="color:#ef7d2d;">RYD</span></span>
            </a>
            <a href="{{ route('home') }}" class="btn-back-home">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </nav>

    {{-- Content --}}
    <div class="flex-grow-1 d-flex align-items-center justify-content-center px-3 py-5">
        <div class="auth-card">
            {{ $slot }}
        </div>
    </div>

    {{-- Footer --}}
    <div class="text-center pb-4" style="color:rgba(255,255,255,0.3);font-size:0.8rem;">
        &copy; {{ date('Y') }} SportRYD
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @livewireScripts
</body>
</html>
