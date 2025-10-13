@extends('layouts.homepage')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Logo -->
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Toko Delapan" class="mb-3" style="max-height: 70px;">
                <h4 class="fw-bold">Selamat Datang di Toko Delapan</h4>
                <p class="text-muted">Silakan login untuk melanjutkan</p>
            </div>

            <!-- Card -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autofocus
                                   placeholder="Masukkan email Anda">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" required placeholder="Masukkan password Anda">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
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
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                Login
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

            <!-- Register link -->
            <div class="text-center mt-4">
                <p class="text-muted mb-0">Belum punya akun?
                    <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Daftar sekarang</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
