@extends('Frontend.includes.layout')

@section('content')
<main>
    <!-- breadcrumb-area -->
    @php
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
@if($top_videos->isNotEmpty())
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
                 @php
                        $channel = Channel::find($video->channel_id);
                        $thumbnail = $video->thumbnail_url ? Storage::disk(config('filesystems.default'))->url($video->thumbnail_url) : asset('frontend-assets/images/default.png');
                    @endphp
                   @include('Frontend.includes.components.cards.video-card')
                @endforeach
            </div>
        </div>
    </section>
    <!-- Top Videos End -->
@endif
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
                 @php
                        $channel = Channel::find($video->channel_id);
                        $thumbnail = $video->thumbnail_url ? Storage::disk(config('filesystems.default'))->url($video->thumbnail_url) : asset('frontend-assets/images/default.png');
                    @endphp
                   @include('Frontend.includes.components.cards.video-card')
                @endforeach
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="pagination-wrap mt-30">
                        {{ $videos->links() }}
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
