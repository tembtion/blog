@extends('home.common.base')

@section('title', '首页')

@section('content')
    <div role="alert" class="alert alert-success alert-dismissible fade in">
        <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4>通知</h4>
        <p>20160407第一版上线.</p>
    </div>
    <div id="content">
        @if ($posts)
            @include('home.common.content', ['post' => $posts])

            <div id='pagenav'><a href='{{ URL::route('homeTopAjax', ['page' => 2]) }}'></a></div>
        @else
            <div class="alert alert-warning text-center" role="alert">
                <span class="fa fa-exclamation-circle" aria-hidden="true"></span>
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