@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
                <div class="panel-heading">SIGN UP</div>
                <div class="panel-body">
                    <form role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}

                        @if(! empty($invite))
                            <input type="hidden"
                                   name="invite_token"
                                   value="{{ $invite->invite_token }}">
                        @endif

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label sr-only">Name</label>

                            <input type="text" placeholder="Name" class="form-control" name="name" value="{{ old('name') }}">

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label sr-only">E-Mail Address</label>

                            @if(! empty($invite))
                                <input type="email"
                                       placeholder="E-Mail Address"
                                       class="form-control"
                                       name="email"
                                       readonly
                                       value="{{ $invite->email }}">
                            @else
                                <input type="email" placeholder="E-Mail Address" class="form-control" name="email" value="{{ old('email') }}">
                            @endif
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label sr-only">Password</label>

                            <input type="password" placeholder="Password" class="form-control" name="password">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group text-center margin-top">
                            <a class="btn btn-default" href="{{ url('/login') }}">
                                <i class="fa fa-btn fa-sign-in"></i>SIGN IN
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-btn fa-user"></i>SIGN ME UP
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
