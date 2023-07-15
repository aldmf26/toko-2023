<div class="row">
    <table class="table table-bordered table-hover " id="">
        <thead>
            <tr>
                <th rowspan="2" width="1%" class="text-center dhead">Kdg</th>
                <th colspan="3" class="text-center  putih">Populasi</th>
                <th colspan="7" class="text-center abu"> Telur </th>
                <th colspan="2" class="text-center putih">pakan</th>
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
                    <td align="center" class="detail_perencanaan">
                        {{ $d->nm_kandang }}</td>
                    @php
                        $populasi = DB::table('populasi')
                            ->where([['id_kandang', $d->id_kandang], ['tgl', $tgl]])
                            ->first();
                        $mati = $populasi->mati ?? 0;
                        $jual = $populasi->jual ?? 0;
                        $kelas = $mati > 3 ? 'merah' : 'putih';
                    @endphp
                    <td class="tambah_populasi putih">82 / 91%</td>

                    @php
                        $pop = DB::selectOne("SELECT sum(a.mati + a.jual) as pop,b.stok_awal FROM populasi as a
                                LEFT JOIN kandang as b ON a.id_kandang = b.id_kandang
                                WHERE a.id_kandang = '$d->id_kandang';");
                    @endphp

                    <td class="tambah_populasi putih">{{ $pop->stok_awal - $pop->pop }}</td>

                    {{-- mati dan jual --}}
                    <td class="tambah_populasi {{ $kelas }}">{{ $mati ?? 0 }} / {{ $jual ?? 0 }}</td>
                    {{-- end mati dan jual --}}

                    {{-- telur --}}
                    @php
                        $telur = DB::table('telur_produk')->get();
                        $ttlKg = 0;
                        $ttlPcs = 0;
                    @endphp
                    @foreach ($telur as $t)
                        @php
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
                            // dd($pcsKemarin - $pcs);
                            $kelasTelur = $pcsKemarin - $pcs > 60 ? 'merah' : 'abu';
                        @endphp

                        <td class="tambah_telur {{ $kelasTelur }}">
                            <span>{{ $stok->pcs ?? 0 }}</span>
                        </td>
                    @endforeach
                    <td class="tambah_telur abu">{{ $ttlPcs }}</td>
                    <td class="tambah_telur abu">{{ $ttlKg }}</td>
                    {{-- end telur --}}

                    <td class="tambah_perencanaan">150</td>
                    <td class="tambah_perencanaan">65</td>

                </tr>
            @endforeach
        </tbody>

    </table>
</div>
