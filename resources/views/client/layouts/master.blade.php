<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="horizontal" data-layout-style="" data-layout-position="fixed" data-topbar="light" data-sidebar-visibility="show" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="img-2" data-preloader="disable">

    <head>
        @include('client.layouts.title-meta')
        @include('client.layouts.head-css')
    </head>

    <body>
        <div id="layout-wrapper">

            @include('client.layouts.topbar')
            @include('client.layouts.sidebar')
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
                @include('client.layouts.footer')
            </div>
        </div>

        @include('client.layouts.vendor-scripts')
    </body>

</html>
