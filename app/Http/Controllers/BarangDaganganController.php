<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use App\Models\Jurnal;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Stok;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use SettingHal;

class BarangDaganganController extends Controller
{
    protected
        $id_departemen = 1,
        $gudang,
        $produk;
    public function __construct()
    {
        $this->produk = Produk::with('satuan')->where([['kontrol_stok', 'Y'], ['kategori_id', 3]])->get();
        $this->gudang = Gudang::where('kategori_id', 3)->get();
    }
    public function index($gudang_id = null)
    {
        $kd_produk = Produk::latest('kd_produk')->first();
        $id_user = auth()->user()->id;
        $data = [
            'title' => 'Data Barang Dagangan',
            'produk' => Stok::getProduk(3, $gudang_id, 'Y'),
            'gudang' => $this->gudang,
            'satuan' => Satuan::all(),
            'gudang_id' => $gudang_id,
            'tgl1' => date('y-m-d'),
            'tgl2' => date('y-m-d'),
            'id_proyek' => 1,
            'kd_produk' => empty($kd_produk) ? 1 : $kd_produk->kd_produk + 1,

            'user' => User::where('posisi_id', 1)->get(),
            'halaman' => 14,
            'create' => SettingHal::btnHal(58, $id_user),
            'edit' => SettingHal::btnHal(59, $id_user),
            'delete' => SettingHal::btnHal(60, $id_user),
            'detail' => SettingHal::btnHal(61, $id_user),

        ];
        return view('persediaan_barang.barang_dagangan.index', $data);
    }

    public function create(Request $r)
    {
        $route = $r->url;

        $file = $r->file('img');
        if (!empty($file)) {

            $fileDiterima = ['jpg', 'png', 'jpeg'];
            $cek = in_array($file->getClientOriginalExtension(), $fileDiterima);
            if ($cek) {
                $maxFileSize = 1024 * 1024; // 1MB
                if ($file instanceof UploadedFile && $file->getSize() > $maxFileSize) {
                    return redirect()->route($route, $r->segment ?? '')->with('error', 'File lebih dari 1MB');
                }
                $fileName = "P-$r->kd_produk" . $file->getClientOriginalName();
                $path = $file->move('upload', $fileName);

                Produk::create([
                    'kd_produk' => $r->kd_produk,
                    'nm_produk' => $r->nm_produk,
                    'gudang_id' => $r->gudang_id,
                    'kategori_id' => 3,
                    'satuan_id' => $r->satuan_id,
                    'departemen_id' => $this->id_departemen,
                    'kontrol_stok' => $r->kontrol_stok,
                    'img' => $fileName,
                    'tgl' => date('Y-m-d'),
                    'admin' => auth()->user()->name,
                ]);
                return redirect()->route($route, $r->segment ?? '')->with('sukses', 'Berhasil tambah data');
            } else {
                return redirect()->route($route, $r->segment ?? '')->with('error', 'File tidak didukung');
            }
        } else {
            Produk::create([
                'kd_produk' => $r->kd_produk,
                'nm_produk' => $r->nm_produk,
                'gudang_id' => $r->gudang_id,
                'kategori_id' => 3,
                'satuan_id' => $r->satuan_id,
                'departemen_id' => $this->id_departemen,
                'kontrol_stok' => $r->kontrol_stok,
                'tgl' => date('Y-m-d'),
                'admin' => auth()->user()->name,
            ]);
            return redirect()->route($route, $r->segment ?? '')->with('sukses', 'Berhasil tambah data');
        }
    }

    public function edit(Request $r)
    {
        $file = $r->file('img');
        $fileDiterima = ['jpg', 'png', 'jpeg'];
        if ($file !== null) {
            $cek = in_array($file->getClientOriginalExtension(), $fileDiterima);
            if ($cek) {
                $path = public_path('upload/' . $r->imgLama);
                if (file_exists($path)) {
                    unlink($path);
                }
                $fileName = "P-$r->kd_produk" . $file->getClientOriginalName();
                $file->move('upload', $fileName);
            } else {
                return redirect()->route('produk.index')->with('error', 'File tidak didukung');
            }
        }

        Produk::where('id_produk', $r->id_produk)->update([
            'nm_produk' => $r->nm_produk,
            'gudang_id' => $r->gudang_id,
            'satuan_id' => $r->satuan_id,
            'kontrol_stok' => $r->kontrol_stok,
            'img' => $fileName ?? $r->imgLama,
            'tgl' => date('Y-m-d'),
            'admin' => auth()->user()->name,
        ]);

        return redirect()->route('barang_dagangan.index')->with('sukses', 'Berhasil update data');
    }

    public function delete(Request $r)
    {
        $produk = Produk::findOrFail($r->id_produk);
        $produk->delete();

        if (!empty($produk->img)) {
            $path = public_path('upload/' . $produk->img);
            if (file_exists($path)) {
                unlink($path);
            }
        }

        return redirect()->route('barang_dagangan.index')->with('sukses', 'Berhasil hapus data');
    }

    public function stokMasuk($gudang_id = null)
    {
        $stok = Stok::select('no_nota', 'tgl', 'jenis', DB::raw('SUM(debit) as debit'))
            ->when($gudang_id, function ($q, $gudang_id) {
                return $q->where('gudang_id', $gudang_id);
            })
            ->where([['status', 'masuk'], ['kategori_id', '3']])
            ->groupBy('no_nota')
            ->orderBy('id_stok_produk', 'DESC')
            ->get();
        $id_user = auth()->user()->id;
        $data = [
            'title' => 'Bahan Dagangan Stok Masuk',
            'gudang' => $this->gudang,
            'stok' => $stok,

            'user' => User::where('posisi_id', 1)->get(),
            'halaman' => 15,
            'create' => SettingHal::btnHal(62, $id_user),
            'edit' => SettingHal::btnHal(63, $id_user),
            'delete' => SettingHal::btnHal(64, $id_user),
            'detail' => SettingHal::btnHal(65, $id_user),
            'print' => SettingHal::btnHal(67, $id_user),
        ];
        return view('persediaan_barang.barang_dagangan.stok_masuk.stok_masuk', $data);
    }

    public function add(Request $r)
    {
        $data = [
            'title' => 'Add Stok Barang Dagangan',
            'allProduk' => $this->produk,
        ];
        return view('persediaan_barang.barang_dagangan.stok_masuk.add', $data);
    }

    public function load_menu(Request $r)
    {
        $no_nota = buatNota('tb_stok_produk', 'urutan');
        $data = [
            'no_nota' => $no_nota,
            'detail' => Stok::getStatus($r->no_nota),
            'stok' => Stok::getStokMasuk($r->no_nota),
            'produk' => $this->produk,
            'gudang' => $this->gudang,
        ];
        return view('persediaan_barang.barang_dagangan.stok_masuk.load_menu', $data);
    }

    public function tbh_baris(Request $r)
    {
        $data = [
            'title' => 'Tambah Barang',
            'count' => $r->count,
            'produk' => $this->produk
        ];
        return view('persediaan_barang.barang_dagangan.stok_masuk.tbh_baris', $data);
    }

    public function store(Request $r)
    {
        if (empty($r->id_produk)) {
            return redirect()->route('barang_dagangan.stok_masuk')->with('error', 'Data Tidak ada');
        }
        for ($i = 0; $i < count($r->id_produk); $i++) {
            
            $jml_sebelumnya = $r->jml_sebelumnya[$i];
            $debit = $r->debit[$i];

            
            $data = [
                'id_produk' => $r->id_produk[$i],
                'tgl' => $r->tgl,
                'urutan' => $r->urutan,
                'no_nota' => $r->no_nota,
                'departemen_id' => '1',
                'kategori_id' => '3',
                'status' => 'masuk',
                'jenis' => $r->simpan == 'simpan' ? 'selesai' : 'draft',
                'gudang_id' => 0,
                'jml_sebelumnya' => $jml_sebelumnya,
                'jml_sesudahnya' => $jml_sebelumnya + $debit,
                'debit' => $debit,
                'ket' => $r->ket,
                'rp_satuan' => $r->rp_satuan[$i],
                'admin' => auth()->user()->name,
            ];

            $id_akun = 31;

            $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $id_akun)->first();
            $akun = DB::table('akun')->where('id_akun', $id_akun)->first();
            $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);

            Jurnal::create([
                'tgl' => $r->tgl,
                'id_akun' => $id_akun,
                'id_buku' => 16,
                'no_nota' => 'PEM-' . $r->no_nota,
                'ket' => "Pembelian",
                'debit' => $r->rp_satuan[$i],
                'kredit' => 0,
                'no_urut' => $akun->inisial . '-' . $urutan,
                'urutan' => $urutan,
                'admin' => auth()->user()->name,
            ]);

            $id_akun = 3;

            $max_akun = DB::table('jurnal')->latest('urutan')->where('id_akun', $id_akun)->first();
            $akun = DB::table('akun')->where('id_akun', $id_akun)->first();
            $urutan = empty($max_akun) ? '1001' : ($max_akun->urutan == 0 ? '1001' : $max_akun->urutan + 1);

            Jurnal::create([
                'tgl' => $r->tgl,
                'id_akun' => $id_akun,
                'id_buku' => 16,
                'no_nota' => 'PEM-' . $r->no_nota,
                'ket' => "Pembelian",
                'kredit' => $r->rp_satuan[$i],
                'debit' => 0,
                'no_urut' => $akun->inisial . '-' . $urutan,
                'urutan' => $urutan,
                'admin' => auth()->user()->name,
            ]);

            if (!empty($r->jenis)) {
                Stok::where([['urutan', $r->urutan], ['id_produk', $r->id_produk[$i]]])->update($data);
            } else {
                Stok::create($data);
            }
        }

        return redirect()->route('barang_dagangan.stok_masuk')->with('sukses', 'Data Berhasil Ditambahkan');
    }

    public function edit_load($id_produk)
    {
        $data = [
            'produk' => Produk::where('id_produk', $id_produk)->first(),
            'gudang' => $this->gudang,
            'satuan' => Satuan::all(),
        ];
        return view('persediaan_barang.bahan_baku.edit', $data);
    }

    public function detail($no_nota)
    {
        $data = [
            'title' => 'Stok Masuk Edit',
            'stok' => Stok::getCetak($no_nota),
            'detail' => Stok::getStatus($no_nota),
        ];
        return view('persediaan_barang.stok_masuk.detail', $data);
    }
    public function get_stok_sebelumnya(Request $r)
    {
        return Stok::getStokMasuk($r->id_produk);
    }

    public function opname($gudang_id = null)
    {
        $produk = Stok::where([['status', 'opname'], ['gudang_id', $gudang_id ?? 4], ['kategori_id', 3]])
            ->whereBetween('tgl', [$tgl1 ?? date('Y-m-1'), $tgl2 ?? date('Y-m-d')])
            ->orderBy('no_nota', 'desc')
            ->groupBy('no_nota')
            ->get();

        $data = [
            'title' => 'Opname Barang Dagangan',
            'gudang' => $this->gudang,
            'stok' => $produk,
        ];
        return view('persediaan_barang.barang_dagangan.opname.opname', $data);
    }

    public function opname_add($gudang_id = null)
    {
        $produk = Stok::getProduk(3, $gudang_id, 'Y');

        $data = [
            'title' => 'Opname',
            'gudang' => $this->gudang,
            'produk' => $produk,
        ];
        return view('persediaan_barang.barang_dagangan.opname.opname_add', $data);
    }

    public function opname_edit(Request $r)
    {
        $no_nota = decrypt($r->no_nota);
        $data = [
            'title' => 'Opname',
            'gudang' => $this->gudang,
            'produk' => Stok::where('no_nota', $no_nota)->get(),
            'no_nota' => $no_nota,
        ];
        return view('persediaan_barang.barang_dagangan.opname.edit',$data);
    }

    public function opname_store(Request $r)
    {
        $no_nota = buatNota('tb_stok_produk', 'urutan');

        for ($i = 0; $i < count($r->id_produk); $i++) {
            $total = $r->buku[$i] - $r->fisik[$i];

            $debit = $total < 0 ? $total * -1 : 0;
            $kredit = $total < 0 ? 0 : $total;

            $data = [
                'id_produk' => $r->id_produk[$i],
                'tgl' => date('Y-m-d'),
                'urutan' => $no_nota,
                'no_nota' => 'OPNDAGANG-' . $no_nota,
                'departemen_id' => '1',
                'kategori_id' => 3,
                'status' => 'opname',
                'jenis' => $r->simpan == 'simpan' ? 'selesai' : 'draft',
                'gudang_id' => $r->gudang_id[$i],
                'jml_sebelumnya' => $r->buku[$i],
                'jml_sesudahnya' => $r->fisik[$i],
                'selisih' => $r->selisih[$i],
                'debit' => $debit,
                'kredit' => $kredit,
                'ket' => 'Opname Bahan DAGANG',
                'rp_satuan' => '0',
                'admin' => auth()->user()->name,
            ];

            Stok::create($data);
        }

        return redirect()->route('barang_dagangan.opname')->with('sukses', 'Berhasil opname');
    }

    public function opname_update(Request $r)
    {
        for ($i=0; $i < count($r->id_produk); $i++) { 
            $total = $r->buku[$i] - $r->fisik[$i];
            
            $debit = $total < 0 ? $total * -1 : 0;
            $kredit = $total < 0 ? 0 : $total;

            $data = [
                'tgl' => date('Y-m-d'),
                'departemen_id' => '1',
                'status' => 'opname',
                'jenis' => $r->simpan == 'simpan' ? 'selesai' : 'draft',
                'gudang_id' => $r->gudang_id[$i],
                'kategori_id' => 3,
                'jml_sesudahnya' => $r->fisik[$i],
                'selisih' => $r->selisih[$i],
                'debit' => $debit,
                'kredit' => $kredit,
                'ket' => 'Opname',
                'rp_satuan' => '0',
                'admin' => auth()->user()->name,
            ];

            Stok::where([['no_nota', $r->no_nota], ['id_produk', $r->id_produk[$i]]])->update($data);
        }

        return redirect()->route('barang_dagangan.opname')->with('sukses', 'Berhasil opname');
    }

    public function opname_detail($no_nota)
    {
        $data = [
            'title' => 'Opname Detail',
            'stok' => Stok::where('no_nota', $no_nota)->get(),
            'detail' => Stok::getStatus($no_nota),
        ];
        return view('persediaan_barang.barang_dagangan.opname.opname_detail', $data);
    }

    public function opname_cetak(Request $r)
    {
        if (strlen($r->no_nota) > 228 || strlen($r->no_nota) < 228) {
            return redirect()->back()->with('error', 'No nota tidak terdaftar !');
        }
        $no_nota = decrypt($r->no_nota);

        $data = [
            'title' => 'Opname Cetak',
            'stok' => Stok::getCetak($no_nota),
            'detail' => Stok::getStatus($no_nota),
        ];
        return view('persediaan_barang.barang_dagangan.opname.cetak', $data);
    }
}
