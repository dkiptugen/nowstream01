<div class="col-xl-3 col-lg-8">
    <div class="yt-comments-card card sticky-top" id="commentsCard">

        {{-- Header --}}
        <div class="card-header yt-comments-header border-0 pb-2">
            <h6 class="mb-0 font-weight-bold text-white">
                Comments (<span id="comment-count">{{ $comments->count() }}</span>)
            </h6>
        </div>

        {{-- Scrollable Comments --}}
        <div class="card-body yt-comments-body" id="comment-list" style="overflow-y:auto; max-height:500px;">
            <div id="commentlist">

                @if($comments->isEmpty())
                    <div id="no-comments" class="text-center text-light-50 py-4">
                        No comments yet. Be the first to comment.
                    </div>
                @else
                    @foreach($comments as $comment)
                        <div class="media py-3 border-bottom border-dark" data-comment-id="{{ $comment->id }}">
                            @if($comment->user->image)
                                <img src="{{ asset($comment->user->image) }}"
                                     class="mr-3 rounded-circle"
                                     style="width:42px;height:42px;object-fit:cover;">
                            @else
                                <div class="mr-3 rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                     style="width:42px;height:42px;font-weight:bold;">
                                    {{ strtoupper(substr($comment->user->name,0,1)) }}
                                </div>
                            @endif
                            
                            <div class="media-body">
                                <strong class="text-white">{{ $comment->user->name }}</strong>
                                <small class="text-light-50 ml-2">{{ $comment->created_at->diffForHumans() }}</small>

                                <div class="text-light mt-1">{{ $comment->comment }}</div>

                                <div class="mt-2 yt-actions">
                                    <a href="#" class="comment-like-btn mr-3">👍 
                                        <span class="likes-count">{{ $comment->likes()->where('type','like')->count() }}</span>
                                    </a>
                                    <a href="#" class="comment-dislike-btn">👎 
                                        <span class="dislikes-count">{{ $comment->likes()->where('type','dislike')->count() }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>

        {{-- Footer Form --}}
        <div class="card-footer border-top border-dark">
            @if(auth()->check())
                <form id="comment-form"
                    action="{{ route('comment.post', ['commentableType'=>$commentableType,'commentableId'=>$commentableId]) }}"
                    method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="comment" id="comment-input" class="form-control" placeholder="Add a comment..." required>
                        <div class="input-group-append">
                            <button class="btn btn-sm btn-send" id="comment-submit-btn">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="text-center text-light-50 py-2">
                    <a href="{{ route('user.login') }}" class="btn btn-sm btn-primary">Login to comment</a>
                </div>
            @endif
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(auth()->check())
    const form = document.getElementById('comment-form');
    const input = document.getElementById('comment-input');
    const list = document.getElementById('commentlist');
    const scrollBox = document.getElementById('comment-list');
    const countEl = document.getElementById('comment-count');
    const btn = document.getElementById('comment-submit-btn');

    function scrollBottom() {
        if (scrollBox) scrollBox.scrollTop = scrollBox.scrollHeight;
    }

    scrollBottom();

    form.addEventListener('submit', function(e){
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;

        // User info
        const userName = "{{ auth()->user()->name }}";
        const userImage = "{{ auth()->user()->image ? asset(auth()->user()->image) : asset('assets/images/avatars/avatar-2.png') }}";

        // Remove placeholder
        const empty = document.getElementById('no-comments');
        if (empty) empty.remove();

        const tempId = 'temp-' + Date.now();
        const safeText = document.createElement('div'); safeText.innerText = text;

        // Add comment instantly
        const html = `
            <div class="media py-3 border-bottom border-dark" data-comment-id="${tempId}">
                <img src="${userImage}" class="mr-3 rounded-circle"
                     style="width:42px;height:42px;object-fit:cover;">
                <div class="media-body">
                    <strong class="text-white">${userName}</strong>
                    <small class="text-light-50 ml-2">just now</small>
                    <div class="text-light mt-1">${safeText.innerHTML}</div>
                    <div class="mt-2 yt-actions">
                        <a href="#" class="comment-like-btn mr-3">👍 <span class="likes-count">0</span></a>
                        <a href="#" class="comment-dislike-btn">👎 <span class="dislikes-count">0</span></a>
                    </div>
                </div>
            </div>`;
        list.insertAdjacentHTML('beforeend', html);

        countEl.textContent = parseInt(countEl.textContent) + 1;
        input.value = '';
        scrollBottom();
        btn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ comment: text })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) throw new Error('Failed');

            const tempEl = document.querySelector(`[data-comment-id="${tempId}"]`);
            if (tempEl) tempEl.setAttribute('data-comment-id', data.comment_id);
        })
        .catch(() => {
            const tempEl = document.querySelector(`[data-comment-id="${tempId}"]`);
            if (tempEl) tempEl.remove();
            countEl.textContent = Math.max(0, parseInt(countEl.textContent) - 1);
            alert('Failed to post comment. Please try again.');
        })
        .finally(() => btn.disabled = false);
    });
    @endif

    // Delegated Like/Dislike for everyone
    document.addEventListener('click', function(e){
        const likeBtn = e.target.closest('.comment-like-btn');
        const dislikeBtn = e.target.closest('.comment-dislike-btn');
        if (!likeBtn && !dislikeBtn) return;

        e.preventDefault();
        const btnEl = likeBtn || dislikeBtn;
        const commentEl = btnEl.closest('.media');
        const commentId = commentEl.dataset.commentId;
        if (commentId.startsWith('temp-')) return;
        const type = likeBtn ? 'like' : 'dislike';

        fetch(`/comment/${commentId}/${type}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            commentEl.querySelector('.likes-count').textContent = data.likes;
            commentEl.querySelector('.dislikes-count').textContent = data.dislikes;
        });
    });
});
</script>
