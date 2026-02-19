@extends('Frontend.includes.layout')
@section('content')
<main>
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="breadcrumb-content">
                <h2 class="title">Privacy Policy</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Privacy</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="movie-area movie-bg" data-background="{{ asset('assets/img/bg/movie_bg.jpg') }}">
        <div class="container">
            <h3>Privacy Policy</h3>
            <p>At Streamer.co.ke, your privacy is important to us. This policy explains how we collect and use your information:</p>
            <ul>
                <li>We collect only the necessary personal information for subscriptions and account management.</li>
                <li>Payment details are securely handled by our payment gateway and are never stored on our servers.</li>
                <li>We use cookies and analytics to improve your experience.</li>
                <li>Your data will not be sold to third parties.</li>
                <li>You may request to view, edit, or delete your personal data.</li>
            </ul>
            <p>For more details, please contact our support team.</p>
        </div>
    </section>
</main>
@endsection
