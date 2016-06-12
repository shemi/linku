@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">SIGN IN</div>
                <div class="panel-body">
                    <form role="form" method="POST" action="{{ url('/login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label sr-only">E-Mail Address</label>
                            <input type="email" placeholder="E-Mail Address" class="form-control" name="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label sr-only">Password</label>
                            <input type="password" placeholder="Password" class="form-control" name="password">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group remember">
                            <label>
                                <input type="checkbox" name="remember" checked> Remember Me
                            </label>
                        </div>

                        <div class="form-group text-center margin-top">

                            <a href="{{ url('register') }}" class="btn btn-default">
                                <i class="fa fa-btn fa-user"></i>SIGN UP
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-btn fa-sign-in"></i>SIGN ME IN
                            </button>

                        </div>
                    </form>
                </div>
            </div>
            <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
        </div>
    </div>
</div>
@endsection
