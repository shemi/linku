@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">New Link</div>

                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/link') }}">
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">URL</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="url" value="{{ old('url') }}">
                                    @if ($errors->has('url'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('url') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            @if(! empty($folders))
                                <div class="form-group{{ $errors->has('folder') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">Folder</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="folder" value="{{ old('folder') }}">
                                            <option value="">ROOT</option>
                                            {!! \Linku\Linku\Folders\Hierarchy::createSelectOptions($folders) !!}
                                        </select>

                                        @if ($errors->has('folder'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('folder') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-plus"></i>Create
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
