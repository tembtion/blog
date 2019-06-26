@extends('auth.base')

@section('bodyClass', 'signin')

@section('css')
    <link href="{{ URL::asset('/') }}auth/css/login.min.css" rel="stylesheet">
@endsection

@section('contents')
    <div class="middle-box text-center loginscreen   animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">B</h1>

            </div>
            <h3>欢迎注册</h3>
            <p>创建一个新账户</p>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
            <form class="m-t" role="form" action="{{ URL::route('authAuthPostRegister') }}" method="post">
                {!! csrf_field() !!}
                <div class="form-group">
                    <input type="text" class="form-control text-muted" name="name" placeholder="请输入用户名" required="">
                </div>
                <div class="form-group">
                    <input type="email" class="form-control text-muted" name="email" placeholder="请输入邮箱" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control text-muted" name="password" placeholder="请输入密码" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control text-muted" name="password_confirmation" placeholder="请再次输入密码" required="">
                </div>

                <button type="submit" class="btn btn-primary block full-width m-b">注 册</button>

                <p class="text-muted text-center"><small>已经有账户了？</small><a href="{{ URL::route('authAuthGetLogin') }}">点此登录</a>
                </p>

            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
    </script>
@endsection
