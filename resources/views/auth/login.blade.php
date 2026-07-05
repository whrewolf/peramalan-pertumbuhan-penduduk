<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | SIPENDUDUK Desa Gunungsari</title>

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

        .main-container {
            position: relative;
            z-index: 1;
            width: 90%;
            max-width: 440px;
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

        .logo-wrapper {
            margin-bottom: 20px;
        }

        .logo-img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 50%;
            border: 3px solid #e8f5e9;
            box-shadow: 0 6px 18px rgba(46, 125, 50, 0.15);
        }

        .app-title {
            font-size: 26px;
            font-weight: 700;
            color: #1b5e20;
            margin-bottom: 4px;
        }

        .app-subtitle {
            font-size: 13px;
            color: #4caf50;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 16px;
            text-align: left;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #2e3b2e;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            font-size: 15px;
            border: 1.5px solid #dcedc8;
            border-radius: 10px;
            background: #f9fff9;
            transition: all 0.2s ease;
            outline: none;
            color: #333;
        }

        .form-input:focus {
            border-color: #66bb6a;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        .error-message {
            color: #d32f2f;
            font-size: 13px;
            margin-top: 4px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            margin-top: 8px;
            font-size: 14px;
            color: #555;
        }

        .remember-row input[type="checkbox"] {
            accent-color: #43a047;
            margin-right: 8px;
            width: 16px;
            height: 16px;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            margin-top: 20px;
            background: linear-gradient(135deg, #43a047, #2e7d32);
            color: white;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(46, 125, 50, 0.3);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #66bb6a, #388e3c);
            box-shadow: 0 10px 28px rgba(46, 125, 50, 0.45);
            transform: translateY(-2px);
        }

        .forgot-link {
            display: block;
            text-align: right;
            font-size: 13px;
            color: #43a047;
            text-decoration: none;
            margin-top: 6px;
            font-weight: 500;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .back-link {
            display: block;
            margin-top: 24px;
            font-size: 14px;
            color: #777;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: #2e7d32;
        }

        .status-message {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .main-container {
                padding: 28px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>

    <div class="main-container">
        <!-- Logo -->
        <div class="logo-wrapper">
            <img src="{{ asset('images/logo-desa-gunungsari.png') }}" 
                 alt="Logo Desa Gunungsari" 
                 class="logo-img"
                 onerror="this.style.display='none'">
        </div>

        <h1 class="app-title">SIPENDUDUK</h1>
        <p class="app-subtitle">Login</p>

        <!-- Session Status -->
        @if(session('status'))
            <div class="status-message">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" 
                       class="form-input" required autofocus autocomplete="username">
                @error('email')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input id="password" type="password" name="password" 
                       class="form-input" required autocomplete="current-password">
                @error('password')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="remember-row">
                <input type="checkbox" id="remember_me" name="remember">
                <label for="remember_me">Ingat saya</label>
            </div>

            <!-- Forgot Password -->
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">
                    Lupa password?
                </a>
            @endif

            <!-- Tombol Login -->
            <button type="submit" class="btn-login">
                Masuk
            </button>
        </form>

        <!-- Kembali ke Beranda -->
        <a href="{{ url('/') }}" class="back-link">← Kembali ke Beranda</a>
    </div>
</body>
</html>