@php
    $cardColor = ['primary', 'info', 'warning', 'danger'];
@endphp

@if (count($getStatusUjian))
    @foreach ($getStatusUjian as $statusUjian)
        @php
            $show = true;
            $count = count($cardColor);
            $randomIndex = rand(0, $count - 1);
            $randomCardClass = $cardColor[$randomIndex];

            // cek rombel
            if ($statusUjian->rombongan_belajar_id) {
                foreach (json_decode($statusUjian->rombongan_belajar_id, true) as $rombelId => $rombel) {
                    if ($rombelId == $rombel_id_peserta) {
                        $show = true;
                        break;
                    } else {
                        $show = false;
                    }
                }
            }

            // cek ruang
            if ($show) {
                if ($statusUjian->ruang_id) {
                    if ($peserta->ruang_id == $statusUjian->ruang_id) {
                        $show = true;
                    } else {
                        $show = false;
                    }
                }
            }

            // cek sesi
            if ($show) {
                if ($peserta->{'sesi_hari_' . $statusUjian->hari_ke} == $statusUjian->sesi_ke) {
                    $show = true;
                } else {
                    $show = false;
                }
            }

            // cek status peserta ujian
            $getStatusPesertaUjian = $statusUjian->statusPesertaUjian()->where('peserta_id', $peserta->id)->first();
            $statusPeserta = '';
            if ($getStatusPesertaUjian) {
                $statusPeserta = $getStatusPesertaUjian->status;
            }

            // cek status peserta ujian belum selesai mengerjakan soal
            $cekStatusPeserta_belumSelesai = $statusPesertaUjianModel->where('peserta_id', $peserta->id)->where('status', 1)->first();
        @endphp

        @if ($show)
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pt-3 pb-2 {{ $statusPeserta == 0 ? 'bg-success' : ($statusPeserta == 1 ? 'bg-warning' : 'bg-primary') }} #bg-{{ $randomCardClass }}">
                        <h3 class="mapel text-white">{{ $statusUjian->mataPelajaran->nama }} </h3>
                    </div>
                    <script>
                        startCountdownSoal("{{ $statusUjian->waktu_ujian }}", "{{ $statusUjian->alokasi_waktu_soal }}", "{{ date('Y-m-d H:i:s') }}", "{{ $statusUjian->id }}");
                    </script>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    @if ($statusUjian->ptk)
                                        <tr>
                                            <td class="fw-bold p-0 pe-2" style="width: 150px;">Guru</td>
                                            <td class="p-0">{{ $statusUjian->ptk?->nama }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="fw-bold p-0 pe-2">Jumlah Soal</td>
                                        <td class="p-0">{{ $statusUjian->jumlah_soal_ditampilkan }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-0 pe-2">Alokasi Waktu</td>
                                        <td class="p-0">
                                            {{ $statusUjian->alokasi_waktu_peserta }}
                                            {{-- Logika untuk menampilkan alokasi waktu soal jika mode_waktu adalah 'waktu-peserta' --}}
                                            @if ($statusUjian->mode_waktu == 'waktu-peserta' && $statusUjian->alokasi_waktu_soal)
                                                / {{ $statusUjian->alokasi_waktu_soal }}
                                            @endif
                                            Menit
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-0 pe-2">Waktu Mulai</td>
                                        {{-- Memformat tanggal dan waktu --}}
                                        <td class="p-0">{{ date('d-m-Y H:i', strtotime($statusUjian->waktu_ujian)) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-0 pe-2">Hari & Sesi</td>
                                        <td class="p-0">Hari Ke {{ $statusUjian->hari_ke }}, Sesi {{ $statusUjian->sesi_ke }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold p-0 pe-2">Ruang</td>
                                        <td class="p-0">{{ $statusUjian->ruang_id ? $statusUjian->nama_ruang : 'Semua Ruang' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="btn btnStatus_{{ $statusUjian->id }}">00:00:00</div>
                        </div>
                    </div>
                    <div class="card-footer pt-2 pb-0 h5 d-flex justify-content-between">
                        @if (session('siswa'))
                            @if ($getStatusPesertaUjian)
                                @if (in_array($statusPeserta, [1]))
                                    <a href="javascript:void(0)" class="btn btn-warning me-2 btnPilihSoal" data-status_ujian_id="{{ $statusUjian->id }}" data-mapel="{{ $statusUjian->mataPelajaran?->nama }}"><i class="ri-check-line"></i> Pilih</a>
                                @else
                                    <div>&nbsp;</div>
                                @endif
                            @else
                                @if ($statusUjian->waktu_selesai_soal > date('Y-m-d H:i:s'))
                                    @if (!$cekStatusPeserta_belumSelesai)
                                        <a href="javascript:void(0)" class="btn btn-warning me-2 btnPilihSoal" data-status_ujian_id="{{ $statusUjian->id }}" data-mapel="{{ $statusUjian->mataPelajaran?->nama }}"><i class="ri-check-line"></i> Pilih</a>
                                    @endif
                                @else
                                    <span class="text-danger">Waktu Habis</span>
                                @endif
                            @endif

                            @if ($getStatusPesertaUjian)
                                @if ($statusPeserta == 0)
                                    <a href="javascript:void(0)" class="btn btn-success s-2" id="statusUjian-{{ $statusUjian->id }}"><i class="ri-check-line"></i> Selesai</a>
                                @elseif ($statusPeserta == 1)
                                    <a href="javascript:void(0)" class="btn btn-info s-2" id="statusUjian-{{ $statusUjian->id }}"><i class="ri-edit-line"></i> Sedang Dikerjakan</a>
                                @endif
                            @else
                                {{-- <a href="javascript:void(0)" class="btn btn-info me-2" id="statusUjian-{{ $statusUjian->id }}">Belum Dikerjakan</a> --}}
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3 d-none">
                <form action="{{ url('ujian/ujian') }}" id="formUjian" method="POST">
                    @csrf
                    <input type="hidden" name="status_ujian_id">
                    <button type="submit"></button>
                </form>
            </div>
        @else
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body p-4 text-center">
                        <h3>Tidak ada data yang ditampilkan.!!</h3>
                    </div>
                </div>
            </div>
        @endif

        <script>
            $('.btnPilihSoal').click(function() {
                const status_ujian_id = $(this).data('status_ujian_id');
                const mapel = $(this).data('mapel');

                Swal.fire({
                    title: "Konfirmasi Pilihan Soal",
                    text: `Yakin memilih soal ${mapel}?`,
                    showDenyButton: true,
                    confirmButtonText: "Ya",
                    denyButtonText: "Batal",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Minta token lewat input swal
                        Swal.fire({
                            title: "Masukkan Token Ujian",
                            input: "text",
                            inputLabel: "Token ini diberikan oleh pengawas ujian.",
                            inputPlaceholder: "Contoh: ABC123",
                            showCancelButton: true,
                            confirmButtonText: "Verifikasi",
                            cancelButtonText: "Batal",
                            customClass: {
                                title: 'swal-center-title',
                                input: 'swal-center-input',
                                inputLabel: 'swal-center-label'
                            },
                            didOpen: () => {
                                // fokus otomatis di tengah
                                const input = Swal.getInput();
                                if (input) input.style.textAlign = 'center';
                            },
                            inputValidator: (value) => {
                                if (!value) return "Token wajib diisi!";
                            }
                        }).then((tokenResult) => {
                            if (tokenResult.isConfirmed) {
                                const token = tokenResult.value.trim();

                                $.ajax({
                                    url: "{{ url('ujian/cek-token') }}",
                                    method: "POST",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        status_ujian_id: status_ujian_id,
                                        token: token
                                    },
                                    beforeSend: function() {
                                        Swal.fire({
                                            title: "Memverifikasi Token...",
                                            text: "Harap tunggu sebentar.",
                                            allowOutsideClick: false,
                                            didOpen: () => Swal.showLoading()
                                        });
                                    },
                                    success: function(response) {
                                        Swal.close();
                                        if (response.status) {
                                            $.post("{{ url('ujian/pilih-soal') }}/" + status_ujian_id, {
                                                '_token': "{{ csrf_token() }}",
                                            }, function(response) {
                                                if (response.status) {
                                                    $('[name="status_ujian_id"]').val(status_ujian_id);
                                                    $('#formUjian').submit();
                                                }
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: "error",
                                                title: "Token Salah!",
                                                text: response.message || "Token yang kamu masukkan tidak valid.",
                                            });
                                        }
                                    },
                                    error: function() {
                                        Swal.close();
                                        Swal.fire({
                                            icon: "error",
                                            title: "Gagal Verifikasi",
                                            text: "Terjadi kesalahan koneksi ke server.",
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            });
        </script>
    @endforeach
@else
    <div class="col-md-12 mb-3">
        <div class="card">
            <div class="card-body p-4 text-center">
                <h3>Tidak ada data yang ditampilkan.!!</h3>
            </div>
        </div>
    </div>
@endif
