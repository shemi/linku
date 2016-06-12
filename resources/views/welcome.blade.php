@extends('layouts.app')

@section('content')
<!-- Header -->
<header id="head">
    <div class="container">
        <div class="row">
            <h1 class="lead">
                <div class='header-content'>
                    <div class='visible'>
                        <p>
                            SHARE BOOKMARKS WITH
                        </p>
                        <ul>
                            <li>Friends</li>
                            <li>Family</li>
                            <li>Coworkers</li>
                            <li>Everybody</li>
                        </ul>
                    </div>
                </div>
            </h1>
            <p class="tagline">

            </p>
            <p>
                {{--<a class="btn btn-default btn-lg" role="button">MORE INFO</a>--}}
                <a class="btn btn-action btn-lg" href="{{ url('extension/linku-chrome-latest.crx') }}" role="button">DOWNLOAD NOW</a>
            </p>
        </div>
    </div>
</header>

<div class="home-content">
    <img class="print-screen" src="{{ url('images/printscreen.jpg') }}" alt="linku print screen">
</div>

<!-- /Header -->
@endsection
