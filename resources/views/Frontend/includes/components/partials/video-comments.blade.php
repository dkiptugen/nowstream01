<div class="col-xl-3 col-lg-8">
    <div class="yt-comments-card card sticky-top" id="commentsCard">

        {{-- Header --}}
        <div class="card-header yt-comments-header border-0 pb-2">
            <h6 class="mb-0 font-weight-bold text-white">
                Comments <span class="text-light-50">(<span id="comment-count">{{ $comments->count() }}</span>)</span>
            </h6>
        </div>

       <div class="yt-comments-body" id="comment-list">
    <div id="commentlist">
                @if($comments->isEmpty())
                    <div class="text-center text-light-50 py-4">
                        No comments yet. Be the first to comment.
                    </div>
                @else
                    @foreach($comments as $comment)
                        @include('Frontend.includes.components.partials.single-comment', ['comment' => $comment])
                    @endforeach
                @endif

        </div>
        </div>

        {{-- Comment Input Form (always at bottom) --}}
        <div class="card-footer yt-comments-footer border-top border-dark">
            <form id="comment-form"
                action="{{ route('comment.post', ['commentableType' => $commentableType, 'commentableId' => $commentableId]) }}"
                method="POST">
                @csrf
                <div class="media align-items-start">
                    @php
                        $user = auth()->user();
                        $initials = collect(explode(' ', $user->name))->map(fn($word) => strtoupper(substr($word, 0, 1)))->join('');
                    @endphp

                    @if($user->image)
                        <img src="{{ asset($user->image) }}" alt="{{ $user->name }}" class="rounded-circle"
                            style="width:42px;height:42px;object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                            style="width:42px;height:42px;font-weight:bold;">
                            {{ $initials }}
                        </div>
                    @endif

                    <div class="media-body">
                        <div class="input-group">
                            <input type="text" name="comment" id="comment-input" class="form-control yt-comment-input"
                                placeholder="Add a comment..." required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-sm btn-send" id="comment-submit-btn">
                                    <i class="fa fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
