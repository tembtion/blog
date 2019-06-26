@extends('admin.base')

@section('contents')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>添加管理员</h5>
                    </div>
                    <div class="ibox-content">
                        <form class="form-horizontal m-t" id="signupForm" action=“{{ URL::route('adminUsersAdd') }}” >
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <label class="col-sm-3 control-label">电话号码：</label>
                                <div class="col-sm-8">
                                    <input id="phone" name="phone" class="form-control" type="text" aria-required="true" aria-invalid="true" class="error">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">邮箱：</label>
                                <div class="col-sm-8">
                                    <input id="email" name="email" class="form-control" type="text" aria-required="true" aria-invalid="true" class="error">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">用户名：</label>
                                <div class="col-sm-8">
                                    <input id="username" name="user_name" class="form-control" type="text" aria-required="true" aria-invalid="true" class="error">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">密码：</label>
                                <div class="col-sm-8">
                                    <input id="password" name="password" class="form-control" type="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">确认密码：</label>
                                <div class="col-sm-8">
                                    <input id="confirm_password" name="password_confirmation" class="form-control" type="password">
                                    <span class="help-block m-b-none"><i class="fa fa-info-circle"></i> 请再次输入您的密码</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-8 col-sm-offset-3">
                                    <button class="btn btn-primary" type="submit">提交</button>
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
<script>
$(function(){
    $('#signupForm').submit(function(){
        var url = "{{ URL::route('adminUsersAdd') }}";
        var data = $(this).serialize();
        $.post(url, data, function(response){
            if (response.result !== true) {
                toastr.error(response.message, "温馨提示");
                return false;
            }
            toastr.success('添加成功', "温馨提示");
            setTimeout(function(){
                location.reload(true);
            }, 1000);
        })
        return false;
    })
})
</script>
@endsection