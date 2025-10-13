@extends('layouts.homepage')

@section('content')
    <style>
        .login-section {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 85vh;
            background: radial-gradient(circle at top left, #0d6efd15, transparent 70%);
            animation: fadeIn 0.6s ease-in-out;
        }

        .login-card {
            backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease-in-out;
        }

        body.dark .login-card {
            background: rgba(20, 20, 20, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #eee;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
        }

        .form-control {
            border-radius: 10px;
            padding: 0.65rem 0.9rem;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .btn-login {
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.4);
        }

        body.dark .form-control {
            background-color: #1e1e1e;
            color: #fff;
            border-color: #333;
        }

        body.dark .form-control:focus {
            background-color: #1e1e1e;
            border-color: #66b2ff;
            box-shadow: 0 0 0 0.2rem rgba(102, 178, 255, 0.25);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <section class="login-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Toko Delapan" class="mb-3"
                            style="max-height: 70px;">
                        <h4 class="fw-bold mb-1">Selamat Datang di Toko Delapan</h4>
                        <p class="text-muted">Silakan login untuk melanjutkan</p>
                    </div>

                    <!-- Card -->
                    <div class="card login-card border-0">
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">Password</label>
                                    <div class="input-group">
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required placeholder="Masukkan password Anda">
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                            <i class="bi bi-eye-slash"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Remember -->
                                <div class="mb-3 form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">Ingat saya</label>
                                </div>

                                <!-- Button -->
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg btn-login">
                                        Masuk Sekarang
                                    </button>
                                </div>

                                <!-- Forgot password -->
                                @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a class="small text-decoration-none" href="{{ route('password.request') }}">
                                            Lupa password?
                                        </a>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>

                    <!-- Register -->
                    <div class="text-center mt-4">
                        <p class="text-muted mb-0">Belum punya akun?
                            <a href="{{ route('register') }}" class="fw-semibold text-decoration-none text-primary">
                                Daftar Sekarang
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            const isHidden = password.type === 'password';
            password.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    </script>
@endsection
