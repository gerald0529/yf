/**
 * Created by rd04 on 2016/5/29.
 */

// $(function() {
/*
 * data is Object
 * thumbnailWidth
 * thumbnailHeight
 * imageContainer (Selector image)
 * uploadButton (Selector div)
 * */

UploadImage = function (uploadArguments) {
    var _this = this;
    this.arguments = uploadArguments;

    /*$.ajax({
        type: "get",
        url: SHOP_BASE_URL + "?ctl=Upload&action=config",
        dataType: "jsonp",
        jsonp: "callback",//传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名(一般默认为:callback)
        // jsonpCallback:"getConfig",//自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名，也可以写"?"，jQuery会自动为你处理数据
        success: function(data){
            uploadConfig = data;
            UploadImage.uploadConfig = uploadConfig;
            _this.init();
        },
        error: function(){
            alert('加载配置文件失败!');
        }
    });*/

        if (typeof(uploadConfig) === 'undefined'){

        $.ajax({
            type: "get",
            url: SITE_URL + "?ctl=Upload&action=config",
            dataType: "jsonp",
            jsonp: "callback",//传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名(一般默认为:callback)
            // jsonpCallback:"getConfig",//自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名，也可以写"?"，jQuery会自动为你处理数据
            success: function(data){
                uploadConfig = data;
                UploadImage.uploadConfig = uploadConfig;
                _this.init();
            },
            error: function(){
                alert('加载配置文件失败!');
            }
        });
    }
    else
    {
        UploadImage.uploadConfig = uploadConfig;
        _this.init();
    }
}

UploadImage.prototype = {
    uploadConfig: {},
    init: function () {
        this.initUploader();
    },
    initUploader: function () {
        var ratio = window.devicePixelRatio || 1,
            thumbnailWidth = this.arguments.thumbnailWidth ? this.arguments.thumbnailWidth * ratio : 113 * ratio,
            thumbnailHeight = this.arguments.thumbnailHeight ? this.arguments.thumbnailHeight * ratio : 113 * ratio,
            uploadButton = this.arguments.uploadButton ? this.arguments.uploadButton : '#uploader',
            $image = this.arguments.imageContainer ? $(this.arguments.imageContainer) : $('#uploadImage'),
            $input = this.arguments.inputHidden ? $(this.arguments.inputHidden) : $('#uploadIpunt'),
            callback = this.arguments.callback ? this.arguments.callback : 0;

            //如果没有图片添加默认图片
            if ( $image.attr('src') == '' ) {
                $image.attr('src', BASE_URL + '/shop/static/common/images/image.png');
            }

            uploader = WebUploader.create({

                auto: true,

                pick: uploadButton,

                accept: {
                    title: 'Images',
                    extensions: UploadImage.uploadConfig.imageManagerAllowFiles.join(',').replace(/\./g, ''),
                    mimeTypes: 'image/*'
                },

                swf: BASE_URL + '/ucenter/static/default/js/Uploader.swf',

                server: SITE_URL + "?ctl=Upload&action=" + UploadImage.uploadConfig.imageActionName,

                fileVal: UploadImage.uploadConfig.imageFieldName,

                duplicate: true,

                fileSingleSizeLimit:  bytes_to_size(UploadImage.uploadConfig.imageMaxSize)

                // fileNumLimit: 1
            });


        // 当有文件添加进来时执行，负责view的创建
        // 当有文件添加进来的时候
        // 只能上传一张图片
        uploader.on('fileQueued', function (file) {

            // 创建缩略图
            uploader.makeThumb(file, function (error, src) {
                if (error) {
                    $image.replaceWith('<span>不能预览</span>');
                    return;
                }

                $image.attr('src', src);


            }, thumbnailWidth, thumbnailHeight);
        });

        uploader.on('uploadSuccess', function (file, response) {

            if (response.state == 'SUCCESS') {
                $input.attr('value', response.url);
            }

            if ( callback && typeof callback == 'function' ) {
                callback(response);
            }
        })
    }
}

function bytes_to_size(bytes) {
    if (bytes === 0) return '0 B';
    var k = 1000, // or 1024
        sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
        i = Math.floor(Math.log(bytes) / Math.log(k));
    return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
}
// });