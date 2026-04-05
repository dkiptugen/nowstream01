<div class="col-xl-3 col-lg-8">
    <div class="yt-comments-card card sticky-top" id="commentsCard">

        {{-- Header --}}
        <div class="card-header yt-comments-header border-0 pb-2">
            <h6 class="mb-0 fw-bold text-white">
                Comments
                <span class="text-light-50">
                    (<span id="comment-count">{{ $comments->count() }}</span>)
                </span>
            </h6>
        </div>

        {{-- Comment List --}}
        <div class="card-body pt-2 pb-0 yt-comments-body" id="comment-list">
            <div id="commentlist">

                @forelse ($comments as $comment)
                    @php
                        $comment_user = $comment->user;

                        $sanitizedComment = preg_replace([
                            '/https?:\/\/\S+/',   // URLs
                            '/\b\d{10,13}\b/'     // phone numbers
                        ], '', $comment->comment);

                        $commentUser = $comment_user;
                        $initials = collect(explode(' ', $commentUser->name ?? 'U'))
                            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                            ->join('');
                    @endphp

                    <div class="d-flex py-3 border-bottom border-dark" data-comment-id="{{ $comment->id }}">

                        {{-- Avatar --}}
                        @if($commentUser && $commentUser->image)
                            <img
                                src="{{ asset($commentUser->image) }}"
                                class="me-3 rounded-circle flex-shrink-0"
                                style="width:42px;height:42px;object-fit:cover;"
                                alt="{{ ucfirst($commentUser->name ?? 'Unknown') }}"
                            >
                        @else
                            <div class="me-3 rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:42px;height:42px;font-weight:bold;">
                                {{ $initials }}
                            </div>
                        @endif

                        {{-- Content --}}
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-wrap">
                                    <strong class="me-2 text-white" style="font-size: 14px;">
                                        {{ ucfirst($comment_user->name ?? 'Unknown') }}
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
                                <a href="javascript:void(0)" class="me-3 btn-like">
                                    <i class="fa fa-thumbs-up"></i> Like
                                    <span class="likes-count">
                                        {{ $comment->likes()->where('type','like')->count() }}
                                    </span>
                                </a>

                                <a href="javascript:void(0)" class="me-3 btn-dislike">
                                    <i class="fa fa-thumbs-down"></i> Dislike
                                    <span class="dislikes-count">
                                        {{ $comment->likes()->where('type','dislike')->count() }}
                                    </span>
                                </a>

                                <a href="javascript:void(0)" class="btn-reply">
                                    <i class="fa fa-reply"></i> Reply
                                </a>
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
        <div class="card-footer yt-comments-footer border-top border-dark">
            <form id="comment-form"
                  action="{{ route('comment.post', ['commentableType' => 'stream', 'commentableId' => $stream->uuid]) }}"
                  method="POST">
                @csrf

                <div class="d-flex align-items-start gap-3">
                    @php
                        $user = auth()->user();
                        $userInitials = collect(explode(' ', $user->name ?? 'U'))
                            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                            ->join('');
                    @endphp

                    {{-- User Avatar --}}
                    @if($user && $user->image)
                        <img src="{{ asset($user->image) }}"
                             alt="{{ $user->name }}"
                             class="rounded-circle"
                             style="width:42px;height:42px;object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                             style="width:42px;height:42px;font-weight:bold;">
                            {{ $userInitials }}
                        </div>
                    @endif

                    <div class="flex-grow-1">
                        <div class="input-group">
                            <input type="text"
                                   name="comment"
                                   id="comment-input"
                                   class="form-control yt-comment-input"
                                   placeholder="Add a comment..."
                                   required>

                            <button type="submit" class="btn btn-sm btn-send" id="comment-submit-btn">
                                <i class="fa fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    function sendReaction(commentId, type, actionsEl) {
        const url = type === "like"
            ? `/comment/${commentId}/like`
            : `/comment/${commentId}/dislike`;

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "Accept": "application/json",
            },
        })
        .then(res => res.json())
        .then(data => {
            if (!data || !data.success) return;

            // update counts
            actionsEl.querySelector(".likes-count").textContent = data.likes ?? 0;
            actionsEl.querySelector(".dislikes-count").textContent = data.dislikes ?? 0;
        })
        .catch(err => console.error("Reaction error:", err));
    }

    document.querySelectorAll(".yt-actions").forEach(actionsEl => {

        const commentEl = actionsEl.closest("[data-comment-id]");
        const commentId = commentEl?.dataset?.commentId;

        if (!commentId) return;

        actionsEl.querySelector(".btn-like")?.addEventListener("click", function () {
            sendReaction(commentId, "like", actionsEl);
        });

        actionsEl.querySelector(".btn-dislike")?.addEventListener("click", function () {
            sendReaction(commentId, "dislike", actionsEl);
        });

    });

});
</script>
