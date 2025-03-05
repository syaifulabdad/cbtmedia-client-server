@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2 {
            /* z-index: 9999999 */
            z-index: 50 !important;
        }

        td {
            /* vertical-align: top !important; */
        }

        .table-dt td {
            border: 0px !important;
            /* padding: 0px !important; */
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Dashboard
        @endslot
        @slot('title')
            {{ $title }}
        @endslot
        @slot('title_sm')
            {{ $title_sm }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div>
                <div class="card">
                    <div class="card-header border-0 d-flex align-items-center pb-0">
                        <div class="col-auto">
                            <select id="jadwal_id" class="form-control form-select" onchange="getSelectBankSoal()">
                                @foreach ($getJadwal as $jadwal)
                                    <option value="{{ $jadwal->id }}" {{ $jadwal->tanggal == date('Y-m-d') ? 'selected' : null }}>{{ "Hari Ke $jadwal->hari_ke. " . date('D, d/m/Y', strtotime($jadwal->tanggal)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto ms-2">
                            <select id="bank_soal_id" class="form-control form-select">
                            </select>
                        </div>
                        <div class="col-auto ms-2">
                            <a href="javascript:void(0)" class="btn btn-primary btnSubmit"><i class="ri-search-line me-1"></i> Submit</a>
                        </div>
                    </div>
                    <hr>

                    <div class="card-header border-0 d-flex align-items-center pt-0 pb-0">
                        <div class="col-auto me-2">
                            <select id="ruang_id" class="form-control form-select" onchange="reload()">
                                <option value="">Semua Ruang</option>
                                @foreach ($getRuang as $ruang)
                                    <option value="{{ $ruang->id }}">{{ $ruang->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm">
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <button type="button" class="btn btn-info" onclick="reload()"><i class="ri-refresh-line ri-lg"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="dataTables" class="table table-nowrap table-bordered table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        @foreach ($dataTable as $key => $value)
                                            <th>
                                                @php echo $value['label'] ?? ucwords(strtolower(str_replace(['_id', '_'], ['', ' '], isset(explode('.', $key)[1]) ? explode('.', $key)[1] : $key))) @endphp
                                            </th>
                                        @endforeach
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-data" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">&nbsp;</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-data" class="tablelist-form" autocomplete="off">
                        @csrf
                        <input type="hidden" class="form-control" name="id">
                        <input type="hidden" class="form-control" name="jadwal_id">
                        <input type="hidden" class="form-control" name="bank_soal_id">

                        {{-- d-flex justify-content-end align-items-end pe-3 --}}
                        <div class="row mb-1">
                            <label for="" class="col-sm-4 col-form-label ">Jadwal</label>
                            <div class="col-md-8">
                                <input type="text" id="jadwal" class="form-control" disabled>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="" class="col-sm-4 col-form-label ">Mata Pelajaran</label>
                            <div class="col-md-8">
                                <input type="text" id="mata_pelajaran" class="form-control" disabled>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="" class="col-sm-4 col-form-label ">Tingkat & Jurusan</label>
                            <div class="col-md-8">
                                <input type="text" id="tingkat_jurusan" class="form-control" disabled>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="" class="col-sm-4 col-form-label ">Rombongan Belajar</label>
                            <div class="col-md-8">
                                <select name="rombongan_belajar_id[]" class="form-control form-select rombongan_belajar_id select2" multiple>
                                    <option value=""></option>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="" class="col-sm-4 col-form-label ">Ruang Ujian</label>
                            <div class="col-md-8">
                                <select name="ruang_id" class="form-control form-select">
                                    <option value="">Semua Ruang</option>
                                    @foreach ($getRuang as $ruang)
                                        <option value="{{ $ruang->id }}">{{ $ruang->nama }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="" class="col-sm-4 col-form-label ">Sesi Ujian</label>
                            <div class="col-md-4">
                                <select name="sesi_ke" class="form-control form-select">
                                    @foreach (range(1, $ujian->jumlah_sesi) as $sesi)
                                        <option value="{{ $sesi }}">Sesi {{ $sesi }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="" class="col-sm-4 col-form-label ">Mode Waktu</label>
                            <div class="col-md-6">
                                <select name="mode_waktu" class="form-control form-select">
                                    <option value="waktu-admin">Waktu Admin/Server</option>
                                    <option value="waktu-peserta">Waktu Peserta</option>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="row mb-1 alokasiWaktuPeserta">
                            <label for="" class="col-sm-4 col-form-label ">Alokasi Waktu Peserta</label>
                            <div class="col-md-3">
                                <input type="number" name="alokasi_waktu" class="form-control">
                                <span class="invalid-feedback"></span>
                            </div>
                            <label class="col-sm-4 col-form-label">Menit</label>
                        </div>
                        <div class="row mb-1 alokasiWaktuSoal d-none">
                            <label for="" class="col-sm-4 col-form-label ">Alokasi Waktu Soal</label>
                            <div class="col-md-3">
                                <input type="number" name="alokasi_waktu_soal" class="form-control">
                                <span class="invalid-feedback"></span>
                            </div>
                            <label class="col-sm-4 col-form-label">Menit</label>
                        </div>
                        <div class="row mb-1">
                            <label for="" class="col-sm-4 col-form-label ">Waktu Mulai Ujian</label>
                            <div class="col-md-3">
                                <input type="time" name="waktu_mulai" class="form-control">
                                <span class="invalid-feedback"></span>
                            </div>
                            <label class="col-sm-4 col-form-label">Jam : Menit</label>
                        </div>
                        <div class="row mb-1">
                            <label for="" class="col-sm-4 col-form-label ">Toleransi Keterlambatan</label>
                            <div class="col-md-3">
                                <input type="number" name="batas_masuk" class="form-control" value="0">
                                <span class="invalid-feedback"></span>
                            </div>
                            <label class="col-sm-4 col-form-label">Menit</label>
                        </div>
                        <div class="row mb-1">
                            <label for="" class="col-sm-4 col-form-label ">Waktu Minimal Mengerjakan</label>
                            <div class="col-md-3">
                                <input type="number" name="waktu_minimal" class="form-control" value="0">
                                <span class="invalid-feedback"></span>
                            </div>
                            <label class="col-sm-4 col-form-label">Menit</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <a href="javascript:void(0)" class="btn btn-success btnSave"><i class="bx bx-save"></i> Simpan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('.select2').select2({
            dropdownParent: $("#modal-data"),
            width: '100%',
        });
    </script>

    <script>
        var table;
        var ordering = {{ isset($dataTableOrder) ? 'true' : 'false' }};

        $(function() {
            table = $('#dataTables').DataTable({
                initComplete: function() {
                    var api = this.api();
                    $('#dataTables_filter input')
                        .off('.DT')
                        .on('keyup.DT', function(e) {
                            if (e.keyCode == 13) {
                                api.search(this.value).draw();
                            }
                        });
                },
                "language": {
                    "paginate": {
                        "previous": "<",
                        "next": ">",
                    },
                    "emptyTable": "<div class='m-4 text-bold'>Tidak ada data yang ditampilkan.</div>"
                },
                oLanguage: {
                    sProcessing: 'loading...',
                    sSearch: '',
                    sSearchPlaceholder: 'Search',
                    sLengthMenu: '_MENU_',
                },
                "paging": false,
                "searching": false,
                "ordering": ordering,
                "info": false,
                "autoWidth": false,
                "lengthChange": true,
                "bDestroy": true,
                "responsive": true,
                'processing': true,
                'serverSide': true,
                "ajax": {
                    "url": "{{ $cUrl }}/data-tables",
                    "type": "GET",
                    data: function(dt) {
                        dt._token = "{{ csrf_token() }}";
                        dt.jadwal_id = $('#jadwal_id').val();
                        dt.ruang_id = $('#ruang_id').val();

                        @isset($dataTableFilter)
                            @foreach ($dataTableFilter as $key => $value)
                                dt.{{ isset(explode('.', $key)[1]) ? explode('.', $key)[1] : $key }} = $('#{{ isset(explode('.', $key)[1]) ? explode('.', $key)[1] : $key }}').val();
                            @endforeach
                        @endisset
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        width: "30px",
                        className: "text-center nowrap"
                    },

                    @foreach ($dataTable as $key => $value)
                        {
                            data: "{{ isset(explode('.', $key)[1]) ? explode('.', $key)[1] : $key }}",
                            name: "{{ $key }}",
                            orderable: {{ isset($value['orderable']) && $value['orderable'] ? 'true' : 'false' }},
                            searchable: {{ isset($value['searchable']) && $value['searchable'] ? 'true' : 'false' }},
                            width: "{{ isset($value['width']) && $value['width'] ? $value['width'] : null }}",
                            className: "{{ isset($value['className']) && $value['className'] ? $value['className'] : null }}",
                        },
                    @endforeach

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        width: "10px",
                        className: "text-center nowrap"
                    },
                ],
                @isset($dataTableOrder)
                    order: [
                        @php
                            foreach ($dataTableOrder as $orderName) {
                                $no = 1;
                                foreach ($dataTable as $key => $value) {
                                    $order = explode(' ', $orderName);
                                    if ($key == $order[0]) {
                                        echo "[$no,'$order[1]'],";
                                    }
                                    ++$no;
                                }
                            }
                        @endphp
                    ]
                @endisset

            });
        });

        function reload() {
            table.ajax.reload(null, false);
        }

        $('body').on('click', '.btnDelete', function() {
            var id = $(this).data('id');
            if (confirm("Yakin akan menghapus data ini.?")) {
                $.ajax({
                    type: 'DELETE',
                    data: {
                        '_token': "{{ csrf_token() }}"
                    },
                    url: "{{ $cUrl }}/destroy/" + id,
                    success: function(data) {
                        if (data.status) {
                            reload();
                            Swal.fire({
                                title: "Sukses.!",
                                text: 'Data berhasil dihapus.',
                                icon: "success",
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error.! Data gagal dihapus');
                    }
                });
                return false;
            }
        });

        $('.btnBulkDelete').click(function() {
            var id = [];
            $(".data-check:checked").each(function() {
                id.push(this.value);
            });
            if (id.length > 0) {
                if (confirm("Yakin akan menghapus data ini.?")) {
                    $.ajax({
                        type: 'DELETE',
                        data: {
                            '_token': "{{ csrf_token() }}",
                            'id': id
                        },
                        url: "{{ $cUrl }}/destroy/" + id,
                        success: function(data) {
                            if (data.status) {
                                reload();
                                Swal.fire({
                                    title: "Sukses.!",
                                    text: 'Data berhasil dihapus.',
                                    icon: "success",
                                });
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            alert('Error.! Data gagal dihapus');
                        }
                    });
                    return false;
                }
            } else {
                alert('Pilih salah satu data');
            }
        });

        $(".check-all").click(function() {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>

    <script>
        $('#jadwal_id').change(function() {});

        function getSelectBankSoal() {
            $('#bank_soal_id').html('<option selected disabled>Memuat data...</option>');
            $.get('{{ url('status-ujian/select-bank-soal') }}', {
                'jadwal_id': $('#jadwal_id').val(),
            }, function(response) {
                $('#bank_soal_id').html(response);
            });
        }
        getSelectBankSoal();

        $('[name="mode_waktu"]').change(function() {
            var mode_waktu = $(this).val();
            if (mode_waktu == 'waktu-peserta') {
                $('.alokasiWaktuSoal').removeClass('d-none');
                $('[name="alokasi_waktu"]').focus().select();
            } else {
                $('.alokasiWaktuSoal').addClass('d-none');
                $('[name="alokasi_waktu"]').focus().select();
            }
        });

        $('.btnSubmit').click(function() {
            $('[name="id"]').val(null);
            $('#form-data')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();

            $.get("{{ $cUrl }}/bank-soal", {
                'bank_soal_id': $('#bank_soal_id').val(),
                'jadwal_id': $('#jadwal_id').val(),
            }, function(response) {
                if (response.status) {
                    $('[name="jadwal_id"]').val(response.jadwal.id);
                    $('[name="bank_soal_id"]').val(response.id);

                    $('#jadwal').val('Hari ke ' + response.jadwal.hari_ke + " Tanggal " + response.jadwal.tanggal);
                    $('#mata_pelajaran').val(response.nama_mapel);
                    $('#tingkat_jurusan').val("Tingkat Kelas: " + response.tingkat + ", Jurusan: " + (response.nama_jurusan));

                    $('[name="alokasi_waktu"]').val(response.alokasi_waktu);
                    $('[name="alokasi_waktu_soal"]').val(response.alokasi_waktu);
                    $('.alokasiWaktuSoal').addClass('d-none');
                    $('[name="waktu_mulai"]').val(response.waktu_mulai);
                    $('.rombongan_belajar_id').html(response.selectRombel);
                    $('.rombongan_belajar_id').select2().trigger('change');

                    $('.modal-title').html('Aktifkan Soal Ujian');
                    $('#modal-data').modal('show');

                    setTimeout(() => {
                        $('[name="ruang_id"]').focus();
                    }, 1000);
                }
            });
        });

        $('body').on('click', '.btnEdit', function() {
            $('#form-data')[0].reset();
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();

            var id = $(this).data('id');
            $('[name="id"]').val(id);

            $.get("{{ $cUrl }}/edit/" + id, function(response) {
                $('[name="jadwal_id"]').val(response.jadwal_id);
                $('[name="bank_soal_id"]').val(response.bank_soal_id);

                $('#jadwal').val('Hari ke ' + response.hari_ke + " Tanggal " + response.tanggal);
                $('#mata_pelajaran').val(response.nama_mapel);
                $('#tingkat_jurusan').val("Tingkat Kelas: " + response.bank_soal.tingkat + ", Jurusan: " + response.nama_jurusan);

                $('[name="mode_waktu"]').val(response.mode_waktu);
                $('[name="alokasi_waktu"]').val(response.alokasi_waktu_peserta);
                $('[name="alokasi_waktu_soal"]').val(response.alokasi_waktu_soal);
                if (response.mode_waktu == 'waktu-peserta') {
                    $('.alokasiWaktuSoal').removeClass('d-none');
                } else {
                    $('.alokasiWaktuSoal').addClass('d-none');
                }

                $('.rombongan_belajar_id').html(response.selectRombel);
                $('[name="ruang_id"]').val(response.ruang_id);
                $('[name="sesi_ke"]').val(response.sesi_ke);
                $('[name="waktu_mulai"]').val(response.jam);
                $('[name="batas_masuk"]').val(response.batas_masuk);
                $('[name="waktu_minimal"]').val(response.waktu_minimal);

                $('.modal-title').html('Edit Status Soal');
                $('#modal-data').modal('show');
            });
        });

        $('.btnSave').click(function() {
            $('.btnSave').attr('disabled', true).html('menyimpan...');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();

            var formData = $('#form-data').serialize();
            $.ajax({
                data: formData,
                url: "{{ $cUrl }}/store",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        $('#form-data').trigger("reset");
                        $('.modal').modal('hide');
                        reload();
                        Swal.fire({
                            title: "Sukses.!",
                            text: 'Data berhasil disimpan.',
                            icon: "success",
                        });
                    } else {
                        if (data.message) {
                            Swal.fire({
                                title: "Gagal.!!",
                                text: data.message,
                                icon: "warning",
                            });
                        } else {
                            for (var i = 0; i < data.error_string.length; i++) {
                                if (data.error_string[i]) {
                                    $('[name="' + data.inputerror[i] + '"]').addClass('is-invalid').next(
                                        '.invalid-feedback').html(
                                        data.error_string[i]);
                                }
                            }
                        }
                    }
                    $('.btnSave').attr('disabled', false).html('<i class="bx bx-save"></i> Simpan');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                    $('.btnSave').attr('disabled', false).html('<i class="bx bx-save"></i> Simpan');

                }
            });
        });

        function tampilkanRombel(value) {
            Swal.fire({
                title: "Daftar Rombel",
                text: value,
                icon: "info",
            });
        }
    </script>
@endsection
