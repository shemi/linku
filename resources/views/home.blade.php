@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="margin-bottom: 25px">
                <div class="btn-group" role="group" aria-label="...">
                    <a href="{{ route('folder.create') }}" class="btn btn-default">New Folder</a>
                    <a href="{{ route('link.create') }}" class="btn btn-default">New Link</a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel-group" id="accordion-0" role="tablist" aria-multiselectable="true">
                    @foreach($links as $link)
                        @include('link.partials.link-panel', ['link' => $link])
                    @endforeach
                    @foreach($folders as $folder)
                        @include('folders.partials.folder-panel', ['folder' => $folder])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
