@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="fas fa-cube me-2"></i> Varian Produk</h3>
                <button class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#varianModal">
                    <i class="fas fa-plus"></i> Tambah Varian
                </button>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered" id="varianTable">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>SKU</th>
                            <th>Warna</th>
                            <th>Ukuran</th>
                            <th>Lengan</th>
                            <th>Bahan</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($varians as $v)
                            <tr>
                                <td>{{ $v->produk->nama }}</td>
                                <td>{{ $v->sku }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2 rounded"
                                            style="display:inline-block; width:20px; height:20px;border:1px solid #000; background: {{ $v->warna }};"></span>
                                        {{ $v->warna }}
                                    </div>
                                </td>

                                <td>{{ $v->ukuran }}</td>
                                <td>{{ ucfirst($v->lengan) }}</td>
                                <td>{{ $v->material->nama ?? '-' }}</td>
                                <td>{{ $v->stok }}</td>
                                <td>Rp {{ number_format($v->harga ?? $v->produk->harga, 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm text-white btn-edit" data-id="{{ $v->id }}"
                                        data-produk="{{ $v->produk_id }}" data-sku="{{ $v->sku }}"
                                        data-warna="{{ $v->warna }}" data-ukuran="{{ $v->ukuran }}"
                                        data-lengan="{{ $v->lengan }}" data-material="{{ $v->material_id }}"
                                        data-stok="{{ $v->stok }}" data-harga="{{ $v->harga }}"
                                        data-bs-toggle="modal" data-bs-target="#varianModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $v->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit -->
    <div class="modal fade" id="varianModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="varianForm" method="POST" class="modal-content">
                @csrf
                <div id="method"></div>
                <div class="modal-header">
                    <h5 id="modalTitle">Tambah Varian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Produk</label>
                        <select name="produk_id" id="produk_id" class="form-select" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($produk as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>SKU</label>
                        <input type="text" name="sku" id="sku" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Warna</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-palette"></i></span>
                            <input type="color" name="warna" id="warna" class="form-control form-control-color"
                                required>
                        </div>
                        <small class="text-muted">Pilih warna (hexadecimal)</small>
                    </div>

                    <div class="mb-3">
                        <label>Ukuran</label>
                        <select name="ukuran" id="ukuran" class="form-select" required>
                            <option>XS</option>
                            <option>S</option>
                            <option>M</option>
                            <option>L</option>
                            <option>XL</option>
                            <option>XXL</option>
                            <option>XXXL</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Lengan</label>
                        <select name="lengan" id="lengan" class="form-select" required>
                            <option value="pendek">Pendek</option>
                            <option value="panjang">Panjang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Bahan</label>
                        <select name="material_id" id="material_id" class="form-select">
                            <option value="">-</option>
                            @foreach ($materials as $m)
                                <option value="{{ $m->id }}">{{ $m->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Stok</label>
                        <input type="number" name="stok" id="stok" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="number" name="harga" id="harga" class="form-control">
                        <small class="text-muted">Kosongkan untuk pakai harga produk utama</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#varianTable').DataTable();

            // Tambah
            $('.btn-add').on('click', function() {
                $('#modalTitle').text('Tambah Varian');
                $('#varianForm').attr('action', "{{ route('produk-varian.store') }}");
                $('#method').html('');
                $('#produk_id, #sku, #warna, #ukuran, #lengan, #material_id, #stok, #harga').val('');
            });

            // Edit
            $('.btn-edit').on('click', function() {
                let id = $(this).data('id');
                $('#modalTitle').text('Edit Varian');
                $('#varianForm').attr('action', '/admin/produk-varian/' + id);
                $('#method').html('@method('PUT')');

                $('#produk_id').val($(this).data('produk'));
                $('#sku').val($(this).data('sku'));
                $('#warna').val($(this).data('warna'));
                $('#ukuran').val($(this).data('ukuran'));
                $('#lengan').val($(this).data('lengan'));
                $('#material_id').val($(this).data('material'));
                $('#stok').val($(this).data('stok'));
                $('#harga').val($(this).data('harga'));
            });

            // Delete
            $('.btn-delete').on('click', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin?',
                    text: "Varian akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/produk-varian/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Terhapus!', 'Varian berhasil dihapus.',
                                            'success')
                                        .then(() => location.reload());
                                }
                            });
                    }
                })
            });
        });
    </script>
@endpush
