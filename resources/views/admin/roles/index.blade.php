@extends('admin.base')

@section('contents')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>角色一览</h5>
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
                                <th>角色名称</th>
                                <th>角色描述</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($result['data'] as $value)
                            <tr class="gradeX">
                                    <th><input type="checkbox" class="btSelect" name="ids[]" value="{{ $value->role_id }}"></th>
                                    <td>{{ $value->role_id }}</td>
                                    <td>{{ $value->role_name }}</td>
                                    <td>{{ $value->role_describe }}</td>
                                    <td><a href="javascript:;" class="edit" 
                                            data-role_id="{{ $value->role_id }}" 
                                            data-role_name="{{ $value->role_name }}" 
                                            data-role_describe="{{ $value->role_describe }}" 
                                            data-action="{{ $value->action_list }}">编辑</a>
                                    </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5"><span class="pull-right">{!! $result['data']->render() !!}</span></td>
                            </tr>
                        </tfoot>
                    </table>
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
                <h4 class="modal-title">添加角色</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="get">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">角色名称</label>

                        <div class="col-sm-10">
                            <input type="text" name="role_name" class="form-control">
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">角色描述</label>
                        <div class="col-sm-10">
                            <input type="text" name="role_describe" class="form-control"> <span class="help-block m-b-none">帮助文本，可能会超过一行，以块级元素显示</span>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">角色权限</label>
                        <div class="col-sm-10">
                        <ul class="todo-list m-t small-list ui-sortable">
                            @foreach ($result['menu'] as $menu)
                            <li class="menu">
                                <label>
                                <input type="checkbox" id="action{{ $menu['level1']->id }}" name="action[]" class="level1" value="{{ $menu['level1']->id }}">
                                <span class="m-l-xs">{{ $menu['level1']->title }}</span>
                                </label>
                                <ul class="todo-list m-t small-list ui-sortable">
                                    @foreach ($menu['level2'] as $level2)
                                    <li>
                                        <label>
                                        <input type="checkbox" id="action{{ $level2->id }}" name="action[]" class="level2" value="{{ $level2->id }}">
                                        <span class="m-l-xs">{{ $level2->title }}</span>
                                        </label>
                                    </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endforeach
                        </ul>
                        </div>
                    </div>
                    <input type="hidden" name="role_id">
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
    $('.level1').click(function(){
        var checked = false;
        if ($(this).is(':checked')) {
            checked = true;
        }
        $(this).closest('.menu').find('input[type="checkbox"]').prop('checked', checked);
    })

    $('.level2').click(function(){
        if ($(this).is(':checked')) {
            $(this).closest('.menu').find('.level1').prop('checked', true);
        } else if ($(this).closest('ul').find('.level2:checked').size() == 0) {
            $(this).closest('.menu').find('.level1').prop('checked', false);
        }
    })

    $('.add').click(function(){
        var url = "{{ URL::route('adminRolesAdd') }}";
        $('#reset').click();
        $('#modal-box').find('form').attr('action', url);
        $('#modal-box').modal();
    })

    $('.edit').click(function(){
        var url = "{{ URL::route('adminRolesEdit') }}";
        $('#reset').click();
        $('#modal-box').find('form').attr('action', url);
        $.each($(this).data(), function(key, value){
            $('#modal-box').find('[name="' + key + '"]').val(value);
        })
        var action = $(this).data('action');
        var action_list = action.split(",");
        $.each(action_list, function(key, value){
            $('#modal-box').find('#action' + value).prop('checked', true);
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
                    url: "{{ URL::route('adminRolesDelete') }}", 
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