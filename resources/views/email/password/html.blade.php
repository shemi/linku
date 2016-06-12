@extends('email.template')
@section('content')
    <tr>
        <td>
            <h1>Reset your {{ env('APP_NAME') }} Account Password</h1>
            <p>To reset your account password click on the button below</p>
        </td>
    </tr>
    <tr>
        <td align="center">
            <p>
                <a href="{{ route('auth.password.form', ['token' => $token]) }}" class="btn-primary">Reset your password</a>
            </p>
        </td>
    </tr>
@endsection