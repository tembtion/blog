@extends('admin.base')

@section('contents')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>操作员一览</h5>
                </div>
                <div class="ibox-content">
                    <div role="group" id="exampleTableEventsToolbar" class="btn-group hidden-xs">
                        <button class="btn btn-outline btn-default add" type="button" data-toggle="modal">
                            <i aria-hidden="true" class="glyphicon glyphicon-plus"></i>
                        </button>
                        <button class="btn btn-outline btn-default delete" type="button">
                            <i aria-hidden="true" class="glyphicon glyphicon-trash"></i>
                        </button>
                        <button class="btn btn-outline btn-default refresh" type="button">
                            <i aria-hidden="true" class="glyphicon glyphicon-repeat"></i>
                        </button>
                    </div>

                    <table class="footable table table-stripped footable-loaded" id="editable">
                        <thead>
                            <tr>
                                <th style="width: 36px; " class="bs-checkbox ">
                                    <div class="th-inner "><input type="checkbox" class="btSelectAll" name="btSelectAll"></div>
                                </th>
                                <th>ID</th>
                                <th>角色名</th>
                                <th>用户名</th>
                                <th>电话号码</th>
                                <th>邮箱</th>
                                <th>最后登录时间</th>
                                <th>超级管理员</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($result['users'] as $value)
                            <tr class="gradeX">
                                    <th><input type="checkbox" class="btSelect" name="ids[]" value="{{ $value->id }}"></th>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ isset($result['roles'][$value->role_id]) ? $result['roles'][$value->role_id] : '' }}</td>
                                    <td>{{ $value->user_name }}</td>
                                    <td>{{ $value->phone }}</td>
                                    <td>{{ $value->email }}</td>
                                    <td>{{ $value->created_at }}</td>
                                    <td>
                                            @if ($value->is_sys == 1)
                                                是
                                            @else
                                                否
                                            @endif
                                    </td>
                                    <td><a href="javascript:;" class="edit" 
                                            data-id="{{ $value->id }}" 
                                            data-phone="{{ $value->phone }}" 
                                            data-email="{{ $value->email }}" 
                                            data-role_id="{{ $value->role_id }}" 
                                            data-user_name="{{ $value->user_name }}">编辑</a>
                                    </td>
                            </tr>
                            @endforeach
                        </tbody>
                                
                    </table>
                    <div class="row">
                        <div class="col-sm-12"><span class="pull-right">{!! $result['users']->render() !!}</span></div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="modal inmodal fade" id="modal-box" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">管理员添加</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="get">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">电话号码</label>

                        <div class="col-sm-10">
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">邮箱</label>
                        <div class="col-sm-10">
                            <input type="text" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">用户名</label>
                        <div class="col-sm-10">
                            <input type="text" name="user_name" class="form-control">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">密码</label>
                        <div class="col-sm-10">
                            <input type="password" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">确认密码</label>
                        <div class="col-sm-10">
                            <input type="password" name="password_confirmation" class="form-control"> <span class="help-block m-b-none">帮助文本，可能会超过一行，以块级元素显示</span>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">角色选择</label>
                        <div class="col-sm-10">
                                <select name="role_id" class="form-control m-b">
                                    @foreach ($result['roles'] as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                        </div>
                    </div>

                    <input type="hidden" name="id">
                    <input type="reset" class="hidden" id="reset">
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="modal-submit">保存</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
$(function(){
    $('.add').click(function(){
        var url = "{{ URL::route('adminUsersAdd') }}";
        $('#reset').click();
        $('#modal-box').find('form').attr('action', url);
        $('#modal-box').modal();
    })

    $('.edit').click(function(){
        var url = "{{ URL::route('adminUsersEdit') }}";
        $('#reset').click();
        $('#modal-box').find('form').attr('action', url);
        $.each($(this).data(), function(key, value){
            $('#modal-box').find('[name="' + key + '"]').val(value);
        })

        $('#modal-box').modal();
    })

    $('.btSelectAll').click(function(){
        var checked = false;
        if ($(this).is(':checked')) {
            checked = true;
        }
        $('#editable').find('.btSelect').prop('checked', checked);
    })

    $('.btSelect').click(function(){
        var checked = false;
        if ($('#editable').find('.btSelect').size() == $('#editable').find('.btSelect:checked').size()) {
            checked = true;
        }
        $('#editable').find('.btSelectAll').prop('checked', checked);
    })

    $('.delete').click(function(){
        var ids = new Array();
        $('#editable').find('.btSelect:checked').each(function(){
            ids.push($(this).val());
        })
        if (ids.length < 1) {
            toastr.error("请选择要删除的数据", "温馨提示");
            return false;
        }
        swal({
            title: "您确定要删除这条信息吗",
            text: "删除后将无法恢复，请谨慎操作！",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "是的，我要删除！",
            cancelButtonText: "让我再考虑一下…",
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{ URL::route('adminUsersDelete') }}", 
                    data: {'ids': ids},
                    type: "POST", 
                    timeout: 3000,
                    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                    async:true,
                    cache:false,
                    success: function (response) { 
                        if (response.result !== true) {
                            toastr.error(response.message, "温馨提示");
                            return false;
                        }
                        toastr.success('删除成功', "温馨提示");
                        setTimeout(function(){
                            location.reload(true);
                        }, 1000);
                    },
                    error: function () {
                        toastr.error("请求失败请重新提交", "温馨提示");
                    }
                });
            }
        })

        return false;
    })

    $('.refresh').click(function(){
        location.reload(true);
    })

    $('#modal-submit').click(function(){
        $.ajax({
            url: $('form').attr('action'), 
            data: $('form').serialize(),
            type: "POST", 
            timeout: 3000,
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            async:true,
            cache:false,
            success: function (response) { 
                if (response.result !== true) {
                    toastr.error(response.message, "温馨提示");
                    return false;
                }
                toastr.success('添加成功', "温馨提示");
                setTimeout(function(){
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