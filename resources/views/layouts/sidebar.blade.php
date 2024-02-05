<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>@lang('menu')</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('dashboard') }}">
                        <i class="ri-home-5-line"></i> <span>Dashboard</span>
                    </a>
                </li>

                @if ((new App\Models\Sekolah())->count())
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
                    <a class="nav-link menu-link" href="{{ url('tarik-data') }}">
                        <i class="ri-database-line"></i> <span>Tarik Data</span>
                    </a>
                </li>

                @if (in_array(session('type'), ['admin', 'ops']))
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('api-token') }}">
                            <i class="ri-stack-line"></i> <span>API TOKEN</span>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
