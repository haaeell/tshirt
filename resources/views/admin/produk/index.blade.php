@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0 fw-bold"><i class="fas fa-box me-2 text-primary"></i> Data Produk</h3>
            <button class="btn btn-sm btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#produkModal">
                <i class="fas fa-plus"></i> Tambah Produk
            </button>
        </div>
        <div class="card-body">
            <table class="table table-striped table-bordered" id="produkTable">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Jenis Produk</th>
                        <th>Harga</th>
                        <th>Tanggal Dibuat</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produk as $p)
                        <tr>
                            <td>{{ $p->nama }}</td>
                            <td>{{ $p->jenis_produk }}</td>
                            <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                            <td>{{ $p->created_at->format('d M Y') }}</td>
                            <td class="text-nowrap">
                                <button class="btn btn-warning text-white btn-sm btn-edit"
                                    data-id="{{ $p->id }}"
                                    data-nama="{{ $p->nama }}"
                                    data-jenis_produk="{{ $p->jenis_produk }}"
                                    data-harga="{{ $p->harga }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#produkModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm text-white btn-delete" data-id="{{ $p->id }}">
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

<!-- Modal Tambah/Edit Produk -->
<div class="modal fade" id="produkModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="produkForm" method="POST" class="modal-content">
            @csrf
            <div id="method"></div>
            <div class="modal-header">
                <h5 id="modalTitle" class="modal-title fw-bold">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Produk</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Produk</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                        <input type="text" name="jenis_produk" id="jenis_produk" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Harga</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga" id="harga" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
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
        $('#jenis_produk').val('');
        $('#harga').val('');
    });

    // Edit Produk
    $('.btn-edit').on('click', function() {
        let id = $(this).data('id');
        $('#modalTitle').text('Edit Produk');
        $('#produkForm').attr('action', '/admin/produk/' + id);
        $('#method').html('@method("PUT")');

        $('#nama').val($(this).data('nama'));
        $('#jenis_produk').val($(this).data('jenis_produk'));
        $('#harga').val($(this).data('harga'));
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
                        Swal.fire('Terhapus!', 'Produk berhasil dihapus.', 'success')
                            .then(() => location.reload());
                    }
                });
            }
        })
    });
});
</script>
@endpush
