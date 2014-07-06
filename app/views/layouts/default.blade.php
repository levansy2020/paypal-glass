<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @yield('page_title')

    <!-- Core CSS - Include with every page -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">

    @yield('page_level_css')

    <!-- SB Admin CSS - Include with every page -->
    <link href="/css/sb-admin.css" rel="stylesheet">

</head>

<body>

@yield('content')

<!-- Core Scripts - Include with every page -->
<script src="/js/jquery-1.10.2.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>

@yield('page_level_js')

<!-- SB Admin Scripts - Include with every page -->
<script src="/js/sb-admin.js"></script>

</body>

</html>