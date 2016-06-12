@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit Link</div>

                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST"
                              action="{{ route('link.update', ['link' => $link->id]) }}">
                            {!! csrf_field() !!}
                            {!! method_field('put') !!}
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{ $link->name }}">
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
                                    <input type="text" class="form-control" name="url" value="{{ $link->src }}">
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
                                            <option value="">Root</option>
                                            {!! \Linku\Linku\Folders\Hierarchy::createSelectOptions($folders, $link->folder_id) !!}
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
                                        <i class="fa fa-btn fa-floppy-o" aria-hidden="true"></i>Save
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
