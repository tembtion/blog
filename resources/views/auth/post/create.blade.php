@extends('auth.base')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/') }}auth/css/plugins/webuploader/webuploader.css">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/') }}auth/css/demo/webuploader-demo.min.css">
<link href="{{ URL::asset('/') }}auth/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<link href="{{ URL::asset('/') }}auth/css/plugins/clockpicker/clockpicker.css" rel="stylesheet">
@endsection

@section('contents')
<div class="wrapper wrapper-content fadeIn">
    <form class="form-horizontal" action="{{ isset($post) ? URL::route('authPostEdit') : URL::route('authPostAdd') }}">
        <div class="row clearfix">
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>分类目录</h2>
                    </div>
                    <div class="ibox-content">
                        <div class="category-box">
                            @foreach ($category as $value)
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox"
                                           value="{{ $value->term->term_id }}"
                                           name="termTaxonomy[]"
                                           @if (isset($post_category[$value->term->term_id]))
                                           checked
                                           @endif
                                           id="checkbox{{ $value->term->term_id }}">
                                    <label for="checkbox{{ $value->term->term_id }}">
                                        {{ $value->term->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="input-group m-t">
                            <input type="text" class="input input-sm form-control input-category"
                                   placeholder="添加新分类">
                        <span class="input-group-btn">
                            <button class="btn btn-sm btn-white add-category" type="button"><i
                                        class="fa fa-plus"></i> 添加
                            </button>
                        </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>标签</h2>
                    </div>
                    <div class="ibox-content">
                        <div class="tag-box">
                            @if (isset($post_tag))
                                @foreach ($post_tag as $value)
                                    <a class="btn btn-xs btn-danger tag-remove m-r-xs">
                                        <input type="hidden" value="{{ $value->term->term_id }}"
                                               name="termTaxonomy[]">
                                        <i class="fa fa-remove"></i>
                                        {{ $value->term->name }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                        <div class="input-group m-t">
                            <input type="text" class="input input-sm form-control input-tag" placeholder="添加新标签">
                        <span class="input-group-btn">
                                    <button class="btn btn-sm btn-white add-tag" type="button"><i
                                                class="fa fa-plus"></i> 添加
                                    </button>
                        </span>
                        </div>
                        <span class="help-block m-b-none">多个标签请用英文逗号（,）分开</span>

                        <div class="m-t">
                            @foreach ($tag as $value)
                                <a class="btn btn-xs btn-success tag-add" data-id="{{ $value->term->term_id }}"><i
                                            class="fa fa-plus"></i> {{ $value->term->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>发布</h2>
                    </div>
                    <div class="ibox-content">
                        <p><i class="fa fa-map-pin"></i> 状态：<strong id="post-status-show">{{ isset($post) ? $post_status_map[$post->post_status] : '发布' }}</strong>
                            <a data-toggle="collapse" href="#post-status" aria-controls="collapseExample">编辑</a>
                        </p>
                        <div class="collapse" id="post-status">
                            <select class="form-control m-b">
                                @foreach (config('const.POST_STATUS') as $value)
                                    @if ($value['edit'])
                                        <option value="{{ $value['value'] }}">{{ $value['name'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button class="btn btn-primary btn-sm post-status-submit" type="button">确定</button>
                            <button class="btn btn-default btn-sm post-status-cancel" type="button">取消</button>
                            <input type="hidden" name="post_status" value="{{ $post->post_status or '' }}">
                        </div>

                        <p><i class="fa fa-calendar"></i> 发布于：<strong id="post-date-show">{{ $post->post_date or '立即' }}</strong>
                            <a data-toggle="collapse" href="#post-date" aria-controls="collapseExample">编辑</a>
                        </p>
                        <div class="collapse" id="post-date">
                            <div class="input-group date datetimepicker m-b">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="date" class="form-control" disabled value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="input-group date clockpicker m-b" data-autoclose="true">
                                <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                <input type="text" name="time" class="form-control" disabled value="{{ date('H:i') }}">
                            </div>
                            <button class="btn btn-primary btn-sm post-date-submit" type="button">确定</button>
                            <button class="btn btn-default btn-sm post-date-cancel" type="button">取消</button>
                            <input type="hidden" name="post_date" value="{{ $post->post_date or '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-sm-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h2>撰写新文章</h2>
                    </div>
                    <div class="ibox-content">

                        <div class="form-group">
                            <label class="col-sm-2 control-label">标题：</label>

                            <div class="col-sm-10">
                                <input type="text" name="post_title" class="form-control"
                                       value="{{ $post->post_title or '' }}">
                            </div>
                        </div>
                        <!-- 加载编辑器的容器 -->
                        <script id="container" name="post_content" type="text/plain"
                                style="width:100%">{!! $post->post_content or '' !!}</script>
                        <div class="mail-body text-right tooltip-demo">
                            <a href="mailbox.html" class="btn btn-sm btn-primary submit" data-toggle="tooltip"
                               data-placement="top" title="Send"><i class="fa fa-reply"></i>
                                {{ isset($post) ? '更新' : '保存' }}
                            </a>
                            <a href="{{ URL::route('authPostIndex') }}" class="btn btn-white btn-sm"
                               data-toggle="tooltip" data-placement="top" title="Discard email"><i
                                        class="fa fa-arrow-left"></i> 返回列表页</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="post_id" value="{{ $post->post_id or '' }}">
        </div>
    </form>
</div>
@endsection

@section('js')
<script src="{{ URL::asset('/') }}auth/js/plugins/ueditor/ueditor.config.js"></script>
<script src="{{ URL::asset('/') }}auth/js/plugins/ueditor/ueditor.all.min.js"></script>
<script src="{{ URL::asset('/') }}auth/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="{{ URL::asset('/') }}auth/js/plugins/clockpicker/clockpicker.js"></script>
<!-- 实例化编辑器 -->
<script type="text/javascript">
$(function(){
    $("#post-date .datetimepicker").datepicker({
        todayBtn: "linked",
        keyboardNavigation: !1,
        forceParse: !1,
        calendarWeeks: !0,
        autoclose: !0
    });
    $(".clockpicker").clockpicker()

    $('.post-status-submit').click(function(){
        $('input[name="post_status"]').val($('#post-status select').val());
        $('#post-status-show').html($('#post-status option:selected').text());
        $('#post-status').collapse('hide');
    })


    $('.post-status-cancel').click(function(){
        $('input[name="post_status"]').val('');
        $('#post-status-show').html('发布');
        $('#post-status').collapse('hide');
    })

    $('.post-date-submit').click(function(){
        var date_time = $('input[name="date"]').val() + ' ' + $('input[name="time"]').val() + ':00';
        $('input[name="post_date"]').val(date_time);
        $('#post-date-show').html(date_time);
        $('#post-date').collapse('hide');
    })

    $('.post-date-cancel').click(function(){
        $('input[name="post_date"]').val('');
        $('#post-date-show').html('立即');
        $('#post-date').collapse('hide');
    })

    var indexUrl = "{{ URL::route('authPostIndex') }}";
    var ue = UE.getEditor('container', {
        serverUrl: "{{ URL::route('authPostUeditor') }}",
        autoFloatEnabled: true,
        //初始化高度
        initialFrameHeight: 500,
        //自动保存间隔时间
        saveInterval: 60,
        toolbars: [
            [
                'anchor', //锚点
                'undo', //撤销
                'redo', //重做
                'bold', //加粗
                'indent', //首行缩进
                'italic', //斜体
                'underline', //下划线
                'strikethrough', //删除线
                'subscript', //下标
                'fontborder', //字符边框
                'superscript', //上标
                'formatmatch', //格式刷
                'blockquote', //引用
                'pasteplain', //纯文本粘贴模式
                'selectall', //全选
                'horizontal', //分隔线
                'removeformat', //清除格式
                'time', //时间
                'date', //日期
                'link', //超链接
                'unlink', //取消链接
                'inserttable', //插入表格
                'insertrow', //前插入行
                'insertcol', //前插入列
                'mergeright', //右合并单元格
                'mergedown', //下合并单元格
                'deleterow', //删除行
                'deletecol', //删除列
                'splittorows', //拆分成行
                'splittocols', //拆分成列
                'splittocells', //完全拆分单元格
                'deletecaption', //删除表格标题
                'inserttitle', //插入标题
                'mergecells', //合并多个单元格
                'deletetable', //删除表格
                'insertparagraphbeforetable', //"表格前插入行"
                'edittable', //表格属性
                'cleardoc', //清空文档
                'insertcode', //代码语言
                'fontfamily', //字体
                'fontsize', //字号
                'paragraph', //段落格式
                'insertimage', //多图上传
                'edittd', //单元格属性
                'emotion', //表情
                'spechars', //特殊字符
                'searchreplace', //查询替换
                'map', //Baidu地图
                'insertvideo', //视频
                'justifyleft', //居左对齐
                'justifyright', //居右对齐
                'justifycenter', //居中对齐
                'justifyjustify', //两端对齐
                'forecolor', //字体颜色
                'backcolor', //背景色
                'insertorderedlist', //有序列表
                'insertunorderedlist', //无序列表
                'fullscreen', //全屏
                'directionalityltr', //从左向右输入
                'directionalityrtl', //从右向左输入
                'rowspacingtop', //段前距
                'rowspacingbottom', //段后距
                'lineheight', //行间距
                'pagebreak', //分页
                'insertframe', //插入Iframe
                'imagenone', //默认
                'imageleft', //左浮动
                'imageright', //右浮动
                'imagecenter', //居中
                'attachment', //附件
                'edittip ', //编辑提示
                'customstyle', //自定义标题
                'autotypeset', //自动排版
                'touppercase', //字母大写
                'tolowercase', //字母小写
                'background', //背景
                'template', //模板
                'scrawl', //涂鸦
                'music', //音乐
                'drafts', // 从草稿箱加载
                'charts', // 图表
                'source', //源代码
                'preview', //预览
                'help', //帮助
            ]
        ]
    });

    $('.add-category').click(function () {
        var self = $(this);
        $.ajax({
            url: "{{ URL::route('authCategoryAdd') }}",
            data: {'name': $('.input-category').val()},
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
                var html = '<div class="checkbox checkbox-primary"> ' +
                        '<input type="checkbox" name="termTaxonomy[]" checked id="checkbox' + response.id + '" value="' + response.id + '">' +
                        '<label for="checkbox' + response.id + '">' + response.name + '</label>' +
                        '</div>';
                $('.category-box').append(html);
                $('.input-category').val('');

            },
            error: function () {
                toastr.error("请求失败请重新提交", "温馨提示");
            }
        });

        return false;
    })
    $('.add-tag').click(function () {
        var self = $(this);
        $.ajax({
            url: "{{ URL::route('authTagAdd') }}",
            data: {'name': $('.input-tag').val()},
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
                $.each(response.data, function (key, value) {
                    var html = '<a class="btn btn-xs btn-danger tag-remove m-r-xs">' +
                            '<input name="termTaxonomy[]" type="hidden" value="' + key + '">' +
                            '<i class="fa fa-remove"></i> ' + value + '</a>';
                    $('.tag-box').append(html);
                })
                $('.input-tag').val('');
            },
            error: function () {
                toastr.error("请求失败请重新提交", "温馨提示");
            }
        });

        return false;
    })

    $(document).on('click', '.tag-remove', function () {
        $(this).remove();
    })

    $('.tag-add').click(function () {
        var tag_id = $(this).data('id');
        var tag_name = $(this).text();
        if ($('.tag-box').find("input[value='" + tag_id + "']").size() > 0) {
            return false;
        }
        var html = '<a class="btn btn-xs btn-danger tag-remove m-r-xs">' +
                '<input name="termTaxonomy[]" type="hidden" value="' + tag_id + '">' +
                '<i class="fa fa-remove"></i> ' + tag_name + '</a>';
        $('.tag-box').append(html);
    })
    $('.submit').click(function () {
        var self = $(this);
        if (self.hasClass('disabled')) {
            return false;
        }
        self.addClass('disabled');
        $.ajax({
            url: $('form').attr('action'),
            data: $('form').serialize(),
            type: "POST",
            timeout: 30000,
            headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
            async: true,
            cache: false,
            success: function (response) {
                if (response.result !== true) {
                    toastr.error(response.message, "温馨提示");
                    self.removeClass('disabled');
                    return false;
                }
                toastr.success('添加成功', "温馨提示");
                setTimeout(function () {
                    location.href = indexUrl;
                }, 1000);
            },
            error: function () {
                self.removeClass('disabled');
                toastr.error("请求失败请重新提交", "温馨提示");
            }
        });

        return false;
    })
})
</script>
@endsection