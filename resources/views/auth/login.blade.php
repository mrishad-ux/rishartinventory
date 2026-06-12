<!DOCTYPE html>
<html lang="en" data-theme="ember">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — Lord Of Wraps</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --accent: #6366f1;
            --accent-2: #4f46e5;
            --accent-rgb: 99,102,241;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 600px;
            padding: 20px;
        }
        .login-card {
            background: #1e293b;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        .brand-name {
            font-family: 'Inter', sans-serif;
            font-size: 48px;
            font-weight: 800;
            color: #6366f1;
            text-align: center;
            display: block;
            margin: 0 auto 8px;
        }
        .brand-sub {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 400;
            color: rgba(255,255,255,0.5);
            text-align: center;
            display: block;
            margin-bottom: 28px;
        }
        .login-title {
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            font-weight: 600;
            color: rgba(255,255,255,0.9);
            text-align: center;
            margin-bottom: 24px;
        }
        .form-input {
            width: 100%;
            padding: 11px 14px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            color: #e2e8f0;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
            background: rgba(255,255,255,0.08);
        }
        .form-input::placeholder { color: #64748b; }
        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 7px;
        }
        .form-group { margin-bottom: 18px; }
        .password-wrapper { position: relative; }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 14px;
            padding: 4px;
        }
        .password-toggle:hover { color: #94a3b8; }
        .btn-login {
            width: 100%;
            background: #6366f1;
            color: #ffffff;
            border: none;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 600;
            padding: 13px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            margin-top: 8px;
        }
        .btn-login:hover {
            background: #4f46e5;
            transform: translateY(-1px);
        }
        .alert-error {
            background: rgba(220,38,38,0.1);
            border: 1px solid rgba(220,38,38,0.3);
            color: #fca5a5;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 13px;
            text-align: center;
        }
        .footer-note {
            text-align: center;
            font-size: 11px;
            color: rgba(255,255,255,0.25);
            margin-top: 20px;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body style="font-family:'Inter',sans-serif; background: linear-gradient(135deg,#1e293b 0%,#1e293b 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; color:white;">
    <div style="width:100%; max-width:520px; padding:20px;">
        <div class="brand-name">Lord Of Wraps</div>
        <div class="brand-sub">Restaurant Management System</div>

        <div class="login-card">
            <div class="login-title">Sign In to Your Account</div>
                
                @if($errors->any())
                    <div class="alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-input" 
                               placeholder="Enter your email"
                               value="{{ old('email') }}"
                               required 
                               autocomplete="email"
                               autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-input" 
                                   placeholder="Enter your password"
                                   required 
                                   autocomplete="current-password"
                                   style="padding-right: 40px;">
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <span id="toggleIcon">👁</span>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-login">
                        Sign In
                    </button>
                </form>
            </div>
        </div>
        <p class="footer-note">Lord Of Wraps v1.0 — Internal Use Only</p>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = '👁';
            }
        }
    </script>
</body>
</html>