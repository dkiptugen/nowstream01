@extends('Frontend.includes.layout')

@section('content')
<main>
<!-- breadcrumb-area -->
@php
    // Default values if not passed from the controller
    $breadcrumbTitle = $breadcrumbTitle ?? 'Videos';
    $breadcrumbSubtitle = $breadcrumbSubtitle ?? 'Our';
    $breadcrumbItems = $breadcrumbItems ?? [
        ['title' => 'Home', 'url' => url('/')],
        ['title' => $breadcrumbTitle, 'url' => null],
    ];
@endphp

<section class="breadcrumb-area breadcrumb-bg" data-background="{{ asset('assets/img/bg/breadcrumb_bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-content">
                    <h2 class="title">{{ $breadcrumbSubtitle }} <span>{{ $breadcrumbTitle }}</span></h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @foreach ($breadcrumbItems as $item)
                                @if ($loop->last || !$item['url'])
                                    <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] }}</li>
                                @else
                                    <li class="breadcrumb-item"><a href="{{ $item['url'] }}">{{ $item['title'] }}</a></li>
                                @endif
                            @endforeach
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- breadcrumb-area-end -->


    <!-- Top Videos -->
    <section class="movie-area movie-bg" data-background="{{ asset('assets/img/bg/movie_bg.jpg') }}">
        <div class="container">
            <div class="row align-items-end mb-60">
                <div class="col-lg-6">
                    <div class="section-title text-center text-lg-left">
                        <span class="sub-title">.......</span>
                        <h2 class="title">Top Videos</h2>
                    </div>
                </div>
            </div>
            <div class="row tr-movie-active">
            @php use App\Models\Channel; @endphp
            @foreach($top_videos as $video) 
                <div class="col-xl-4 col-lg-4 col-sm-6 grid-item grid-sizer">
                    <div class="movie-item mb-60 shadow-sm bg-dark">
                        <div class="movie-poster mb-0">
                            <a href="{{ url("/video/{$video->uuid}/{$video->slug}") }}">
                                <img src="{{$video->thumbnail ?? asset('frontend-assets/images/default.png')}}"
                                    class="w-100 d-block w-100" alt="...">
                                <div class="play fs-40">
                                    <i class="fadeIn animated bx bx-play-circle"></i>
                                </div>
                            </a>
                        </div>
                        <div class="movie-content p-2">
                            <div class="top">
                            @php
                            $channel = Channel::find($video->channel_id);
                            @endphp
                                <h5 class="title mt-0">
                                    <a href="{{ url("/video/{$video->id}/{$video->slug}") }}">
                                        {{ucfirst($video->title)}}
                                    </a>
                                </h5> 
                            </div>
                            <div class="bottom">
                                <!-- Display number of views -->

                                <ul>
                                    <li><span class="quality">hd</span></li>
                                    <li>
                                        <span class="channel"><i class="far fa-user"></i>
                                            {{ $channel ? $channel->name : 'Unknown' }}</span>
                                        <span class="rating"><i class="fas fa-thumbs-up"></i> 3.5</span>
                                        <span class="views ml-2">
                                            <i class="fas fa-eye"></i> {{ $video->views ?? 0 }} views
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach 
            </div>
        </div>
    </section>
    <!-- Top Videos End -->

    <!-- Latest Videos -->
    <section class="movie-area movie-bg" data-background="{{ asset('assets/img/bg/movie_bg.jpg') }}">
        <div class="container">
            <div class="row align-items-end mb-60">
                <div class="col-lg-6">
                    <div class="section-title text-center text-lg-left">
                        <span class="sub-title">.......</span>
                        <h2 class="title">Latest Videos</h2>
                    </div>
                </div>
            </div>

            <div class="row tr-movie-active">
                @foreach ($videos as $video)
                    @php $channel = $channels[$video->channel_id] ?? null; @endphp
                <div class="col-xl-4 col-lg-4 col-sm-6 grid-item grid-sizer">
                    <div class="movie-item mb-60 shadow-sm bg-dark">
                        <div class="movie-poster mb-0">
                            <a href="{{ url("/video/{$video->id}/{$video->slug}") }}">
                                <img src="{{$video->thumbnail ?? asset('frontend-assets/images/default.png')}}"
                                    class="w-100 d-block w-100" alt="...">
                                <div class="play fs-40">
                                    <i class="fadeIn animated bx bx-play-circle"></i>
                                </div>
                            </a>
                        </div>
                        <div class="movie-content p-2">
                            <div class="top">
                            @php
                            $channel = Channel::find($video->channel_id);
                            @endphp
                                <h5 class="title mt-0">
                                    <a href="{{ url("/video/{$video->id}/{$video->slug}") }}">
                                        {{ucfirst($video->title)}}
                                    </a>
                                </h5> 
                            </div>
                            <div class="bottom">
                                <!-- Display number of views -->

                                <ul>
                                    <li><span class="quality">hd</span></li>
                                    <li>
                                        <span class="channel"><i class="far fa-user"></i>
                                            {{ $channel ? $channel->name : 'Unknown' }}</span>
                                        <span class="rating"><i class="fas fa-thumbs-up"></i> 3.5</span>
                                        <span class="views ml-2">
                                            <i class="fas fa-eye"></i> {{ $video->views ?? 0 }} views
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="pagination-wrap mt-30">
                        {{ $videos->links() }} {{-- Laravel pagination --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Latest Videos End -->

</main>
@endsection

@section('header')
@endsection

@section('footer')
@endsection
