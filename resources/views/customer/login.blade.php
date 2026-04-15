<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Produk Resep Medikpedia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0D47A1 0%, #1565C0 40%, #1976D2 70%, #1E88E5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative blobs */
        body::before {
            content: '';
            position: absolute;
            top: -120px; right: -120px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(124,179,66,0.18) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: absolute;
            bottom: -100px; left: -100px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 2;
        }

        /* Logo / Brand */
        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand a {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
        }
        .brand-icon {
            width: 44px; height: 44px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            border: 1px solid rgba(255,255,255,0.25);
        }
        .brand-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            letter-spacing: -0.02em;
        }
        .brand-text span { color: #7CB342; }

        /* Card */
        .login-card {
            background: white;
            border-radius: 24px;
            padding: 2.5rem 2rem;
            box-shadow: 0 25px 60px rgba(0,0,0,0.25), 0 8px 20px rgba(0,0,0,0.15);
        }

        .card-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .card-icon {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            color: #1E88E5;
            margin: 0 auto 1rem;
        }
        .card-header h2 {
            font-size: 1.35rem;
            font-weight: 800;
            color: #1f2937;
            margin-bottom: 0.35rem;
        }
        .card-header p {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* Alert */
        .alert {
            padding: 0.85rem 1rem;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .alert-error {
            background: #fee2e2;
            color: #7f1d1d;
            border: 1px solid #fca5a5;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        /* Form */
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .input-wrap {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 0.9rem;
            pointer-events: none;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 0.9rem 0.75rem 2.5rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.95rem;
            color: #1f2937;
            background: #f9fafb;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .form-input:focus {
            outline: none;
            border-color: #1E88E5;
            background: white;
            box-shadow: 0 0 0 3px rgba(30,136,229,0.1);
        }
        .form-input.is-invalid {
            border-color: #ef4444;
            background: #fff5f5;
        }

        /* Password toggle */
        .toggle-pw {
            position: absolute;
            right: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 0.9rem;
            padding: 0;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: #1E88E5; }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #1E88E5, #1565C0);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #1565C0, #0D47A1);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30,136,229,0.35);
        }
        .btn-login:active { transform: translateY(0); }

        /* Info box */
        .info-box {
            margin-top: 1.5rem;
            padding: 0.85rem 1rem;
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 10px;
            font-size: 0.8rem;
            color: #0369a1;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            line-height: 1.5;
        }
        .info-box i { margin-top: 1px; flex-shrink: 0; }

        /* Back link */
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .back-link a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: color 0.2s;
        }
        .back-link a:hover { color: white; }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.25rem 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }
        .divider span {
            font-size: 0.75rem;
            color: #9ca3af;
            font-weight: 500;
            white-space: nowrap;
        }

        @media (max-width: 480px) {
            .login-card { padding: 2rem 1.5rem; border-radius: 20px; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">

        {{-- Brand --}}
        <div class="brand">
            <a href="{{ route('home') }}">
                <div class="brand-icon">💊</div>
                <div class="brand-text">Medik<span>pedia</span></div>
            </a>
        </div>

        {{-- Card --}}
        <div class="login-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fa-solid fa-prescription-bottle-medical"></i>
                </div>
                <h2>Akses Produk Resep</h2>
                <p>Masukkan username dan password Anda</p>
            </div>

            {{-- Alert --}}
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('customer.login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="username">User Name</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-user input-icon"></i>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-input {{ $errors->has('username') ? 'is-invalid' : '' }}"
                            placeholder="Masukkan username"
                            value="{{ old('username') }}"
                            autocomplete="username"
                            required
                            autofocus
                        >
                    </div>
                    @error('username')
                        <div style="font-size:0.78rem;color:#ef4444;margin-top:0.3rem;">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="toggle-pw" onclick="togglePassword()" id="toggleBtn">
                            <i class="fa-solid fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div style="font-size:0.78rem;color:#ef4444;margin-top:0.3rem;">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Masuk ke Produk Resep
                </button>
            </form>

            <div class="info-box">
                <i class="fa-solid fa-circle-info"></i>
                <span>Halaman ini khusus untuk mitra dan pelanggan terdaftar Medikpedia yang memiliki akses produk resep.</span>
            </div>
        </div>

        {{-- Back link --}}
        <div class="back-link">
            <a href="{{ route('home') }}">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa-solid fa-eye';
            }
        }
    </script>
</body>
</html>
