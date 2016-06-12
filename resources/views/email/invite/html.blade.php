@extends('email.template')


@section('content')
    <tr>
        <td>
            <h1>hi {{ $toName }}, {{ $by->name }} share with you "{{ $shared->name }}" on <b>linku</b></h1>
            <p>To enjoy this,</p>
            <p>Please verify Your Email Address by clicking the button below, and start using <b>linku</b>.</p>
        </td>
    </tr>
    <tr>
        <td align="center">
            <p>
                <a href="{{ url('register', ['token' => $token]) }}" class="btn-primary">
                    Confirm my email
                </a>
            </p>
        </td>
    </tr>
@endsection