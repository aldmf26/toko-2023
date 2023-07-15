<tr class="baris{{$count}}">
    <td>
        <select required name="id_produk[]" count="{{ $count }}" class="form-control pilih_telur pilih_telur{{$count}} select2-add produk-change" id="">
            <option value="">- Pilih Produk -</option>
            @foreach ($produk as $d)
                <option value="{{ $d->id_produk }}">{{ $d->nm_produk }}
                    ({{ strtoupper($d->satuan->nm_satuan) }})
                </option>
            @endforeach
            <option value="tambah">+ Produk</option>
        </select>
    </td>
    <td>
        <input name="qty[]" value="0" type="text" class="form-control qty qty{{$count}}">
    </td>
    <td>
        <input type="text" class="form-control dikanan setor-nohide text-end"
            value="Rp. 0" count="{{$count}}">
        <input type="hidden"
            class="form-control dikanan setor-hide setor-hide{{$count}}"
            value="" name="rp_satuan[]">
    </td>
    
    <td align="center">
        <button type="button" class="btn rounded-pill remove_baris" count="{{$count}}"><i
                class="fas fa-trash text-danger"></i>
        </button>
    </td>
</tr>