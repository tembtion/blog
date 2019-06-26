@extends('home.common.base')

@section('title', '搜索' . $keyword)

@section('content')
    <div class="title">
        <h2>
            <i aria-hidden="true" class="glyphicon glyphicon-search"></i>
            文章搜索：{{ $keyword }}
        </h2>
    </div>
    <div id="content">
    @if (count($posts) > 0)
        @include('home.common.content', ['post' => $posts])

        <div id='pagenav'><a href='{{ URL::route('homePostSearchAjax', ['page' => 2, 's' => $keyword]) }}'></a></div>
    @else
        <div class="alert alert-warning text-center" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            未找到任何数据
        </div>
    @endif
    </div>
@endsection

@section('js')
<script type="text/javascript" src="{{ URL::asset('/') }}home/js/jquery.infinitescroll.min.js"></script>
<script type="text/javascript">
<!--
$(function(){
    $('#content').infinitescroll({
        navSelector  : '#pagenav',
        nextSelector : '#pagenav a',
        itemSelector : ".ibox",
        debug        : false,
        dataType      :'html',
        loading: {
            finishedMsg: '没有更多内容了', //当加载失败，或者加载不出内容之后的提示语
            msgText : '正在加载',    //加载时的提示语
            img : "{{ URL::asset('/') }}home/image/loader.gif",    //加载时的提示语
            speed:1,
        },
        animate : true,
        maxPage : '{{ $posts->lastPage() }}',
    });
})
//-->
</script>
@endsection