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
                <a class="btn btn-action btn-lg"
                   id="install-btn"
                   target="_blank"
                   href="https://chrome.google.com/webstore/detail/linku-shared-bookmarks/lkkjjknlchameodlpbjgcjfdkdedmgbb" role="button">
                    INSTALL NOW
                </a>
            </p>
        </div>
    </div>
</header>

<div class="home-content">
    <img class="print-screen" src="{{ url('images/printscreen.jpg') }}" alt="linku print screen">
</div>

<script>
    if(chrome) {
        var installBtn = document.getElementById('install-btn');

        installBtn.addEventListener('click', function(event) {
            chrome.webstore.install();
            event.preventDefault();
        })

    }
</script>

<!-- /Header -->
@endsection
