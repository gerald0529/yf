function bytes_to_size(bytes) {
    if (bytes === 0) return '0 B';
    var k = 1024, // or 1024
        sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
        i = Math.floor(Math.log(bytes) / Math.log(k));
    return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
}

var serverUrl =PAYCENTER_URL + "?ctl=Seller_Album";

/*var album_id = frameElement.api && frameElement.api.data.album_id || 1;*/
$(function ()
{
    var remoteImage,
        uploadImage,
        $confirmButton = $('.js-confirm');

    function initEvent()
    {

        /* 选中图片 */
        $('ul.image-list').on('click', 'li.js-image-item', function ()
        {
            if ($(this).children('div.attachment-selected').length == 1)
            {
                $(this).children('div.attachment-selected').remove();
                if ($('.js-attachment-list-region').find('div.attachment-selected').length == 0)
                {
                    $(".js-confirm").addClass('ui-btn-disabled').removeClass('ui-btn-primary').prop('disabled', true);
                }
            }
            else
            {
                $(this).append('<div class="attachment-selected"><i class="icon-ok icon-white"></i></div>');
                $(".js-confirm").addClass('ui-btn-primary').removeClass('ui-btn-disabled').prop('disabled', false);
            }
        })

        /* 标题事件  */
        $('.js-show-upload-view').on('click', function ()
        {
            $('#image-list').hide(), $('#upload').show();
            $(window.parent.document.getElementById('first-title')).html('选择图片').addClass('unselected-title');
            $(window.parent.document.getElementById('second-title')).html('上传图片').removeClass('unselected-title');

            uploadImage = uploadImage || new UploadImage();
            remoteImage = remoteImage || new RemoteImage();

            $confirmButton.off();
            $confirmButton.on('click', function ()
            {
                uploadImage.uploader.upload();
            })

        })

        /* 返回选择图片 */
        $(window.parent.document.getElementById('first-title')).on('click', function ()
        {
            $('#image-list').show(), $('#upload').hide();
            $(window.parent.document.getElementById('first-title')).html('我的图片').removeClass('unselected-title');
            $(window.parent.document.getElementById('second-title')).html('图标库').addClass('unselected-title');

            $confirmButton.off();
            $confirmButton.on('click', function ()
            {
                dialog.close(true);
            })
        })

    }

    var page = 1;

    function initImageList()
    {
        $.ajax({
            url: serverUrl + "&typ=json&action=" + uploadConfig.imageManagerActionName + '&user=admin',
            data: {page: page, rows: 15, sord: 'asc'},
            success: function (data)
            {
                var data = data.data;
                if (data && data['items'].length > 0)
                {
                    var items = data['items'];
                    $imageUl = $('.js-attachment-list-region').children('ul');
                    for (i = 0; i < items.length; i++)
                    {
                        $imageUl.append('<li class="image-item js-image-item" data-id="' + items[i].upload_id + '">' +
                            '<div class="image-box" style="background-image: url(' + items[i].upload_path + ')"></div>' +
                            '<div class="image-meta">600*400</div>' +
                            '<div class="image-title">' + items[i].upload_name + items[i].upload_mime_type + '</div>' +
                            '</li>')
                    }
                    //初始化分页
                    $('.ui-pagination-total').html('共' + data.totalsize + '条, 每页15条');
                    $('.js-category-item').children('span').html(data.totalsize);
                    if (data.total > 2)
                    {
                        for (i = 1; i <= data.total; i++)
                        {
                            var $a = $('<a href="javascript:;" class="ui-pagination-num ' + (i == data.page ? 'active' : '') + ' " data-page-num="' + i + '">' + i + '</a>');

                            $a.on('click', function ()
                            {
                                $('.js-attachment-list-region').find('li').remove();
                                $('.ui-pagination').children('a').remove();
                                page = $(this).data('page-num');
                                initImageList();
                            });

                            $('.ui-pagination').append($a);
                        }
                        firstVisit = false;
                    }
                }
            }

        });
        $(".js-confirm").off();
        $(".js-confirm").on('click', function ()
        {
            dialog.close(true);
        });
    }


    /* 初始化onok事件 */
    function initButtons()
    {

        /!*准备需要的组件*!/
        var remote,
            parent = window.parent
        list = [];
        //dialog对象
        if (parent.$EDITORUI) {
            dialog = parent.$EDITORUI[window.frameElement.id.replace(/_iframe$/, '')];
        } else {
            dialog = '';
        }

        //当前打开dialog的编辑器实例
        if (dialog)
        {
            editor = (dialog && dialog.editor);
            dialog.onok = function ()
            {

                if (!$('#image-list').is(':hidden'))
                {
                    var imageSelectList = $('.js-attachment-list-region').find('div.attachment-selected');
                    for (i = 0; i < imageSelectList.length; i++)
                    {
                        //var url = $(imageSelectList[i]).prevAll('.image-box')[0].style.backgroundImage.slice(4, -1);
                        var alt = $(imageSelectList[i]).prevAll('.image-title').html();

                        var patt=/\"|\'|\)|\(|url/g;
                        var url = $($(imageSelectList[i]).prevAll('.image-box')[0]).css('background-image').replace(patt,'');
                        list.push({src: url, alt: alt});
                    }
                }
                else
                {
                    if (remoteImage.imageList.length > 0)
                    {
                        list = remoteImage.imageList;
                    }
                    else
                    {
                        list = uploadImage.getInsertList();
                    }
                }

                if (list)
                {
                    editor.execCommand('insertimage', list);
                }
            };
        }
        else
        {
            dialog = parent.aloneImage.DOM.dialog;
            dialog.close = function (flag)
            {
                if (flag)
                {
                    if (!$('#image-list').is(':hidden'))
                    {
                        var imageSelectList = $('.js-attachment-list-region').find('div.attachment-selected');
                        for (i = 0; i < imageSelectList.length; i++)
                        {
                            //var url = $(imageSelectList[i]).prevAll('.image-box')[0].style.backgroundImage.slice(4, -1);
                            var alt = $(imageSelectList[i]).prevAll('.image-title').html();

                            var patt=/\"|\'|\)|\(|url/g;
                            var url = $($(imageSelectList[i]).prevAll('.image-box')[0]).css('background-image').replace(patt,'');
                            list.push({src: url, alt: alt});
                        }
                    }
                    else
                    {
                        if (remoteImage.imageList.length > 0)
                        {
                            list = remoteImage.imageList;
                        }
                        else
                        {
                            list = uploadImage.getInsertList();
                        }
                    }
                    parent.aloneImage.data.callback(list);
                    parent.aloneImage.close();
                }
            }
        }
    }

    function UploadImage()
    {
        this.$wrap = $('#upload');
        this.init();
    }

    UploadImage.prototype = {
        init: function ()
        {
            this.imageList = [];
            this.initContainer();
            this.initUploader();
        },
        /* 初始化容器 */
        initContainer: function ()
        {
            this.$queue = this.$wrap.find('.upload-local-image-list');
        },
        initUploader: function ()
        {
            var _this = this,
                $warp = _this.$wrap,
            // 图片容器
                $queue = $warp.find('.upload-local-image-list'),
            // 添加的文件数量
                fileCount = 0,
            // 不管成功或者失败，文件上传完成时触发
                fileCompleteCount = 0;
            // 添加的文件总大小
            // 优化retina, 在retina下这个值是2
            ratio = window.devicePixelRatio || 1,
                // 缩略图大小
                thumbnailWidth = 113 * ratio,
                thumbnailHeight = 113 * ratio,
                // WebUploader实例
                uploader = _this.uploader = WebUploader.create({

                    // 文件接收服务端。
                    // editor会自动配置action, 其它页面调用需手动配置action
                    server: serverUrl + "&action=" + uploadConfig.imageActionName + '&user=admin',

                    // 指定选择文件的按钮容器，不指定则不创建按钮。
                    pick: {
                        id: ".js-add-local-attachment",
                    },
                    // 指定接受哪些类型的文件
                    accept: {
                        title: 'Images',
                        extensions: uploadConfig.imageManagerAllowFiles.join(',').replace(/\./g, ''),
                        mimeTypes: 'image/*'
                    },
                    swf: BASE_URL + "/shop/static/common/js/Uploader.swf",
                    // 设置文件上传域的name
                    fileVal: uploadConfig.imageFieldName,
                    // 去重， 根据文件名字、文件大小和最后修改时间来生成hash Key
                    duplicate: true,
                    // 验证单个文件大小是否超出限制, 超出则不允许加入队列
                    fileSingleSizeLimit: bytes_to_size(uploadConfig.imageMaxSize), // 1M
                    // 配置生成缩略图的选项
                    //compress:false,
                    //detail width = 790
                    //800x800   482/418  60
                    compress: {
                        width: 200,
                        height: 200,
                        // 图片质量，只有type为`image/jpeg`的时候才有效。
                        quality: 100,
                        // 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
                        allowMagnify: false,
                        // 是否允许裁剪。
                        crop: true,
                        // 是否保留头部meta信息。
                        preserveHeaders: true
                    }
                });

            // 当有文件添加进来时执行，负责view的创建
            function addFile(file)
            {
                var $prgress = $('<div class="image-progress hide js-progress"></div>'),
                    $removeAtta = $('<a class="close-modal small js-remove-attachment">×</a>');

                if (file.getStatus() == 'invalid')
                {
                    // 判断文件有效性
                    alert('无效文件');
                }
                else
                {
                    uploader.makeThumb(file, function (error, src)
                    {
                        if (error || !src)
                        {
                            alert('生成缩略图失败');
                        }
                        else
                        {
                            var $li = $(
                                    '<li class="upload-local-image-item" id="' + file.id + '">' +
                                    '<div class="image-box" style="background-image: url(' + src + ')"></div>' +
                                    '</li>'
                                ),
                                $img = $('<img src="' + src + '">');
                            $li.append($prgress).append($removeAtta), $queue.append($li);
                            $img.on('error', function ()
                            {
                                alert('生成缩略图失败');
                            })
                        }
                    }, thumbnailWidth, thumbnailHeight);
                }

                // 绑定事件
                file.on('statuschange', function (cur, prev)
                {
                    if (prev === 'progress')
                    {
                        $prgress.removeClass('hide');
                    }
                });

                // 负责view的销毁
                $removeAtta.on('click', function ()
                {
                    uploader.removeFile(file), fileCount--;
                    //	判断队列中是否还存在等待上传的文件
                    if (uploader.getFiles().length == uploader.getFiles('cancelled').length)
                    {
                        $(".js-confirm").addClass('ui-btn-disabled').removeClass('ui-btn-primary').prop('disabled', true);
                    }
                    var $li = $(this).parent();
                    $li.remove();
                });
            };

            // 当文件被加入队列以后触发
            uploader.on('fileQueued', function (file)
            {
                fileCount++;
                addFile(file);
                if ($(".js-confirm").prop('disabled') === true)
                {
                    $(".js-confirm").addClass('ui-btn-primary').removeClass('ui-btn-disabled').prop('disabled', false);
                }
            });

            // 上传进度条
            uploader.on('uploadProgress', function (file, percentage)
            {
                var $li = $('#' + file.id),
                    $percent = $li.find('div.js-progress');

                $percent.html(percentage * 100 + '%');
            });

            // 上传
            $('#start-upload').on('click', function ()
            {
                uploader.upload();
            });

            // 上传完成时触发
            uploader.on('uploadComplete', function (file, ret)
            {
                fileCompleteCount++;

                if (fileCompleteCount == fileCount)
                {
                    dialog.close(true);
                }
            })

            // 上传成功时触发
            uploader.on('uploadSuccess', function (file, ret)
            {
                try
                {
                    var responseText = (ret._raw || ret),
                        json = JSON.parse(responseText);
                    if (json.state == 'SUCCESS')
                    {
                        _this.imageList.push(json);
                    }
                    else
                    {
                        alert(json.state);
                    }
                } catch (e)
                {
                    alert('服务器返回出错');
                }
            });
            $(".js-confirm").on('click', function ()
            {
                uploader.upload();
            });

        },


        // 获取渲染到页面的img
        getInsertList: function ()
        {

            var i, data, list = [];
            for (i = 0; i < this.imageList.length; i++)
            {
                data = this.imageList[i];
                list.push({
                    src: data.url,
                    _src: data.url,
                    title: data.title,
                    alt: data.original,
                });
            }
            return list;
        }
    }

    /* 在线图片 */
    function RemoteImage()
    {
        this.imageList = [],
        this.$image = $('.js-network-image-preview');
        this.init();
    }

    RemoteImage.prototype = {
        init: function ()
        {
            this.initEvents();
        },
        initEvents: function ()
        {
            //  keyup事件
            $('.js-network-image-confirm').on('click', function (){
                var url = $('.js-network-image-url').val();
                $('.js-network-image-preview').attr('src', url);
            }),
            this.$image.on('error', function(){
                alert('提取失败，请确认图片地址是否正确');
            }),
            this.$image.load(function() {
                $.ajax({
                    url: serverUrl + "&action=" + uploadConfig.catcherActionName,
                    data: { 'source': [ this.src ] },
                    success: function(data){
                        data = JSON.parse(data);
                        if(data && data['state'] == 'SUCCESS') {
                            var list = data.list[0];
                            list.src = list.url;
                            remoteImage.imageList.push(list);
                            dialog.close(true);
                        }
                    },
                    error: function() {
                        alert('服务器响应失败');
                    }
                })
            })
        }
    }


    if (typeof(uploadConfig) === 'undefined'){

        function initConfig(data)
        {
            uploadConfig = data;

            initButtons(), initEvent(), initImageList();
        }

        $.ajax({
            type: "get",
            url: serverUrl + "&action=config",
            dataType: "jsonp",
            jsonp: "callback",//传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名(一般默认为:callback)
            jsonpCallback:"getConfig",//自定义的jsonp回调函数名称，默认为jQuery自动生成的随机函数名，也可以写"?"，jQuery会自动为你处理数据
            success: function(data){
                initConfig(data);
            },
            error: function(){
                alert('加载配置文件失败!');
            }
        });
    }
    else
    {
        initButtons(), initEvent(), initImageList();
    }

});