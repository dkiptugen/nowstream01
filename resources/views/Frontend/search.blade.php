@extends('Frontend.includes.layout')
@section('content')

<div class="d-flex align-items-center justify-content-center my-5">
    <div class="container">
        <div class="row mt-md-5 mt-2">
            <div class="col-md mx-auto">
                <div class="form-body mb-3 mt-md-5">
                    <form class="row g-3" id="searchForm">
                        <div class="col-12">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search...">
                        </div>
                    </form>
                </div>

                <div class="mt-4">
                    <div class="card-body">
                        <h5 class="">Search Results For <span id="searchQuery">Football</span></h5>
                        <ul class="nav nav-pills mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="pill" href="#primary-pills-stream" role="tab"
                                    aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class="bx bx-tv font-18 me-1"></i>
                                        </div>
                                        <div class="tab-title">Live Streams</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="pill" href="#primary-pills-video" role="tab"
                                    aria-selected="false" tabindex="-1">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class="bx bx-video font-18 me-1"></i>
                                        </div>
                                        <div class="tab-title">Videos</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="pill" href="#primary-pills-channel" role="tab"
                                    aria-selected="false" tabindex="-1">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class="bx bx-tv font-18 me-1"></i>
                                        </div>
                                        <div class="tab-title">Channels</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="primary-pills-stream" role="tabpanel">
                                <div class="row" id="streamResults">
                                    @foreach ($streams as $stream)
                                        <div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-3 mb-4">
                                            @include('Frontend.includes.components.cards.stream-card')
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="primary-pills-video" role="tabpanel">
                                <div class="row" id="videoResults">
                                    @foreach ($videos as $video)
                                        <div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-3 mb-4">
                                            @include('Frontend.includes.components.cards.video-card')
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane fade" id="primary-pills-channel" role="tabpanel">
                                <div class="row" id="channelResults">
                                    @foreach ($channels as $channel)
                                        <div class="col-12 col-lg-3 col-md-6 col-xl-3 col-xxl-3 mb-4">
                                            @include('Frontend.includes.components.cards.channels')
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
