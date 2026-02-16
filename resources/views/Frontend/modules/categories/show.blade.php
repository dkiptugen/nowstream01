@extends('Frontend.includes.layout')
@section('content')
<main>
    <!-- breadcrumb-area -->
    <section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-content">
                        <h2 class="title">Category: <span>{{ ucfirst($category->name) }}</span></h2>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{'/'}}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ ucfirst($category->name) }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb-area-end -->    
    <section class="top-rated-movie tr-movie-bg" data-background="{{ asset('assets/img')}}/bg/tr_movies_bg.jpg">
        <div class="container"> 
            <div class="episode-top-wrap">
                <div class="section-title"> <span class="sub-title">Category: {{ ucfirst($category->name) }}</span>
                    <h2 class="title">Category: {{ ucfirst($category->name) }}</h2>
                </div> 
            </div>  
            <div class="row tr-movie-active">
                @foreach($podcasts as $podcast)
                @include('Frontend.includes.components.cards.podcast-card', ['podcast' => $podcast])
                @endforeach
            </div>
        </div>
    </section>
</main>
@endsection 