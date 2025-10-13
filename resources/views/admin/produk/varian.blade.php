@extends('layouts.app')

@section('content')
    <style>
        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
        }

        .nav-tabs .nav-link {
            color: #495057;
            border: none;
            border-bottom: 2px solid transparent;
            font-weight: 500;
            transition: all 0.2s ease;
            padding: 10px 20px;
        }

        .nav-tabs .nav-link:hover {
            color: #0d6efd;
            background-color: #f8f9fa;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            background-color: transparent;
            font-weight: 600;
        }
    </style>

    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0 fw-bold"><i class="fas fa-cubes me-2 text-primary"></i> Varian Produk</h3>

                <div class="d-flex align-items-center gap-2">
                    <label class="fw-semibold">Pilih Produk:</label>
                    <select id="produkSelect" class="form-select w-auto" onchange="window.location='?produk_id=' + this.value">
                        @foreach ($produk as $p)
                            <option value="{{ $p->id }}"
                                {{ $selectedProduk && $selectedProduk->id == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card-body">
                @if ($selectedProduk)
                    <div class="alert alert-light-primary border fw-semibold mb-4">
                        Produk aktif: <span class="text-primary">{{ $selectedProduk->nama }}</span>
                    </div>

                    <ul class="nav nav-tabs" id="varianTabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#warna">Warna</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#bahan">Bahan</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#lengan">Lengan</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#ukuran">Ukuran</a></li>
                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#mockup">Mockup</a></li>
                    </ul>

                    <div class="tab-content mt-4">

                        <!-- ========== WARNA ========== -->
                        <div class="tab-pane fade show active" id="warna">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="fw-bold">Daftar Warna</h5>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahWarna">
                                    <i class="fas fa-plus"></i> Tambah Warna
                                </button>
                            </div>

                            <table class="table table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Kode HEX</th>
                                        <th>Preview</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($selectedProduk->warna as $warna)
                                        <tr>
                                            <td>{{ $warna->nama }}</td>
                                            <td>{{ $warna->hex }}</td>
                                            <td>
                                                <div
                                                    style="width:25px;height:25px;border-radius:4px;background:{{ $warna->hex }};">
                                                </div>
                                            </td>
                                            <td class="d-flex gap-2">
                                                <button class="btn btn-sm btn-light border editWarnaBtn"
                                                    data-id="{{ $warna->id }}" data-nama="{{ $warna->nama }}"
                                                    data-hex="{{ $warna->hex }}">
                                                    <i class="fas fa-edit text-primary"></i>
                                                </button>
                                                <form method="POST"
                                                    action="{{ route('varian.warna.destroy', $warna->id) }}"
                                                    onsubmit="return confirm('Yakin hapus warna ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger text-white"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Belum ada warna</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- ========== BAHAN ========== -->
                        <div class="tab-pane fade" id="bahan">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="fw-bold">Daftar Bahan</h5>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahBahan">
                                    <i class="fas fa-plus"></i> Tambah Bahan
                                </button>
                            </div>
                            <table class="table table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Tambahan Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($selectedProduk->bahan as $b)
                                        <tr>
                                            <td>{{ $b->nama }}</td>
                                            <td>Rp {{ number_format($b->tambahan_harga, 0, ',', '.') }}</td>
                                            <td class="d-flex gap-2">
                                                <button class="btn btn-sm btn-light border editBahanBtn"
                                                    data-id="{{ $b->id }}" data-nama="{{ $b->nama }}"
                                                    data-harga="{{ $b->tambahan_harga }}">
                                                    <i class="fas fa-edit text-primary"></i>
                                                </button>
                                                <form method="POST" action="{{ route('varian.bahan.destroy', $b->id) }}"
                                                    onsubmit="return confirm('Hapus bahan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger text-white"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Belum ada bahan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- ========== LENGAN ========== -->
                        <div class="tab-pane fade" id="lengan">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="fw-bold">Daftar Lengan</h5>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahLengan">
                                    <i class="fas fa-plus"></i> Tambah Lengan
                                </button>
                            </div>
                            <table class="table table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tipe</th>
                                        <th>Tambahan Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($selectedProduk->lengan as $l)
                                        <tr>
                                            <td>{{ ucfirst($l->tipe) }}</td>
                                            <td>Rp {{ number_format($l->tambahan_harga, 0, ',', '.') }}</td>
                                            <td class="d-flex gap-2">
                                                <button class="btn btn-sm btn-light border editLenganBtn"
                                                    data-id="{{ $l->id }}" data-tipe="{{ $l->tipe }}"
                                                    data-harga="{{ $l->tambahan_harga }}">
                                                    <i class="fas fa-edit text-primary"></i>
                                                </button>
                                                <form method="POST"
                                                    action="{{ route('varian.lengan.destroy', $l->id) }}"
                                                    onsubmit="return confirm('Hapus tipe lengan ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger text-white"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Belum ada data lengan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- ========== UKURAN ========== -->
                        <div class="tab-pane fade" id="ukuran">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="fw-bold">Daftar Ukuran (Global)</h5>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahUkuran">
                                    <i class="fas fa-plus"></i> Tambah Ukuran
                                </button>
                            </div>

                            <table class="table table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Tambahan Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (\App\Models\Ukuran::all() as $u)
                                        <tr>
                                            <td>{{ $u->nama }}</td>
                                            <td>Rp {{ number_format($u->tambahan_harga, 0, ',', '.') }}</td>
                                            <td class="d-flex gap-2">
                                                <button class="btn btn-sm btn-light border editUkuranBtn"
                                                    data-id="{{ $u->id }}" data-nama="{{ $u->nama }}"
                                                    data-harga="{{ $u->tambahan_harga }}">
                                                    <i class="fas fa-edit text-primary"></i>
                                                </button>
                                                <form method="POST"
                                                    action="{{ route('varian.ukuran.destroy', $u->id) }}"
                                                    onsubmit="return confirm('Hapus ukuran ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-danger text-white"><i
                                                            class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Belum ada ukuran</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                        <!-- ========== MOCKUP ========== -->
                        <div class="tab-pane fade" id="mockup">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="fw-bold">Daftar Mockup</h5>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahMockup">
                                    <i class="fas fa-plus"></i> Tambah Mockup
                                </button>
                            </div>
                            <div class="row">
                                @forelse ($selectedProduk->mockup as $m)
                                    <div class="col-md-3 mb-3">
                                        <div class="card h-100 shadow-sm">
                                            <img src="{{ asset('storage/' . $m->file_path) }}" class="card-img-top"
                                                alt="mockup">
                                            <div class="card-body text-center">
                                                <p class="fw-semibold text-capitalize">{{ $m->angle }}</p>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <form method="POST"
                                                        action="{{ route('varian.mockup.destroy', $m->id) }}"
                                                        onsubmit="return confirm('Hapus mockup ini?')">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-danger"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted">Belum ada mockup</div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                @else
                    <div class="alert alert-warning text-center">Belum ada produk tersedia.</div>
                @endif
            </div>
        </div>
    </div>

    {{-- ========================== MODAL TAMBAH & EDIT ========================== --}}
    @include('admin.produk.varian-all-modals')
@endsection
