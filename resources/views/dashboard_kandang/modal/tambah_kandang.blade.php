<form action="{{ route('data_kandang.store') }}" method="post">
    @csrf
    <input type="hidden" name="route" value="dashboard_kandang.index">
    <x-theme.modal title="Tambah Kandang" idModal="tambah_kandang">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">Tanggal Lahir</label>
                    <input required value="{{ date('Y-m-d') }}" type="date" name="tgl" class="form-control">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">Nama Kandang</label>
                    <input required type="text" name="nm_kandang" class="form-control">
                </div>
            </div>
            <div class="col-lg-6">
                <label for="">Strain</label>
                <select name="strain" class="form-control select2-kandang" id="">
                    <option value="">- Pilih Strain -</option>
                    @php
                    $strain = ['isa', 'lohman', 'hisex', 'hyline', 'hovogen'];
                    @endphp
                    @foreach ($strain as $d)
                    <option value="{{ ucwords($d) }}">{{ ucwords($d) }} Brown</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="">Ayam Awal</label>
                    <input required type="text" name="ayam_awal" class="form-control">
                </div>
            </div>
        </div>
    </x-theme.modal>
</form>