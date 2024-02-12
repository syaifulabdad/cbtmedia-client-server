<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="semibox" data-sidebar-visibility="show" data-topbar="dark" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="img-2" data-preloader="disable">

<head>
    @include('layouts.title-meta')
    @include('layouts.head-css')
</head>

@section('body')
    @include('layouts.body')
@show
<!-- Begin page -->
<div id="layout-wrapper">
    @include('layouts.topbar')
    @include('layouts.sidebar')
    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        @include('layouts.footer')
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->

{{-- @include('layouts.customizer') --}}

<!-- JAVASCRIPT -->
@include('layouts.vendor-scripts')
</body>

</html>
