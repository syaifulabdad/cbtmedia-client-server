<header id="page-topbar" class="">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('images/logo-cbt.png') }}" alt="" height="25">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('images/logo-cbt-media.png') }}" alt="" height="45">
                        </span>
                    </a>

                    <a href="" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('images/logo-cbt.png') }}" alt="" height="25">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('images/logo-cbt-media.png') }}" alt="" height="45">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>

            <div class="d-flex align-items-center">
                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                        <i class='bx bx-fullscreen fs-22'></i>
                    </button>
                </div>

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="{{ Auth::user()->avatar ? Auth::user()->avatar : asset('build/images/users/user-dummy-img.jpg') }}" alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text">{{ Auth::user()->name }}</span>
                                <span class="d-none d-xl-block ms-1 fs-13 user-name-sub-text">
                                    {{ strtoupper(session('type')) }}
                                </span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">Selamat Datang!</h6>
                        @if (!session('siswa'))
                            <a class="dropdown-item" href="/profile"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
                        @endif
                        <a class="dropdown-item " href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle me-1"></i> <span key="t-logout">Logout</span></a>
                        <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
