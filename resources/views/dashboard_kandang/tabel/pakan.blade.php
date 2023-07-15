<div id="load_stok_pakan"></div>

<form action="{{route('dashboard_kandang.save_opname_pakan')}}" method="post">
    @csrf
    <x-theme.modal title="Opname Pakan" size="modal-lg" idModal="opname_pakan">
        <div class="row">
            <div class="col-lg-12">
                <div id="opname_stk_pkn"></div>
            </div>
        </div>
    </x-theme.modal>
</form>
<form action="{{route('dashboard_kandang.save_opname_pakan')}}" method="post">
    @csrf
    <x-theme.modal title="Opname Vitamin" size="modal-lg" idModal="opname_vitamin">
        <div class="row">
            <div class="col-lg-12">
                <div id="opname_stk_vtmn"></div>
            </div>
        </div>
    </x-theme.modal>
</form>
<form action="{{route('dashboard_kandang.save_vaksin')}}" method="post">
    @csrf
    <x-theme.modal title="Tambah vaksin" size="modal-lg" idModal="tbh_vaksin">
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="">Tanggal</label>
                    <input value="{{ date('Y-m-d') }}" type="date" name="tgl" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <label for="">Kandang</label>
                <select name="id_kandang" class="form-control" id="">
                    <option value="">- Pilih Kandang -</option>
                    @foreach ($kandang as $d)
                        <option value="{{ $d->id_kandang }}">{{ $d->nm_kandang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="">Nama Vaksin</label>
                    <input type="text" name="nm_vaksin" class="form-control">
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label for="">Stok</label>
                    <input type="text" name="stok" class="form-control">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="">Total Rp</label>
                    <input type="text" name="ttl_rp" class="form-control">
                </div>
            </div>
        </div>
    </x-theme.modal>
</form>
<x-theme.modal title="History Stok" btnSave='T' size="modal-lg" idModal="history_stok">
    <div class="row">
        <div class="col-lg-12">
            <div id="history_stk"></div>
        </div>
    </div>
</x-theme.modal>
<form action="{{route('dashboard_kandang.save_tambah_pakan_stok')}}" method="post">
    @csrf
    <x-theme.modal title="Tambah Stok Pakan" size="modal-lg" idModal="tbh_pakan">
        <div class="row">
            <div id="load_tambah_pakan"></div>
        </div>
    </x-theme.modal>
</form>

