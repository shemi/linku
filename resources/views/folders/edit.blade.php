@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit folder</div>

                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST"
                              action="{{ route('folder.update', ['folder' => $folder->id]) }}">
                            {!! csrf_field() !!}
                            {!! method_field('put') !!}

                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Name</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{ $folder->name }}">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            @if(! empty($folders))
                                <div class="form-group{{ $errors->has('parent') ? ' has-error' : '' }}">
                                    <label class="col-md-4 control-label">Parent</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="parent" value="{{ old('parent') }}">
                                            <option value="">ROOT</option>
                                            {!! \Linku\Linku\Folders\Hierarchy::createSelectOptions($folders, $folder->parent_id) !!}
                                        </select>

                                        @if ($errors->has('parent'))
                                            <span class="help-block">
                                            <strong>{{ $errors->first('parent') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-floppy-o"></i>Save
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
