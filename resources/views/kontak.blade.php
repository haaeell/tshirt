@extends('layouts.homepage')
@section('title', 'Kontak Kami - Toko Delapan')

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-3">Hubungi Kami</h1>
            <p class="text-muted">Ada pertanyaan atau ingin melakukan pemesanan? Kami siap membantu!</p>
        </div>

        <div class="row gy-4 justify-content-center">
            <div class="col-md-5">
                <div class="p-4 border rounded-4 shadow-sm bg-white">
                    <h5 class="fw-semibold mb-3">Informasi Kontak</h5>
                    <p>📍 Jl. Merdeka No. 88, Jakarta, Indonesia</p>
                    <p>📞 +62 812-3456-7890</p>
                    <p>📧 toko8@gmail.com</p>
                    <p>🕘 Senin - Sabtu, 09.00 - 18.00</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-4 border rounded-4 shadow-sm bg-white">
                    <h5 class="fw-semibold mb-3">Kirim Pesan</h5>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" placeholder="Nama lengkap">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" placeholder="Alamat email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pesan</label>
                            <textarea class="form-control" rows="4" placeholder="Tulis pesanmu di sini"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
