@extends('home.common.base')

@section('title', "$post->post_title")
@section('keywords', "$post->post_title")
@section('description', "$post->post_title")

@section('content')
    <div class="">
        <div class="text-center">
            <h1>{{ $post->post_title }}</h1>
        </div>
        <div class="info text-center">
            <span aria-hidden="true" class="glyphicon glyphicon-calendar"></span>
            日期：{{ date('Y-m-d', strtotime($post->post_date)) }}
            <span aria-hidden="true" class="glyphicon glyphicon-comment"></span>
            评论：{{ number_format($post->comment_count) }}
            <span aria-hidden="true" class="glyphicon glyphicon-eye-open"></span>
            浏览：{{ number_format($post->visitor_count) }}
        </div>
        <div class="media-contents">
            <div id="content" class="word-break">
                {!! $post->post_content !!}
            </div><!-- .lead -->
        </div>
    </div>
    <nav>
        <ul class="pager">
            @if ($prev)
                <li class="previous">
                    <a rel="prev" href="{{ URL::route('homePostIndex', ['post_id' => $prev->post_id]) }}"
                       title="{{ $prev->post_title }}">
                        <span class="meta-nav">←</span> {{ str_limit($prev->post_title, 10) }}
                    </a>
                </li>
            @endif
            @if ($next)
                <li class="next">
                    <a rel="next" href="{{ URL::route('homePostIndex', ['post_id' => $next->post_id]) }}"
                       title="{{ $next->post_title }}">
                        {{ str_limit($next->post_title, 10) }} <span class="meta-nav">→</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
    <div class="comments-area" id="comments">
        <div class="commentlist">
            <div id="infscr-loading"><img src="{{ URL::asset('/') }}home/image/loader.gif">

                <div style="opacity: 1;">加载评论中</div>
            </div>
        </div><!-- .commentlist -->
        <div class="comment-respond" id="respond">
            <h3 class="comment-reply-title" id="reply-title">发表评论</h3>
            @if (Auth::check())
                <form class="comment-form" id="commentform" method="post">
                    <div class="form-group">
                        <textarea aria-required="true" rows="8" cols="45" name="comment_content"
                                  class="form-control"></textarea>
                    </div>
                    <p class="form-submit"><input type="submit" value="发表评论" class="btn btn-primary" id="submit"
                                                  name="submit">
                        <input type="hidden" value="{{ $post->post_id }}" name="post_id">
                        <input type="hidden" value="" id="comment_parent" name="comment_parent">
                    </p>
                </form>
            @else
                <div class="text-center m-b">
                    <a href="{{ URL::route('authAuthGetLogin', ['return_url' => Request::path()]) }}"
                       class="btn btn-default">去登录</a>
                </div>
            @endif
        </div><!-- #respond -->
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('/') }}auth/js/plugins/ueditor/ueditor.parse.min.js"></script>
    <script type="text/javascript">
        var initcommentUrl = "{{ URL::route('homeCommentIndex', ['post_id' => $post->post_id]) }}";
        var currentCommentUrl = "";
        $(function () {

            refreshComment(initcommentUrl);

            $(document).on('click', '.refresh', function (e) {
                e.preventDefault();
                refreshComment(initcommentUrl, $('#comments').offset().top - 50, true);
            });
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                refreshComment($(this).attr('href'), $('#comments').offset().top - 50, true);
            });
            $(document).on('click', '.commentlist .reply', function (e) {
                e.preventDefault();
                var nickname = '>' + $(this).parent('.media-reply').prevAll('.nickname').text() + "\n";
                $('#commentform').find('textarea').val(nickname).focus();
                $('#commentform').find('#comment_parent').val($(this).data('id'));
                $("html, body").animate({scrollTop: $('#respond').offset().top}, 600);
            });
            $('#submit').click(function () {
                var self = $(this);
                if (self.hasClass('disabled')) {
                    return false;
                }
                self.addClass('disabled');
                var comment_parent = $('#commentform').find('#comment_parent').val();
                $.ajax({
                    url: "{{ URL::route('homeCommentPost') }}",
                    data: $('#commentform').serialize(),
                    type: "POST",
                    timeout: 30000,
                    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                    async: true,
                    cache: false,
                    success: function (response) {
                        if (response.result !== true) {
                            layer.msg(response.message);
                            return false;
                        }
                        $('#commentform').find('textarea').val('');
                        $('#commentform').find('#comment_parent').val('');
                        if (response.comment_parent == 0) {
                            $('.commentlist').html(response.content);
                            $("html, body").animate({scrollTop: $('#comments').offset().top - 50}, 600);
                        } else {
                            $('#comment-' + response.comment_parent).replaceWith(response.content);
                            $("html, body").animate({scrollTop: $('#comment-' + response.comment_parent).offset().top - 50}, 600);
                        }
                    },
                    complete: function () {
                        self.removeClass('disabled');
                    },
                    error: function () {
                        layer.msg('请求失败请重新提交');
                    }
                });

                return false;
            });

            $(document).on('click', '.comment-delete', function () {
                var comment_id = $(this).closest('.media').data('id');
                layer.confirm('您确定要删除吗？', {
                    icon: 3, title: '提示', btn: ['确定', '取消'] //按钮
                }, function (index) {
                    layer.close(index);
                    $.ajax({
                        url: "{{ URL::route('homeCommentDelete') }}",
                        data: {'comment_ID': comment_id},
                        type: "POST",
                        timeout: 30000,
                        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                        async: true,
                        cache: false,
                        success: function (response) {
                            if (response.result !== true) {
                                layer.msg(response.message, {icon: 5});
                                return false;
                            }
                            $('#comment-' + comment_id).slideUp('slow', function () {
                                $(this).remove();
                            });
                            $('#delete').modal('hide');
                        },
                        error: function () {
                            layer.msg('请求失败请重新提交', {icon: 5});
                        }
                    });
                }, function (index) {
                    layer.close(index);
                });

                return false;
            });

            uParse('#content', {
                rootPath: '{{ URL::asset('/') }}auth/js/plugins/ueditor/', //ueditor所在的路径，这个要给出，让uparse能找到third-party目录
                //因为需要引入目录下的那些js文件，当然会根据你的编辑数据，按需加载
                liiconpath: 'http://bs.baidu.com/listicon/', //自定义列表标号图片的地址，默认是这个地址
                listDefaultPaddingLeft: '20' //自定义列表标号与文字的横向间距
            })
        })

        function refreshComment(url, scrollTop, showMessage) {
            if (showMessage) {
                var index = layer.load();
            }
            $.ajax({
                url: url,
                data: {},
                type: "GET",
                timeout: 30000,
                async: true,
                cache: false,
                success: function (response) {
                    $('.commentlist').html(response);
                    currentCommentUrl = url;
                    if (scrollTop) {
                        $("html, body").animate({scrollTop: scrollTop}, 600);
                    }
                    if (showMessage) {
                        layer.msg('数据更新成功', {icon: 1});
                    }
                },
                complete: function () {
                    layer.close(index);
                },
                error: function () {
                    layer.msg('请求失败请重新提交', {icon: 5});
                }
            });
        }
    </script>
@endsection