@extends('Frontend.includes.layout')
@section('content')
<main>
    <!-- Breadcrumb -->
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="breadcrumb-content">
                <h2 class="title">User Data Deletion</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User Data Deletion</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <!-- User Data Deletion Info -->
    <section class="movie-area movie-bg" data-background="{{ asset('assets/img/bg/movie_bg.jpg') }}">
        <div class="container">
            <h3>Requesting Deletion of Your Data</h3>
            <p>At Streamer.co.ke, we respect your privacy and give you control over your personal data. If you wish to delete your account and associated data, please follow the steps below:</p>

            <ol>
                <li>Log in to your account on Streamer.co.ke.</li>
                <li>Navigate to your account settings and select <strong>"Delete Account"</strong>.</li>
                <li>Confirm your request by entering your password.</li>
                <li>Our system will process your request within 48 hours. You will receive a confirmation email once your data is deleted.</li>
            </ol>

            <h4>Important Notes:</h4>
            <ul>
                <li>Deleted data includes your profile, preferences, subscription history, and any uploaded content.</li>
                <li>Transactional records (e.g., payment receipts) may be retained for legal or accounting purposes but will be anonymized.</li>
                <li>Once deleted, this action is irreversible.</li>
            </ul>

            <p>If you encounter any issues or need assistance, please contact our support team via the <a href="{{ route('help.center') }}">Help Center</a>.</p>
        </div>
    </section>
</main>
@endsection
