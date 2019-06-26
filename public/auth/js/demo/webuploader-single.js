function initWebUploader(uploader, settings) {
    if( !settings) {
        settings = {};
    }
    var pick = settings.pick || '#filePicker';
    var fileList = settings.fileList || '#fileList';
    var imageClass = settings.imageClass || '';
    var imageWith = settings.w || '128';
    var imageHeight = settings.h || '';
    var inputName = settings.inputName || 'image';

    // 当有文件添加进来的时候
    uploader.on('fileQueued', function( file ) {
        $(pick).find('.webuploader-pick').text('上传中...').nextAll().hide();
    });
    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on( 'uploadAccept', function( file, ret) {
        if (ret.state !== 'SUCCESS') {
            return false;
        }
        return true;
    });
    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on( 'uploadSuccess', function(file, resopnse) {
        var imageView = '?imageView2/1';
        if (imageWith) {
            imageView += '/w/' + imageWith;
        }
        if (imageHeight) {
            imageView += '/h/' + imageHeight;
        }
        var $li = $(
                '<div id="' + file.id + '" >' +
                    '<img class="'+imageClass+'" src="'+ resopnse.url + imageView +'">' +
                    '<input type="hidden" name="'+inputName+'" value="'+ resopnse.key +'">' +
                '</div>'
                );
        $(fileList).html($li);
    });
    // 文件上传失败，现实上传出错。
    uploader.on( 'uploadError', function( file ) {
        toastr.error("上传失败", "温馨提示");
    });
    uploader.on( 'uploadComplete', function( file ) {
        $(pick).find('.webuploader-pick').text('选择图片').nextAll().show();
        uploader.removeFile(file, true);
    });
}