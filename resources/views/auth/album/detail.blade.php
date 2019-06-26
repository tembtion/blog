@extends('auth.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/') }}auth/css/plugins/webuploader/webuploader.css">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/') }}auth/css/demo/webuploader-demo.min.css">
@endsection

@section('contents')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h2>{{ $album->album_name }}</h2>
                    </div>
                    <div class="ibox-content">
                        <div role="group" id="exampleTableEventsToolbar" class="btn-group hidden-xs">
                            <a href="{{ URL::route('authAlbumIndex') }}" class="btn btn-outline btn-default add" type="button">
                                <i aria-hidden="true" class="fa fa-list"></i>
                            </a>
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
                        @if (count($photo) > 0)
                            <table class="footable table table-stripped footable-loaded" id="editable">
                                <thead>
                                <tr>
                                    <th style="width: 36px; " class="bs-checkbox ">
                                        <div class="th-inner "><input type="checkbox" class="btSelectAll" name="btSelectAll"></div>
                                    </th>
                                    <th>图片</th>
                                    <th>图片名称</th>
                                    <th>创建时间</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($photo as $value)
                                    <tr class="gradeX">
                                        <th><input type="checkbox" class="btSelect" name="ids[]" value="{{ $value->photo_id }}"></th>
                                        <td><img src="{{ show_photo($value->photo_key) }}?imageView2/1/w/100/q/100"></td>
                                        <td>{{ $value->photo_name }}</td>
                                        <td>{{ $value->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5"><span class="pull-right">{!! $photo->render() !!}</span></td>
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
    {{--<div class="wrapper wrapper-content animated fadeIn">--}}
        {{--<div class="row">--}}
            {{--<div class="col-sm-12">--}}
                {{--<div class="ibox float-e-margins">--}}
                    {{--<div class="ibox-title">--}}
                        {{--<h2>多媒体</h2>--}}
                    {{--</div>--}}
                    {{--<div class="ibox-content">--}}
                        {{--<div class="page-container">--}}
                            {{--<p>您可以尝试文件拖拽，使用QQ截屏工具，然后激活窗口后粘贴，或者点击添加图片按钮.</p>--}}

                            {{--<div id="uploader" class="wu-example">--}}
                                {{--<div class="queueList">--}}
                                    {{--<div id="dndArea" class="placeholder">--}}
                                        {{--<div id="filePicker"></div>--}}
                                        {{--<p>或将照片拖到这里，单次最多可选300张</p>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="statusBar" style="display:none;">--}}
                                    {{--<div class="progress">--}}
                                        {{--<span class="text">0%</span>--}}
                                        {{--<span class="percentage"></span>--}}
                                    {{--</div>--}}
                                    {{--<div class="info"></div>--}}
                                    {{--<div class="btns">--}}
                                        {{--<div id="filePicker2"></div>--}}
                                        {{--<div class="uploadBtn">开始上传</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    <div class="modal inmodal fade" id="modal-box" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">添加图片</h4>
                </div>
                <div class="modal-body">
                    <div class="page-container">
                        <p>您可以尝试文件拖拽，使用QQ截屏工具，然后激活窗口后粘贴，或者点击添加图片按钮.</p>

                        <div id="uploader" class="wu-example">
                            <div class="queueList">
                                <div id="dndArea" class="placeholder">
                                    <div id="filePicker"></div>
                                    <p>或将照片拖到这里，单次最多可选300张</p>
                                </div>
                            </div>
                            <div class="statusBar" style="display:none;">
                                <div class="progress">
                                    <span class="text">0%</span>
                                    <span class="percentage"></span>
                                </div>
                                <div class="info"></div>
                                <div class="btns">
                                    <div id="filePicker2"></div>
                                    <div class="uploadBtn">开始上传</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('/') }}auth/js/plugins/webuploader/webuploader.min.js"></script>
    <script>
        $(function () {
            var delete_url = "{{ URL::route('authPhotoDelete') }}";
            var index_url = "{{ URL::route('authAlbumDetail', array('album_id' => $album->album_id)) }}";

            $('.add').click(function(){
                $('#reset').click();
                $('#modal-box').modal();
            })

            $('#modal-box').on('shown.bs.modal', function (e) {

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
                                            location.href = index_url;
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
















            function e(e) {
                var a = o('<li id="' + e.id + '"><p class="title">' + e.name + '</p><p class="imgWrap"></p><p class="progress"><span></span></p></li>'),
                        s = o('<div class="file-panel"><span class="cancel">删除</span><span class="rotateRight">向右旋转</span><span class="rotateLeft">向左旋转</span></div>').appendTo(a),
                        i = a.find("p.progress span"),
                        t = a.find("p.imgWrap"),
                        r = o('<p class="error"></p>'),
                        d = function (e) {
                            switch (e) {
                                case "exceed_size":
                                    text = "文件大小超出";
                                    break;
                                case "interrupt":
                                    text = "上传暂停";
                                    break;
                                default:
                                    text = "上传失败，请重试"
                            }
                            r.text(text).appendTo(a)
                        };
                "invalid" === e.getStatus() ? d(e.statusText) : (t.text("预览中"), n.makeThumb(e,
                        function (e, a) {
                            if (e) return void t.text("不能预览");
                            var s = o('<img src="' + a + '">');
                            t.empty().append(s)
                        },
                        v, b), w[e.id] = [e.size, 0], e.rotation = 0),
                        e.on("statuschange",
                                function (t, n) {
                                    "progress" === n ? i.hide().width(0) : "queued" === n && (a.off("mouseenter mouseleave"), s.remove()),
                                            "error" === t || "invalid" === t ? (console.log(e.statusText), d(e.statusText), w[e.id][1] = 1) : "interrupt" === t ? d("interrupt") : "queued" === t ? w[e.id][1] = 0 : "progress" === t ? (r.remove(), i.css("display", "block")) : "complete" === t && a.append('<span class="success"></span>'),
                                            a.removeClass("state-" + n).addClass("state-" + t)
                                }),
                        a.on("mouseenter",
                                function () {
                                    s.stop().animate({
                                        height: 30
                                    })
                                }),
                        a.on("mouseleave",
                                function () {
                                    s.stop().animate({
                                        height: 0
                                    })
                                }),
                        s.on("click", "span",
                                function () {
                                    var a, s = o(this).index();
                                    switch (s) {
                                        case 0:
                                            return void n.removeFile(e);
                                        case 1:
                                            e.rotation += 90;
                                            break;
                                        case 2:
                                            e.rotation -= 90
                                    }
                                    x ? (a = "rotate(" + e.rotation + "deg)", t.css({
                                        "-webkit-transform": a,
                                        "-mos-transform": a,
                                        "-o-transform": a,
                                        transform: a
                                    })) : t.css("filter", "progid:DXImageTransform.Microsoft.BasicImage(rotation=" + ~~(e.rotation / 90 % 4 + 4) % 4 + ")")
                                }),
                        a.appendTo(l)
            }

            function a(e) {
                var a = o("#" + e.id);
                delete w[e.id],
                        s(),
                        a.off().find(".file-panel").off().end().remove()
            }

            function s() {
                var e, a = 0,
                        s = 0,
                        t = f.children();
                o.each(w,
                        function (e, i) {
                            s += i[0],
                                    a += i[0] * i[1]
                        }),
                        e = s ? a / s : 0,
                        t.eq(0).text(Math.round(100 * e) + "%"),
                        t.eq(1).css("width", Math.round(100 * e) + "%"),
                        i()
            }

            function i() {
                var e, a = "";
                "ready" === k ? a = "选中" + m + "张图片，共" + WebUploader.formatSize(h) + "。" : "confirm" === k ? (e = n.getStats(), e.uploadFailNum && (a = "已成功上传" + e.successNum + "张照片至XX相册，" + e.uploadFailNum + '张照片上传失败，<a class="retry" href="#">重新上传</a>失败图片或<a class="ignore" href="#">忽略</a>')) : (e = n.getStats(), a = "共" + m + "张（" + WebUploader.formatSize(h) + "），已上传" + e.successNum + "张", e.uploadFailNum && (a += "，失败" + e.uploadFailNum + "张")),
                        p.html(a)
            }

            function t(e) {
                var a;
                if (e !== k) {
                    switch (c.removeClass("state-" + k), c.addClass("state-" + e), k = e) {
                        case "pedding":
                            u.removeClass("element-invisible"),
                                    l.parent().removeClass("filled"),
                                    l.hide(),
                                    d.addClass("element-invisible"),
                                    n.refresh();
                            break;
                        case "ready":
                            u.addClass("element-invisible"),
                                    o("#filePicker2").removeClass("element-invisible"),
                                    l.parent().addClass("filled"),
                                    l.show(),
                                    d.removeClass("element-invisible"),
                                    n.refresh();
                            break;
                        case "uploading":
                            o("#filePicker2").addClass("element-invisible"),
                                    f.show(),
                                    c.text("暂停上传");
                            break;
                        case "paused":
                            f.show(),
                                    c.text("继续上传");
                            break;
                        case "confirm":
                            if (f.hide(), c.text("开始上传").addClass("disabled"), a = n.getStats(), a.successNum && !a.uploadFailNum) return void t("finish");
                            break;
                        case "finish":
                            a = n.getStats();
                            if (!a.uploadFailNum) {
                                k = "done";
                                location.reload()
                            }
                    }
                    i()
                }
            }

            var n,
                    o = jQuery,
                    r = o("#uploader"),
                    l = o('<ul class="filelist"></ul>').appendTo(r.find(".queueList")),
                    d = r.find(".statusBar"),
                    p = d.find(".info"),
                    c = r.find(".uploadBtn"),
                    u = r.find(".placeholder"),
                    f = d.find(".progress").hide(),
                    m = 0,
                    h = 0,
                    g = window.devicePixelRatio || 1,
                    v = 110 * g,
                    b = 110 * g,
                    k = "pedding",
                    w = {},
                    x = function () {
                        var e = document.createElement("p").style,
                                a = "transition" in e || "WebkitTransition" in e || "MozTransition" in e || "msTransition" in e || "OTransition" in e;
                        return e = null,
                                a
                    }();
            if (!WebUploader.Uploader.support()) throw alert("Web Uploader 不支持您的浏览器！如果你使用的是IE浏览器，请尝试升级 flash 播放器"),
                    new Error("WebUploader does not support the browser you are using.");
            n = WebUploader.create({
                pick: {
                    id: "#filePicker",
                    label: "点击选择图片"
                },
                // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
                resize: false,
                dnd: "#uploader .queueList",
                paste: document.body,
                accept: {
                    title: "Images",
                    extensions: "gif,jpg,jpeg,bmp,png",
                    mimeTypes: "image/*"
                },
                swf: "{{ URL::asset('/') }}js/plugins/webuploader/Uploader.swf",
                formData: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'album_id' : "{{ $album->album_id }}"
                },
                disableGlobalDnd: !0,
                chunked: !0,
                server: "{{ URL::route('authPhotoUpload') }}",
                fileNumLimit: 300,
                fileSizeLimit: 1115242880,
                fileSingleSizeLimit: 1115242880
            }),
                    n.on('uploadAccept', function (file, response) {
                        if (response.state !== 'SUCCESS') {
                            return false;
                        }
                        return true;
                    });
            n.addButton({
                id: "#filePicker2",
                label: "继续添加"
            }),
                    n.onUploadProgress = function (e, a) {
                        var i = o("#" + e.id),
                                t = i.find(".progress span");
                        t.css("width", 100 * a + "%"),
                                w[e.id][1] = a,
                                s()
                    },
                    n.onFileQueued = function (a) {
                        m++,
                                h += a.size,
                        1 === m && (u.addClass("element-invisible"), d.show()),
                                e(a),
                                t("ready"),
                                s()
                    },
                    n.onFileDequeued = function (e) {
                        m--,
                                h -= e.size,
                        m || t("pedding"),
                                a(e),
                                s()
                    },
                    n.on("all",
                            function (e) {
                                switch (e) {
                                    case "uploadFinished":
                                        t("confirm");
                                        break;
                                    case "startUpload":
                                        t("uploading");
                                        break;
                                    case "stopUpload":
                                        t("paused")
                                }
                            }),
                    n.onError = function (e) {
                        alert("Eroor: " + e)
                    },
                    c.on("click",
                            function () {
                                return o(this).hasClass("disabled") ? !1 : void("ready" === k ? n.upload() : "paused" === k ? n.upload() : "uploading" === k && n.stop())
                            }),
                    p.on("click", ".retry",
                            function () {
                                n.retry()
                            }),
                    p.on("click", ".ignore",
                            function () {
                                alert("todo")
                            }),
                    c.addClass("state-" + k),
                    s()
        });
    </script>
@endsection