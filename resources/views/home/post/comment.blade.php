<div id="comment-{{ $comment->comment_ID }}" class="media" data-id="{{ $comment->comment_ID }}">
    <div class="media-left">
        <img class="avatar avatar-64" src="{{ show_avatar($comment->user->avatar) }}?imageView2/1/w/50/h/50/p/100">
    </div>
    <div class="media-body">

        <a class="nickname" href="{{ URL::route('homeProfileIndex', ['user_id' => $comment->user->id]) }}"><strong>{{ $comment->user->name }}</strong></a>
        <small class="text-navy m-l-xs">{{ show_date($comment->comment_date) }}</small>
        @if (Auth::id() == $comment->user_id)

        <button data-dismiss="modal" class="close comment-delete m-r" type="button">
            <span aria-hidden="true">×</span>
            <span class="sr-only">Close</span>
        </button>
        @endif
        <div>
            <p class="comment-content">{!! nl2br(e($comment->comment_content)) !!}</p>
        </div>
        @if ($comment->comment_parent == 0 && Auth::check())
        <div class="media-reply">
            <button class="btn btn-white btn-xs reply" data-id="{{ $comment->comment_ID }}">
                <i class="glyphicon glyphicon-share-alt"></i> 回复
            </button>
        </div>
        @endif
        <div id="collapse-{{ $comment->comment_ID }}" class="m-t">
        @foreach ($comment->comment()->where('comment_approved', config('const.COMMENT_APPROVED.OPEN'))->get() as $child)
            @include('home.post.comment', ['comment' => $child])
        @endforeach
        </div>
    </div>
</div>