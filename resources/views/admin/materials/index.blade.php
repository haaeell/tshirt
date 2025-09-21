@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="fas fa-cogs me-2"></i> Material</h3>
                <button class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#materialModal">
                    <i class="fas fa-plus"></i> Tambah Material
                </button>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="materialTable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materials as $m)
                            <tr>
                                <td>{{ $m->nama }}</td>
                                <td>{{ $m->deskripsi ?? '-' }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm text-white btn-edit" data-id="{{ $m->id }}"
                                        data-nama="{{ $m->nama }}" data-deskripsi="{{ $m->deskripsi }}"
                                        data-bs-toggle="modal" data-bs-target="#materialModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $m->id }}">
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

    <!-- Modal -->
    <div class="modal fade" id="materialModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="materialForm" method="POST" class="modal-content">
                @csrf
                <div id="method"></div>
                <div class="modal-header">
                    <h5 id="modalTitle">Tambah Material</h5>
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
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
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
            $('#materialTable').DataTable();

            $('.btn-add').on('click', function() {
                $('#modalTitle').text('Tambah Material');
                $('#materialForm').attr('action', "{{ route('materials.store') }}");
                $('#method').html('');
                $('#nama').val('');
                $('#deskripsi').val('');
            });

            $('.btn-edit').on('click', function() {
                let id = $(this).data('id');
                $('#modalTitle').text('Edit Material');
                $('#materialForm').attr('action', '/admin/materials/' + id);
                $('#method').html('@method('PUT')');
                $('#nama').val($(this).data('nama'));
                $('#deskripsi').val($(this).data('deskripsi'));
            });

            $('.btn-delete').on('click', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin?',
                    text: "Material akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/materials/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Terhapus!', 'Material berhasil dihapus.',
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
