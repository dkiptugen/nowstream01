<div class="col-xl-3 col-lg-8">
    <div class="yt-comments-card card sticky-top" id="commentsCard">

        {{-- Header --}}
        <div class="card-header yt-comments-header border-0 pb-2">
            <h6 class="mb-0 font-weight-bold text-white">
                Comments <span class="text-light-50">(<span id="comment-count">{{ $comments->count() }}</span>)</span>
            </h6>
        </div>

        {{-- Comment Input (always at the bottom visually) --}}
        <div class="card-body pt-2 pb-0 yt-comments-body" id="comment-list">

            {{-- Comments container --}}
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

        {{-- Comment Input Form --}}
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

commentForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const input = document.getElementById('comment-input');
    const commentText = input.value.trim();
    if (!commentText) return;

    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ comment: commentText })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Remove "No comments yet" placeholder if exists
            const noComments = commentList.querySelector('.text-center');
            if (noComments) noComments.remove();

            // Insert new comment HTML at the top
            commentList.insertAdjacentHTML('afterbegin', data.html);
            input.value = '';
        }
    });
});

        function bindLikeDislike() {
            document.querySelectorAll('.yt-actions').forEach(actionsEl => {
                const commentEl = actionsEl.closest('.media');
                const commentId = commentEl.dataset.commentId;

                actionsEl.querySelector('.comment-like-btn').onclick = () => toggleReaction(commentId, 'like', actionsEl);
                actionsEl.querySelector('.comment-dislike-btn').onclick = () => toggleReaction(commentId, 'dislike', actionsEl);
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