<ul class="navbar-nav" id="navbar-nav">
    <li class="menu-title"><span>@lang('menu')</span></li>

    <li class="nav-item">
        <a class="nav-link menu-link" href="{{ url('dashboard') }}">
            <i class="ri-home-5-line"></i> <span>Dashboard</span>
        </a>
    </li>

    @php
        $newSekolah = new App\Models\Sekolah();
    @endphp

    @if (in_array(session('type'), ['admin', 'ops']))
        @if ($newSekolah->count())
            <li class="menu-title"><span>Data Ujian</span></li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarMasterUjian" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMasterUjian">
                    <i class="ri-file-list-3-line"></i> <span>Status Ujian</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarMasterUjian">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ url('status-ujian') }}" class="nav-link">Status Ujian</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('status-peserta-ujian') }}" class="nav-link">Status Peserta Ujian</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('reset-peserta') }}" class="nav-link">Reset Peserta</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarLaporan" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLaporan">
                    <i class="ri-file-list-3-line"></i> <span>Laporan</span>
                </a>
                <div class="collapse menu-dropdown" id="sidebarLaporan">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="" class="nav-link">Laporan Ujian</a>
                        </li>
                        <li class="nav-item">
                            <a href="" class="nav-link">Analisis Soal</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        <li class="menu-title"><span>PENGATURAN</span></li>
        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarLaporan" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLaporan">
                <i class="ri-user-3-line"></i> <span>User</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarLaporan">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="{{ url('user-proktor') }}" class="nav-link">Proktor</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('user-pengawas') }}" class="nav-link">Pengawas</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('user-siswa') }}" class="nav-link">Peserta Ujian</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link menu-link" href="{{ url('tarik-data') }}">
                <i class="ri-download-line"></i> <span>Tarik Data</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link menu-link" href="{{ url('backup-data') }}">
                <i class="ri-database-2-line"></i> <span>Backup Data</span>
            </a>
        </li>

        {{-- <li class="nav-item">
            <a class="nav-link menu-link" href="{{ url('api-token') }}">
                <i class="ri-stack-line"></i> <span>API Token</span>
            </a>
        </li> --}}
    @endif

    @if (in_array(session('type'), ['siswa']))
        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarLaporan" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLaporan">
                <i class="ri-file-list-3-line"></i> <span>Laporan</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarLaporan">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="" class="nav-link">Laporan Ujian</a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link">Analisis Soal</a>
                    </li>
                </ul>
            </div>
        </li>
    @endif

</ul>
