<div id="commentlist">
    @forelse ($comments as $comment)
        @php
            $commentUser = $comment->user;
            $sanitizedComment = preg_replace(['/https?:\/\/\S+/', '/\b\d{10,13}\b/'], '', $comment->comment);

            $likesCount = $comment->likes->where('type','like')->count();
            $dislikesCount = $comment->likes->where('type','dislike')->count();

            $initials = collect(explode(' ', $commentUser->name ?? 'U'))
                ->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('');
        @endphp

        <div class="media py-3 border-bottom border-dark" data-comment-id="{{ $comment->id }}">
            {{-- Avatar --}}
            @if($commentUser->image)
                <img src="{{ asset($commentUser->image) }}" class="mr-3 rounded-circle" style="width:42px;height:42px;object-fit:cover;" alt="{{ $commentUser->name }}">
            @else
                <div class="mr-3 rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width:42px;height:42px;font-weight:bold;">
                    {{ $initials }}
                </div>
            @endif

            <div class="media-body">
                <strong class="mr-2 text-white" style="font-size:14px;">{{ $commentUser->name ?? 'Unknown' }}</strong>
                <small class="text-light-50" style="font-size:12px;">{{ $comment->created_at->diffForHumans() }}</small>

                <div class="mt-1 text-light" style="font-size:14px; line-height:1.4;">{{ trim($sanitizedComment) }}</div>

                <div class="mt-2 d-flex align-items-center yt-actions" style="font-size:13px;">
                    <a href="javascript:void(0)" class="mr-3 comment-like-btn">
                        <i class="fa fa-thumbs-up"></i> Like
                        <span class="likes-count">{{ $likesCount }}</span>
                    </a>
                    <a href="javascript:void(0)" class="mr-3 comment-dislike-btn">
                        <i class="fa fa-thumbs-down"></i> Dislike
                        <span class="dislikes-count">{{ $dislikesCount }}</span>
                    </a>
                    <a href="javascript:void(0)"><i class="fa fa-reply"></i> Reply</a>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-light-50 py-4">No comments yet. Be the first to comment.</div>
    @endforelse
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.yt-actions').forEach(actionsEl => {
        const commentEl = actionsEl.closest('.media');
        const commentId = commentEl.dataset.commentId;

        // Like button
        actionsEl.querySelector('.comment-like-btn').addEventListener('click', function() {
            toggleReaction(commentId, 'like', actionsEl);
        });

        // Dislike button
        actionsEl.querySelector('.comment-dislike-btn').addEventListener('click', function() {
            toggleReaction(commentId, 'dislike', actionsEl);
        });
    });

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
});
</script>
