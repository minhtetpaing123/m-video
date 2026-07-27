<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>M-VIDEO - Login</title>
    
    <style>
        /* Base Reset & Variables */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 32px 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        /* Header / Logo */
        .header {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo-icon {
            font-size: 48px;
            margin-bottom: 8px;
            display: inline-block;
        }

        .logo-text {
            background: linear-gradient(135deg, #6c63ff, #a855f7, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
            font-size: 32px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: #9ca3af;
            font-size: 13px;
            margin-top: 6px;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 18px;
        }

        .input-relative {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-field {
            width: 100%;
            padding: 14px 44px 14px 44px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #ffffff;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .input-field::placeholder {
            color: #9ca3af;
        }

        .input-field:focus {
            border-color: #6c63ff;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 15px rgba(108, 99, 255, 0.25);
        }

        .input-icon {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            fill: #9ca3af;
            transition: fill 0.3s ease;
            pointer-events: none;
        }

        .input-field:focus + .input-icon {
            fill: #6c63ff;
        }

        .toggle-btn {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .toggle-icon {
            width: 18px;
            height: 18px;
            fill: #9ca3af;
            transition: fill 0.3s ease;
        }

        .toggle-btn:hover .toggle-icon {
            fill: #6c63ff;
        }

        /* Remember & Forgot Row */
        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #d1d5db;
            cursor: pointer;
            user-select: none;
        }

        /* Custom Checkbox (No Font Awesome/Tailwind Needed) */
        .custom-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.08);
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            position: relative;
            outline: none;
            transition: all 0.3s ease;
        }

        .custom-checkbox:hover {
            border-color: #6c63ff;
        }

        .custom-checkbox:checked {
            background: linear-gradient(135deg, #6c63ff, #a855f7);
            border-color: #6c63ff;
            box-shadow: 0 0 10px rgba(108, 99, 255, 0.4);
        }

        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 6px;
            width: 4px;
            height: 8px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .forgot-link {
            color: #9ca3af;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: #818cf8;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4f46e5, #9333ea);
            color: white;
            font-weight: 600;
            font-size: 15px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(79, 70, 229, 0.45);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: #6b7280;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Social Buttons */
        .social-group {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .social-btn {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .social-btn svg {
            width: 20px;
            height: 20px;
            fill: #d1d5db;
            transition: fill 0.3s ease;
        }

        .social-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .social-btn:hover svg {
            fill: #ffffff;
        }

        /* Bottom Text */
        .signup-text {
            text-align: center;
            font-size: 13px;
            color: #9ca3af;
        }

        .signup-link {
            color: #818cf8;
            font-weight: 600;
            text-decoration: none;
            margin-left: 4px;
            transition: color 0.3s ease;
        }

        .signup-link:hover {
            color: #a5b4fc;
        }

        /* Alerts */
        .alert {
            padding: 12px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        .error-text {
            color: #f87171;
            font-size: 12px;
            margin-top: 6px;
        }

        .footer-copyright {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-top: 24px;
        }

        @media (max-width: 480px) {
            .glass {
                padding: 24px 18px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="glass">
            
            {{-- Logo --}}
            <div class="header">
                <div class="logo-icon">🎬</div>
                <h1 class="logo-text">M-VIDEO</h1>
                <p class="subtitle">Sign in to continue watching</p>
            </div>

            {{-- Session Messages --}}
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                {{-- Email or Phone --}}
                <div class="form-group">
                    <div class="input-relative">
                        <input 
                            type="text" 
                            id="login" 
                            name="login" 
                            value="{{ old('login') }}"
                            placeholder="Email or Phone Number"
                            class="input-field"
                            required 
                            autofocus
                        >
                        <!-- Inline SVG User Icon -->
                        <svg class="input-icon" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                    @error('login')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <div class="input-relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Password"
                            class="input-field"
                            required
                        >
                        <!-- Inline SVG Lock Icon -->
                        <svg class="input-icon" viewBox="0 0 24 24">
                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                        </svg>

                        <!-- Toggle Password Button -->
                        <button type="button" class="toggle-btn" onclick="togglePasswordVisibility()">
                            <svg class="toggle-icon" id="eyeIcon" viewBox="0 0 24 24">
                                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember & Forgot --}}
                <div class="remember-row">
                    <label class="remember-label">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="custom-checkbox" 
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span>Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="submit-btn">
                    <span>Sign In</span>
                </button>

                {{-- Divider --}}
                <div class="divider">
                    <span>or continue with</span>
                </div>

                {{-- Social Buttons (Inline SVG Icons) --}}
                <div class="social-group">
                    <!-- Google SVG -->
                    <button type="button" class="social-btn" title="Google">
                        <svg viewBox="0 0 24 24">
                            <path d="M12.24 10.285V13.4h6.887c-.58 3.025-3.13 5.274-6.887 5.274-4.217 0-7.63-3.413-7.63-7.63s3.413-7.63 7.63-7.63c1.9 0 3.63.69 4.97 1.83l2.36-2.36C17.65 1.15 15.1 0 12.24 0 5.48 0 0 5.48 0 12.24s5.48 12.24 12.24 12.24c6.84 0 12.01-4.8 12.01-11.83 0-.82-.08-1.57-.19-2.37H12.24z"/>
                        </svg>
                    </button>
                    <!-- Facebook SVG -->
                    <button type="button" class="social-btn" title="Facebook">
                        <svg viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </button>
                    <!-- Apple SVG -->
                    <button type="button" class="social-btn" title="Apple">
                        <svg viewBox="0 0 24 24">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 6.32c.62-.75 1.04-1.8 0.93-2.85-.9.04-2 0.6-2.65 1.36-.58.67-1.09 1.75-.95 2.78 1.01.08 2.05-.54 2.67-1.29z"/>
                        </svg>
                    </button>
                </div>

                {{-- Register Link --}}
                <p class="signup-text">
                    Don't have an account?
                    <a href="{{ Route::has('register') ? route('register') : '#' }}" class="signup-link">
                        Sign Up
                    </a>
                </p>
            </form>
        </div>
        
        {{-- Footer --}}
        <p class="footer-copyright">
            &copy; {{ date('Y') }} M-VIDEO. All rights reserved.
        </p>
    </div>

    {{-- Toggle Password Script --}}
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.44-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.17c0-1.66-1.34-3-3-3l-.17.02z"/>`;
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>`;
            }
        }
    </script>
</body>
</html>
