@extends('client.layouts.master')
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

    <style>
        .swal-center-title {
            text-align: center;
        }

        .swal-center-label {
            display: block;
            text-align: center;
            margin-bottom: 10px;
        }

        .swal-center-input {
            text-align: center !important;
            font-weight: bold;
            font-size: 18px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
    </style>

    <div class="row">
        <div class="col-md-5">
            <div class="col-md-12 mb-2 h5">Biodata:</div>
            <div class="card #card-secondary">
                <div class="card-header pt-3 pb-2 bg-secondary">
                    <h5 class="mapel text-white">{{ $peserta->nama }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless">
                            {{-- <thead>
                                <tr>
                                    <td colspan="2" class="h3 text-white fw-bold p-0 pb-2">{{ $peserta->nama }}</td>
                                </tr>
                            </thead> --}}
                            <tbody>
                                {{-- <tr>
                                    <td colspan="2" class="p-2"></td>
                                </tr> --}}
                                <tr class="mb-1">
                                    <td class="fw-bold p-0 pe-2" style="width: 150px;">NIS/NISN</td>
                                    <td class="p-0">{{ $peserta->nis }} / {{ $peserta->nisn ? $peserta->nisn : '-' }}</td>
                                </tr>
                                <tr class="mb-1">
                                    <td class="fw-bold p-0 pe-2">Jenis Kelamin</td>
                                    <td class="p-0">
                                        {{ $peserta->jenis_kelamin == 'L' ? 'Laki-laki' : ($peserta->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                                    </td>
                                </tr>
                                <tr class="mb-1">
                                    <td class="fw-bold p-0 pe-2">Tanggal Lahir</td>
                                    <td class="p-0">{{ $peserta->tanggal_lahir }}</td>
                                </tr>
                                <tr class="mb-1">
                                    <td class="fw-bold p-0 pe-2">Agama</td>
                                    <td class="p-0">{{ $peserta->agama }}</td>
                                </tr>
                                <tr class="mb-1">
                                    <td class="fw-bold p-0 pe-2">Tingkat Kelas</td>
                                    <td class="p-0">{{ $peserta->tingkat }}</td>
                                </tr>
                                <tr class="mb-1">
                                    <td class="fw-bold p-0 pe-2">Kelas</td>
                                    <td class="p-0">{{ $peserta->nama_rombel }}</td>
                                </tr>
                                <tr class="mb-1">
                                    <td class="fw-bold p-0 pe-2">Ruang Ujian</td>
                                    <td class="p-0">{{ $peserta->nama_ruang }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-2 h5">Jadwal:</div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" colspan="{{ $ujian->jumlah_hari }}">Sesi Ujian</th>
                                </tr>
                                <tr>
                                    @foreach (range(1, $ujian->jumlah_hari) as $hariKe)
                                        <th class="text-center"> Hari Ke {{ $hariKe }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach (range(1, $ujian->jumlah_hari) as $hariKe)
                                        <td class="text-center">{{ $peserta->{'sesi_hari_' . $hariKe} }}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="row">
                {{-- Menggunakan d-flex dan justify-content-between di sini --}}
                <div class="col-md-12 mb-2 h5 d-flex justify-content-between">
                    <div>Daftar Soal Aktif:</div>
                    <a href="javascript:void(0)" onclick="daftarSoalList()">Refresh</a>
                </div>
                <span id="daftarSoalList"></span>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function daftarSoalList() {
            $.get('{{ url('home/daftar-soal') }}', {}, function(data) {
                $('#daftarSoalList').html(data);
            });
        }

        daftarSoalList();
        setInterval(() => {
            daftarSoalList();
        }, 10000);
    </script>


    <script>
        function startCountdownSoal(waktuMulai, alokasiWaktuMenit, waktuServerSekarang, tombolId) {
            const tombol = $(`.btnStatus_${tombolId}`);
            if (!tombol.length) return;

            // waktu server → real time sync
            const localStart = Date.now();
            const serverNow = new Date(waktuServerSekarang.replace(' ', 'T'));
            const serverOffset = localStart - serverNow.getTime();

            const startTime = new Date(waktuMulai.replace(' ', 'T')).getTime();
            const endTime = startTime + (alokasiWaktuMenit * 60000);

            const interval = setInterval(() => {
                const now = Date.now() - serverOffset; // waktu server realtime

                // --- BELUM MULAI ---
                if (now < startTime) {
                    const diff = startTime - now;

                    const m = Math.floor(diff / 1000 / 60);
                    const s = Math.floor((diff / 1000) % 60);

                    tombol.text(`MENUNGGU ${String(m).padStart(2,'0')}:${String(s).padStart(2,'0')}`)
                        .removeClass('btn-info btn-warning btn-secondary')
                        .addClass('btn-dark');

                    return; // jangan mulai countdown sebelum waktunya
                }

                // --- SUDAH MULAI ---
                const remaining = endTime - now;

                if (remaining <= 0) {
                    clearInterval(interval);
                    waktuHabisSoal(tombol);
                    return;
                }

                const hours = Math.floor((remaining / 1000 / 60 / 60) % 24);
                const minutes = Math.floor((remaining / 1000 / 60) % 60);
                const seconds = Math.floor((remaining / 1000) % 60);

                tombol.text(
                    `${String(hours).padStart(2, '0')}:` +
                    `${String(minutes).padStart(2, '0')}:` +
                    `${String(seconds).padStart(2, '0')}`
                );

                // warna indikator waktu hampir habis
                if (remaining <= 5 * 60 * 1000) {
                    tombol.removeClass('btn-info').addClass('btn-warning');
                } else {
                    tombol.removeClass('btn-warning btn-dark').addClass('btn-info');
                }

            }, 1000);
        }

        function waktuHabisSoal(tombol) {
            tombol.text('SELESAI')
                .removeClass('btn-info btn-warning btn-dark')
                .addClass('btn-secondary');
        }
    </script>
@endsection
