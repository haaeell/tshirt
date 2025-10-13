@extends('layouts.homepage')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4 text-center">Daftar Akun Baru</h4>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row g-3">
                            {{-- Nama --}}
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autofocus
                                    placeholder="Masukkan nama lengkap">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required
                                    placeholder="contoh@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- No HP --}}
                            <div class="col-md-6">
                                <label for="no_hp" class="form-label fw-semibold">Nomor HP</label>
                                <input id="no_hp" type="text"
                                    class="form-control @error('no_hp') is-invalid @enderror"
                                    name="no_hp" value="{{ old('no_hp') }}"
                                    placeholder="08xxxxxxxxxx">
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div class="col-md-6">
                                <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin</label>
                                <select id="jenis_kelamin"
                                    class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                    name="jenis_kelamin">
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin')=='Laki-laki' ? 'selected':'' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin')=='Perempuan' ? 'selected':'' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div class="col-md-6">
                                <label for="tgl_lahir" class="form-label fw-semibold">Tanggal Lahir</label>
                                <input id="tgl_lahir" type="date"
                                    class="form-control @error('tgl_lahir') is-invalid @enderror"
                                    name="tgl_lahir" value="{{ old('tgl_lahir') }}">
                                @error('tgl_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password" required placeholder="Minimal 8 karakter">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="col-md-6">
                                <label for="password-confirm" class="form-label fw-semibold">Konfirmasi Password</label>
                                <input id="password-confirm" type="password"
                                    class="form-control" name="password_confirmation" required
                                    placeholder="Ulangi password">
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                Daftar Akun
                            </button>
                        </div>

                        {{-- Link Login --}}
                        <div class="text-center mt-3">
                            <p class="text-muted mb-0">Sudah punya akun?
                                <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Login sekarang</a>
                            </p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
