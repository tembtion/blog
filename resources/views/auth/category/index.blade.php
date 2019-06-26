@extends('auth.base')

@section('contents')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>分类一览</h2>
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
                        @if ($category)
                            <table class="footable table table-stripped footable-loaded" id="editable">
                                <thead>
                                <tr>
                                    <th style="width: 36px; " class="bs-checkbox ">
                                        <div class="th-inner "><input type="checkbox" class="btSelectAll" name="btSelectAll"></div>
                                    </th>
                                    <th>名称</th>
                                    <th>总数</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($category as $value)
                                    <tr class="gradeX">
                                        <th><input type="checkbox" class="btSelect" name="ids[]" value="{{ $value->term_id }}"></th>
                                        <td>{{ $value->term->name }}</td>
                                        <td>{{ $value->count }}</td>
                                        <td><a href="javascript:;" class="edit"
                                               data-term_id="{{ $value->term_id }}"
                                               data-name="{{ $value->term->name }}"
                                               data-slug="{{ $value->slug }}">编辑</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5"><span class="pull-right">{!! $category->render() !!}</span></td>
                                </tr>
                                </tfoot>
                            </table>
                        @else
                            <div class="alert alert-warning text-center m-t">
                                <i class="fa fa-exclamation-circle"></i> 未检索到数据
                            </div>
                        @endif
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
                    <h4 class="modal-title">添加分类</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" method="get">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">分类名称</label>

                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>

                        <input type="hidden" name="term_id">
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
    var add_url = "{{ URL::route('authCategoryAdd') }}";
    var edit_url = "{{ URL::route('authCategoryEdit') }}";
    var delete_url = "{{ URL::route('authCategoryDelete') }}";

    $('.add').click(function(){
        $('#reset').click();
        $('#modal-box').find('form').attr('action', add_url);
        $('#modal-box').modal();
    })

    $('.edit').click(function(){
        $('#reset').click();
        $('#modal-box').find('form').attr('action', edit_url);
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
                            url: delete_url,
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
        var self = $(this);
        if (self.is(':disabled')) {
            return false;
        }
        self.prop('disabled', true);
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
                    self.prop('disabled', false);
                    return false;
                }
                toastr.success('添加成功', "温馨提示");
                setTimeout(function(){
                    location.reload(true);
                }, 1000);
            },
            error: function () {
                toastr.error("请求失败请重新提交", "温馨提示");
                self.prop('disabled', false);
            }
        });

        return false;
    })

})
</script>
@endsection