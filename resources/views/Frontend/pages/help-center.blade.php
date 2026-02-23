@extends('Frontend.includes.layout')
@section('content')
<main>
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="breadcrumb-content">
                <h2 class="title">Help Center</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Help Center</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="movie-area movie-bg" data-background="{{ asset('assets/img/bg/movie_bg.jpg') }}">
        <div class="container">
            <h3>Need Assistance?</h3>
            <p>If you are experiencing issues with streaming, billing, or your account, please check the topics below:</p>

            <ul class="list-group list-group-flush">
                <li class="list-group-item"><a href="#">Account & Login</a></li>
                <li class="list-group-item"><a href="#">Billing & Subscriptions</a></li>
                <li class="list-group-item"><a href="#">Technical Support</a></li>
                <li class="list-group-item"><a href="#">Content & Streaming Issues</a></li>
            </ul>

            <p class="mt-3">Still need help? Contact our support team via <a href="{{ route('help.center') }}">Help Center form</a>.</p>
        </div>
    </section>
</main>
@endsection
