<!-- BEGIN URL JS-->
<script type="text/javascript">
    const app = {
        host: {!! json_encode(url('/')) !!}+"",
    }
    window.CSRF_TOKEN = '{{ csrf_token() }}';
</script>
<!-- END URL JS-->

<!-- ================ Main js files start ========================= -->
<script src="{{asset('cpanel-asset/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{asset('cpanel-asset/plugins/bootstrap/js/popper.min.js')}}"></script>
<script src="{{asset('cpanel-asset/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{asset('cpanel-asset/js/jquery.slimscroll.js')}}"></script>
<!--Wave Effects -->
<script src="{{asset('cpanel-asset/js/waves.js')}}"></script>
<!--Menu sidebar -->
<script src="{{asset('cpanel-asset/js/sidebarmenu.js')}}"></script>
<!--stickey kit -->
<script src="{{asset('cpanel-asset/plugins/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
<script src="{{asset('cpanel-asset/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<!--stickey kit -->
<script src="{{asset('cpanel-asset/plugins/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>
<script src="{{asset('cpanel-asset/plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<!-- toastify  -->
<script src="{{asset('cpanel-asset/plugins/toastify/js/toastify.js')}}"></script>
<script src="{{asset('cpanel-asset/js/toastify.js')}}"></script>
{{--<script src="https://cdn.jsdelivr.net/gh/dmuy/Material-Toast/mdtoast.min.js"></script>--}}
<!-- ================ Main js files end ========================= -->

<!-- ================== Custom js files start ====================== -->
<script src="{{asset('cpanel-asset/js/custom.js')}}"></script>
<!-- ================== Custom js files end ====================== -->

{{--  sweet alert  --}}
<script src="{{asset('cpanel-asset/plugins/sweetalert/sweetalert.min.js')}}"></script>

{{--  bootstrap switch  --}}
<script src="{{asset('cpanel-asset/plugins/bootstrap-switch/bootstrap-switch.min.js')}}"></script>

{{--  block UI  --}}
<script src="{{asset('cpanel-asset/plugins/blockUI/jquery.blockUI.js')}}"></script>

<!-- ============================================================== -->
<!-- ============================================================== -->
