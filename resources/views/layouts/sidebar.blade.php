<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ url('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('images/logo-cbt.png') }}" alt="" height="25">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('images/logo-cbt-media.png') }}" alt="" height="45">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ url('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('images/logo-cbt.png') }}" alt="" height="25">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('images/logo-cbt-media.png') }}" alt="" height="45">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu"></div>
            @include('layouts.menu-list')
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
