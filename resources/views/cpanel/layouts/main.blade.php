<!doctype html>
<html>
<head>
    @include('cpanel.includes.head')
    @include('cpanel.includes.main_css')
    @include('cpanel.includes.datatable_style')
    @stack('add-style')
</head>
<body class="fix-header fix-sidebar card-no-border">
@routes
<!-- Preloader -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
    </svg>
</div>


<!-- Topbar header -->
@include('cpanel.includes.top_head')

<!-- Left Sidebar header -->
@include('cpanel.includes.aside')

<!-- Page wrapper start  -->
<div class="page-wrapper">

    <!-- Container fluid start  -->
    <div class="container-fluid">

        <!-- Bread crumb start -->
    @include('cpanel.includes.breadcrumbs')
    <!-- Bread crumb end -->

        <!-- Page content start -->

    @yield('content')

    <!-- Page content end -->

    </div>
    <!-- Container fluid end  -->

</div>
<!-- Page wrapper end  -->

@include('cpanel.includes.main_js')
@include('cpanel.includes.toastify')
@include('cpanel.includes.datatable_script')
@stack('add-script')
</body>
</html>
