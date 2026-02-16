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
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const commentForm = document.getElementById('comment-form');
        const commentList = document.getElementById('commentlist');

        $('#comment-form').on('submit', function(e) {
    e.preventDefault();

    let commentInput = $('#comment-input').val();
    let uuid = 'your-video-uuid';
    let contentGroup = 'video';

    $.ajax({
        url: `/comment/post/${contentGroup}/${uuid}`,
        type: 'POST',
        data: { comment: commentInput, _token: $('input[name="_token"]').val() },
        success: function(res) {
            if (res.success) {
                // Append new comment at the bottom
                $('#commentlist').append(`
                    <div class="media py-3 border-bottom border-dark" data-comment-id="${res.comment.id}">
                        <img src="${res.user_image}" class="mr-3 rounded-circle" style="width:42px;height:42px;object-fit:cover;" alt="${res.user_name}">
                        <div class="media-body">
                            <strong class="mr-2 text-white" style="font-size:14px;">${res.user_name}</strong>
                            <small class="text-light-50" style="font-size:12px;">Just now</small>
                            <div class="mt-1 text-light" style="font-size:14px; line-height:1.4;">${res.comment}</div>
                            <div class="mt-2 d-flex align-items-center yt-actions" style="font-size:13px;">
                                <a href="javascript:void(0)" class="mr-3 comment-like-btn">
                                    <i class="fa fa-thumbs-up"></i> Like
                                    <span class="likes-count">0</span>
                                </a>
                                <a href="javascript:void(0)" class="mr-3 comment-dislike-btn">
                                    <i class="fa fa-thumbs-down"></i> Dislike
                                    <span class="dislikes-count">0</span>
                                </a>
                            </div>
                        </div>
                    </div>
                `);
                $('#comment-input').val('');
                $('#comment-count').text(parseInt($('#comment-count').text()) + 1);
            }
        }
    });
});

        function bindLikeDislike() {
            document.querySelectorAll('.yt-actions').forEach(actionsEl => {
                const commentEl = actionsEl.closest('.media');
                const commentId = commentEl.dataset.commentId;

                const likeBtn = actionsEl.querySelector('.comment-like-btn');
                const dislikeBtn = actionsEl.querySelector('.comment-dislike-btn');

                if (likeBtn) likeBtn.onclick = () => toggleReaction(commentId, 'like', actionsEl);
                if (dislikeBtn) dislikeBtn.onclick = () => toggleReaction(commentId, 'dislike', actionsEl);
            });
        }

        function toggleReaction(commentId, type, container) {
            fetch(`/comment/${commentId}/${type}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
                .then(res => res.json())
                .then(data => {
                    container.querySelector('.likes-count').textContent = data.likes;
                    container.querySelector('.dislikes-count').textContent = data.dislikes;
                });
        }

        bindLikeDislike();

    });

</script>