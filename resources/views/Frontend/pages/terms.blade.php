@extends('Frontend.includes.layout')
@section('content')
<main>
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="breadcrumb-content">
                <h2 class="title">Terms of Use</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Terms of Use</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="movie-area movie-bg" data-background="{{ asset('assets/img/bg/movie_bg.jpg') }}">
        <div class="container">
            <h3>Terms of Use</h3>
            <p>Welcome to Streamer.co.ke. By using our platform, you agree to the following terms and conditions:</p>
            <ul>
                <li>You must be at least 18 years old or have parental consent.</li>
                <li>Do not share your account credentials with others.</li>
                <li>Respect copyrights and intellectual property rights.</li>
                <li>We reserve the right to suspend accounts violating our terms.</li>
                <li>All content is for personal use only.</li>
            </ul>
            <p>For full legal terms, please contact our support or read the official document.</p>
        </div>
    </section>
</main>
@endsection
