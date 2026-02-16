<div class="col-xl-3 col-lg-8">
    <div class="yt-comments-card card sticky-top" id="commentsCard">

        {{-- Header --}}
        <div class="card-header yt-comments-header border-0 pb-2">
            <h6 class="mb-0 font-weight-bold text-white">
                Comments <span class="text-light-50">(<span id="comment-count">{{ $comments->count() }}</span>)</span>
            </h6>
        </div>

        {{-- Comment List --}}
        <div class="card-body pt-2 pb-0 yt-comments-body" id="comment-list">
            <div id="commentlist">
                @forelse ($comments as $comment)
                    @php
                        $user = $comment->user;
                        $sanitizedComment = preg_replace([
                            '/https?:\/\/\S+/',   // URLs
                            '/\b\d{10,13}\b/'     // phone numbers
                        ], '', $comment->comment);

                        $initials = collect(explode(' ', $user->name ?? 'U'))
                            ->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('');
                    @endphp

                    <div class="media py-3 border-bottom border-dark" data-comment-id="{{ $comment->id }}">
                        {{-- Avatar --}}
                        @if($user->image)
                            <img src="{{ asset($user->image) }}" class="mr-3 rounded-circle"
                                style="width:42px;height:42px;object-fit:cover;"
                                alt="{{ $user->name ?? 'Unknown' }}">
                        @else
                            <div class="mr-3 rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                style="width:42px;height:42px;font-weight:bold;">
                                {{ $initials }}
                            </div>
                        @endif

                        {{-- Content --}}
                        <div class="media-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-wrap">
                                    <strong class="mr-2 text-white" style="font-size: 14px;">
                                        {{ $user->name ?? 'Unknown' }}
                                    </strong>
                                    <small class="text-light-50" style="font-size: 12px;">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>

                            <div class="mt-1 text-light" style="font-size: 14px; line-height: 1.4;">
                                {{ trim($sanitizedComment) }}
                            </div>

                            {{-- Actions --}}
                            <div class="mt-2 d-flex align-items-center yt-actions" style="font-size: 13px;">
                                <a href="javascript:void(0)" class="mr-3 action-btn" data-action="like">
                                    <i class="fa fa-thumbs-up"></i> Like
                                    <span class="likes-count">{{ $comment->likes()->where('type','like')->count() }}</span>
                                </a>
                                <a href="javascript:void(0)" class="mr-3 action-btn" data-action="dislike">
                                    <i class="fa fa-thumbs-down"></i> Dislike
                                    <span class="dislikes-count">{{ $comment->likes()->where('type','dislike')->count() }}</span>
                                </a>
                                <a href="javascript:void(0)" class="reply-btn"><i class="fa fa-reply"></i> Reply</a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center text-light-50 py-4">
                        No comments yet. Be the first to comment.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Comment Input --}}
        @auth
            <div class="card-footer yt-comments-footer border-top border-dark">
                <form id="comment-form" action="{{ route('comment.post', ['commentableType' => $commentableType, 'commentableId' => $commentableId]) }}" method="POST">
                    @csrf
                    <div class="media align-items-start">
                        @php
                            $user = auth()->user();
                            $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('');
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
        @else
            <div class="card-footer yt-comments-footer border-top border-dark text-center text-light-50">
                Please <a href="{{ route('login') }}">login</a> to comment.
            </div>
        @endauth

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Handle like/dislike actions
    document.querySelectorAll('.action-btn').forEach(el => {
        el.addEventListener('click', function () {
            const commentEl = el.closest('.media');
            const commentId = commentEl.dataset.commentId;
            const action = el.dataset.action;

            if (!commentId || !action) return;

            fetch(`/comment/${commentId}/${action}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    commentEl.querySelector('.likes-count')?.textContent = data.likes || 0;
                    commentEl.querySelector('.dislikes-count')?.textContent = data.dislikes || 0;
                }
            });
        });
    });

    // Handle comment submission via AJAX
    const commentForm = document.getElementById('comment-form');
    if(commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(commentForm);

            fetch(commentForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    const commentList = document.getElementById('commentlist');
                    commentList.insertAdjacentHTML('afterbegin', data.html ?? '');
                    document.getElementById('comment-count').textContent = parseInt(document.getElementById('comment-count').textContent) + 1;
                    commentForm.reset();
                }
            });
        });
    }

});
</script>
