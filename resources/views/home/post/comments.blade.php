<div class="title">
    <h2>
        <i aria-hidden="true" class="glyphicon glyphicon-comment"></i>
        《<span>{{ $post->post_title }}</span>》上有{{ $post->comment_count }}条评论
    </h2>
</div>
<div class="clearfix m-b-sm">
    <div class="btn-group btn-group-sm pull-right" role="group" aria-label="...">
        <button type="button" class="btn btn-default refresh">
            <i class="glyphicon glyphicon-refresh"></i> 刷新
        </button>
    </div>
</div>
@forelse ($comment as $value)
    @include('home.post.comment', ['comment' => $value])
@empty
    <div role="alert" class="alert alert-warning text-center">
        <span aria-hidden="true" class="glyphicon glyphicon-exclamation-sign"></span>
        未找到任何数据
    </div>
@endforelse
<nav class="text-right">
{!! $comment->appends(['post_id' => $post->post_id])->render() !!}
</nav>