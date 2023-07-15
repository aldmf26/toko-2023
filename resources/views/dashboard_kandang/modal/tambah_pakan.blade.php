{{-- modal --}}
<form id="form_tambah_pakan" method="post">
    @csrf
    <x-theme.modal title="Tambah Perencanaan" idModal="tambah_pakan">
        <div class="row">
            <div class="col-lg-5">
                <div class="form-group">
                    <label for="">Nama Pakan</label>
                    <input required type="text" name="nm_produk" class="form-control">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="">Stok Awal (Gr)</label>
                    <input type="text" name="stok_awal" class="form-control">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="">Total Rp</label>
                    <input type="text" name="total_rp" class="form-control">
                </div>
            </div>
        </div>
    </x-theme.modal>
</form>
{{-- end modal --}}