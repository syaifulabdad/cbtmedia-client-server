<script src="{{ URL::asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>

<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ URL::asset('build/js/plugins.js') }}"></script>

<script>
    $('[href="{{ url()->current() }}"]').removeClass('collapsed').addClass('active');
    $('[href="{{ url()->current() }}"]').parent().parent().parent().addClass('show');
</script>

<!-- Sweet Alerts js -->
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('vendor/datatables/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ URL::asset('vendor/datatables/dataTables.responsive.min.js') }}"></script>

<!-- App js -->
{{-- <script src="{{ URL::asset('build/js/app.js') }}"></script> --}}

@yield('script')

@yield('script-bottom')
