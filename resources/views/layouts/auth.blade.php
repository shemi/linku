<!DOCTYPE html>
<html lang="en" class="full-h">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>linku - share your bookmarks</title>

    <link rel="stylesheet" media="screen" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
    <link rel="stylesheet" href="{{ url("css/bootstrap.min.css") }}">
    <link rel="stylesheet" href="{{ url("css/font-awesome.min.css") }}">

    <!-- Custom styles for our template -->
    <link rel="stylesheet" href="{{ url("css/bootstrap-theme.css") }}" media="screen" >
    <link rel="stylesheet" href="{{ url("css/main.css") }}">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="{{ url("js/html5shiv.js") }}"></script><script src="{{ url("js/respond.min.js") }}"></script>
    <![endif]-->

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
