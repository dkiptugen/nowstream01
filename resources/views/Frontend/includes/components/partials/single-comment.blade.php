<div class="media py-3 border-bottom border-dark" data-comment-id="{{ $comment->id }}">
    <img src="{{ $comment->user->image ? asset($comment->user->image) : 'https://via.placeholder.com/42' }}" 
         class="mr-3 rounded-circle" style="width:42px;height:42px;object-fit:cover;" alt="{{ $comment->user->name }}">
    <div class="media-body">
        <strong class="mr-2 text-white" style="font-size:14px;">{{ $comment->user->name }}</strong>
        <small class="text-light-50" style="font-size:12px;">{{ $comment->created_at->diffForHumans() }}</small>
        <div class="mt-1 text-light" style="font-size:14px; line-height:1.4;">{{ $comment->comment }}</div>
        <div class="mt-2 d-flex align-items-center yt-actions" style="font-size:13px;">
            <a href="javascript:void(0)" class="mr-3 comment-like-btn">
                <i class="fa fa-thumbs-up"></i> Like
                <span class="likes-count">{{ $comment->likes->where('type','like')->count() }}</span>
            </a>
            <a href="javascript:void(0)" class="mr-3 comment-dislike-btn">
                <i class="fa fa-thumbs-down"></i> Dislike
                <span class="dislikes-count">{{ $comment->likes->where('type','dislike')->count() }}</span>
            </a>
        </div>
    </div>
</div>
