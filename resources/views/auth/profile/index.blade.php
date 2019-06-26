@extends('auth.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/') }}auth/css/plugins/webuploader/webuploader.css">
@endsection

@section('contents')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>基本资料</h2>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-sm-12">
                                <form role="form" id="profile-base-form" action="{{ URL::route('authProfileEdit') }}">
                                    <div class="widget-head-color-box navy-bg p-lg text-center">
                                        <div class="m-b-md">
                                            <h2 class="font-bold no-margins">{{ Auth::getUser()->user_name }}</h2>
                                            <small>{{ Auth::getUser()->user_name }}</small>
                                        </div>

                                        <div id="fileList">
                                            <img id="avatar" class="img-circle circle-border m-b-md"
                                                 src="{{ show_avatar(Auth::getUser()->avatar) }}?imageView2/1/w/128/h/128">
                                        </div>
                                        <div id="filePicker">选择图片</div>
                                    </div>
                                    <div class="widget-text-box">
                                        <div class="form-group">
                                            <label>电话号码</label>
                                            <input type="text" name="phone" class="form-control" placeholder="请输入您的电话号码"
                                                   value="{{ Auth::getUser()->phone }}">
                                        </div>
                                        <div class="form-group">
                                            <label>邮箱</label>
                                            <input type="email" name="email" class="form-control"
                                                   placeholder="请输入您的E-mail" value="{{ Auth::getUser()->email }}">
                                        </div>
                                        <div>
                                            <button class="btn btn-block  btn-primary" type="submit"><strong>编 辑</strong></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>修改密码</h2>
                    </div>
                    <div class="ibox-content">
                        <form class="form-horizontal" id="profile-password-form"
                              action="{{ URL::route('authProfilePasswordreset') }}">
                            <p>欢迎登录本站(⊙o⊙)</p>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">登录密码：</label>

                                <div class="col-sm-8">
                                    <input type="password" class="form-control" name="password_login"
                                           placeholder="登录密码">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">密码：</label>

                                <div class="col-sm-8">
                                    <input type="password" class="form-control" name="password" placeholder="新密码">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">确认密码：</label>

                                <div class="col-sm-8">
                                    <input type="password" class="form-control" name="password_confirmation"
                                           placeholder="确认密码">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-8">
                                    <button type="submit" class="btn btn-sm btn-white">修 改</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('/') }}auth/js/plugins/webuploader/webuploader.min.js"></script>
    <script src="{{ URL::asset('/') }}auth/js/demo/webuploader-single.js"></script>
    <script>
        $(function () {
            var pick = '#filePicker';
            var uploader = WebUploader.create({
                // 选完文件后，是否自动上传。
                auto: true,
                // 文件接收服务端。
                server: '{{ URL::route("authProfileAvatarUpload") }}',
                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: {'id': pick, 'multiple': false},
                fileNumLimit: 1,
                formData: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                // 只允许选择图片文件。
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                }
            });
            initWebUploader(uploader, {'inputName': 'avatar', 'imageClass': 'img-circle circle-border m-b-md'});

            $('#profile-base-form').submit(function () {
                $.ajax({
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    type: "POST",
                    timeout: 30000,
                    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                    async: true,
                    cache: false,
                    success: function (response) {
                        if (response.result !== true) {
                            toastr.error(response.message, "温馨提示");
                            return false;
                        }
                        toastr.success('编辑成功', "温馨提示");
                    },
                    error: function () {
                        toastr.error("请求失败请重新提交", "温馨提示");
                    }
                });

                return false;
            })

            $('#profile-password-form').submit(function () {
                $.ajax({
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    type: "POST",
                    timeout: 30000,
                    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                    async: true,
                    cache: false,
                    success: function (response) {
                        if (response.result !== true) {
                            toastr.error(response.message, "温馨提示");
                            return false;
                        }
                        toastr.success('修改成功', "温馨提示");
                        setTimeout(function () {
                            location.reload(true);
                        }, 1000);
                    },
                    error: function () {
                        toastr.error("请求失败请重新提交", "温馨提示");
                    }
                });

                return false;
            })
        })
    </script>
@endsection