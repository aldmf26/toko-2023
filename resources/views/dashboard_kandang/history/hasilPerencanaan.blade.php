<style>
    .freeze {
        position: sticky;
        top: 0px;
        z-index: 100;

    }

    .end {
        z-index: 20;
    }
</style>
<div class="row">
    <div class="col-lg-12 freeze">
        <div class="card">
            <div class="card-body">
                <h5 style="color: #787878; font-weight: bold;">Perencanaan : {{ $kandang->nm_kandang }}
                    ({{ tanggal($tgl_per) }})</h5>
                <h4 class="float-start" class="" style="color: #787878; font-weight: bold;">Populasi :
                    {{ $populasi }} | Pakan/Gr :
                    {{ number_format($pakan->total / $populasi, 0) }}
                    | {{ $umur->mgg + 1 }} Minggu</h4>
                <a href="#" id_kandang="{{ $id_kandang }}" tgl="{{ $tgl_per }}"
                    class="btn  btn-primary float-end " id="edit_per"><i class="fas fa-edit"></i>
                    Edit</a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 style="color: #787878;">

                    @if (!empty($pakan1->gr))
                        {{ $pakan1->gr }} Karung
                        {{ $pakan1->karung }} Kg
                    @endif
                </h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" border="1" width="100%">
                        <thead style="font-family: Helvetica; color: #78909C; text-transform: uppercase;">
                            <tr>
                                <th class="dhead">Pakan</th>
                                <th class="dhead">Qty</th>
                                <th class="dhead">Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pakan2 as $p)
                                <tr>
                                    <td style="">{{ $p->nm_pakan }} <br></td>
                                    <td style="">
                                        {{ number_format(($p->persen / 100) * $pakan1->karung, 2) }} </td>
                                    <td style="">Kg</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <thead style="font-family: Helvetica; color: #78909C; text-transform: uppercase;">
                            <tr>
                                <th class="dhead">Obat</th>
                                <th class="dhead">Qty</th>
                                <th class="dhead">Satuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($obat_pakan as $o)
                                <tr>
                                    <td style="">{{ $o->nm_produk }} <br></td>
                                    <td style="">
                                        {{ number_format(($o->dosis * $pakan1->karung) / $o->campuran, 2) }}
                                    </td>
                                    <td style="">Kg</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 end">
        <div class="card">
            <div class="card-header">
                @if (!empty($pakan1->gr2))
                <h4 style="color: #787878;">1 Karung
                    {{$pakan1->gr2}} Kg
                </h4>
                @endif
            
            </div>
            <div class="card-body">
                <table class="table table-bordered" border="1" width="100%">
                    <thead
                        style="font-family: Helvetica; color: #78909C; background-color: #BDEED9; font-weight: 700; box-shadow: 0px 1px 3px 0px #cccccc;text-transform: uppercase;">
                        <tr>
                            <th class="dhead">Pakan</th>
                            <th class="dhead">Qty</th>
                            <th class="dhead">Satuan</th>
                        </tr>
                    </thead>
                    @php
                        $ttl = 0;
                    @endphp
                    <tbody>
                        @foreach ($pakan2 as $p)
                            @php
                                $ttl += $p->gr_pakan;
                            @endphp
                            <tr>
                                <td style="">
                                    <?= $p->nm_pakan ?>
                                </td>
                                <td style="">
                                    <?= number_format(($p->persen / 100) * $pakan1->gr2, 2) ?>
                                </td>
                                <td style="">Kg</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <thead
                        style="font-family: Helvetica; color: #78909C; background-color: #F4F8F9; font-weight: 700; box-shadow: 0px 1px 3px 0px #cccccc;text-transform: uppercase;">
                        <tr>
                            <th class="dhead">Obat</th>
                            <th class="dhead">Qty</th>
                            <th class="dhead">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($obat_pakan as $o) : ?>
                        <tr>
                            <td style="">
                                <?= $o->nm_produk ?>
                            </td>
                            <td style="">
                                <?= number_format($o->dosis * $ttl, 1) ?>
                            </td>
                            <td style="">
                                <?= $o->satuan ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4 end">
        <div class="card">
            <div class="card-header">
                @if (empty($pakan1->gr2))
                @else
                    <h4 style="color: #787878;">Total Pakan</h4>
                @endif
            </div>
            <div class="card-body">
                <table class="table table-bordered" border="1" width="100%">
                    <thead
                        style="font-family: Helvetica; color: #78909C; background-color: #BDEED9; font-weight: 700; box-shadow: 0px 1px 3px 0px #cccccc;text-transform: uppercase;">
                        <tr>
                            <th class="dhead">Pakan</th>
                            <th class="dhead">Qty</th>
                            <th class="dhead">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $ttl = 0;
                        @endphp
                        @foreach ($pakan2 as $p)
                            @php
                                $ttl += $p->gr_pakan;
                            @endphp
                            <tr>
                                <td style="">
                                    <?= $p->nm_pakan ?>
                                </td>
                                <td style="">
                                    <?= number_format($p->gr_pakan, 2) ?>
                                </td>
                                <td style="">Kg</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <thead
                        style="font-family: Helvetica; color: #78909C; background-color: #F4F8F9; font-weight: 700; box-shadow: 0px 1px 3px 0px #cccccc;text-transform: uppercase;">
                        <tr>
                            <th class="dhead">Obat</th>
                            <th class="dhead">Qty</th>
                            <th class="dhead">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($obat_pakan as $o)
                            <tr>
                                <td style="">{{ $o->nm_produk }}</td>
                                <td style="">{{ number_format($o->dosis * $ttl, 1) }} </td>
                                <td style="">{{ $o->satuan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <div class="col-lg-12 mt-3 end">
        <div class="table-responsive">
            <table class="table table-bordered" border="1" width="100%">
                <thead
                    style="font-family: Helvetica; color: #78909C; background-color: #F4F8F9; font-weight: 700; box-shadow: 0px 1px 3px 0px #cccccc;text-transform: uppercase;">
                    <tr>
                        <th class="dhead" colspan="8">Obat/vit dengan campuran air</th>
                    </tr>
                    <tr>
                        <th class="dhead" style="background-color: #F5B4C5; color: white;">Obat</th>
                        <th class="dhead" style="background-color: #F5B4C5; color: white;">Dosis</th>
                        <th class="dhead" style="background-color: #F5B4C5; color: white;">Satuan</th>
                        <th class="dhead" style="background-color: #F5B4C5; color: white;">Campuran</th>
                        <th class="dhead" style="background-color: #F5B4C5; color: white;">Satuan</th>
                        <th class="dhead" style="background-color: #F5B4C5; color: white;">Waktu</th>
                        <th class="dhead" style="background-color: #F5B4C5; color: white;">Ket</th>
                        <th class="dhead" style="background-color: #F5B4C5; color: white;">Cara Pemakaian</th>
                    </tr>
                </thead>
                <tbody style="color: #787878; font-family:  Helvetica;">

                    @if (empty($obat_air))
                        <tr>
                            <td style=" text-align: center;" colspan="8">Data tidak ada</td>
                        </tr>
                    @else
                        @foreach ($obat_air as $o)
                            <tr>
                                <td style="">{{ $o->nm_produk }}</td>
                                <td style="">{{ $o->dosis }}</td>
                                <td style="">{{ $o->satuan }}</td>
                                <td style="">{{ $o->campuran }}</td>
                                <td style="">{{ $o->satuan2 }}</td>
                                <td style="">{{ $o->waktu }}</td>
                                <td style="">{{ $o->ket }}</td>
                                <td style="">{{ $o->cara }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-4 end">
        <div class="table-responsive">
            <table class="table table-bordered" border="1" width="100%">
                <thead
                    style="font-family: Helvetica; color: #78909C; background-color: #F4F8F9; font-weight: 700; box-shadow: 0px 1px 3px 0px #cccccc;text-transform: uppercase;">
                    <tr>
                        <th class="dhead" colspan="7">Obat/ayam</th>
                    </tr>
                    <tr>
                        <th class="dhead">Obat</th>
                        <th class="dhead">Dosis</th>
                        <th class="dhead">Satuan</th>
                    </tr>
                </thead>
                <tbody style="color: #787878; font-family:  Helvetica;">
                    @if (empty($obat_ayam))
                        <tr>
                            <td style=" text-align: center;" colspan="7">Data tidak ada</td>
                        </tr>
                    @else
                        @foreach ($obat_ayam as $o)
                            <tr>
                                <td style="">{{ $o->nm_produk }}</td>
                                <td style="">{{ number_format($o->dosis, 0) }}</td>
                                <td style="">{{ $o->satuan }}</td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>
    </div>
</div>
