<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - CLS | Licensing & Renewal Management System</title>

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

        /* Right Panel - Register Form */
        .register-panel {
            width: 100%;
            max-width: 700px;
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
            .register-panel {
                min-height: auto;
                padding: 3rem 4rem;
                margin-top: 2rem;
                margin-bottom: 2rem;
                border-radius: 24px 0 0 24px;
                box-shadow: -20px 0 60px rgba(0, 0, 0, 0.15);
            }
        }

        .register-header {
            margin-bottom: 2rem;
        }

        .register-header-mobile {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 992px) {
            .register-header-mobile {
                display: none;
            }
        }

        .register-header-mobile .logo-icon {
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

        .register-header-mobile .logo-text {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--bs-primary);
        }

        .register-header-mobile .logo-text span {
            color: var(--bs-gold);
        }

        .register-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--bs-primary);
            margin-bottom: 0.5rem;
        }

        .register-subtitle {
            font-size: 1rem;
            color: #64748b;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.25rem;
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

        .form-select {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            font-size: 0.9375rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
            color: var(--bs-primary);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--bs-gold);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(212, 169, 76, 0.1);
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

        .btn-register {
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
            margin-top: 1.5rem;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212, 169, 76, 0.4);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9375rem;
            color: #64748b;
        }

        .login-link a {
            color: var(--bs-gold-dark);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .login-link a:hover {
            color: var(--bs-primary);
            text-decoration: underline;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.5rem;
        }

        .col-md-12 {
            flex: 0 0 100%;
            padding: 0 0.5rem;
        }

        @media (min-width: 768px) {
            .col-md-12 {
                flex: 0 0 50%;
            }
        }

        .alert {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .alert-danger {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .alert-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }

        .text-danger {
            color: #dc2626;
            font-size: 0.8125rem;
            margin-top: 0.25rem;
        }

        /* Footer */
        .register-footer {
            margin-top: auto;
            padding-top: 2rem;
            text-align: center;
            font-size: 0.8125rem;
            color: #94a3b8;
        }

        .register-footer a {
            color: #64748b;
            text-decoration: none;
            margin: 0 0.5rem;
        }

        .register-footer a:hover {
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
                Create your account to manage permits, track renewals, and stay compliant with all licensing requirements.
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

    <!-- Register Panel (Right) -->
    <div class="register-panel">
        <!-- Mobile Logo -->
        <div class="register-header-mobile">
            <div class="logo-icon">
                <i class="bi bi-lightning-charge-fill"></i>
            </div>
            <span class="logo-text">CL<span>S</span></span>
        </div>

        <div class="register-header">
            <h2 class="register-title">Create Account</h2>
            <p class="register-subtitle">Register to start managing your licenses and permits</p>
        </div>

        <form action="{{ route('auth.register.post') }}" method="POST">
            @csrf

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name <span style="color: #dc2626;">*</span></label>
                        <div class="form-control-wrapper">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" value="{{ old('name') }}" required>
                            <i class="bi bi-person form-icon"></i>
                        </div>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address <span style="color: #dc2626;">*</span></label>
                        <div class="form-control-wrapper">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                            <i class="bi bi-envelope form-icon"></i>
                        </div>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="contact_no">Contact Number</label>
                        <div class="form-control-wrapper">
                            <input type="text" class="form-control" id="contact_no" name="contact_no" placeholder="Enter contact number" value="{{ old('contact_no') }}">
                            <i class="bi bi-telephone form-icon"></i>
                        </div>
                        @error('contact_no')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                {{-- <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="role_id">Role <span style="color: #dc2626;">*</span></label>
                        <div class="form-control-wrapper">
                            <select class="form-select" id="role_id" name="role_id" required style="padding-left: 3rem;">
                                <option value="">Select a role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <i class="bi bi-shield form-icon"></i>
                        </div>
                        @error('role_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div> --}}
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="password">Password <span style="color: #dc2626;">*</span></label>
                        <div class="form-control-wrapper">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Create password" required>
                            <i class="bi bi-lock form-icon"></i>
                            <button type="button" class="password-toggle" onclick="togglePassword('password', 'toggleIcon1')">
                                <i class="bi bi-eye" id="toggleIcon1"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm Password <span style="color: #dc2626;">*</span></label>
                        <div class="form-control-wrapper">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                            <i class="bi bi-lock form-icon"></i>
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                                <i class="bi bi-eye" id="toggleIcon2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-register">
                <i class="bi bi-person-plus"></i>
                Create Account
            </button>
        </form>

        <p class="login-link">
            Already have an account? <a href="{{ route('auth.login') }}">Sign in</a>
        </p>

        <div class="register-footer">
            <a href="#">Privacy Policy</a> • <a href="#">Terms of Service</a> • <a href="#">Help</a>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            
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
