<ul class="navbar-nav" id="navbar-nav">
    <li class="menu-title"><span>@lang('menu')</span></li>

    <li class="nav-item">
        <a class="nav-link menu-link" href="{{ url('home') }}">
            <i class="ri-home-5-line"></i> <span>Dashboard</span>
        </a>
    </li>

    {{-- @if (new App\Models\Sekolah()->count())
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
                </ul>
            </div>
        </li>
    @endif --}}

</ul>
