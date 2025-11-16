@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Dashboard
        @endslot
        @slot('title')
            {{ $title }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div>
                <div class="card">
                    <div class="card-header  border-0 d-flex align-items-center">
                        <div class="col-sm">
                            <button type="button" class="btn btn-default" onclick="location.reload()"><i class="ri-refresh-line ri-lg"></i></button>
                            <a href="javascript:void(0)" class="btn btn-primary btnSettingToken" id="btnSettingToken"><i class="ri-settings-line me-1"></i> Pengaturan Token</a>
                            <a href="javascript:void(0)" class="btn btn-info btnCekKoneksi"><i class="ri-check-line me-1"></i> Cek Koneksi</a>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="javascript:void(0)" class="btn btn-success btnTarikData d-none"><i class="ri-download-line me-1"></i> Tarik Data</a>
                            </div>
                        </div>
                    </div>

                    <!-- end card header -->
                    <div class="card-body">
                        <h3 class="text-center">Tarik Data Terakhir: <span class="text-info tarik_data_terakhir">{{ $token ? $token->tarik_data_terakhir : null }}</span></h3>
                        <br>

                        <div class="table-responsive">
                            <table id="dataTables" class="table table-nowrap table-bordered table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Jenis Data</th>
                                        <th class="text-center" width="150px">Data Server</th>
                                        <th class="text-center" width="150px">Data Local</th>
                                        <th class="text-center" width="100px">Status</th>
                                        <th class="text-center"width="150px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Data Sekolah</th>
                                        <td class="text-center pusat-sekolah"></td>
                                        <td class="text-center local-sekolah"></td>
                                        <td class="text-center status-sekolah"></td>
                                        <td class="text-center"><a href="javascript:void(0)" onclick="tarikData('data-sekolah', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data Semester</th>
                                        <td class="text-center pusat-semester"></td>
                                        <td class="text-center local-semester"></td>
                                        <td class="text-center status-semester"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-semester', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data Jurusan</th>
                                        <td class="text-center pusat-jurusan"></td>
                                        <td class="text-center local-jurusan"></td>
                                        <td class="text-center status-jurusan"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-jurusan', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data Mata Pelajaran</th>
                                        <td class="text-center pusat-mapel"></td>
                                        <td class="text-center local-mapel"></td>
                                        <td class="text-center status-mapel"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-mata-pelajaran', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data PTK</th>
                                        <td class="text-center pusat-ptk"></td>
                                        <td class="text-center local-ptk"></td>
                                        <td class="text-center status-ptk"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-ptk', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data Kelas</th>
                                        <td class="text-center pusat-rombel"></td>
                                        <td class="text-center local-rombel"></td>
                                        <td class="text-center status-rombel"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-rombongan-belajar', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data Ujian</th>
                                        <td class="text-center pusat-ujian"></td>
                                        <td class="text-center local-ujian"></td>
                                        <td class="text-center status-ujian"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-ujian', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data Bank Soal</th>
                                        <td class="text-center pusat-bank_soal"></td>
                                        <td class="text-center local-bank_soal"></td>
                                        <td class="text-center status-bank_soal"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-soal', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data Soal</th>
                                        <td class="text-center pusat-soal"></td>
                                        <td class="text-center local-soal"></td>
                                        <td class="text-center status-soal"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-soal', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data Jadwal Ujian</th>
                                        <td class="text-center pusat-jadwal"></td>
                                        <td class="text-center local-jadwal"></td>
                                        <td class="text-center status-jadwal"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-jadwal', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data Ruang Ujian</th>
                                        <td class="text-center pusat-ruang"></td>
                                        <td class="text-center local-ruang"></td>
                                        <td class="text-center status-ruang"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-ruang', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data Peserta</th>
                                        <td class="text-center pusat-peserta"></td>
                                        <td class="text-center local-peserta"></td>
                                        <td class="text-center status-peserta"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-peserta', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data Pengawas</th>
                                        <td class="text-center pusat-pengawas"></td>
                                        <td class="text-center local-pengawas"></td>
                                        <td class="text-center status-pengawas"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-pengawas', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data User</th>
                                        <td class="text-center pusat-user"></td>
                                        <td class="text-center local-user"></td>
                                        <td class="text-center status-user"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-user', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data Pengaturan</th>
                                        <td class="text-center pusat-pengaturan"></td>
                                        <td class="text-center local-pengaturan"></td>
                                        <td class="text-center status-pengaturan"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-pengaturan', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                    <tr>
                                        <th>Data Referensi</th>
                                        <td class="text-center pusat-referensi"></td>
                                        <td class="text-center local-referensi"></td>
                                        <td class="text-center status-referensi"></td>
                                        <td class="text-center" width=""><a href="javascript:void(0)" onclick="tarikData('data-referensi', true)" class="btn btn-primary btn-sm btnTarikDt d-none"><i class="ri-download-line"></i> Tarik Data</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modal-data" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel">&nbsp;</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <div class="modal-body">
                    <form id="form-data" class="tablelist-form" autocomplete="off">
                        @csrf

                        {{-- <div class="row mb-2">
                            <label for="" class="col-sm-3 col-form-label">Host</label>
                            <div class="col-sm-9">
                                <input type="text" name="host" value="{{ $token ? $token->host : null }}" class="form-control">
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="" class="col-sm-3 col-form-label">Token</label>
                            <div class="col-sm-9">
                                <input type="text" name="token" value="" class="form-control">
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                         --}}
                        <div class="row mb-2">
                            <label for="" class="col-sm-12 col-form-label">Username / Email CBT Server</label>
                            <div class="col-sm-12">
                                <input type="text" name="email" value="" class="form-control">
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="" class="col-sm-12 col-form-label">Password CBT Server</label>
                            <div class="col-sm-12">
                                <input type="password" name="password" value="" class="form-control">
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <a href="javascript:void(0)" class="btn btn-success btnSave"><i class="bx bx-download"></i> Get Token</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function getJumlahData() {
            $.getJSON('{{ url('tarik-data') }}/jumlah-data', function(response) {
                @foreach (['sekolah', 'semester', 'jurusan', 'rombel', 'mapel', 'ujian', 'bank_soal', 'soal', 'jadwal', 'server', 'ruang', 'peserta', 'pengawas', 'ptk', 'user', 'pengaturan', 'referensi'] as $data)
                    $('.tarik_data_terakhir').text(response.tarik_data_terakhir);

                    $('.pusat-{{ $data }}').text(response.pusat.{{ $data }});
                    $('.local-{{ $data }}').text(response.local.{{ $data }});
                    if (response.local.{{ $data }} >= response.pusat.{{ $data }}) {
                        $('.status-{{ $data }}').html('OK');
                    } else {
                        $('.status-{{ $data }}').html('---');
                    }
                @endforeach
            });
        }

        $('.btnSettingToken').click(function() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();
            $('[name="host"]').focus();

            $('.modal-title').html('Setting Token CBTmedia Server');
            $('#modal-data').modal('show');
        });

        $('.btnSave').click(function() {
            $('.btnSave').attr('disabled', true).html('menyimpan...');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').empty();

            var formData = $('#form-data').serialize();
            $.ajax({
                data: formData,
                // url: "{{ url('setting-api') }}/store",
                url: "{{ url('getTokenServer') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    if (data.status) {
                        $('.modal').modal('hide');
                        location.reload();
                        Swal.fire({
                            title: "Sukses.!",
                            text: 'Data berhasil disimpan.',
                            icon: "success",
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
                    $('.btnSave').attr('disabled', false).html('<i class="bx bx-download"></i> Get Token');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                    $('.btnSave').attr('disabled', false).html('<i class="bx bx-download"></i> Get Token');

                }
            });
        });

        function cekKoneksi(alert = false) {
            $('.btnCekKoneksi').html('Memeriksa Koneksi...');
            $.getJSON("{{ route('cekApiServer') }}", function(response) {
                if (response.success) {
                    getJumlahData();
                    $('.btnTarikData, .btnTarikDt').removeClass('d-none');
                    if (alert) {
                        Swal.fire({
                            title: "Sukses.!",
                            text: "Terhubung ke Server CBTmedia",
                            // text: response.data.nama,
                            icon: "success",
                        });
                    }
                } else {
                    $('.btnTarikData, .btnTarikDt').addClass('d-none');
                    if (alert) {
                        Swal.fire({
                            title: "Gagal.!",
                            text: 'Koneksi gagal.!!',
                            icon: "error",
                        });
                    }
                }
                $('.btnCekKoneksi').html('<i class="ri-check-line me-1"></i> Cek Koneksi');
            }).fail(function() {
                $('.btnCekKoneksi').html('<i class="ri-check-line me-1"></i> Cek Koneksi');
                $('.btnTarikData, .btnTarikDt').addClass('d-none');
                if (alert) {
                    Swal.fire({
                        title: "Error.!",
                        text: 'Terjadi Kesalahan pada Server.!!',
                        icon: "error",
                    });
                }
            });
        }

        $('.btnCekKoneksi').click(function() {
            cekKoneksi(true);
        });

        setTimeout(() => {
            cekKoneksi(false);
        }, 1500);

        $('.btnTarikData').click(function() {
            $('.btnTarikData').html('Mengambil Data...');
            tarikData();
        });

        function tarikData(url = 'data-master', onlyOne = false) {
            $.getJSON("{{ url('tarik-data') }}/" + url, function(data) {
                if (data.status) {
                    if (onlyOne) {
                        Swal.fire({
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            icon: "success",
                            title: 'Sukses.!',
                            text: data.message,
                            buttons: [
                                'OK'
                            ],
                        }).then(function(isConfirm) {
                            if (isConfirm) {
                                // location.reload();
                                getJumlahData();
                            }
                        });
                        $('.btnTarikDt d-none').html('<i class="ri-download-line me-1"></i> Tarik Data');
                    } else {

                        @php
                            // $dataArr = ['sekolah', 'pengaturan', 'semester', 'jurusan', 'ptk', 'rombongan-belajar', 'mata-pelajaran', 'ujian', 'soal', 'jadwal', 'server', 'ruang', 'peserta', 'pengawas', 'user', 'referensi'];
                            $dataArr = ['master', 'master-ujian', 'peserta', 'soal'];
                        @endphp

                        @foreach ($dataArr as $key => $data)
                            @if ($data == 'soal')

                                if (data.data == 'data-soal') {
                                    $('.btnTarikData').html('<i class="ri-download-line me-1"></i> Tarik Data');
                                    Swal.fire({
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        icon: "success",
                                        title: "SUKSES.!",
                                        text: "DATA BARHASIL DITARIK",
                                        buttons: [
                                            'OK'
                                        ],
                                    }).then(function(isConfirm) {
                                        if (isConfirm) {
                                            getJumlahData();
                                        }
                                    });
                                }
                            @else

                                if (data.data == '{{ $data }}') {
                                    $('.btnTarikData').html('<i class="ri-download-line me-1"></i> Tarik Data');
                                    Swal.fire({
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        icon: "info",
                                        title: data.message,
                                        // text: "Lanjut mengambil data {{ ucwords(strtolower(str_replace('-', ' ', $dataArr[$key + 1]))) }}...",
                                    });
                                    tarikData('data-{{ $dataArr[$key + 1] }}');
                                }
                            @endif
                        @endforeach
                    }
                } else {
                    Swal.fire({
                        title: "Gagal.!",
                        text: 'Data Gagal Ditarik.!!',
                        icon: "warning",
                    });
                    $('.btnTarikData').html('<i class="ri-download-line me-1"></i> Tarik Data');
                    $('.btnTarikDt d-none').html('<i class="ri-download-line me-1"></i> Tarik Data');
                }
            }).fail(function() {
                Swal.fire({
                    title: "Error.!",
                    text: 'Terjadi Kesalahan pada Server.!!',
                    icon: "error",
                });
                $('.btnTarikData').html('<i class="ri-download-line me-1"></i> Tarik Data');
                $('.btnTarikDt d-none').html('<i class="ri-download-line me-1"></i> Tarik Data');
            });
        }

        $('body').on('click', '.btnTarikDt d-none', function() {
            $(this).html('Mengambil Data...');
        });
    </script>
@endsection
