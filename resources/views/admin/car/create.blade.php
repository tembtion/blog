@extends('admin.base')

@section('css')
<link rel="stylesheet" href="{{ URL::asset('/') }}third-party/uploadify/uploadify.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ URL::asset('/') }}shamcey/css/bootstrap-fileupload.min.css" type="text/css" />
<link rel="stylesheet" href="{{ URL::asset('/') }}shamcey/prettify/prettify.css" type="text/css" />
<style>
.upload_area {
    text-align:center;
    margin-bottom:20px;
}

.photothumb {
    position:relative;
    background-color: #eaeaea;
    border: 1px solid #ddd;
    border-radius: 4px;
    line-height: 1.42857;
    margin-bottom: 20px;
    transition: border 0.2s ease-in-out 0s;
}

.car_img_area img {
    width:200px;
}

.photothumb img{
    width:200px;
    vertical-align: middle;
}

.photothumb .close{
    position:absolute;
    right:15px;
    top:10px;
}

.uploadify-button {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #f5f5f5;
    background-image: linear-gradient(to bottom, #ffffff, #e6e6e6);
    background-repeat: repeat-x;
    border-color: #cccccc #cccccc #b3b3b3;
    border-image: none;
    border-radius: 0;
    border-style: solid;
    border-width: 1px;
    box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
    color: #333333;
    cursor: pointer;
    display: inline-block;
    font-size: 14px;
    font-weight: normal;
    line-height: 20px;
    margin-bottom: 0;
    padding: 4px 12px;
    text-align: center;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
    vertical-align: middle;
    background: #eee none repeat scroll 0 0;
    font-size: 13px;
    padding: 5px 12px;
    text-shadow: none;
    border-color: #bbb;
    box-shadow: none;
    margin-bottom: 5px;
    background: #0866c6 none repeat scroll 0 0;
    border-color: #0a6bce;
    color: #fff;
    border: none;
    border: none;
    padding: 0;
}

#uploadfileQueue {
    overflow: hidden; 
    width: auto; 
    max-height: 208px;
}
.progress {
    margin-bottom: 0px;
}
</style>
@endsection

@section('breadcrumbs')
    <ul class="breadcrumbs">
        <li><a href="/admin"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
        <li>汽车添加</li>
    </ul>
@endsection

@section('contents')

<div class="tabbedwidget tab-primary">
    <ul>
        <li><a href="#base_info">基本信息</a></li>
        <li><a href="#photo_info">相册信息</a></li>
    </ul>
    <form id="form1" class="stdform" method="post" 
        @if (isset($carInfo))
        action="/admin/car/update/{{ $carInfo->car_id }}" 
        @else
        action="/admin/car/insert" 
        @endif
        enctype="multipart/form-data">
    <div id="base_info">
        {!! csrf_field() !!}
        <div class="par control-group">
            <label class="control-label" for="car_name">汽车名称</label>
            <div class="controls"><input type="text" name="car_name" id="car_name" class="input-large" value="{{ $carInfo->car_name or '' }}" /></div>
        </div>
        <div class="par control-group">
            <label class="control-label" for="car_ename">汽车英文名称</label>
            <div class="controls"><input type="text" name="car_ename" id="car_ename" class="input-large" value="{{ $carInfo->car_ename or '' }}" /></div>
        </div>
        <div class="control-group">
                <label class="control-label" for="car_price">价格</label>
            <div class="controls"><input type="text" name="car_price" id="car_price" class="input-large" value="{{ $carInfo->car_price or '' }}" /></div>
        </div>
        <div class="control-group">
            <label class="control-label">封面</label>
            <div class="controls">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="input-append">

                    <input id="car_img" type="file" name="upload_image"/>
                    <div id="carimgQueue"></div>
                    <div class="par car_img_area">
                    @if (isset($carInfo))
                        <img src="@getPhotoThumb($carInfo->photo_key)" alt="">
                        <input type="hidden" name="car_img" value="{{ $carInfo->photo_id }}">
                    @endif
                    </div>
                    </div>

                </div>


            </div>
        </div>

        <div class="par control-group">
                <label class="control-label" for="car_desc">简介</label>
            <div class="controls">
                <textarea cols="80" rows="5" name="car_desc" class="input-xxlarge" id="car_desc">{{ $carInfo->car_desc or '' }}</textarea>
            </div> 
        </div>
    </div>
    <div id="photo_info">
       <div class="upload_area">
            <div class="entry_wrap">
                <div class="entry_img">
                    <input id="uploadify" type="file" name="upload_image"/>
                    <a id="upload_image_btn" href="javascript:$('#uploadify').uploadify('upload','*')" class="btn btn-primary"> 上传 </a>
                </div>
                <div class="entry_content">
                    <div id="uploadfileQueue" class="mousescroll">
                    </div>
                </div>
            </div>
        </div>

        <div class="photo_area">
            @if (isset($carPhotoInfo))
            @foreach ($carPhotoInfo as $carPhotoItem)
               <div class="photothumb">
                    <img src="@getPhotoThumb($carPhotoItem->photo_key)" alt="{{ $carPhotoItem->photo_name }}">
                    <input type="hidden" name="car_photo[]" value="{{ $carPhotoItem->photo_id }}">
                    <span class="close">×</span>
               </div>
            @endforeach
            @endif
        </div>

    </div>
    <p style="text-align: center;">
    <button class="btn btn-primary"> 保 存 </button>
    </p>
    </form>
</div><!--tabbedwidget-->
@endsection

@section('js')
<script type="text/javascript" src="{{ URL::asset('/') }}third-party/uploadify/jquery.uploadify.js"></script>
<script type="text/javascript" src="{{ URL::asset('/') }}shamcey/js/jquery.slimscroll.js"></script>
<script type="text/javascript" src="{{ URL::asset('/') }}shamcey/prettify/prettify.js"></script>
<script type="text/javascript" src="{{ URL::asset('/') }}shamcey/js/elements.js"></script>
<script type="text/javascript" src="{{ URL::asset('/') }}shamcey/js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('/') }}js/ajaxfileupload.js"></script>
<script type="text/javascript">
jQuery(function(){
    jQuery('#uploadfileQueue').slimscroll({
         color: '#666',
         size: '10px',
         width: 'auto',
         height: '208px'
     });

    jQuery("#car_img").uploadify({
        'swf': '/third-party/uploadify/uploadify.swf',
        'uploader': '/admin/image/upload',
        'formData' : {'_token': jQuery('meta[name="csrf-token"]').attr('content')},
        'folder': 'UploadFile',
        'queueID': 'carimgQueue',
        'buttonText':'选择文件',
        'progressData' : 'speed',
        'auto': true,
        'width': '80',
        'height': '30',
        'multi': false,
        'itemTemplate' :  '<div id="${fileID}" class="uploadify-queue-item">'
                        +   '<div class="cancel">'
                        +       '<a href="javascript:$(\'#${instanceID}\').uploadify(\'cancel\', \'${fileID}\')">X</a>'
                        +   '</div>'
                        +   '<span class="fileName">${fileName} (${fileSize})</span><span class="data"></span>' 
                        +   '<div class="uploadify-progress progress progress-striped active">'						
                        +       '<div class="uploadify-progress-bar bar">'
                        +       '</div>'				
                        +    '</div>'
                        + '</div>',
        'onUploadComplete' : function(file) {

        },
        'onUploadSuccess' : function(file, data, response) {
            var data = eval('(' + data + ')');
            if (data.result == true) {
                var photothumb = '<img src="' + data.image + '" alt="">' + 
                    '<input type="hidden" name="car_img" value="' + data.imageId + '">';
                jQuery('.car_img_area').html(photothumb);
            }
        }
    });
    jQuery("#uploadify").uploadify({
        'swf': '/third-party/uploadify/uploadify.swf',
        'uploader': '/admin/image/upload',
        'formData' : {'_token': jQuery('meta[name="csrf-token"]').attr('content')},
        'folder': 'UploadFile',
        'queueID': 'uploadfileQueue',
        'buttonText':'选择文件',
        'progressData' : 'speed',
        'auto': false,
        'width': '80',
        'height': '30',
        'multi': true,
        'itemTemplate' :  '<div id="${fileID}" class="uploadify-queue-item">'
                        +   '<div class="cancel">'
                        +       '<a href="javascript:$(\'#${instanceID}\').uploadify(\'cancel\', \'${fileID}\')">X</a>'
                        +   '</div>'
                        +   '<span class="fileName">${fileName} (${fileSize})</span><span class="data"></span>' 
                        +   '<div class="uploadify-progress progress progress-striped active">'						
                        +       '<div class="uploadify-progress-bar bar">'
                        +       '</div>'				
                        +    '</div>'
                        + '</div>',
        'onUploadComplete' : function(file) {

        },
        'onUploadSuccess' : function(file, data, response) {
            var data = eval('(' + data + ')');
            if (data.result == true) {
                var photothumb = '<div class="photothumb">' + 
                    '<img src="' + data.image + '" alt="">' + 
                    '<input type="hidden" name="car_photo[]" value="' + data.imageId + '">' + 
                    '<span class="close">×</span></div>';
                jQuery('.photo_area').append(photothumb);
            }
        }
    });
//    jQuery('#car_img').change(function(){
//        jQuery.ajaxFileUpload({
//            url:'/admin/image/upload',
//            secureuri: false,
//            fileElementId: 'car_img',
//            dataType : 'json',
//            data : {'_token': jQuery('meta[name="csrf-token"]').attr('content')},
//            success: function(response){
//                if (response.result !== true) {
//                    alert(response.message);
//                } else {
//                    var photothumb = '<img src="' + response.image + '" alt="">' + 
//                        '<input type="hidden" name="car_img" value="' + response.imageId + '">';
//                    jQuery('.car_img_area').html(photothumb);
//                }
//            },
//            error: function() {
//                alert('上传失败');
//            }
//        });
//    })

//    jQuery('#upload_image_btn').click(function(){
//        if (!jQuery('#upload_image').val()) {
//            alert('请选择一张图片');
//            return false;
//        }
//        jQuery.ajaxFileUpload({
//            url:'/admin/image/upload',
//            secureuri: false,
//            fileElementId: 'upload_image',
//            dataType : 'json',
//            data : {'_token': jQuery('meta[name="csrf-token"]').attr('content')},
//            success: function(response){
//                if (response.result !== true) {
//                    alert(response.message);
//                } else {
//                    var photothumb = '<div class="photothumb">' + 
//                        '<img src="' + response.image + '" alt="">' + 
//                        '<input type="hidden" name="car_photo[]" value="' + response.imageId + '">' + 
//                        '<span class="close">×</span></div>';
//                    jQuery('.photo_area').append(photothumb);
//                }
//            },
//            error: function() {
//                alert('上传失败');
//            }
//        });
//    })

    jQuery(document).on('click', '.photothumb .close', function(){
        var self = this;
        var photo_id = jQuery(self).prev('input').val();
        jQuery.ajax({
            url: '/admin/image/delete', 
            data: {'id': photo_id},
            type: "POST", 
            timeout: 3000,
            headers: {'X-CSRF-Token': jQuery('meta[name="csrf-token"]').attr('content')},
            async:true,
            cache:false,
            success: function (response) { 
                if (response.result !== true) {
                    alert('图片删除失败');
                    return false;
                }
                jQuery(self).closest('.photothumb').slideUp(function(){
                    jQuery(this).remove();
                });
            },
            error: function () {
                alert('请求失败，请重新操作');
            }
        });
    })
})
</script>
@endsection