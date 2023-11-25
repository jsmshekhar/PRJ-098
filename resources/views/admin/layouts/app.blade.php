<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Dashboard | EVA2Z')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Eva2z EV Rental Site" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('public/assets/images/favicon.png') }}">

    <!-- Style css -->
    @include('admin.layouts.css')
    @yield('css')
</head>

<body>
    <!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('admin.layouts.header')

        <!-- ========== Left Sidebar Start ========== -->
        @include('admin.layouts.sidebar')
        <!-- Left Sidebar End -->

        <!-- Start main content here -->
        <div class="main-content">

            <div class="page-content">
                <!-- start page title -->
                @yield('content')
                <!-- end page title -->
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    @include('admin.layouts.js')
    @yield('js')

    <script>
        function refreshPage() {
            var selectedValue = $('#perPageDropdown').val();
            $('#perPageHidden').val(selectedValue);
            $("#searchForm").submit();
        }
        function clearSearch(pageUrl) {
            window.location.href = pageUrl;
        }

        function submitSearchForm() {
            var selectedValue = $('#perPageDropdown').val();
            $('#perPageHidden').val(selectedValue);
            $("#searchForm").submit();
        }

        function perPage(select) {
            $('#perPageHidden').val(select.value);
            $("#searchForm").submit();
        }
    </script>
</body>

</html>
