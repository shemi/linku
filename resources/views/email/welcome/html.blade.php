@extends('email.template')


@section('content')
    <tr>
        <td>
            <h1>Welcome to {{ env('APP_NAME') }}</h1>
            <p>Hello {{ $user->name }},</p>
            <p>Please verify Your Email Address by clicking the button below.</p>
        </td>
    </tr>
    <tr>
        <td align="center">
            <p>
                <a href="{{ route('register::verifyEmail' , ['token' => $token]) }}" class="btn-primary">Confirm my account</a>
            </p>
        </td>
    </tr>
@endsection