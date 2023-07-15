<div class="col-lg-8">

    <h6>
        Input Kandang Harian ~ {{ tanggal(date('Y-m-d')) }}
        <a data-bs-toggle="modal" data-bs-target="#export_telur" title="Export Telur" href="#"
            class="FCR(week) badge bg-primary text-sm float-end mb-2 me-2"><i class="fas fa-print"></i> Export Telur</a>
        <a data-bs-toggle="modal" data-bs-target="#daily_layer" title="Export daily Layer" href="#"
            class="badge bg-primary text-sm float-end mb-2 me-2"><i class="fas fa-print"></i> Export Daily Layer</a>
        <a data-bs-toggle="modal" data-bs-target="#week_layer" title="Export week Layer" href="#"
            class="badge bg-primary text-sm float-end mb-2 me-2"><i class="fas fa-print"></i> Export Week Layer</a>
    </h6>
    <table class="table table-bordered table-hover " id="">
        <thead>
            <tr>
                <th rowspan="2" width="1%" class="text-center dhead">Kdg</th>
                <th colspan="3" class="text-center  putih">Populasi</th>
                <th colspan="7" class="text-center abu"> Telur </th>
                <th colspan="2" class="text-center putih">pakan</th>
                <th width="2%" class="text-center dhead" rowspan="2">Aksi</th>
            </tr>
            
            <tr>
                <th width="2%" class="dhead text-center">Minggu</th>
                <th width="1%" class="dhead text-center">Pop</th>
                <th width="6%" class="dhead text-center">Mati / Jual</th>
                @php
                    $telur = DB::table('telur_produk')->get();
                @endphp
                @foreach ($telur as $d)
                    <th width="1%" class="dhead text-center">
                        {{ ucwords(str_replace('telur', '', strtolower($d->nm_telur))) }}</th>
                @endforeach
                <th width="1%" class="dhead text-center">Ttl Pcs</th>
                <th width="1%" class="dhead text-center">Ttl Kg</th>
                <th width="1%" class="dhead text-center">Kg</th>
                <th width="3%" class="dhead text-center">Gr / Ekor</th>
                
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($kandang as $no => $d)
                <tr>
                    <td align="center" class="detail_perencanaan" id_kandang="{{ $d->id_kandang }}"
                        data-bs-toggle="modal" data-bs-target="#detail_perencanaan">
                        {{ $d->nm_kandang }}</td>
                    @php
                        $populasi = DB::table('populasi')
                            ->where([['id_kandang', $d->id_kandang], ['tgl', date('Y-m-d')]])
                            ->first();
                        
                        $mati = $populasi->mati ?? 0;
                        $jual = $populasi->jual ?? 0;
                        $kelas = $mati > 3 ? 'merah' : 'putih';
                        $kelasMgg = $d->mgg >= 80 ? 'merah' : 'putih'
                    @endphp
                    <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}" nm_kandang="{{ $d->nm_kandang }}"
                        class="tambah_populasi {{$kelasMgg}}" data-bs-target="#tambah_populasi">{{ $d->mgg }} /
                        {{ ($d->mgg / 80) * 100 }} %</td>

                    @php
                        $popu = DB::selectOne("SELECT sum(a.mati + a.jual) as pop,b.stok_awal FROM populasi as a
                LEFT JOIN kandang as b ON a.id_kandang = b.id_kandang
                WHERE a.id_kandang = '$d->id_kandang';");
                        
                        $pop = $popu->stok_awal - $popu->pop;
                        $kelasPop = ($pop / $popu->stok_awal) * 100 <= 85 ? 'merah' : 'putih';
                    @endphp

                    <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}" nm_kandang="{{ $d->nm_kandang }}"
                        class="tambah_populasi {{ $kelasPop }}" data-bs-target="#tambah_populasi">{{ $pop }}</td>

                    {{-- mati dan jual --}}
                    <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}" nm_kandang="{{ $d->nm_kandang }}"
                        class="tambah_populasi {{ $kelas }}" data-bs-target="#tambah_populasi">
                        {{ $mati ?? 0 }} / {{ $jual ?? 0 }}</td>
                    {{-- end mati dan jual --}}

                    {{-- telur --}}
                    @php
                        $telur = DB::table('telur_produk')->get();
                        $ttlKg = 0;
                        $ttlPcs = 0;
                        $ttlPcsKemarin = 0;
                        $ttlKgKemarin = 0;
                    @endphp
                    @foreach ($telur as $t)
                        @php
                            $tgl = date('Y-m-d');
                            $tglKemarin = Carbon\Carbon::yesterday()->format('Y-m-d');
                            
                            $stok = DB::selectOne("SELECT * FROM stok_telur as a WHERE a.id_kandang = '$d->id_kandang'
                                AND a.tgl = '$tgl' AND a.id_telur = '$t->id_produk_telur'");
                            
                            $stokKemarin = DB::selectOne("SELECT * FROM stok_telur as a WHERE a.id_kandang =
                                '$d->id_kandang'
                                AND a.tgl = '$tglKemarin' AND a.id_telur = '$t->id_produk_telur'");
                            
                            $pcs = $stok->pcs ?? 0;
                            $pcsKemarin = $stokKemarin->pcs ?? 0;
                            
                            $ttlKg += $stok->kg ?? 0;
                            $ttlPcs += $stok->pcs ?? 0;

                            $ttlPcsKemarin += $pcsKemarin;
                            $ttlKgKemarin += $stokKemarin->kg ?? 0;
                            // dd($pcsKemarin - $pcs);
                            $kelasTtlPcsTelur = $ttlPcs - $ttlPcsKemarin < -60 ? 'merah' : 'abu';
                            $kelasTtKgTelur = $ttlKg - $ttlKgKemarin < 2.5 ? 'merah' : 'abu';
                        @endphp

                        <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}" nm_kandang="{{ $d->nm_kandang }}"
                            class="tambah_telur " data-bs-target="#tambah_telur">
                            <span>{{ $stok->pcs ?? 0 }}</span>
                        </td>
                    @endforeach
                    <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}" nm_kandang="{{ $d->nm_kandang }}"
                        class="tambah_telur {{ $kelasTtlPcsTelur }}" data-bs-target="#tambah_telur">{{ $ttlPcs }}</td>
                    <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}" nm_kandang="{{ $d->nm_kandang }}"
                        class="tambah_telur {{ $kelasTtKgTelur }}" data-bs-target="#tambah_telur">{{ $ttlKg }}</td>
                    {{-- end telur --}}

                    {{-- pakan --}}
                    @php
                        $pakan = DB::selectOne("SELECT *,sum(gr) as total FROM tb_pakan_perencanaan as a 
                            WHERE a.tgl = '$tgl' AND a.id_kandang = '$d->id_kandang' 
                            GROUP BY a.id_kandang");
                        $gr_pakan = DB::selectOne("SELECT sum(a.gr) as ttl, a.no_nota FROM tb_pakan_perencanaan as a where a.id_kandang = '$d->id_kandang' and a.tgl = '$tgl' group by a.id_kandang");
                        $gr_perekor = empty($pakan) ? 0 : $pakan->total / $pop;
                        $kelas = $gr_perekor < 100 ? 'merah' : 'putih';
                    @endphp
                    <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}" class="tambah_perencanaan"
                        data-bs-target="#tambah_perencanaan">
                        {{ empty($gr_pakan) ? 0 : $gr_pakan->ttl / 1000 }}</td>
                    <td data-bs-toggle="modal" id_kandang="{{ $d->id_kandang }}" class="{{ $kelas }} tambah_perencanaan"
                        data-bs-target="#tambah_perencanaan">
                        {{ $gr_perekor }}</td>
                    
                    {{-- end pakan --}}
                    <td align="center">
                        <a onclick="return confirm('Yakin ingin di selesaikan ?')"
                            href="{{ route('dashboard_kandang.kandang_selesai', $d->id_kandang) }}"
                            class="badge bg-primary"><i class="fas fa-check"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>    
</div>

@include('dashboard_kandang.modal.tambah_kandang')
@include('dashboard_kandang.modal.tambah_karung')

{{-- tambah telur --}}
<form action="{{ route('dashboard_kandang.tambah_telur') }}" method="post">
    @csrf
    <x-theme.modal title="Tambah Telur" size="modal-lg-max" idModal="tambah_telur">
        <div id="load_telur"></div>
    </x-theme.modal>
</form>
{{-- end tambah telur --}}

{{-- tambah populasi --}}
<form action="{{ route('dashboard_kandang.tambah_populasi') }}" method="post">
    @csrf
    <x-theme.modal title="Tambah Populasi" size="modal-lg-max" idModal="tambah_populasi">
        <div id="load_populasi"></div>
    </x-theme.modal>
</form>
{{-- end tambah populasi --}}

{{-- tambah perencanaan --}}
<form action="{{ route('dashboard_kandang.tambah_perencanaan') }}" method="post">
    @csrf
    <x-theme.modal title="Tambah Perencanaan" size="modal-lg" idModal="tambah_perencanaan">
        <div id="load_perencanaan"></div>
    </x-theme.modal>
</form>


{{-- end tambah perencanaan --}}


{{-- detail perencanaan --}}
<x-theme.modal title="Detail Perencanaan" btnSave="" size="modal-lg-max" idModal="detail_perencanaan">
    <div class="row">
        <div class="col-lg-12">
            <a data-bs-toggle="collapse" href="#perencanaan" class="btn btn-sm btn-primary">History
                Perencanaan</a>
            <button data-bs-toggle="collapse" href="#layer" type="button" class="btn btn-sm btn-primary">History
                Layer</button>
        </div>
    </div>
    <hr style="border: 1px solid #6777EF;">

    <div id="load_detail_perencanaan"></div>
</x-theme.modal>
{{-- end detail perencanaan --}}

{{-- edit perencanaan --}}
<form action="{{ route('dashboard_kandang.edit_perencanaan') }}" method="post">
    @csrf
    <x-theme.modal title="Edit Perencanaan" size="modal-lg" idModal="edit_perencanaan">
        <div id="hasilEditPerencanaan"></div>
    </x-theme.modal>
</form>
{{-- end edit perencanaan --}}


<form action="{{ route('dashboard_kandang.export_telur') }}" method="post">
    @csrf
    <x-theme.modal title="Export Telur" idModal="export_telur">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="">Dari</label>
                    <input type="date" name="tgl1" class="form-control">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label for="">Sampai</label>
                    <input type="date" name="tgl2" class="form-control">
                </div>
            </div>
        </div>
    </x-theme.modal>
</form>

<form action="{{ route('dashboard_kandang.daily_layer') }}" method="post">
    @csrf
    <x-theme.modal title="Export Daily Layer" idModal="daily_layer">
        <div class="row">
            <div class="form-group">
                <label for="">Kandang</label>
                <select name="id_kandang" class="form-control" id="">
                    <option value="">- Pilih Kandang -</option>
                    @foreach ($kandang as $kd)
                        <option value="{{ $kd->id_kandang }}">{{ $kd->nm_kandang }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-theme.modal>
</form>
<form action="{{ route('dashboard_kandang.week_layer') }}" method="post">
    @csrf
    <x-theme.modal title="Export Week Layer" idModal="week_layer">
        <div class="row">
            <div class="form-group">
                <label for="">Kandang</label>
                <select name="id_kandang" class="form-control" id="">
                    <option value="">- Pilih Kandang -</option>
                    @foreach ($kandang as $kd)
                        <option value="{{ $kd->id_kandang }}">{{ $kd->nm_kandang }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-theme.modal>
</form>
