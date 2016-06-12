<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{{ $subject }}</title>

    @include('email.partials.style')

</head>

<body bgcolor="#f1f1f1">
<!-- Main Body -->
<table class="body-wrap">
    <tr>
        <td></td>
        <td class="container" bgcolor="#FFFFFF">
            <div class="content">
                <table>
                    <tr>
                        <td align="center">
                            <img src="{{ url('images/logo.png') }}" alt="linku Logo"/>
                        </td>
                    </tr>
                    @yield('content')
                </table>
            </div>
        </td>
        <td></td>
    </tr>
</table>


<table class="footer-wrap">
    <tr>
        <td></td>
        <td class="container">
            <div class="content">
                <table>
                    <tr>
                        <td align="center">
                            <p>
                                <a href="{{ url('/') }}">
                                    Linku <small>beta</small> - share your bookmarks
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td></td>
    </tr>
</table>
<!-- /Footer -->
</body>
</html>