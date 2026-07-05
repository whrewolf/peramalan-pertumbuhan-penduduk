<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang | SIPENDUDUK Desa Gunungsari</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 50%, #a5d6a7 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Background pattern */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.1;
            pointer-events: none;
            background-image:
                radial-gradient(circle at 20% 50%, #2e7d32 1px, transparent 1px),
                radial-gradient(circle at 80% 20%, #1b5e20 1px, transparent 1px),
                radial-gradient(circle at 50% 80%, #388e3c 1px, transparent 1px);
            background-size: 50px 50px, 80px 80px, 60px 60px;
        }

        /* Container utama */
        .main-container {
            position: relative;
            z-index: 1;
            width: 90%;
            max-width: 480px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.5);
            padding: 40px 32px;
            text-align: center;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo */
        .logo-wrapper {
            margin-bottom: 24px;
            position: relative;
        }

        .logo-img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            border-radius: 50%;
            border: 4px solid #e8f5e9;
            box-shadow: 0 8px 24px rgba(46, 125, 50, 0.2);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 8px 24px rgba(46, 125, 50, 0.2);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 12px 32px rgba(46, 125, 50, 0.35);
            }
        }

        /* Judul */
        .app-title {
            font-size: 28px;
            font-weight: 700;
            color: #1b5e20;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .app-subtitle {
            font-size: 14px;
            color: #4caf50;
            font-weight: 500;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .desa-name {
            font-size: 16px;
            color: #555;
            margin-bottom: 28px;
            font-weight: 500;
        }

        /* Divider */
        .divider {
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, #4caf50, #2e7d32);
            border-radius: 2px;
            margin: 0 auto 24px;
        }

        /* Tombol Login */
        .btn-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px 32px;
            background: linear-gradient(135deg, #43a047, #2e7d32);
            color: white;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(46, 125, 50, 0.3);
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #66bb6a, #388e3c);
            box-shadow: 0 10px 28px rgba(46, 125, 50, 0.45);
            transform: translateY(-2px);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
        }

        .btn-icon {
            width: 20px;
            height: 20px;
        }

        /* Info footer */
        .footer-info {
            margin-top: 24px;
            font-size: 12px;
            color: #999;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .footer-dot {
            width: 6px;
            height: 6px;
            background: #4caf50;
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* Responsif */
        @media (max-width: 480px) {
            .main-container {
                padding: 28px 20px;
                border-radius: 20px;
            }
            .app-title {
                font-size: 24px;
            }
            .logo-img {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <!-- Background pattern -->
    <div class="bg-pattern"></div>

    <!-- Container utama -->
    <div class="main-container">
        <!-- Logo -->
        <div class="logo-wrapper">
            <img src="{{ asset('images/logo-desa-gunungsari.png') }}" 
                 alt="Logo Desa Gunungsari" 
                 class="logo-img"
                 onerror="this.style.display='none'">
        </div>

        <!-- Judul -->
        <h1 class="app-title">SIPENDUDUK</h1>

        <!-- Divider -->
        <div class="divider"></div>

        <!-- Tombol Login -->
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-login">
                    <svg class="btn-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4"/>
                    </svg>
                    Masuk
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-login">
                    <svg class="btn-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Masuk ke Sistem
                </a>
            @endauth
        @endif

        <!-- Footer -->
        <div class="footer-info">
            <span class="footer-dot"></span>
            <span>Sistem Peramalan Penduduk</span>
        </div>
    </div>
</body>
</html>