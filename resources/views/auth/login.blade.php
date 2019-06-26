@extends('auth.base')

@section('css')
    <link href="{{ URL::asset('/') }}auth/css/login.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ URL::asset('/') }}home/css/aa.css" type="text/css" media="all">
@endsection

@section('contents')
    <div id="particles-js" style="position: absolute;top:0;left:0;"></div>
    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name"><a href="{{ URL::route('homeTopIndex') }}">B</a></h1>
            </div>
            <h3>欢迎登录312blog管理系统</h3>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form class="m-t" role="form" action="{{ URL::route('authAuthPostLogin') }}" method="post">
                {!! csrf_field() !!}
                <div class="form-group">
                    <input type="text" name="email" class="form-control text-muted" placeholder="邮箱" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control text-muted" placeholder="密码" required="">
                </div>
                <div class="form-group" style="text-align:left!important">
                    <label><input type="checkbox" name="remember" class="i-checks">自动登录</label>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>
                {{--<p class="text-muted text-center"> <a href="{{ URL::route('authAuthGetRegister') }}">注册一个新账号</a>--}}
                <p class="text-muted text-center">
                    <a href="{{ $weiboAuthorizeUrl }}">
                        <img src="{{ URL::asset('/') }}auth/img/loginButton_24.png">
                    </a>
                </p>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ URL::asset('/') }}auth/js/particles.js"></script>
    <script type="text/javascript" src="{{ URL::asset('/') }}auth/js/particles.config.js"></script>
    <script>
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    </script>
@endsection

