<x-theme.app title="{{$title}}" table="Y" sizeCard="12">
    <x-slot name="cardHeader">
        <div class="row justify-content-end">
            <div class="col-lg-6">
                {{--
                <x-theme.button modal="Y" idModal="view" icon="fas fa-search" addClass="float-end" teks="" /> --}}
            </div>
        </div>
    </x-slot>
    <x-slot name="cardBody">
        <section class="row">
            <table class="table table-hover table-striped" id="table1">
                <thead>
                    <tr>
                        <th width="5">#</th>
                        <th>Tanggal</th>
                        <th>No Nota</th>
                        <th>Akun</th>
                        <th>Keterangan</th>
                        <th style="text-align: right">Debit</th>
                        <th style="text-align: right">Kredit</th>
                        <th style="text-align: right">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $saldo = 0;
                    @endphp
                    @foreach ($detail as $n => $d)
                    @php
                    $saldo += $d->debit - $d->kredit;
                    if ($d->debit + $d->kredit == 0) {
                    continue;
                    } else {
                    # code...
                    }

                    @endphp
                    <tr>
                        <td>{{ $n+1 }}</td>
                        <td class="nowrap">{{ date('d-m-Y',strtotime($d->tgl)) }}</td>
                        <td>{{ $d->no_nota }}</td>
                        <td>{{ $d->saldo == 'Y' ? 'Saldo Awal' : ucwords(strtolower($d->nm_akun)) }}</td>
                        <td>{{ $d->ket }}</td>
                        <td style="text-align: right">{{ number_format($d->debit,0) }}</td>
                        <td style="text-align: right">{{ number_format($d->kredit,0) }}</td>
                        <td style="text-align: right">{{ number_format($saldo,0) }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </section>

    </x-slot>

</x-theme.app>