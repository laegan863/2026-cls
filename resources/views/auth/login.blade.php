<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - CLS | Licensing & Renewal Management System</title>

    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #1a2b4a;
            --bs-primary-dark: #0f1a2e;
            --bs-primary-light: #2a3f5f;
            --bs-gold: #d4a94c;
            --bs-gold-light: #e5c678;
            --bs-gold-dark: #b8922f;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            background: linear-gradient(135deg, var(--bs-primary-dark) 0%, var(--bs-primary) 50%, var(--bs-primary-light) 100%);
            position: relative;
        }

        /* Animated background shapes */
        .bg-shapes {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
            pointer-events: none;
        }

        .bg-shape {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(212, 169, 76, 0.1) 0%, rgba(212, 169, 76, 0.05) 100%);
            animation: float 20s infinite ease-in-out;
        }

        .bg-shape:nth-child(1) {
            width: 600px;
            height: 600px;
            top: -200px;
            right: -100px;
            animation-delay: 0s;
        }

        .bg-shape:nth-child(2) {
            width: 400px;
            height: 400px;
            bottom: -150px;
            left: -100px;
            animation-delay: -5s;
        }

        .bg-shape:nth-child(3) {
            width: 300px;
            height: 300px;
            top: 50%;
            left: 10%;
            animation-delay: -10s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-30px) rotate(5deg); }
            66% { transform: translateY(20px) rotate(-5deg); }
        }

        /* Left Panel - Branding */
        .brand-panel {
            flex: 1;
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            position: relative;
            z-index: 1;
        }

        @media (min-width: 992px) {
            .brand-panel {
                display: flex;
            }
        }

        .brand-content {
            max-width: 480px;
            text-align: center;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .brand-logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--bs-gold) 0%, var(--bs-gold-dark) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--bs-primary-dark);
            box-shadow: 0 10px 40px rgba(212, 169, 76, 0.3);
        }

        .brand-logo-text {
            font-size: 2.5rem;
            font-weight: 800;
            color: #fff;
        }

        .brand-logo-text span {
            color: var(--bs-gold);
        }

        .brand-tagline {
            font-size: 1.5rem;
            font-weight: 300;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 1.5rem;
            line-height: 1.4;
        }

        .brand-description {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.8;
            margin-bottom: 3rem;
        }

        .brand-features {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-align: left;
        }

        .brand-feature-icon {
            width: 48px;
            height: 48px;
            background: rgba(212, 169, 76, 0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--bs-gold);
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .brand-feature-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9375rem;
        }

        .brand-feature-text strong {
            display: block;
            color: #fff;
            font-weight: 600;
            margin-bottom: 0.125rem;
        }

        /* Right Panel - Login Form */
        .login-panel {
            width: 100%;
            max-width: 520px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2rem;
            background: #fff;
            position: relative;
            z-index: 1;
            margin-left: auto;
        }

        @media (min-width: 992px) {
            .login-panel {
                min-height: auto;
                padding: 3rem 4rem;
                margin-top: 2rem;
                margin-bottom: 2rem;
                border-radius: 24px 0 0 24px;
                box-shadow: -20px 0 60px rgba(0, 0, 0, 0.15);
            }
        }

        .login-header {
            margin-bottom: 2.5rem;
        }

        .login-header-mobile {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 992px) {
            .login-header-mobile {
                display: none;
            }
        }

        .login-header-mobile .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--bs-gold) 0%, var(--bs-gold-dark) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--bs-primary-dark);
        }

        .login-header-mobile .logo-text {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--bs-primary);
        }

        .login-header-mobile .logo-text span {
            color: var(--bs-gold);
        }

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--bs-primary);
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            font-size: 1rem;
            color: #64748b;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--bs-primary);
            margin-bottom: 0.5rem;
        }

        .form-control-wrapper {
            position: relative;
        }

        .form-control-wrapper .form-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.125rem;
            transition: color 0.2s ease;
        }

        .form-control-wrapper .form-control:focus ~ .form-icon {
            color: var(--bs-gold);
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            font-size: 0.9375rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
            color: var(--bs-primary);
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--bs-gold);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(212, 169, 76, 0.1);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0;
            font-size: 1.125rem;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: var(--bs-primary);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #cbd5e1;
            border-radius: 4px;
            cursor: pointer;
            accent-color: var(--bs-gold);
        }

        .form-check-label {
            font-size: 0.875rem;
            color: #64748b;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.875rem;
            color: var(--bs-gold-dark);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .forgot-link:hover {
            color: var(--bs-primary);
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--bs-primary-dark);
            background: linear-gradient(135deg, var(--bs-gold) 0%, var(--bs-gold-dark) 100%);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(212, 169, 76, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212, 169, 76, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 2rem 0;
        }

        .divider-line {
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider-text {
            font-size: 0.875rem;
            color: #94a3b8;
        }

        .social-login {
            display: flex;
            gap: 1rem;
        }

        .btn-social {
            flex: 1;
            padding: 0.875rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--bs-primary);
            background: #fff;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-social:hover {
            border-color: var(--bs-primary);
            background: #f8fafc;
        }

        .btn-social i {
            font-size: 1.25rem;
        }

        .btn-social.google i { color: #ea4335; }
        .btn-social.microsoft i { color: #00a4ef; }

        .signup-link {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9375rem;
            color: #64748b;
        }

        .signup-link a {
            color: var(--bs-gold-dark);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .signup-link a:hover {
            color: var(--bs-primary);
            text-decoration: underline;
        }

        /* Footer */
        .login-footer {
            margin-top: auto;
            padding-top: 2rem;
            text-align: center;
            font-size: 0.8125rem;
            color: #94a3b8;
        }

        .login-footer a {
            color: #64748b;
            text-decoration: none;
            margin: 0 0.5rem;
        }

        .login-footer a:hover {
            color: var(--bs-gold-dark);
        }
    </style>
</head>

<body>
    <!-- Animated Background Shapes -->
    <div class="bg-shapes">
        <div class="bg-shape"></div>
        <div class="bg-shape"></div>
        <div class="bg-shape"></div>
    </div>

    <!-- Brand Panel (Left) -->
    <div class="brand-panel">
        <div class="brand-content">
            <div class="brand-logo">
                <div class="brand-logo-icon">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
                <span class="brand-logo-text">CL<span>S</span></span>
            </div>

            <h1 class="brand-tagline">Licensing & Renewal Management System</h1>
            <p class="brand-description">
                Streamline your permit applications, track license renewals, and manage compliance effortlessly with our comprehensive licensing solution.
            </p>

            <div class="brand-features">
                <div class="brand-feature">
                    <div class="brand-feature-icon">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                    <div class="brand-feature-text">
                        <strong>License Management</strong>
                        Track permits, renewals, and expirations
                    </div>
                </div>
                <div class="brand-feature">
                    <div class="brand-feature-icon">
                        <i class="bi bi-bell"></i>
                    </div>
                    <div class="brand-feature-text">
                        <strong>Automated Reminders</strong>
                        Never miss a renewal deadline again
                    </div>
                </div>
                <div class="brand-feature">
                    <div class="brand-feature-icon">
                        <i class="bi bi-clipboard-data"></i>
                    </div>
                    <div class="brand-feature-text">
                        <strong>Compliance Tracking</strong>
                        Stay compliant with all jurisdictions
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Panel (Right) -->
    <div class="login-panel">
        <!-- Mobile Logo -->
        <div class="login-header-mobile">
            <div class="logo-icon">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <span class="logo-text">Blue<span>side</span></span>
        </div>

        <div class="login-header">
            <h2 class="login-title">Welcome back!</h2>
            <p class="login-subtitle">Sign in to manage your licenses and permits</p>
        </div>

        <form action="{{ route('auth.login.post') }}" method="POST">
            @csrf
            
            @if(session('success'))
                <div class="alert alert-success mb-3" style="padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.875rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger mb-3" style="padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.875rem;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <div class="form-control-wrapper">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                    <i class="bi bi-envelope form-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="form-control-wrapper">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    <i class="bi bi-lock form-icon"></i>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="bi bi-eye" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <div class="form-options">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <a href="#" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i>
                Sign In
            </button>
        </form>

        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-text">or continue with</span>
            <div class="divider-line"></div>
        </div>

        <div class="social-login">
            <a href="#" class="btn-social google">
                <i class="bi bi-google"></i>
                Google
            </a>
            <a href="#" class="btn-social microsoft">
                <i class="bi bi-microsoft"></i>
                Microsoft
            </a>
        </div>

        <p class="signup-link">
            Don't have an account? <a href="{{ route('auth.register') }}">Create account</a>
        </p>

        <div class="login-footer">
            <a href="#">Privacy Policy</a> • <a href="#">Terms of Service</a> • <a href="#">Help</a>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</body>

</html>
