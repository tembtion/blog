@extends('auth.base')

@section('contents')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>评论一览</h2>
                    </div>
                    <div class="ibox-content">
                        <div role="group" id="exampleTableEventsToolbar" class="btn-group hidden-xs">
                            <button class="btn btn-outline btn-default delete" type="button">
                                <i aria-hidden="true" class="glyphicon glyphicon-trash"></i>
                            </button>
                            <button class="btn btn-outline btn-default refresh" type="button">
                                <i aria-hidden="true" class="glyphicon glyphicon-repeat"></i>
                            </button>
                        </div>
                        <div class="tabs-container m-t">
                            <ul class="nav nav-tabs">
                                <li @if (!isset($filter['comment_approved']))class="active"@endif>
                                    <a aria-expanded="false" href="{{ URL::route('authCommentIndex') }}"> 全部</a>
                                </li>
                                @foreach (config('const.COMMENT_APPROVED') as $value)
                                <li @if (isset($filter['comment_approved']) && $filter['comment_approved'] == $value['value'])class="active"@endif>
                                    <a href="{{ URL::route('authCommentIndex', ['comment_approved' => $value['value']]) }}">{{ $value['name'] }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @if (count($comments) > 0)
                            <table class="footable table table-stripped footable-loaded" id="editable">
                                <thead>
                                <tr>
                                    <th style="width: 36px; " class="bs-checkbox ">
                                        <div class="th-inner "><input type="checkbox" class="btSelectAll" name="btSelectAll"></div>
                                    </th>
                                    <th width="100">作者</th>
                                    <th >评论</th>
                                    <th width="150">回应给</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($comments as $value)
                                    <tr class="gradeX">
                                        <th><input type="checkbox" class="btSelect" name="ids[]" value="{{ $value->comment_ID }}"></th>
                                        <td>
                                            <p><img src="{{ show_avatar($value->user->avatar) }}?imageView2/1/w/32/h/32" class="img-circle" alt="image"></p>
                                            <p>{{ $value->user->name }}</p>
                                            <p>{{ $value->comment_author_IP }}</p>
                                        </td>
                                        <td>
                                            <p>提交于{{ $value->comment_date }}
                                                @if ($value->comment_parent > 0)
                                                    | 回复给{{ $value->parent->user->name }}
                                                @endif
                                            </p>
                                            <p>{!! nl2br(htmlentities($value->comment_content)) !!}</p>
                                        </td>
                                        <td>
                                            <p><a href="{{ URL::route('authPostCreate', ['post_id' => $value->post->post_id]) }}">{{ $value->post->post_title }}</a></p>
                                            <p>
                                                <a target="_blank" href="{{ URL::route('homePostIndex', ['post_id' => $value->post->post_id]) }}">
                                                    查看文章 <span class="badge">{{ number_format($value->post->comment_count) }}</span>
                                                </a>
                                            </p>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5"><span class="pull-right">{!! $comments->render() !!}</span></td>
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
    var delete_url = "{{ URL::route('authCommentDelete') }}";

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
})
</script>
@endsection