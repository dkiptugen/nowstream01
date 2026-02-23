@extends('Frontend.includes.layout')
@section('content')
<main>
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="breadcrumb-content">
                <h2 class="title">FAQ</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">FAQ</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="movie-area movie-bg" data-background="{{ asset('assets/img/bg/movie_bg.jpg') }}">
        <div class="container">
            <h3>Frequently Asked Questions</h3>
            <div class="accordion" id="faqAccordion">
                <div class="card">
                    <div class="card-header" id="faqHeading1">
                        <h5 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#faq1" aria-expanded="true" aria-controls="faq1">
                                How do I subscribe to a premium plan?
                            </button>
                        </h5>
                    </div>
                    <div id="faq1" class="collapse show" aria-labelledby="faqHeading1" data-parent="#faqAccordion">
                        <div class="card-body">
                            You can subscribe by clicking the "Subscribe" button on any premium content page and following the checkout instructions.
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" id="faqHeading2">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#faq2" aria-expanded="false" aria-controls="faq2">
                                How do I report an issue?
                            </button>
                        </h5>
                    </div>
                    <div id="faq2" class="collapse" aria-labelledby="faqHeading2" data-parent="#faqAccordion">
                        <div class="card-body">
                            Please visit our <a href="{{ route('help.center') }}">Help Center</a> or contact support via the contact form.
                        </div>
                    </div>
                </div>
                <!-- Add more FAQs here -->
            </div>
        </div>
    </section>
</main>
@endsection
