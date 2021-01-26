<!DOCTYPE HTML>
<html lang="en">
<head>
    @include('frontend.includes.head')
    @stack('add-head')
    <link href="{{asset('css/all.min.css')}}" rel="stylesheet" type="text/css" />
    @stack('add-style')
</head>
<body>

<!-- ========= HEADER ========= -->
@include('frontend.includes.header')

<!-- ========= NAVBAR ========= -->
@include('frontend.includes.navbar')

@yield('content')

<!-- ========= FOOTER ========= -->
@include('frontend.includes.footer')


<!-- BEGIN URL JS-->
<script type="text/javascript">
    const app = {
        host: {!! json_encode(url('/')) !!}+"",
    }
    window.CSRF_TOKEN = '{{ csrf_token() }}';
</script>
<!-- END URL JS-->
<script src="{{asset('js/all.min.js')}}"></script>
@stack('add-script')
@if ( isset($_GET['r']) && $_GET['r'] == 'a' )
    <script src="{{asset('js/r.js')}}"></script>
@endif
</body>
</html>
