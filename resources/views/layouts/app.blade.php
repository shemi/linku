<!DOCTYPE html>
<html lang="en">
<head>
    @include("layouts.partials.site-head")
</head>
<body class="home" id="app-layout">

    @include('layouts.partials.navbar')

    @yield('content')

    @include('layouts.partials.footer')
    <!-- JavaScript libs are placed at the end of the document so the pages load faster -->
    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/bootstrap.min.js') }}"></script>
    <script src="{{ url("js/headroom.min.js") }}"></script>
    <script src="{{ url("js/jQuery.headroom.min.js") }}"></script>
    <script src="{{ url("js/template.js") }}"></script>

</body>
</html>
