@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="fas fa-user-shield me-2"></i> Data Admin</h3>
                <button class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#adminModal">
                    <i class="fas fa-plus"></i> Tambah Admin
                </button>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered" id="adminTable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <button class="btn btn-warning text-white btn-sm btn-edit" data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                        data-bs-toggle="modal" data-bs-target="#adminModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-info text-white btn-sm btn-delete" data-id="{{ $user->id }}">
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
    <div class="modal fade" id="adminModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="adminForm" method="POST" class="modal-content">
                @csrf
                <div id="method"></div>
                <div class="modal-header">
                    <h5 id="modalTitle">Tambah Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password <span id="passwordNote" class="text-muted"></span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" id="btnSubmit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#adminTable').DataTable();

            // Tambah Admin
            $('.btn-add').on('click', function() {
                $('#modalTitle').text('Tambah Admin');
                $('#adminForm').attr('action', "{{ route('admin.users.store') }}");
                $('#method').html(''); // hapus method PUT
                $('#name').val('');
                $('#email').val('');
                $('#password').val('').attr('required', true);
                $('#passwordNote').text('');
            });

            // Edit Admin
            $('.btn-edit').on('click', function() {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');

                $('#modalTitle').text('Edit Admin');
                $('#adminForm').attr('action', '/admin/users/' + id);
                $('#method').html('@method('PUT')');
                $('#name').val(name);
                $('#email').val(email);
                $('#password').val('').removeAttr('required');
                $('#passwordNote').text('(kosongkan jika tidak diubah)');
            });

            // Hapus Admin
            $('.btn-delete').on('click', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin?',
                    text: "Data admin akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/users/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Terhapus!', 'Data admin berhasil dihapus.',
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
