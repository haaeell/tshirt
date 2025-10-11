<!-- ===== MODAL EDIT WARNA ===== -->
<div class="modal fade" id="modalEditWarna" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="formEditWarna" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-edit me-2"></i> Edit Warna
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Warna</label>
                    <input type="text" name="nama" id="editWarnaNama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode HEX</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="color" id="editWarnaPicker" class="form-control form-control-color" style="width:60px">
                        <input type="text" name="hex" id="editWarnaHex" class="form-control" placeholder="#FFFFFF" maxlength="10">
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

<!-- ==================== MODAL EDIT UKURAN ==================== -->
<div class="modal fade" id="modalEditUkuran" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="formEditUkuran" class="modal-content">
            @csrf
            @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-ruler me-2"></i> Edit Ukuran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Ukuran</label>
                    <input type="text" name="nama" id="editUkuranNama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tambahan Harga</label>
                    <input type="number" name="tambahan_harga" id="editUkuranHarga" class="form-control" min="0" placeholder="0">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>



<!-- ===== MODAL EDIT BAHAN ===== -->
<div class="modal fade" id="modalEditBahan" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="formEditBahan" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i> Edit Bahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" id="editBahanNama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tambahan Harga</label>
                    <input type="number" name="tambahan_harga" id="editBahanHarga" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL EDIT LENGAN ===== -->
<div class="modal fade" id="modalEditLengan" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="formEditLengan" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i> Edit Lengan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Tipe</label>
                    <input type="text" name="tipe" id="editLenganTipe" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tambahan Harga</label>
                    <input type="number" name="tambahan_harga" id="editLenganHarga" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL EDIT MOCKUP ===== -->
<div class="modal fade" id="modalEditMockup" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" enctype="multipart/form-data" id="formEditMockup" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i> Edit Mockup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="produk_id" id="editMockupProdukId">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Sudut Tampilan</label>
                    <select name="angle" id="editMockupAngle" class="form-select" required>
                        <option value="depan">Depan</option>
                        <option value="belakang">Belakang</option>
                        <option value="kiri">Samping Kiri</option>
                        <option value="kanan">Samping Kanan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Ganti File (Opsional)</label>
                    <input type="file" name="file_path" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
