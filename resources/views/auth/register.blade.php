@extends('layouts.homepage')

@section('content')
    <style>
        .register-section {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 90vh;
            animation: fadeIn 0.6s ease-in-out;
        }

        .register-card {
            max-width: 720px;
            margin: auto;
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 18px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease-in-out;
        }

        body.dark .register-card {
            background: rgba(20, 20, 20, 0.88);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #eee;
        }

        .register-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 0.55rem 0.9rem;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
        }

        body.dark .form-control,
        body.dark .form-select {
            background-color: #1e1e1e;
            color: #fff;
            border-color: #333;
        }

        /* === Compact Modern Button === */
        .btn-primary {
            position: relative;
            overflow: hidden;
            border: none;
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            background: linear-gradient(135deg, #0d6efd, #3b8efc);
            border-radius: 40px;
            padding: 0.55rem 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(13, 110, 253, 0.45);
            background: linear-gradient(135deg, #3b8efc, #0d6efd);
        }

        .btn-primary:active {
            transform: scale(0.97);
            box-shadow: 0 3px 6px rgba(13, 110, 253, 0.4);
        }

        .btn-primary::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
            transform: skewX(-20deg);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 120%;
        }

        body.dark .btn-primary {
            background: linear-gradient(135deg, #66b2ff, #007bff);
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

    <section class="register-section">
        <div class="container">
            <div class="register-card border-0">
                <div class="card-body p-4 p-md-4">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="mb-3" style="max-height: 60px;">
                        <h5 class="fw-bold">Buat Akun Baru</h5>
                        <p class="text-muted small">Gabung bersama Toko Delapan dan nikmati kemudahan berbelanja.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama" class="form-label fw-semibold small">Nama Lengkap</label>
                                <input id="nama" type="text"
                                    class="form-control @error('nama') is-invalid @enderror" name="nama"
                                    value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold small">Alamat Email</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required placeholder="contoh@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="no_hp" class="form-label fw-semibold small">Nomor HP</label>
                                <input id="no_hp" type="text"
                                    class="form-control @error('no_hp') is-invalid @enderror" name="no_hp"
                                    value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="jenis_kelamin" class="form-label fw-semibold small">Jenis Kelamin</label>
                                <select id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                    name="jenis_kelamin">
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="tgl_lahir" class="form-label fw-semibold small">Tanggal Lahir</label>
                                <input id="tgl_lahir" type="date"
                                    class="form-control @error('tgl_lahir') is-invalid @enderror" name="tgl_lahir"
                                    value="{{ old('tgl_lahir') }}">
                                @error('tgl_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold small">Password</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required
                                    placeholder="Minimal 8 karakter">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password-confirm" class="form-label fw-semibold small">Konfirmasi
                                    Password</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required placeholder="Ulangi password">
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-md">
                                <i class="fa-solid fa-user-plus me-2"></i> Daftar Akun
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="text-muted mb-0 small">Sudah punya akun?
                                <a href="{{ route('login') }}" class="fw-semibold text-decoration-none text-primary">
                                    Login sekarang
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
