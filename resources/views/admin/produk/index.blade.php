@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="fas fa-box me-2"></i> Data Produk</h3>
                <button class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#produkModal">
                    <i class="fas fa-plus"></i> Tambah Produk
                </button>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered" id="produkTable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Jenis</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produk as $p)
                            <tr>
                                <td>{{ $p->nama }}</td>
                                <td>{{ $p->jenis }}</td>
                                <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $p->aktif ? 'success' : 'secondary' }}">
                                        {{ $p->aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning text-white btn-sm btn-edit" data-id="{{ $p->id }}"
                                        data-nama="{{ $p->nama }}" data-jenis="{{ $p->jenis }}"
                                        data-harga="{{ $p->harga }}" data-deskripsi="{{ $p->deskripsi }}"
                                        data-aktif="{{ $p->aktif }}" data-bs-toggle="modal"
                                        data-bs-target="#produkModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $p->id }}">
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
    <div class="modal fade" id="produkModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="produkForm" method="POST" class="modal-content">
                @csrf
                <div id="method"></div>
                <div class="modal-header">
                    <h5 id="modalTitle">Tambah Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" name="nama" id="nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-list"></i></span>
                            <input type="text" name="jenis" id="jenis" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-money-bill"></i></span>
                            <input type="number" name="harga" id="harga" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="aktif" id="aktif" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
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
            $('#produkTable').DataTable();

            // Tambah Produk
            $('.btn-add').on('click', function() {
                $('#modalTitle').text('Tambah Produk');
                $('#produkForm').attr('action', "{{ route('produk.store') }}");
                $('#method').html('');
                $('#nama').val('');
                $('#jenis').val('');
                $('#harga').val('');
                $('#deskripsi').val('');
                $('#aktif').val(1);
            });

            // Edit Produk
            $('.btn-edit').on('click', function() {
                let id = $(this).data('id');
                $('#modalTitle').text('Edit Produk');
                $('#produkForm').attr('action', '/admin/produk/' + id);
                $('#method').html('@method('PUT')');

                $('#nama').val($(this).data('nama'));
                $('#jenis').val($(this).data('jenis'));
                $('#harga').val($(this).data('harga'));
                $('#deskripsi').val($(this).data('deskripsi'));
                $('#aktif').val($(this).data('aktif'));
            });

            // Hapus Produk
            $('.btn-delete').on('click', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin?',
                    text: "Produk akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/produk/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Terhapus!', 'Produk berhasil dihapus.',
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
