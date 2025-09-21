@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0"><i class="fas fa-tags me-2"></i> Voucher</h3>
                <button class="btn btn-primary btn-add" data-bs-toggle="modal" data-bs-target="#voucherModal">
                    <i class="fas fa-plus"></i> Tambah Voucher
                </button>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered" id="voucherTable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Tipe</th>
                            <th>Nilai</th>
                            <th>Maks Diskon</th>
                            <th>Min Belanja</th>
                            <th>Periode</th>
                            <th>Limit</th>
                            <th>Dipakai</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vouchers as $v)
                            <tr>
                                <td>{{ $v->kode }}</td>
                                <td>{{ ucfirst($v->tipe) }}</td>
                                <td>{{ $v->tipe == 'persen' ? $v->nilai . ' %' : 'Rp ' . number_format($v->nilai, 0, ',', '.') }}
                                </td>
                                <td>{{ $v->maks_diskon ? 'Rp ' . number_format($v->maks_diskon, 0, ',', '.') : '-' }}</td>
                                <td>{{ $v->min_belanja ? 'Rp ' . number_format($v->min_belanja, 0, ',', '.') : '-' }}</td>
                                <td>{{ $v->mulai ? $v->mulai : '-' }} -
                                    {{ $v->berakhir ? $v->berakhir : '-' }}</td>
                                <td>{{ $v->limit_pemakaian ?? '-' }}</td>
                                <td>{{ $v->jumlah_dipakai }}</td>
                                <td>
                                    <span class="badge {{ $v->aktif ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $v->aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm text-white btn-edit"
                                        data-id="{{ $v->id }}" data-kode="{{ $v->kode }}"
                                        data-tipe="{{ $v->tipe }}" data-nilai="{{ $v->nilai }}"
                                        data-maks_diskon="{{ $v->maks_diskon }}" data-min_belanja="{{ $v->min_belanja }}"
                                        data-mulai="{{ $v->mulai }}" data-berakhir="{{ $v->berakhir }}"
                                        data-limit="{{ $v->limit_pemakaian }}" data-aktif="{{ $v->aktif }}"
                                        data-bs-toggle="modal" data-bs-target="#voucherModal">
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
    <div class="modal fade" id="voucherModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form id="voucherForm" method="POST" class="modal-content">
                @csrf
                <div id="method"></div>
                <div class="modal-header">
                    <h5 id="modalTitle">Tambah Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="text" name="kode" id="kode" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipe</label>
                        <select name="tipe" id="tipe" class="form-select" required>
                            <option value="persen">Persen</option>
                            <option value="nominal">Nominal</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nilai</label>
                        <input type="number" name="nilai" id="nilai" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Maks Diskon</label>
                        <input type="number" name="maks_diskon" id="maks_diskon" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Min Belanja</label>
                        <input type="number" name="min_belanja" id="min_belanja" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Periode</label>
                        <div class="input-group">
                            <input type="date" name="mulai" id="mulai" class="form-control">
                            <span class="input-group-text">s/d</span>
                            <input type="date" name="berakhir" id="berakhir" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Limit Pemakaian</label>
                        <input type="number" name="limit_pemakaian" id="limit_pemakaian" class="form-control">
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check form-switch mt-4">
                            <input type="checkbox" class="form-check-input" name="aktif" id="aktif"
                                value="1">
                            <label class="form-check-label" for="aktif">Aktif</label>
                        </div>
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
            $('#voucherTable').DataTable();

            // Tambah
            $('.btn-add').on('click', function() {
                $('#modalTitle').text('Tambah Voucher');
                $('#voucherForm').attr('action', "{{ route('voucher.store') }}");
                $('#method').html('');
                $('#voucherForm')[0].reset();
                $('#aktif').prop('checked', false);
            });

            // Edit
            $('.btn-edit').on('click', function() {
                let id = $(this).data('id');
                $('#modalTitle').text('Edit Voucher');
                $('#voucherForm').attr('action', '/admin/voucher/' + id);
                $('#method').html('@method('PUT')');

                $('#kode').val($(this).data('kode'));
                $('#tipe').val($(this).data('tipe'));
                $('#nilai').val($(this).data('nilai'));
                $('#maks_diskon').val($(this).data('maks_diskon'));
                $('#min_belanja').val($(this).data('min_belanja'));
                $('#mulai').val($(this).data('mulai')?.substring(0, 10));
                $('#berakhir').val($(this).data('berakhir')?.substring(0, 10));
                $('#limit_pemakaian').val($(this).data('limit'));
                $('#aktif').prop('checked', $(this).data('aktif') == 1);
            });

            // Delete
            $('.btn-delete').on('click', function() {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Yakin?',
                    text: "Voucher akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/voucher/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('Terhapus!', 'Voucher berhasil dihapus.',
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
