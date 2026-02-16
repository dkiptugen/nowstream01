@extends('Frontend.includes.layout')

@section('content')
<main>
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="breadcrumb-content">
                <h2 class="title">Genre: {{ ucfirst($genre) }}</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ ucfirst($genre) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <section class="content-list pt-4">
        <div class="container">
            @if($contents->isEmpty())
                <div class="text-center text-light-50 py-4">No content found for this genre.</div>
            @else
                <div class="row">
                    @foreach($contents as $content)
                        @if($content->content_group == 'tv')
                            @include('Frontend.includes.components.cards.tv-card', ['tv' => $content])
                        @elseif($content->content_group == 'podcast')
                            @include('Frontend.includes.components.cards.podcast-card', ['podcast' => $content])
                        @elseif($content->content_group == 'radio')
                            @include('Frontend.includes.components.cards.radio-card', ['radio' => $content])
                        @endif
                    @endforeach
                </div>

                <div class="mt-4">
                    {{ $contents->links() }} {{-- pagination links --}}
                </div>
            @endif
        </div>
    </section>
</main>
@endsection
