<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — roomor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-wrapper">

        <!-- ===== LEFT: Form ===== -->
        <div class="auth-form-panel">
            <div class="auth-form-inner">

                <h1 class="auth-heading" style="justify-content: center;">
                    Reset Password
                </h1>
                <p class="auth-subheading">Masukkan email dan password baru Anda</p>

                {{-- Session Status --}}
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-error">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.direct-reset') }}" class="auth-form">
                    @csrf

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email <span class="required">*</span>
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-input @error('email') is-error @enderror"
                            placeholder="Masukkan email Anda"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>

                    {{-- Password Baru --}}
                    <div class="form-group">
                        <label for="password" class="form-label">
                            Password Baru <span class="required">*</span>
                        </label>
                        <div class="input-password-wrap">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-input @error('password') is-error @enderror"
                                placeholder="Masukkan password baru"
                                required
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword('password', this)" aria-label="Tampilkan password">
                                <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            Konfirmasi Password <span class="required">*</span>
                        </label>
                        <div class="input-password-wrap">
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="form-input"
                                placeholder="Ulangi password baru"
                                required
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', this)" aria-label="Tampilkan konfirmasi password">
                                <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth-primary">
                        Reset Password
                    </button>

                    <p class="auth-switch">
                        Ingat password?
                        <a href="{{ route('login') }}" class="auth-switch-link">Masuk</a>
                    </p>

                </form>

            </div>
        </div>

        <!-- ===== RIGHT: Image ===== -->
        <div class="auth-image-panel">
            <div class="auth-image-wrap">
                <img src="{{ asset('images/login&signup.png') }}" alt="roomor Interior" class="auth-bg-img">
            </div>
        </div>

    </div>

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            btn.classList.toggle('active', isPassword);
        }
    </script>
</body>
</html>