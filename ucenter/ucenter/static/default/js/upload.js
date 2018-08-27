/**
 * Created by xinze on 16/3/30.
 */


// 图片上传demo
jQuery(function() {

    function bytes_to_size(bytes) {
        if (bytes === 0) return '0 B';
        var k = 1024, // or 1024
            sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
            i = Math.floor(Math.log(bytes) / Math.log(k));
        return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
    }

    $.ajax({
        type: "get",
        url:SITE_URL + "?ctl=Upload&action=config",
        dataType: "jsonp",
        jsonp: "callback",//传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名(一般默认为:callback)
        // jsonpCallback:"getConfig",//自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名，也可以写"?"，jQuery会自动为你处理数据
        success: function(data){
            uploadImage(data);
        },
        error: function(){
            alert('加载配置文件失败!');
        }
    });

    function uploadImage (uploadConfig){
        var $ = jQuery,
            $list = $('#fileList'),
        // 优化retina, 在retina下这个值是2
            ratio = window.devicePixelRatio || 1,

        // 缩略图大小
            thumbnailWidth = 100 * ratio,
            thumbnailHeight = 100 * ratio,

        // Web Uploader实例
            uploader;

        // 初始化Web Uploader

        uploader = WebUploader.create({

            // 自动上传。
            auto: true,

            swf: BASE_URL + 'shop/static/common/js/uploader.swf',
            // 文件接收服务端
            server:SITE_URL + "?ctl=Upload&action=" + uploadConfig.imageActionName,

            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '.js-file-picker',

            fileVal: uploadConfig.imageFieldName,
            
            fileSingleSizeLimit: bytes_to_size(uploadConfig.imageMaxSize), // 1M

            fileNumLimit: 5,
            // 只允许选择文件，可选。
            accept: {
                title: 'Images',
                extensions: uploadConfig.imageManagerAllowFiles.join(',').replace(/\./g, ''),
                mimeTypes: 'image/*'
            }
        });

        if ( $('.js-file-picker').length > 1 )
        {
            $(document).on('click', '.js-file-picker', function () {
                //当前list
                $list = $(this).next().find('.uploader-list');
            });
        }

        // 当有文件添加进来的时候
        uploader.on( 'fileQueued', function( file ) {
            var $li = $(
                    '<div id="' + file.id + '" class="file-item thumbnail">' +
                    '<img>' +
                    '<div class="info">' + file.name + '</div>' +
                    '</div>'
                ),
                $img = $li.find('img');

            $list.append( $li );

            // 创建缩略图
            uploader.makeThumb( file, function( error, src ) {
                if ( error ) {
                    $img.replaceWith('<span>不能预览</span>');
                    return;
                }

                $img.attr( 'src', src );
            }, thumbnailWidth, thumbnailHeight );
        });

        // 文件上传过程中创建进度条实时显示。
        uploader.on( 'uploadProgress', function( file, percentage ) {
            var $li = $( '#'+file.id ),
                $percent = $li.find('.progress span');

            // 避免重复创建
            if ( !$percent.length ) {
                $percent = $('<p class="progress"><span></span></p>')
                    .appendTo( $li )
                    .find('span');
            }

            $percent.css( 'width', percentage * 100 + '%' );
        });

        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on( 'uploadSuccess', function( file,res ) {
            // console.info(res);
            $( '#'+file.id ).addClass('upload-state-done').data('img_src', res.url);
        });

        // 文件上传失败，现实上传出错。
        uploader.on( 'uploadError', function( file ) {
            var $li = $( '#'+file.id ),
                $error = $li.find('div.error');

            // 避免重复创建
            if ( !$error.length ) {
                $error = $('<div class="error"></div>').appendTo( $li );
            }

            $error.text('上传失败');
        });

        // 完成上传完了，成功或者失败，先删除进度条。
        uploader.on( 'uploadComplete', function( file ) {
            $( '#'+file.id ).find('.progress').remove();
        });
    }

});