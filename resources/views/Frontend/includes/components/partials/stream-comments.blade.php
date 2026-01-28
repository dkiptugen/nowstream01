<div class="col-12 col-lg-4">
 	<div class="card radius-5 comment sticky-top">
 		<!-- Comment Section -->
 		<div class="comment-top" id="comment-list">
 			<div class="" id="commentlist">
 				@foreach ($comments as $comment)
 				<div class="card-body d-flex py-2 border-top px-2 mx-0 w-100 justify-content-between comment-item">
 					<div class="d-flex">
 						<div class="align-self-center text-center">
 							@php
 							$comment_user = \App\Models\User::find($comment->user_id);
 							@endphp
						    <img src="{{ $comment_user->image ?? asset('avatar.png') }}" height="50" class="w-100 d-block w-100 aspect1" alt="...">
 						</div>
 						<div class="mx-1 mx-md-2">
 							<div class="media-body">
 								<h6 class="my-0">
 									{{ $comment_user->name }}
 								</h6>
 								<p class="mb-0">
 									@php
 									// Sanitize comment to remove links and phone numbers
 									$sanitizedComment = preg_replace([
 									'/https?:\/\/\S+/', // Remove URLs
 									'/\b\d{10,13}\b/' // Remove phone numbers
 									], '', $comment->comment);
 									@endphp
 									{{ $sanitizedComment }}
 								</p>
 							</div>
 						</div>
 					</div>
 					<small class="text-muted float-end time-comm">
 						{{ $comment->created_at->diffForHumans() }}
 					</small>
 				</div>
 				@endforeach
 			</div>
 		</div>

 		<div class="card-body row border-top px-0 mt-70 mx-0">
 				@auth
			    <form id="comment-form" action="{{ route('comment.post', ['commentableType' => 'stream', 'commentableId' => $stream->id]) }}" method="POST">
 					@csrf
				    <div class="chat-footer d-flex align-items-center">
 				<div class="flex-grow-1 pe-2">
 					<div class="input-group">
 						<input type="text" class="form-control"  name="comment" rows="3" placeholder="Type a comment">
						<button type="submit" class="input-group-text"><i class="bx bx-send"></i></button>
 					</div>
 				</div>
 			</div>
 				</form>
		    @else
			    <p class="text-center mt-3">Please <a href="{{ route('user.login') }}">login</a> to post a comment.
 				</p>
		    @endauth

 		</div>

			</div>
		</div>
