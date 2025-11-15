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
        @foreach ($statusUjianModel->where('status', 1)->get() as $statusUjian)
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header pt-3 pb-2">
                        <h5>{{ $statusUjian->mataPelajaran->nama }}</h5>
                    </div>
                    <div class="card-body">
                        @if ($statusUjian->ptk)
                            <div class="row mb-1">
                                <div class="col-md-3">Guru</div>
                                <div class="col-md-9">{{ $statusUjian->ptk?->nama }}</div>
                            </div>
                        @endif

                        <div class="row mb-1">
                            <div class="col-md-3">Jumlah Soal</div>
                            <div class="col-md-9">{{ $statusUjian->jumlah_soal_ditampilkan }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-3">Alokasi Waktu</div>
                            <div class="col-md-9">{{ $statusUjian->alokasi_waktu_peserta . ($statusUjian->mode_waktu == 'waktu-peserta' ? ' / ' . $statusUjian->alokasi_waktu_soal : '') }} Menit</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-3">Waktu Mulai</div>
                            <div class="col-md-9">{{ date('d-m-Y H:i', strtotime($statusUjian->waktu_ujian)) }}</div>
                        </div>
                        {{-- <div class="row mb-1">
                            <div class="col-md-3">Waktu Selesai</div>
                            <div class="col-md-9">{{ date('d-m-Y H:i', strtotime($statusUjian->waktu_selesai_soal)) }}</div>
                        </div> --}}
                    </div>
                    <div class="card-footer pt-2 pb-2">
                        @if (session('siswa'))
                            <a href="javascript:void(0)" class="btn btn-danger"><i class="ri-check-line"></i> Pilih</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
