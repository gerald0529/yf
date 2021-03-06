function bytes_to_size(bytes) {
    if (bytes === 0) return '0 B';
    var k = 1024, // or 1024
        sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
        i = Math.floor(Math.log(bytes) / Math.log(k));
    return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
}

var serverUrl =SITE_URL + "?ctl=Seller_Album";

var album_id = frameElement.api && frameElement.api.data && frameElement.api.data.album_id || 0;
var checkedImageList = []; //选中图片
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
            var imageId = $(this).data('id');

            if ($(this).children('div.attachment-selected').length == 1)
            {
                //删除队列
                for(var i in checkedImageList) {
                    if (checkedImageList[i].id == imageId) {
                        checkedImageList.splice(i, 1);
                    }
                }

                $(this).children('div.attachment-selected').remove();
                if ($('.js-attachment-list-region').find('div.attachment-selected').length == 0)
                {
                    $(".js-confirm").addClass('ui-btn-disabled').removeClass('ui-btn-primary').prop('disabled', true);
                }
            }
            else
            {
                //记录选中图片

                var alt = $(this).find('.image-title').html();
                var patt=/\"|\'|\)|\(|url/g;
                var url = $(this).find('.image-box').css('background-image').replace(patt,'');
                checkedImageList.push({src: url, alt: alt, id: imageId});

                $(this).append('<div class="attachment-selected"><i class="icon-ok icon-white"></i></div>');
                $(".js-confirm").addClass('bbc_seller_btns').removeClass('ui-btn-disabled').prop('disabled', false);
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
        });

        //相册列表
        $('.js-category-list-region').on('click', 'li', function () {
            $('.category-list').find('li').removeClass('active');
            $(this).addClass('active');
            album_id = $(this).data('album_id');
            var param = { album_id: album_id };
            $('.image-list').find('li').remove(), $('.ui-pagination').find('a').remove();
            initImageList(param);
        })
    }

    var page = 1,
        firstRow = 0,
        totalRows = 0;

    function initImageList(param)
    {
		if(typeof(param) === 'undefinded' || !param){
			param = { album_id: $("li[class='js-category-item active']").data('album_id') };
		}
		
        $.ajax({
            url: serverUrl + "&typ=json&action=" + uploadConfig.imageManagerActionName + '&firstRow=' + firstRow + '&totalRows=' + totalRows,
            data: {page: page, rows: 15, sord: 'asc', param: param},
            success: function (data)
            {
                $('.js-attachment-list-region').find('li').remove();
                $('.ui-pagination').empty();

                var data = data.data;

                //初始化分页
                /*$('.ui-pagination-total').html('共' + data.totalsize + '条, 每页15条');*/
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

                    var page_nav = $(data.page_nav).each(function(index, element){
                        var href = $(this).prop('href');
                        if ( !(typeof href == 'undefined') ) {
                            var firstRow = href.match(/firstRow=\d+/).join().replace('firstRow=', ''),
                                totalRows = href.match(/totalRows=\d+/).join().replace('totalRows=', '');
                            $(this).data('firstRow', firstRow);
                            $(this).data('totalRows', totalRows);
                        }
                        $(this).prop('href', 'javascript:void(0)');
                    });

                    $('.ui-pagination').append(page_nav);
                }
            }

        });

        $(".js-confirm").off();
        $(".js-confirm").on('click', function ()
        {
            dialog.close(true);
        });
    }

    //分页
    $('.ui-pagination').on('click', 'a', function (){
        var _thisPage;
        if ( $(this).hasClass('nextPage') || $(this).hasClass('prePage') ) {
            if ( $(this).hasClass('nextPage') ){
                _thisPage =  parseInt($('.ui-pagination').find('b').html()) + 1;
            } else {
                _thisPage =  parseInt($('.ui-pagination').find('b').html()) - 1;
            }

        } else {
            _thisPage = $(this).html();
        }
        page = _thisPage;

        firstRow = $(this).data('firstRow'), totalRows = $(this).data('totalRows');


        initImageList();
    });

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
                var list;
                if (!$('#image-list').is(':hidden'))
                {
                    // var imageSelectList = $('.js-attachment-list-region').find('div.attachment-selected');
                    // for (i = 0; i < imageSelectList.length; i++)
                    // {
                    //     var alt = $(imageSelectList[i]).prevAll('.image-title').html();
                    //
                    //     var patt=/\"|\'|\)|\(|url/g;
                    //     var url = $($(imageSelectList[i]).prevAll('.image-box')[0]).css('background-image').replace(patt,'');
                    //     list.push({src: url, alt: alt});
                    // }

                    list = checkedImageList;
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
                    parent.aloneImage.data.callback(list, album_id);
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
            this.imageList = {};
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
                    server: serverUrl + "&action=" + uploadConfig.imageActionName + '&album_id=' + album_id,

                    // 指定选择文件的按钮容器，不指定则不创建按钮。
                    pick: {
                        id: ".js-add-local-attachment",
                    },
                    // 指定接受哪些类型的文件
                    accept: {
                        title: 'Images',
                        extensions: "gif,jpg,jpeg,bmp,png",
                        mimeTypes: 'image/jpg,image/jpeg,image/png'
                    },
                    swf: BASE_URL + "/shop/static/common/js/Uploader.swf",
                    // 设置文件上传域的name
                    fileVal: uploadConfig.imageFieldName,
                    // 去重， 根据文件名字、文件大小和最后修改时间来生成hash Key
                    duplicate: true,
                    // 验证单个文件大小是否超出限制, 超出则不允许加入队列
                    fileSingleSizeLimit: bytes_to_size(uploadConfig.imageMaxSize), // 1M
                    // 配置生成缩略图的选项
                    compress:false,
                    //detail width = 790
                    //800x800   482/418  60
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
                        _this.imageList[file.id] = json;
                    }
                    else
                    {
                        parent.Public.tips( {content: json.state, type: 1} );
                    }
                } catch (e)
                {
                    parent.Public.tips( {content: '服务器返回出错', type: 1} );
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

            var data, list = [], files = uploader.getFiles();
            for (var i = 0; i< files.length; i ++) {
                data = this.imageList[files[i].id];
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
        //相册列表
        $.post(serverUrl + "&met=getAlbumList&typ=json", {}, function (data) {
            var li = new String();
            if ( data.status == 200 ) {
                var items = data.data.items;
                for( var i = 0; i < items.length; i++ ) {
                    li += '<li class="js-category-item '+ (i == 0 ? 'active' : '') +'" data-album_id="' + items[i].album_id + '">' + items[i].album_desc + '<span>' + items[i].image_num + '</span></li>';
                }
                $('#image-list').find('ul.category-list').append(li);
            } else {
                parent.Public.tips({ content: data.msg, type: 1 });
            }
        });
    }

});

function getImageSize(){
    var image = new Image, imageList;
    imageList = $(".image-box");

    if (imageList.length > 0) {
        $.each(imageList, function(i, imageBox) {
            image.src = imageBox.style.backgroundImage.replace(/^url\(['"]|['"]\)$/g, "");
            if (image.width > 0) {
                $(imageBox).next().html(image.width + "*" + image.height);
            }
        })
    }
}

setInterval("getImageSize()", 1000);


