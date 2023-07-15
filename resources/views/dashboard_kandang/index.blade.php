<x-theme.app title="{{ $title }}" table="Y" sizeCard="12" cont="container-fluid">
    <x-slot name="cardHeader">
        <style>
            .abu {
                background-color: #d3d3d3 !important;
                color: rgb(37, 37, 37);
            }

            .putih {
                background-color: #f6e6e6 !important;
                color: rgb(37, 37, 37);
            }

            .merah {
                background-color: #ff3030 !important;
                color: white;
            }
        </style>
        <h5 class="float-start mt-1">{{ $title }} ~ {{ tanggal(date('Y-m-d')) }}</h5>

        <div class="row justify-content-end">

            <div class="col-lg-12">
                <div class="btn-group border-1 float-end" role="group">
                    <span class="btn btn-sm" data-bs-toggle="dropdown">
                        <i class="fas fa-plus-circle text-primary"></i> Tambah
                    </span>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        <li>
                            <a class="dropdown-item text-info" href="#" data-bs-toggle="modal"
                                data-bs-target="#tambah_kandang"><i class="me-2 fas fa-border-all"></i>kandang</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    </x-slot>
    <x-slot name="cardBody">

        @include('dashboard_kandang.tabel.stokTelur')
        <section class="row">
            @if (session()->has('error'))
                <div class="col-lg-12">
                    <x-theme.alert pesan="kontak dr anto kalo ada yg merah" />
                </div>
            @endif

            @include('dashboard_kandang.tabel.penjualanUmum')
            @include('dashboard_kandang.tabel.inputKandangHarian')
            @include('dashboard_kandang.tabel.pakan')


            @include('dashboard_kandang.modal.tambah_pakan')
            @include('dashboard_kandang.modal.tambah_obat_pakan')
            @include('dashboard_kandang.modal.tambah_obat_air')
            @include('dashboard_kandang.modal.tambah_obat_ayam')
        </section>
    </x-slot>
    @section('js')
        <script>
            function modalSelect2() {
                $('.select2-kandang').select2({
                    dropdownParent: $('#tambah_kandang .modal-content')
                });
                $('.select2-obat').select2({
                    dropdownParent: $('#tambah_obat_pakan .modal-content')
                });
                $('.select2-air').select2({
                    dropdownParent: $('#tambah_obat_air .modal-content')
                });
                $('.select2-edit-perencanaan').select2({
                    dropdownParent: $('#edit_perencanaan .modal-content')
                });
                $('.select2-pakan').select2({
                    dropdownParent: $('#opname_pakan .modal-content')
                });
            }
            edit('tambah_telur', 'id_kandang', 'dashboard_kandang/load_telur', 'load_telur')
            edit('tambah_populasi', 'id_kandang', 'dashboard_kandang/load_populasi', 'load_populasi')
            edit('detail_nota', 'urutan', 'dashboard_kandang/load_detail_nota', 'load_detail_nota')
            edit('detail_perencanaan', 'id_kandang', 'dashboard_kandang/load_detail_perencanaan', 'load_detail_perencanaan')
            viewHistoryPerencanaan()
            viewEditPerencanaan()
            hasilLayer()

            function viewHistoryPerencanaan() {
                $(document).on('click', '#btnPerencanaan', function() {
                    var tgl = $("#tglHistoryPerencanaan").val();
                    var id_kandang = $("#id_kandangPerencanaan").val();

                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.viewHistoryPerencanaan') }}",
                        data: {
                            tgl: tgl,
                            id_kandang: id_kandang
                        },
                        success: function(r) {
                            $("#hasilPerencanaan").html(r);
                        }
                    });
                })
            }

            function hasilLayer() {
                $(document).on('click', '#btnLayer', function() {
                    var tgl = $("#tglLayer").val();

                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.hasilLayer') }}?tgl=" + tgl,
                        success: function(data) {
                            $("#hasilLayer").html(data);
                            $('.select2').select2()
                        }
                    });
                })
            }

            function viewEditPerencanaan() {
                $(document).on('click', "#edit_per", function() {
                    var id_kandang = $(this).attr('id_kandang')
                    var tgl = $(this).attr('tgl')
                    $("#edit_perencanaan").modal('show')
                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.viewHistoryEditPerencanaan') }}",
                        data: {
                            id_kandang: id_kandang,
                            tgl: tgl,
                        },
                        success: function(r) {
                            $('#hasilEditPerencanaan').html(r)
                            $('.select2').select2()
                        }
                    });
                })
            }

            // perencanaan -------------------------------------
            var count = 1

            function toast(pesan) {
                Toastify({
                    text: pesan,
                    duration: 3000,
                    style: {
                        background: "#EAF7EE",
                        color: "#7F8B8B"
                    },
                    close: true,
                    avatar: "https://cdn-icons-png.flaticon.com/512/190/190411.png"
                }).showToast();
            }
            editPerencanaan('tambah_perencanaan', 'id_kandang', 'dashboard_kandang/load_perencanaan', 'load_perencanaan')

            function editPerencanaan(kelas, attr, link, load) {
                $(document).on('click', `.${kelas}`, function() {
                    var id = $(this).attr(`${attr}`)
                    $.ajax({
                        type: "GET",
                        url: `${link}/${id}`,
                        success: function(r) {
                            $(`#${load}`).html(r);

                            loadPakanPerencanaan()
                            loadObatPakan()
                            loadObatAir()
                            loadObatAyam()
                        }
                    });
                })
            }

            // tambah pakan perencanaan
            keyupKgPakanBox()
            keyupPersen()
            keyupPersenEdit()

            function keyupPersenEdit() {
                $(document).on('keyup', '.persenEdit', function() {
                    var detail = $(this).attr('kd');
                    var persen = $(this).val();
                    var pop = $("#getPopulasiEdit").val();
                    var gr = $("#grEdit").val();
                    // alert(`${detail} - ${persen} - ${pop} - ${gr}`)
                    var krng = $("#krngEdit").val();


                    var hasil = (parseFloat(persen) * parseFloat(gr) * parseFloat(pop)) / 100;
                    // alert(hasil);

                    $("#hasilEdit" + detail).val(hasil);

                    var total = 0;
                    $(".hasilEdit").each(function() {
                        total += parseFloat($(this).val());
                    });
                    // var kg = parseFloat(total) / (parseFloat(krng) * 1000)
                    var kg = Math.floor(parseFloat(total) / (parseFloat(krng) * 1000));


                    $('#totalEdit').val(total);
                    $('#krng_fEdit').val(kg);
                    var krng_f = $("#krng_fEdit").val();
                    var kg_sisa = ((parseFloat(total) / (parseFloat(krng) * 1000)) - parseFloat(krng_f)) *
                        10;
                    var number = kg_sisa.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
                    $('#krng_sEdit').val(number);
                })

            }

            function keyupKgPakanBox() {
                $(document).on('keyup', '#krng', function() {
                    var krng = $(this).val()
                    var pop = $("#getPopulasi").val();
                    var gr = $("#gr").val();

                    var total = 0;
                    $(".hasil").each(function() {
                        total += parseFloat($(this).val());
                    });
                    var kg = Math.floor(parseFloat(total) / (parseFloat(krng) * 1000));
                    $('#total').val(total);
                    $('#krng_f').val(kg);
                    var krng_f = $("#krng_f").val();
                    var kg_sisa = ((parseFloat(total) / (parseFloat(krng) * 1000)) - parseFloat(krng_f)) *
                        10;
                    var number = kg_sisa.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
                    $('#krng_s').val(number);
                })
            }

            function keyupPersen() {
                $(document).on('keyup', '.persen', function() {
                    var detail = $(this).attr('kd');
                    var persen = $(this).val();
                    var pop = $("#getPopulasi").val();
                    var gr = $("#gr").val();
                    // alert(`${detail} - ${persen} - ${pop} - ${gr}`)
                    var krng = $("#krng").val();

                    var hasil = (parseFloat(persen) * parseFloat(gr) * parseFloat(pop)) / 100;
                    // alert(hasil);

                    $("#hasil" + detail).val(hasil);

                    var total = 0;
                    $(".hasil").each(function() {
                        total += parseFloat($(this).val());
                    });
                    console.log(total)
                    // var kg = parseFloat(total) / (parseFloat(krng) * 1000)
                    var kg = Math.floor(parseFloat(total) / (parseFloat(krng) * 1000));

                    $('#total').val(total);
                    $('#krng_f').val(kg);
                    var krng_f = $("#krng_f").val();
                    var kg_sisa = ((parseFloat(total) / (parseFloat(krng) * 1000)) - parseFloat(krng_f)) *
                        10;
                    var number = kg_sisa.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
                    $('#krng_s').val(number);
                })
            }

            function loadPakanPerencanaan() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.load_pakan_perencanaan') }}",
                    success: function(r) {
                        $("#load_pakan_perencanaan").html(r);
                        $('.select2-edit').select2({
                            dropdownParent: $(`#tambah_perencanaan .modal-content`)
                        });
                        plusRowPakan('tbhPakan', 'dashboard_kandang/tbh_pakan')
                    }
                });
            }

            function plusRowPakan(classPlus, url) {
                $(document).on("click", "." + classPlus, function() {
                    count += 1;
                    $.ajax({
                        url: `${url}?count=` + count,
                        type: "GET",
                        success: function(data) {
                            $("#" + classPlus).append(data);
                            $(".select2-pakan").select2({
                                dropdownParent: $(`#tambah_perencanaan .modal-content`)
                            });
                        },
                    });
                });

                $(document).on('click', '.remove_baris', function() {
                    var delete_row = $(this).attr("count");
                    $(".baris" + delete_row).remove();

                    var pop = $("#getPopulasi").val();
                    var gr = $("#gr").val();
                    var krng = $("#krng").val();

                    var total = 0;
                    $(".hasil").each(function() {
                        total += parseFloat($(this).val());
                    });
                    var kg = Math.floor(parseFloat(total) / (parseFloat(krng) * 1000));
                    $('#total').val(total);
                    $('#krng_f').val(kg);
                    var krng_f = $("#krng_f").val();
                    var kg_sisa = ((parseFloat(total) / (parseFloat(krng) * 1000)) - parseFloat(krng_f)) *
                        10;
                    var number = kg_sisa.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
                    $('#krng_s').val(number);
                })
            }
            $(document).on("change", '.pakan_input', function() {
                var id_pakan = $(this).val()
                var count = $(this).attr('count')
                if (id_pakan == 'tambah') {
                    $("#tambah_pakan").modal('show')
                } else {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.get_stok_pakan') }}?id_pakan=" + id_pakan,
                        success: function(r) {
                            $(".get_stok_pakan" + count).val(r);
                        }
                    });
                }
            })
            $(document).on('submit', '#form_tambah_pakan', function(e) {
                e.preventDefault()
                var datas = $("#form_tambah_pakan").serialize()
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.save_tambah_pakan') }}",
                    data: datas,
                    success: function(response) {
                        toast('Berhasil tambah Pakan')
                        loadPakanPerencanaan()
                        $("#tambah_pakan").modal('hide')

                    }
                });
            })

            // tambah obat pakan
            function loadObatPakan() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.load_obat_pakan') }}",
                    success: function(r) {
                        $("#load_obat_pakan").html(r);
                        $('.select2-edit').select2({
                            dropdownParent: $(`#tambah_perencanaan .modal-content`)
                        });
                        plusRowObatPakan('tbhObatPakan', 'dashboard_kandang/tbh_obatPakan')
                    }
                });
            }

            function plusRowObatPakan(classPlus, url) {
                $(document).on("click", "." + classPlus, function() {
                    count += 1;
                    $.ajax({
                        url: `${url}?count=` + count,
                        type: "GET",
                        success: function(data) {
                            $("#" + classPlus).append(data);
                            $(".select2-pakan").select2({
                                dropdownParent: $(`#tambah_perencanaan .modal-content`)
                            });
                        },
                    });
                });

                $(document).on('click', '.remove_baris', function() {
                    var delete_row = $(this).attr("count");
                    $(".baris" + delete_row).remove();
                })
            }
            $(document).on("change", '.obat_pakan_input', function() {
                var id_produk = $(this).val()
                var count = $(this).attr('count')
                if (id_produk == 'tambah') {
                    $("#tambah_obat_pakan").modal('show')
                } else {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.get_stok_obat_pakan') }}?id_produk=" + id_produk,
                        dataType: 'json',
                        success: function(r) {
                            $(".get_dosis_satuan" + count).val(r.dosis_satuan);
                            $(".get_campuran_satuan" + count).val(r.campuran_satuan);
                        }
                    });
                }
            })
            $(document).on('submit', '#form_tambah_obat_pakan', function(e) {
                e.preventDefault()
                var datas = $("#form_tambah_obat_pakan").serialize()
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.save_tambah_obat_pakan') }}",
                    data: datas,
                    success: function(response) {
                        toast('Berhasil tambah Obat Pakan')
                        loadObatPakan()
                        $("#tambah_obat_pakan").modal('hide')

                    }
                });
            })

            function loadObatAir() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.load_obat_air') }}",
                    success: function(r) {
                        $("#load_obat_air").html(r);
                        $('.select2-edit').select2({
                            dropdownParent: $(`#tambah_perencanaan .modal-content`)
                        });
                        plusRowObatAir('tbhObatAir', 'dashboard_kandang/tbh_obatAir')
                    }
                });
            }

            function plusRowObatAir(classPlus, url) {
                $(document).on("click", "." + classPlus, function() {
                    count += 1;
                    $.ajax({
                        url: `${url}?count=` + count,
                        type: "GET",
                        success: function(data) {
                            $("#" + classPlus).append(data);
                            $(".select2-pakan").select2({
                                dropdownParent: $(`#tambah_perencanaan .modal-content`)
                            });
                        },
                    });
                });

                $(document).on('click', '.remove_baris', function() {
                    var delete_row = $(this).attr("count");
                    $(".baris" + delete_row).remove();
                })
            }
            $(document).on("change", '.obat_air_input', function() {
                var id_produk = $(this).val()
                var count = $(this).attr('count')
                if (id_produk == 'tambah') {
                    $("#tambah_obat_air").modal('show')
                } else {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.get_stok_obat_air') }}?id_produk=" + id_produk,
                        dataType: 'json',
                        success: function(r) {
                            $(".get_dosis_satuan_air" + count).val(r.dosis_satuan);
                            $(".get_campuran_satuan_air" + count).val(r.campuran_satuan);
                        }
                    });
                }
            })
            $(document).on('submit', '#form_tambah_obat_air', function(e) {
                e.preventDefault()
                var datas = $("#form_tambah_obat_air").serialize()
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.save_tambah_obat_air') }}",
                    data: datas,
                    success: function(response) {
                        toast('Berhasil tambah Obat Air')
                        loadObatAir()
                        $("#tambah_obat_air").modal('hide')

                    }
                });
            })

            // obat ayam
            function loadObatAyam() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.load_obat_ayam') }}",
                    success: function(r) {
                        $("#load_obat_ayam").html(r);
                        $('.select2-edit').select2({
                            dropdownParent: $(`#tambah_perencanaan .modal-content`)
                        });
                    }
                });
            }
            $(document).on("change", '.obat_ayam_input', function() {
                var id_produk = $(this).val()
                var count = $(this).attr('count')
                if (id_produk == 'tambah') {
                    $("#tambah_obat_ayam").modal('show')
                } else {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('dashboard_kandang.get_stok_obat_ayam') }}?id_produk=" + id_produk,
                        success: function(r) {
                            $(".get_dosis_satuan_ayam").val(r);
                        }
                    });
                }
            })
            $(document).on('submit', '#form_tambah_obat_ayam', function(e) {
                e.preventDefault()
                var datas = $("#form_tambah_obat_ayam").serialize()
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.save_tambah_obat_ayam') }}",
                    data: datas,
                    success: function(response) {
                        toast('Berhasil tambah Obat Ayam')
                        loadObatAyam()
                        $("#tambah_obat_ayam").modal('hide')

                    }
                });
            })
            // end perencanaan -------------------------------------
            modalSelect2()

            $(document).on("keyup", ".pcs", function() {
                var count = $(this).attr('count');
                var pcs = $('.pcs' + count).val()
                var kgPcs = $('.kgPcs' + count).val()

                var potongan = ((30 - parseFloat(pcs)) * 5.5) / 1000

                $('.potongan' + count).val(potongan);
                $('.ttlKgPcs' + count).val(kgPcs - potongan);

            });

            $(document).on("keyup", ".kgPcs", function() {
                var count = $(this).attr('count');
                var pcs = $('.kgPcs' + count).val()
                var potongan = $('.potongan' + count).val()
                var ttlKg = parseFloat(pcs) - parseFloat(potongan)
                $('.ttlKgPcs' + count).val(ttlKg);
            });
            $(document).on("click", ".history_opname", function() {
                $.ajax({
                    type: "get",
                    url: "/history_opname_mtd",
                    success: function(data) {
                        $('#history_opname').html(data);
                        $('#history_opn').modal('show');
                        $('#table_history').DataTable({
                            "paging": true,
                            "pageLength": 10,
                            "lengthChange": true,
                            "stateSave": true,
                            "searching": true,
                        });
                    }
                });
            });

            $(document).on('submit', '#history_serach_opname_mtd', function(e) {
                e.preventDefault();
                var tgl1 = $(".tgl1").val();
                var tgl2 = $(".tgl2").val();
                $.ajax({
                    type: "get",
                    url: "/history_opname_mtd?tgl1=" + tgl1 + "&tgl2=" + tgl2,
                    success: function(data) {

                        $('#history_opname').html(data)
                        $('#table_history').DataTable({
                            "paging": true,
                            "pageLength": 10,
                            "lengthChange": true,
                            "stateSave": true,
                            "searching": true,
                        });
                    }
                });
            });


            // pakan
        </script>

        <script>
            function load_stok_pakan() {
                $.ajax({
                    type: "GET",
                    url: "{{ route('dashboard_kandang.load_stok_pakan') }}",
                    success: function(r) {
                        $("#load_stok_pakan").html(r);
                        $('[data-bs-toggle="tooltip"]').tooltip();
                    },
                });
            }
            load_stok_pakan();

            $(document).on("click", ".opnme_pakan", function() {
                $.ajax({
                    type: "get",
                    url: "dashboard_kandang/opname_pakan",
                    success: function(r) {
                        $("#opname_stk_pkn").html(r);
                        $("#opname_pakan").modal("show");
                    },
                });
            });
            $(document).on("change", ".tgl_opname", function() {
                var tgl = $(this).val();
                $.ajax({
                    type: "get",
                    url: "dashboard_kandang/opname_pakan?tgl=" + tgl,
                    success: function(r) {
                        $("#opname_stk_pkn").html(r);
                    },
                });
            });
            $(document).on("click", ".opnme_vitamin", function() {
                $.ajax({
                    type: "get",
                    url: "dashboard_kandang/opnme_vitamin",
                    success: function(r) {
                        $("#opname_stk_vtmn").html(r);
                        $("#opname_vitamin").modal("show");
                    },
                });
            });
            $(document).on("change", ".tgl_opname", function() {
                var tgl = $(this).val();
                $.ajax({
                    type: "get",
                    url: "dashboard_kandang/opnme_vitamin?tgl=" + tgl,
                    success: function(r) {
                        $("#opname_stk_vtmn").html(r);
                    },
                });
            });
            $(document).on("keyup", ".aktual", function() {
                var count = $(this).attr("count");
                var aktual = $(".aktual" + count).val();
                var stk_program = $(".stk_program" + count).val();

                var selisih = parseFloat(stk_program) - parseFloat(aktual);

                $(".selisih_pakan" + count).text(selisih);
            });
            $(document).on("click", ".history_stok", function() {
                var id_pakan = $(this).attr("id_pakan");
                $.ajax({
                    type: "get",
                    url: "dashboard_kandang/history_stok?id_pakan=" + id_pakan,
                    success: function(r) {
                        $("#history_stk").html(r);
                        $("#history_stok").modal("show");
                    },
                });
            });
            $(document).on("click", ".tbh_pakan", function() {
                $.ajax({
                    type: "get",
                    url: "dashboard_kandang/tambah_pakan_stok",
                    success: function(r) {
                        $("#load_tambah_pakan").html(r);
                        $('.select2tbhPakan').select2({
                            dropdownParent: $('#tbh_pakan .modal-content')
                        });
                    },
                });
            });
            $(document).on("click", ".tbh_vitamin", function() {
                $.ajax({
                    type: "get",
                    url: "dashboard_kandang/tambah_vitamin",
                    success: function(r) {
                        $("#load_tambah_pakan").html(r);
                        $('.select2tbhPakan').select2({
                            dropdownParent: $('#tbh_pakan .modal-content')
                        });
                    },
                });
            });
            $(document).on("submit", "#search_history_stk", function(e) {
                e.preventDefault();
                var tgl1 = $("#tgl1").val();
                var tgl2 = $("#tgl2").val();
                var id_pakan = $("#id_pakan").val();
                $.ajax({
                    type: "get",
                    url: "dashboard_kandang/history_stok?tgl1=" +
                        tgl1 +
                        "&tgl2=" +
                        tgl2 +
                        "&id_pakan=" +
                        id_pakan,
                    success: function(data) {
                        $("#history_stk").html(data);
                    },
                });
            });
            var count = 2;
            $(document).on("click", ".tbh_baris", function() {
                count = count + 1;
                $.ajax({
                    url: "dashboard_kandang/tambah_baris_stok?count=" + count,
                    type: "Get",
                    success: function(data) {
                        $("#tb_baris_produk").append(data);
                        $('.select2tbhPakan').select2({
                            dropdownParent: $('#tbh_pakan .modal-content')
                        });
                    },
                });
            });
            var count = 2;
            $(document).on("click", ".tbh_baris_vitamin", function() {
                count = count + 1;
                $.ajax({
                    url: "dashboard_kandang/tambah_baris_stok_vitamin?count=" + count,
                    type: "Get",
                    success: function(data) {
                        $("#tb_baris_produk").append(data);
                        $('.select2tbhPakan').select2({
                            dropdownParent: $('#tbh_pakan .modal-content')
                        });
                    },
                });
            });
            $(document).on("click", ".remove_baris", function() {
                var delete_row = $(this).attr("count");
                $(".baris" + delete_row).remove();
            });
        </script>
    @endsection
</x-theme.app>
