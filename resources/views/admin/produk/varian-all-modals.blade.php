<!-- ==================== MODAL TAMBAH WARNA ==================== -->
<div class="modal fade" id="modalTambahWarna" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('varian.warna.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-palette me-2"></i> Tambah Warna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="produk_id" id="warnaProdukId">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Warna</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode HEX</label>
                    <input type="color" name="hex" class="form-control form-control-color">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL TAMBAH BAHAN ==================== -->
<div class="modal fade" id="modalTambahBahan" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('varian.bahan.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-tshirt me-2"></i> Tambah Bahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="produk_id" id="bahanProdukId">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Bahan</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tambahan Harga</label>
                    <input type="number" name="tambahan_harga" step="0.01" class="form-control" placeholder="0">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL TAMBAH LENGAN ==================== -->
<div class="modal fade" id="modalTambahLengan" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('varian.lengan.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-tie me-2"></i> Tambah Lengan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="produk_id" id="lenganProdukId">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tipe Lengan</label>
                    <select name="tipe" class="form-select" required>
                        <option value="pendek">Pendek</option>
                        <option value="panjang">Panjang</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tambahan Harga</label>
                    <input type="number" name="tambahan_harga" value="0" step="0.01" class="form-control"
                        placeholder="0">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL TAMBAH UKURAN ==================== -->
<div class="modal fade" id="modalTambahUkuran" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('varian.ukuran.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-ruler me-2"></i> Tambah Ukuran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Ukuran</label>
                    <input type="text" name="nama" class="form-control" placeholder="Contoh: S, M, L, XL"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tambahan Harga</label>
                    <input type="number" name="tambahan_harga" class="form-control" placeholder="0"
                        min="0">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>


<!-- ==================== MODAL TAMBAH MOCKUP ==================== -->
<div class="modal fade" id="modalTambahMockup" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('varian.mockup.store') }}" enctype="multipart/form-data"
            class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-image me-2"></i> Tambah Mockup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="produk_id" id="mockupProdukId">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Sudut Tampilan</label>
                    <select name="angle" class="form-select" required>
                        <option value="depan">Depan</option>
                        <option value="belakang">Belakang</option>
                        <option value="kiri">Samping Kiri</option>
                        <option value="kanan">Samping Kanan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload File (PNG/JPG)</label>
                    <input type="file" name="file_path" class="form-control" accept="image/*" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL EDIT SEMUA VARIAN ==================== -->
@include('admin.produk.varian-edit-modals')

@push('scripts')
    <script>
        $(document).ready(function() {
            const tabLinks = document.querySelectorAll('#varianTabs .nav-link');

            // === Saat tab diubah, simpan tab aktif ke localStorage ===
            tabLinks.forEach(link => {
                link.addEventListener('shown.bs.tab', function(e) {
                    const activeTab = e.target.getAttribute('href'); // contoh: "#bahan"
                    localStorage.setItem('activeVarianTab', activeTab);
                    console.log('Tab tersimpan:', activeTab); // Debugging
                });
            });

            // === Aktifkan kembali tab yang terakhir dibuka ===
            const lastTab = localStorage.getItem('activeVarianTab');
            if (lastTab) {
                console.log('Tab terakhir:', lastTab); // Debugging
                document.querySelectorAll('.nav-link, .tab-pane').forEach(el => {
                    el.classList.remove('active', 'show');
                });

                const tabLink = document.querySelector(`#varianTabs a[href="${lastTab}"]`);
                const tabPane = document.querySelector(lastTab);

                if (tabLink && tabPane) {
                    tabLink.classList.add('active');
                    tabPane.classList.add('active', 'show');
                }
            }

            // === Auto set produk_id ke semua modal ===
            const produkSelect = document.getElementById('produkSelect');
            const produkInputs = document.querySelectorAll('input[name="produk_id"]');
            const selectedId = produkSelect.value;
            produkInputs.forEach(i => i.value = selectedId);

            // Saat ganti produk, reset tab
            produkSelect.addEventListener('change', function() {
                localStorage.removeItem('activeVarianTab');
                window.location = '?produk_id=' + this.value;
            });


            // ======== EDIT WARNA ========
            $('.editWarnaBtn').click(function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                const hex = $(this).data('hex');

                $('#editWarnaNama').val(nama);
                $('#editWarnaHex').val(hex);
                $('#editWarnaPicker').val(hex);
                $('#formEditWarna').attr('action', `/admin/produk/varian/warna/${id}`);
                $('#modalEditWarna').modal('show');
            });

            // Sinkronisasi picker ↔ text
            $('#editWarnaPicker').on('input', function() {
                $('#editWarnaHex').val($(this).val());
            });
            $('#editWarnaHex').on('input', function() {
                $('#editWarnaPicker').val($(this).val());
            });


            // ======== EDIT BAHAN ========
            $('.editBahanBtn').click(function() {
                const id = $(this).data('id');
                $('#editBahanNama').val($(this).data('nama'));
                $('#editBahanHarga').val($(this).data('harga'));
                $('#formEditBahan').attr('action', `/admin/produk/varian/bahan/${id}`);
                $('#modalEditBahan').modal('show');
            });

            // ======== EDIT LENGAN ========
            $('.editLenganBtn').click(function() {
                const id = $(this).data('id');
                $('#editLenganTipe').val($(this).data('tipe'));
                $('#editLenganHarga').val($(this).data('harga'));
                $('#formEditLengan').attr('action', `/admin/produk/varian/lengan/${id}`);
                $('#modalEditLengan').modal('show');
            });

            // ======== EDIT UKURAN ========
            $('.editUkuranBtn').click(function() {
                const id = $(this).data('id');
                const nama = $(this).data('nama');
                const harga = $(this).data('harga');

                $('#editUkuranNama').val(nama);
                $('#editUkuranHarga').val(harga);
                $('#formEditUkuran').attr('action', `/admin/produk/varian/ukuran/${id}`);
                $('#modalEditUkuran').modal('show');
            });


            // ======== EDIT MOCKUP ========
            $('.editMockupBtn').click(function() {
                const id = $(this).data('id');
                const angle = $(this).data('angle');
                $('#editMockupAngle').val(angle);
                $('#formEditMockup').attr('action', `/admin/produk/varian/mockup/${id}`);
                $('#modalEditMockup').modal('show');
            });
        });
    </script>
@endpush
