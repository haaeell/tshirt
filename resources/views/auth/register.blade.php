@extends('layouts.homepage')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">Register</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- No HP --}}
                            <div class="mb-3">
                                <label for="no_hp" class="form-label">Nomor HP</label>
                                <input id="no_hp" type="text"
                                    class="form-control @error('no_hp') is-invalid @enderror" name="no_hp"
                                    value="{{ old('no_hp') }}">
                                @error('no_hp')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror"
                                    name="jenis_kelamin">
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div class="mb-3">
                                <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                                <input id="tgl_lahir" type="date"
                                    class="form-control @error('tgl_lahir') is-invalid @enderror" name="tgl_lahir"
                                    value="{{ old('tgl_lahir') }}">
                                @error('tgl_lahir')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="mb-3">
                                <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required>
                            </div>

                            {{-- Tombol --}}
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Daftar Akun
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
