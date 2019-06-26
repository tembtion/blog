<!DOCTYPE html>
<html lang="zh-CN">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<meta name="Keywords" content="@yield('keywords', '312博客')">
<meta name="Description" content="@yield('description', '312博客')">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<title>312博客 - @yield('title', '欢迎你')</title>
<link rel="stylesheet" href="{{ URL::asset('/') }}home/css/bootstrap.min.css" type="text/css" media="all">
<link rel="stylesheet" href="{{ URL::asset('/') }}home/css/style.css" type="text/css" media="all">
<link rel="stylesheet" href="{{ URL::asset('/') }}auth/css/plugins/blueimp/css/blueimp-gallery.min.css">
@section('css')
@show
<script type="text/javascript" src="{{ URL::asset('/') }}home/js/jquery-2.1.4.min.js"></script>
</head>

<body>

<header id="header" class="navbar navbar-default navbar-fixed-top" role="banner">
    <div class="container">
        <div class="navbar-header">
            <button aria-expanded="false" data-target="#bs-navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::route('homeTopIndex') }}">312Blog</a>
        </div>
        <div id="bs-navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ URL::route('homeTopIndex') }}">主页</a></li>
                <li><a href="{{ URL::route('homeAlbumIndex') }}">相册</a></li>
            </ul>

            <form class="navbar-form navbar-left" role="search" action="{{ URL::route('homePostSearch') }}" method="get">
                <div class="form-group">
                    <input class="form-search" name="s" placeholder="搜索" type="text">
                    <i class="glyphicon glyphicon-search" id="search"></i>
                </div>
                {!! csrf_field() !!}
            </form>
            <ul class="nav navbar-nav navbar-right">
                @if (!Auth::check())
                    <li><a href="{{ URL::route('authAuthGetLogin') }}">登录</a></li>
                @else
                    <li class="dropdown">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ URL::route('authTopHome') }}">进入后台</a></li>
                            <li><a href="{{ URL::route('authAuthGetLogout') }}">退出</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</header><!-- #header -->
@section('main')
<div id="main" class="container">
    <div class="row">
        <div class="col-md-9 col-xs-12 col-sm-9" role="main">
            @section('content')
            @show
        </div>
        <div class="col-md-3 col-xs-12 col-sm-3" role="complementary">
            @if (!isMobile())
            @include('home.common.category')
            @include('home.common.tag')
            @include('home.common.article')
            @endif
        </div>
    </div>
</div>
@show
@section('footer')
<footer>
    <div class="container flink hidden-xs">
        <div class="row">
            <div class="col-sm-3">
                <h4>内容</h4>
                <ul>
                </ul>
            </div>
            <div class="col-sm-3">
                <h4>关于</h4>
                <ul>
                    <li>关于我们</li>
                </ul>
            </div>
            <div class="col-sm-3">
                <h4>关注</h4>
                <ul>
                </ul>
            </div>
            <div class="col-sm-3">
                <h4>合作</h4>
                <ul>
                    <li>在线QQ：877123557</li>
                    <li>联系邮箱：difashi7896@163.com</li>
                    <li>电话：13820452392</li>
                </ul>
            </div>
        </div>
    </div>
    <p class="copy clearfix">Copyright © 2012-2016 312blog. All Rights Reserved.</p>
</footer>
@show

<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
</div>
<a id="back-to-top" href="javascript:;">
    <i class="glyphicon glyphicon-menu-up"></i>
</a>
<script type="text/javascript" src="{{ URL::asset('/') }}auth/js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('/') }}home/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('/') }}home/plugins/layer/layer.js"></script>
<script type="text/javascript" src="{{ URL::asset('/') }}home/js/common.js"></script>

<script>
$(function () {
    $("#back-to-top").backToTop();
    $('#search').click(function(){
        $(this).closest('form').submit();
    })
});
</script>
@section('js')
@show
</body>
</html>