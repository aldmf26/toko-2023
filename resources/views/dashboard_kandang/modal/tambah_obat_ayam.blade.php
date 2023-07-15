{{-- modal --}}
<form id="form_tambah_obat_ayam" method="post">
    @csrf
    <x-theme.modal title="Tambah Obat Ayam" size="modal-lg" idModal="tambah_obat_ayam">
        @php
            $satuanObat = DB::table('tb_satuan')->get();
        @endphp
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="">Nama Obat</label>
                    <input required type="text" name="nm_produk" class="form-control">
                </div>
            </div>
            <div class="col-lg-2">
                <label for="">Dosis Satuan</label>
                <select name="dosis_satuan" class="form-control select2-ayam" id="">
                    <option value="">- Pilih Satuan -</option>
                    @foreach ($satuanObat as $s)
                        <option value="{{ $s->id_satuan }}">{{ ucwords($s->nm_satuan) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label for="">Stok Awal (Gr)</label>
                    <input type="text" name="stok_awal" class="form-control">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="">Total Rp</label>
                    <input type="text" name="total_rp" class="form-control">
                </div>
            </div>
        </div>
    </x-theme.modal>
</form>
{{-- end modal --}}