<section class="row">

    <table class="table table-bordered">

        <tr>
            <th colspan="4">Uraian</th>
        </tr>
        <tr>
            <th colspan="4"><a href="#" data-bs-toggle="modal" data-bs-target="#tambah-profit">PEREDARAN USAHA</a>
            </th>
        </tr>

        @php
            $total_pendapatan = 0;
        @endphp
        @foreach ($profit as $p)
            @php
                $total_pendapatan += $p->kredit - $p->debit;
            @endphp
            <tr>
                <td></td>
                <td>{{ ucwords(strtolower($p->nm_akun)) }}</td>
                <td width="5%">Rp</td>
                <td align="right">{{ number_format($p->kredit - $p->debit, 0) }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="border-bottom: 1px solid black;"></td>
            <td class="fw-bold" style="border-bottom: 1px solid black;">Total Pendapatan</td>
            <td class="fw-bold" style="border-bottom: 1px solid black;">Rp</td>
            <td class="fw-bold" align="right" style="border-bottom: 1px solid black;">
                {{ number_format($total_pendapatan, 0) }}</td>
        </tr>
        <tr>
            <th colspan="4">BIAYA - BIAYA</th>
        </tr>
        @php
            $total_biaya = 0;
        @endphp
        @foreach ($loss as $l)
            @php
                $total_biaya += $l->debit - $l->kredit;
            @endphp
            <tr>
                <td></td>
                <td>{{ ucwords(strtolower($l->nm_akun)) }}</td>
                <td width="5%">Rp</td>
                <td align="right">{{ number_format($l->debit - $l->kredit, 0) }}</td>
            </tr>
        @endforeach
        <tr>
            <td style="border-bottom: 1px solid black;"></td>
            <td class="fw-bold" style="border-bottom: 1px solid black;">Total Biaya-biaya</td>
            <td class="fw-bold" style="border-bottom: 1px solid black;">Rp</td>
            <td class="fw-bold" align="right" style="border-bottom: 1px solid black;">
                {{ number_format($total_biaya, 0) }}</td>
        </tr>
        <tr>
            <td colspan="2" class="fw-bold">TOTAL LABA BERSIH</td>
            <td class="fw-bold">Rp</td>
            <td class="fw-bold" align="right">{{ number_format($total_pendapatan - $total_biaya, 0) }}</td>
        </tr>

        <tbody>

        </tbody>
    </table>

    
</section>