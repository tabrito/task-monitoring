<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - TaskFlow</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/jpg" href="{{ asset('images/task-logo.jpg') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">

    @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/css/pages/login.css'
    ])
</head>

<body>

    <div class="login-page">
        <div class="login-layout">

            <section class="login-brand-panel">
                <div class="login-brand">
                    <div class="login-brand-icon">
                        <img src="{{ asset('images/task-logo-trans.png') }}" alt="TaskFlow Logo">
                    </div>
                    <span>TaskFlow</span>
                </div>

                <div class="login-brand-content">
                    <span class="login-eyebrow">PROJECT MANAGEMENT</span>

                    <h1>Work Smarter, Achieve More.</h1>

                    <p>
                        Kelola proyek, pantau progres pekerjaan, dan
                        kolaborasi dengan tim dalam satu platform.
                    </p>

                    <div class="login-feature-list">
                        <div class="login-feature">
                            <span class="login-feature-icon">
                                <i class="fa-solid fa-list-check"></i>
                            </span>
                            <div>
                                <strong>Manajemen Task</strong>
                                <span>Pantau tugas dan progres pekerjaan.</span>
                            </div>
                        </div>

                        <div class="login-feature">
                            <span class="login-feature-icon">
                                <i class="fa-solid fa-chart-line"></i>
                            </span>
                            <div>
                                <strong>Monitoring Proyek</strong>
                                <span>Ketahui perkembangan proyek secara praktis.</span>
                            </div>
                        </div>

                        <div class="login-feature">
                            <span class="login-feature-icon">
                                <i class="fa-solid fa-people-group"></i>
                            </span>
                            <div>
                                <strong>Kolaborasi Tim</strong>
                                <span>Satukan pekerjaan dan tanggung jawab tim.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="login-brand-footer">
                    <span class="login-footer-dot"></span>
                    Satu platform untuk pekerjaan yang lebih terorganisir.
                </div>
            </section>

            <section class="login-form-panel">
                <div class="login-form-container">

                    <div class="login-mobile-brand">
                        <span class="login-brand-icon">
                            <i class="fa-solid fa-layer-group"></i>
                        </span>
                        <span>TaskFlow</span>
                    </div>

                    <div class="login-welcome">
                        <span class="login-welcome-label">SELAMAT DATANG KEMBALI</span>
                        <h2>Masuk ke akun Anda</h2>
                        <p>Masukkan email dan password untuk melanjutkan.</p>
                    </div>

                    @if (session('success'))
                    <div class="login-alert login-alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="login-alert login-alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="login-alert login-alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST" class="login-form">
                        @csrf

                        <div class="login-field">
                            <label for="email">Alamat Email</label>

                            <div class="login-input-wrapper">
                                <i class="fa-regular fa-envelope"></i>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    autocomplete="email"
                                    required
                                    autofocus>
                            </div>

                            @error('email')
                            <small class="login-field-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="login-field">
                            <div class="login-password-heading">
                                <label for="password">Password</label>
                            </div>

                            <div class="login-input-wrapper">
                                <i class="fa-solid fa-lock"></i>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Masukkan password"
                                    autocomplete="current-password"
                                    required>

                                <button
                                    type="button"
                                    class="login-toggle-password"
                                    id="togglePassword"
                                    aria-label="Tampilkan password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>

                            @error('password')
                            <small class="login-field-error">{{ $message }}</small>
                            @enderror
                        </div>

                        <label class="login-remember">
                            <input type="checkbox" name="remember" value="1">
                            <span>Ingat saya</span>
                        </label>

                        <button type="submit" class="login-submit">
                            <span>Masuk ke TaskFlow</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </form>

                    <div class="login-security-note">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Akses akun Anda dengan aman dan bertanggung jawab.</span>
                    </div>

                </div>
            </section>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('togglePassword');
            const password = document.getElementById('password');

            if (toggle && password) {
                toggle.addEventListener('click', function() {
                    const isHidden = password.type === 'password';

                    password.type = isHidden ? 'text' : 'password';

                    toggle.innerHTML = isHidden ?
                        '<i class="fa-regular fa-eye-slash"></i>' :
                        '<i class="fa-regular fa-eye"></i>';

                    toggle.setAttribute(
                        'aria-label',
                        isHidden ? 'Sembunyikan password' : 'Tampilkan password'
                    );
                });
            }
        });
    </script>

</body>

</html>