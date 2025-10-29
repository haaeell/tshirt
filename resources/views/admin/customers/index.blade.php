@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="fas fa-users me-2"></i> Data Pelanggan</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered" id="customerTable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Nomor HP</th>
                            <th>Tanggal Daftar</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $c)
                            <tr>
                                <td>{{ $c->nama }}</td>
                                <td>{{ $c->email }}</td>
                                <td>{{ $c->customer->no_hp ?? '-' }}</td>
                                <td>{{ $c->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <button class="btn btn-info text-white btn-sm btn-detail" data-id="{{ $c->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-danger text-white btn-sm btn-delete"
                                        data-id="{{ $c->id }}">
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#customerTable').DataTable();

            // Delete
            $('.btn-delete').on('click', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin?',
                    text: "Data pelanggan akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/customers/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Terhapus!', 'Data pelanggan berhasil dihapus.',
                                            'success')
                                        .then(() => location.reload());
                                }
                            });
                    }
                });
            });
        });
    </script>
@endpush
