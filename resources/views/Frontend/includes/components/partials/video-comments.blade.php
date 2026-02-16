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
                            
                            {{-- Avatar --}}
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
        </div>

    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('comment-form');
    const input = document.getElementById('comment-input');
    const list = document.getElementById('commentlist');
    const scrollBox = document.getElementById('comment-list');
    const countEl = document.getElementById('comment-count');
    const btn = document.getElementById('comment-submit-btn');

    function scrollBottom() {
        scrollBox.scrollTop = scrollBox.scrollHeight;
    }

    // Scroll on load
    scrollBottom();

    // ===== Post Comment =====
    form.addEventListener('submit', function(e){
        e.preventDefault();

        const text = input.value.trim();
        if (!text) return;

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

            if (!data.success) return;

            // Remove placeholder
            const empty = document.getElementById('no-comments');
            if (empty) empty.remove();

            // Build comment
            const html = `
                <div class="media py-3 border-bottom border-dark" data-comment-id="${data.comment_id}">
                    <img src="${data.user_image}" class="mr-3 rounded-circle"
                         style="width:42px;height:42px;object-fit:cover;">

                    <div class="media-body">
                        <strong class="text-white">${data.user_name}</strong>
                        <small class="text-light-50 ml-2">just now</small>

                        <div class="text-light mt-1">${data.comment}</div>

                        <div class="mt-2 yt-actions">
                            <a href="#" class="comment-like-btn mr-3">👍 <span class="likes-count">0</span></a>
                            <a href="#" class="comment-dislike-btn">👎 <span class="dislikes-count">0</span></a>
                        </div>
                    </div>
                </div>
            `;

            list.insertAdjacentHTML('beforeend', html);

            // Update count
            countEl.textContent = parseInt(countEl.textContent) + 1;

            input.value = '';
            scrollBottom();
        })
        .finally(() => btn.disabled = false);
    });


    // ===== Like / Dislike (Delegated) =====
    document.addEventListener('click', function(e){

        if (!e.target.closest('.comment-like-btn') && !e.target.closest('.comment-dislike-btn')) return;

        e.preventDefault();

        const actionBtn = e.target.closest('a');
        const commentEl = actionBtn.closest('.media');
        const commentId = commentEl.dataset.commentId;

        const type = actionBtn.classList.contains('comment-like-btn') ? 'like' : 'dislike';

        fetch(`/comment/${commentId}/${type}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            commentEl.querySelector('.likes-count').textContent = data.likes;
            commentEl.querySelector('.dislikes-count').textContent = data.dislikes;
        });

    });

});
</script>
