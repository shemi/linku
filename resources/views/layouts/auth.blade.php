<!DOCTYPE html>
<html lang="en" class="full-h">
<head>
    @include("layouts.partials.site-head")
</head>
<body class="auth full-h" id="app-layout">
    <div class="auth-logo">
        <a href="{{ url("/") }}">
            <img src="{{ url("images/logo.png") }}" alt="linku - share your bookmarks">
        </a>
    </div>

    @yield('content')

    <!-- JavaScript libs are placed at the end of the document so the pages load faster -->
    <script src="{{ url('js/jquery.min.js') }}"></script>
    <script src="{{ url('js/bootstrap.min.js') }}"></script>
    <script src="{{ url("js/headroom.min.js") }}"></script>
    <script src="{{ url("js/jQuery.headroom.min.js") }}"></script>
    <script src="{{ url("js/template.js") }}"></script>

</body>
</html>
