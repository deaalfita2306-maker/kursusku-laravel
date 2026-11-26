<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KursusKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-purple: #7B2D8E;
            --dark-purple: #5A1E6B;
            --light-purple: #9B4DCA;
            --accent-yellow: #FFD700;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sisi Kiri - Branding Ungu */
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-purple) 0%, var(--dark-purple) 100%);
            color: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(155, 77, 202, 0.3) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .brand-logo-icon {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .brand-logo-text {
            font-size: 28px;
            font-weight: bold;
        }

        .workspace-label {
            font-size: 12px;
            letter-spacing: 3px;
            opacity: 0.8;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 56px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .hero-title .highlight {
            color: var(--accent-yellow);
        }

        .hero-description {
            font-size: 18px;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .class-summary {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            position: relative;
            z-index: 1;
        }

        .class-summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .class-summary-title {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .class-summary-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 14px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .class-summary-item-icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .class-summary-item-text {
            font-size: 14px;
        }

        .class-summary-item-text small {
            opacity: 0.7;
            font-size: 12px;
        }

        /* Sisi Kanan - Form Login */
        .login-right {
            flex: 1;
            background: white;
            padding: 60px;
            display: flex;
            flex-direction: column;
        }

        .login-right-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 60px;
        }

        .back-link {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            color: var(--primary-purple);
        }

        .portal-link {
            color: var(--primary-purple);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .portal-link:hover {
            text-decoration: underline;
        }

        .login-form-container {
            max-width: 420px;
            margin: 0 auto;
            width: 100%;
        }

        .access-badge {
            display: inline-block;
            background: #f3e5f5;
            color: var(--primary-purple);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 20px;
        }

        .welcome-title {
            font-size: 36px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 12px;
        }

        .welcome-subtitle {
            color: #666;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-purple);
            box-shadow: 0 0 0 3px rgba(123, 45, 142, 0.1);
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .input-icon-wrapper .form-control {
            padding-left: 44px;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--primary-purple);
            cursor: pointer;
            font-size: 13px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 20px 0;
            font-size: 14px;
            color: #666;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-purple);
        }

        .btn-login {
            background: var(--primary-purple);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 16px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: var(--dark-purple);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(123, 45, 142, 0.3);
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: #999;
            text-decoration: none;
            font-size: 14px;
        }

        .forgot-password a:hover {
            color: var(--primary-purple);
        }

        .student-portal {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }

        .student-portal-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            border: 2px solid #f0f0f0;
            border-radius: 12px;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s;
        }

        .student-portal-card:hover {
            border-color: var(--primary-purple);
            background: #faf5ff;
        }

        .student-portal-icon {
            width: 48px;
            height: 48px;
            background: #f3e5f5;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .student-portal-text h6 {
            margin: 0;
            font-weight: 600;
            color: #1a1a1a;
        }

        .student-portal-text small {
            color: #999;
            font-size: 13px;
        }

        /* Alert Error */
        .alert-custom {
            background: #fee;
            border-left: 4px solid #dc3545;
            color: #721c24;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .login-left {
                display: none;
            }
            .login-right {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Sisi Kiri: Branding -->
        <div class="login-left">
            <div class="brand-logo">
                <div class="brand-logo-icon">📚</div>
                <div class="brand-logo-text">KursusKu</div>
            </div>

            <div class="workspace-label">WORKSPACE AKADEMIK</div>

            <h1 class="hero-title">
                Setiap kelas.<br>
                Setiap progres.<br>
                <span class="highlight">Lebih terarah.</span>
            </h1>

            <p class="hero-description">
                Kelola presensi, tugas, nilai, dan perkembangan mahasiswa melalui dashboard yang terintegrasi.
            </p>

            <div class="class-summary">
                <div class="class-summary-header">
                    <div class="class-summary-title">📋 RINGKASAN KELAS</div>
                    <div style="font-size: 14px; font-weight: 600;">+ Tambah</div>
                </div>

                <div class="class-summary-item">
                    <div class="class-summary-item-icon">✅</div>
                    <div class="class-summary-item-text">
                        <strong>Presensi QR</strong><br>
                        <small>Rekap absensi presensi</small>
                    </div>
                </div>

                <div class="class-summary-item">
                    <div class="class-summary-item-icon">📄</div>
                    <div class="class-summary-item-text">
                        <strong>Nilai dan Tugas</strong><br>
                        <small>Monitoring kelas pada dashboard</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sisi Kanan: Form Login -->
        <div class="login-right">
            <div class="login-right-header">
                <a href="/" class="back-link">← Kembali ke Beranda</a>
                <a href="#" class="portal-link">Portal Mahasiswa ↗</a>
            </div>

            <div class="login-form-container">
                <div class="access-badge">AKSES ADMINISTRATOR & DOSEN</div>

                <h2 class="welcome-title">Selamat datang 👋</h2>
                <p class="welcome-subtitle">
                    Masuk ke dashboard Anda untuk mengelola aktivitas pembelajaran.
                </p>

                @if ($errors->has('login'))
                <div class="alert-custom">
                    <i class="bi bi-exclamation-triangle"></i>
                    {{ $errors->first('login') }}
                </div>
                @endif

                <form method="POST" action="{{ route('login.process') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-person"></i>
                            <input type="text" name="username" class="form-control" 
                                   placeholder="admin" 
                                   value="{{ old('username') }}" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-icon-wrapper">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" id="password" 
                                   class="form-control" placeholder="••••••••" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                Lihat
                            </button>
                        </div>
                    </div>

                    <div class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn-login">
                        Masuk ke Dashboard →
                    </button>

                    <div class="forgot-password">
                        <a href="#">Lupa password?</a>
                    </div>
                </form>

                <div class="student-portal">
                    <a href="#" class="student-portal-card">
                        <div class="student-portal-icon">🎓</div>
                        <div class="student-portal-text">
                            <h6>Mahasiswa</h6>
                            <small>Masuk sebagai mahasiswa untuk melihat progres & presensi QR</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = event.target;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = 'Sembunyi';
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = 'Lihat';
            }
        }
    </script>
</body>
</html>