<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Roomor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <style>
        /* ── Heading inline logo ── */
        .auth-heading {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            flex-wrap: wrap;
        }
        .auth-heading-logo {
            height: 3.5rem;
            width: auto;
            display: inline-block;
            vertical-align: middle;
        }


    </style>
</head>
<body>
    <div class="auth-wrapper">

        {{-- ===== LEFT PANEL: FORM ===== --}}
        <div class="auth-form-panel">
            <div class="auth-form-inner">

                {{-- Heading with inline logo --}}
                <h1 class="auth-heading" style="justify-content: center;">
                    Daftar ke
                    <img src="{{ asset('images/logo02.png') }}" alt="Roomor" class="auth-heading-logo">
                </h1>
                <p class="auth-subheading">Temukan ruang yang sesuai dengan gaya hidupmu</p>

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-error">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('register.post') }}" class="auth-form">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div class="form-group">
                        <label for="name" class="form-label">
                            Nama Lengkap <span class="required">*</span>
                        </label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            class="form-input @error('name') is-error @enderror"
                            placeholder="Masukkan nama lengkap Anda"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                        >
                    </div>

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
                            autocomplete="username"
                        >
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label for="password" class="form-label">
                            Password <span class="required">*</span>
                        </label>
                        <div class="input-password-wrap">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-input @error('password') is-error @enderror"
                                placeholder="Masukkan password Anda"
                                required
                                autocomplete="new-password"
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword('password', this)" tabindex="-1" aria-label="Tampilkan password">
                                <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg class="icon-eye-off" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
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
                                class="form-input @error('password_confirmation') is-error @enderror"
                                placeholder="Masukkan password Anda"
                                required
                                autocomplete="new-password"
                            >
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', this)" tabindex="-1" aria-label="Tampilkan konfirmasi password">
                                <svg class="icon-eye" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg class="icon-eye-off" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                    <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-auth-primary">
                        Daftar
                    </button>

                    {{-- Login Link --}}
                    <p class="auth-switch">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}" class="auth-switch-link">Log In</a>
                    </p>
                </form>

            </div>
        </div>

        {{-- ===== RIGHT PANEL: IMAGE ===== --}}
        <div class="auth-image-panel">
            <div class="auth-image-wrap">
                <img src="{{ asset('images/login&signup.png') }}" alt="Roomor Interior" class="auth-bg-img">
            </div>
        </div>

    </div>

    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.querySelector('.icon-eye').style.display     = isHidden ? '' : 'none';
            btn.querySelector('.icon-eye-off').style.display = isHidden ? 'none' : '';
        }
    </script>
</body>
</html>