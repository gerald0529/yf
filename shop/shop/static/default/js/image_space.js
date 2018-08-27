/**
 * Created by rd04 on 2016/5/31.
 */

$(function () {

    $('.tabmenu').find('a').prop('id', 'album_name');

    var _page = {
            firstPreview: true,
            page: 1,
            rows: 15,
            firstRow: 0,
            totalRows: 0,
            param: {
                album_id: 0
            },
            $imageList: $('#image-list'),
            $pagination: $('#pagination'),
            $categoryList: $('#category-list'),
            $album_name: $('#album_name'),
            $addAlbum: $('#add-album'),
            $addAlbumPage: $('#add-album-page'),
            $addAlbumSave: $('#add-album-save'),
            $addAlbumCancel: $('#add-album-cancel'),
            $albumDesc: $('#album_desc'),
            $uploadImage: $('#upload-image'),
            $selectAll: $('#select-all'),
            $bulkEdit: $('#bulk-edit'),
            $bulkRemove: $('#bulk-remove'),
            uploader: {},
            resetPage: function () {
                this.page = 1;
                this.rows = 15;
                this.firstRow = 0;
                this.totalRows = 0;
            }
        };

    initPage();

    function initPage() {

        _page.$imageList.empty(), _page.$pagination.empty();

        $.post(SITE_URL + '?ctl=Seller_Album&met=listImage&typ=json&firstRow=' + _page.firstRow + '&totalRows=' + _page.totalRows, {
            page: _page.page,
            rows: _page.rows,
            param: _page.param,
            firstRow: _page.firstRow,
            totalRows: _page.totalRows,
        }, function (data) {console.info(123);
            if (data.status == 200) {
                var data = data.data,
                    itmes = data.items;
                if (itmes.length > 0) {
                    _page.$pagination.parent().show(), loadingPagination(data), loadingImageList(itmes);
                } else {
                    _page.$imageList.append('<div class="no_account"><img src="' + BASE_URL + '/shop/static/default/images/ico_none.png"><p>暂无符合条件的数据记录</p></div>');
                }
            } else {
                alert('读取数据失败');
            }
            if(_page.firstPreview) {
                initCategory();
            }
        })
    }

    function loadingImageList (itmes) {
        //初始化图片列表

        var $imageItem, $imageOpt, $rename, $link, $assignGroup, $delete, $imageTitle;

        for (var i = 0; i < itmes.length; i++) {

            $imageItem = $('<div class="image-item" data-album_id="' + itmes[i].album_id + '" data-upload_id="' + itmes[i].upload_id + '">' +
                '<div class="image-box" style="background-image:url(' + itmes[i].upload_path + ');"></div>' +
            '</div>');

            $imageTitle = $('<div class="image-title"><label><input name="image-box" type="checkbox"><span>' + itmes[i].upload_name + '</span></label></div>');
            $imageOpt = $('<div class="image-opt"></div>');

            $rename = $('<a href="javascript:;">改名</a>');
            $link = $('<a href="javascript:;" name="imageUrl" data-imageurl="' + itmes[i].upload_path + '">链接</a>');
            $assignGroup = $('<a href="javascript:;">分组</a>');
            $delete = $('<a href="javascript:;">删除</a>');

            //bind event

            $imageTitle.on('click', function () {

                var imageList = []
                    $radio = $(this).find('input[type="checkbox"]'),
                    flag = _page.$bulkEdit.hasClass('c-gray');

                if($radio.prop('checked')) {
                    _page.$bulkEdit.css('cursor', '').removeClass('c-gray'),
                    _page.$bulkRemove.css('cursor', '').removeClass('c-gray');
                } else {
                    imageList = $('input[name="image-box"]:checked');
                    if(imageList.length == 0) {
                        _page.$bulkEdit.css('cursor', 'not-allowed').addClass('c-gray'),
                        _page.$bulkRemove.css('cursor', 'not-allowed').addClass('c-gray');
                    }
                }
            })

            $rename.on('click', function() {
                $.dialog({
                    content: '<div class="ui-popover-inner" style="min-width: auto; width: 180px;">' +
                                    '<div style="margin-bottom: 6px;">修改名称</div>' +
                                    '<div style="margin-bottom: 6px;">' +
                                    '<input class="js-name-input" type="text" value="' + $(this).parent().prev().find('span').html() + '" placeholder="" style=" width: 166px;">' +
                                    '</div>' +
                              '</div>',
                    title: '改名',
                    width: 250,
                    cancel: true,
                    data: { upload_id: $(this).parent().parent().data('upload_id'), upload_name:  $(this).parent().prev().find('span').html(), _thisSpan: $(this).parent().prev().find('span') },
                    ok: function () {
                        var _thisSpan = this.data._thisSpan,
                            upload_id = this.data.upload_id,
                            upload_name = this.DOM.wrap.find('.js-name-input').val();

                        if (upload_name && upload_name != this.data.upload_name) {
                            $.post(SITE_URL + '?ctl=Seller_Album&met=edit&typ=json', { upload_id: upload_id, upload_name: upload_name }, function (data) {
                                    if (data.status == 200) {
                                        _thisSpan.html(data.data.upload_name);
                                    }
                            })
                        } else {
                            alert('请输入名称');
                            return false;
                        }

                    }
                })
            });

            $link.on('click', function () {
                /*var url = $(this).parent().prev().prev().css('background-image').replace(/\"|\'|\)|\(|url/g, '');
                $.dialog({
                    title: '链接url',
                    content: '<input class="js-name-input" disabled="disabled" type="text" value="' + url + '" placeholder="" style=" width: 250px;">',
                    okVal: '复制',
                    ok: function () {
                    }
                })*/
            });

            $assignGroup.on('click', function () {

                var thisImageItem = $(this).parent().parent(),
                    upload_id = $(this).parent().parent().data('upload_id');

                assignGroup({
                    thisImageItem: thisImageItem,
                    upload_id: upload_id
                });
            });

            $delete.on('click', function () {

                var thisImageItem = $(this).parent().parent(),
                    upload_id = $(this).parent().parent().data('upload_id');

                deleteImage({
                    thisImageItem: thisImageItem,
                    upload_id: upload_id
                });
            });

            $imageOpt.append($rename).append($link).append($assignGroup).append($delete);
            _page.$imageList.append($imageItem.append($imageTitle).append($imageOpt));
        }
    }


    function initCategory() {

        $.post(SITE_URL + '?ctl=Seller_Album&met=getAlbumList&typ=json', {}, function (data) {
            if (data.status == 200) {

                var items = data.data.items;

                for (var i = 0; i < items.length; i++) {
                    _page.$categoryList.append(' <li class="ui-tooltip ' + ( i == 0 ? 'active' : '') + '" data-album-id="' + items[i].album_id + '" data-tooltip-title="' + items[i].album_desc + '" data-tooltip-placement="left">' +
                        '<span class="category-name">' + items[i].album_desc + '</span>' +
                        '<span class="category-num">' + items[i].image_num + '</span>' +
                        '</li>')
                }
            } else {
                alert('服务器响应失败');
            }

            if(_page.firstPreview) {
                _page.firstPreview = false;
                initEvent();
            }

        })
    }


    function loadingPagination(data) {
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

        _page.$pagination.append(page_nav);
    }

    function initEvent() {

        //分页
        $('#pagination').on('click', 'a', function (){
            var _thisPage;
            if ( $(this).hasClass('nextPage') || $(this).hasClass('prePage') ) {
                if ( $(this).hasClass('nextPage') ){
                    _thisPage =  parseInt($('#pagination').find('b').html()) + 1;
                } else {
                    _thisPage =  parseInt($('#pagination').find('b').html()) - 1;
                }

            } else {
                _thisPage =  $(this).html().replace(/\.+/, '');
            }
            _page.page = _thisPage;

                _page.firstRow = $(this).data('firstRow'),
                _page.totalRows = $(this).data('totalRows');


            initPage();
        });

        _page.$addAlbum.on('click', function (e) {
            $.dialog({
                title: '新增分组',
                content: '<div class="ui-popover-inner" style="min-width: auto; width: 180px;">' +
                '<div style="margin-bottom: 6px;">新增分组</div>' +
                '<div style="margin-bottom: 6px;">' +
                '<input class="js-name-input" type="text" value="" placeholder="" style=" width: 166px;">' +
                '</div>' +
                '</div>',
                width: 250,
                cancel: true,
                lock: true,
                ok: function () {
                    var group_name = $(this.DOM.content).find('input').val();
                    if ( group_name ==  '' ) {
                        Public.tips({ content: '请输入分组名称', type: 1});
                        return false;
                    } else {
                        $.post(SITE_URL + '?ctl=Seller_Album&met=addAlbum&typ=json', {album_desc: group_name}, function(data) {
                            if ( data.status == 200 ) {
                                Public.tips({content: data.msg, type: 3});
                                var $li = $('<li class="ui-tooltip" data-album-id="' +  data.data.album_id + '" data-tooltip-title="' + data.data.album_desc + '" data-tooltip-placement="left">' +
                                    '<span class="category-name">' + data.data.album_desc + '</span>' +
                                    '<span class="category-num">0</span>' +
                                    '</li>');
                                $li.on('click', function(){
                                    readAlbumImages({
                                        album_id: $(this).data('album-id'),
                                        album_name: $(this).data('tooltip-title')
                                    });
                                });
                                _page.$categoryList.append($li);
                                return false;
                            } else {
                                Public.tips({content: data.msg, type: 1});
                            }
                        })
                    }
                }
            })
        });

        //read album image
        _page.$categoryList.children().on('click', function () {
            //切换分组时，重置分页数据
            _page.resetPage();

            var album_id = $(this).data('album-id');

            if (album_id != 0) {
                if ( !$('#rename_album').get(0) ) {
                    _page.$uploadImage.before('<div class="ac_btns"><a href="javascript:;" class="bbc_seller_btns" data-album_id="'+ album_id +'" id="rename_album">重命名</a><a href="javascript:;" class="bbc_seller_btns" data-album_id="'+ album_id +'" id="remove_album">删除分组</a></div>');
                }
            } else {
                $('#rename_album, #remove_album').remove();
            }

            readAlbumImages({
                album_id: album_id,
                album_name: $(this).data('tooltip-title')
            });
        });

        //重命名分组  删除分组
        $('.content:eq(1)').on('click', '#rename_album, #remove_album', function (){

            var url = SITE_URL + '?ctl=Seller_Album&typ=json&met=', param = { album_id: $(this).data('album_id') };

            if (this.id == 'rename_album') {
                url += 'renameAlbum';
                $.dialog({
                    title: '编辑名称',
                    content: '<div class="ui-popover-inner" style="min-width: auto; width: 180px;">' +
                    '<div style="margin-bottom: 6px;">新增分组</div>' +
                    '<div style="margin-bottom: 6px;">' +
                    '<input class="js-name-input" type="text" value="" placeholder="" style=" width: 166px;">' +
                    '</div>' +
                    '</div>',
                    width: 250,
                    cancel: true,
                    lock: true,
                    ok: function () {
                        var name = $(this.DOM.content).find('input').val();
                        if ( name == '') {
                            Public.tips({content: '名称不能为空', type: 1});
                            return false;
                        } else {
                            param.album_desc = name;
                            $.post(url, param, function ( data ) {
                                if ( data.status == 200 ) {
                                    Public.tips({content: data.msg, type: 3}) , window.location.reload();
                                } else {
                                    Public.tips({content: data.msg, type: 1});
                                }
                            })
                        }
                    }
                })

            } else {
                url += 'removeAlbum';
                $.dialog.confirm('仅删除分组，不删除图片，组内图片将自动归入未分组', function () {
                    $.post(url, param, function ( data ) {
                        if ( data.status == 200 ) {
                            Public.tips({content: data.msg, type: 3}) , window.location.reload();
                        } else {
                            Public.tips({content: data.msg, type: 1});
                        }
                    })
                })
            }
        });


        function readAlbumImages (data) {
            //
            _page.$categoryList.find('.active').removeClass('active');
            _page.$categoryList.find('[data-album-id="' + data.album_id + '"]').addClass('active');
            _page.$album_name.html(data.album_name);
            _page.param.album_id = data.album_id;
            _page.$imageList.empty(), _page.$pagination.empty(), initPage();
        }


        //上传图片
        _page.$uploadImage.on('click', function() {

            //获取当前相册id
            var album_id = _page.$categoryList.find('.active').data('album-id');
            window.aloneImage = $.dialog({
                title: '图片管理',
                content: 'url: ' + SITE_URL + '?ctl=Upload&met=image&typ=e',
                data: { callback: getImageList, album_id: album_id },
                // height: 585,
                width: 900,
                max: !1,
                min: !1,
                cache: !1,
                lock: !0
            })

        });

        function getImageList(imageList, album_id) {

            if ( typeof imageList != 'undefined' && imageList.length > 0 ) {
                //更新相册数量
                var $albumSpan = $('#category-list').find('li[data-album-id='+ album_id +']>span.category-num');
                $albumSpan.text(imageList.length + Number($albumSpan.text()));

                var div_img = [];
                $.each(imageList, function(i, e){
                    div_img.push('<div class="image-item" data-album_id="0" data-upload_id="'+ e.upload_id +'"><div class="image-box" style="background-image:url('+ e.src +');"></div><div class="image-title"><label><input name="image-box" type="checkbox"><span>3631203_111238646000_2</span></label></div><div class="image-opt"><a href="javascript:;">改名</a><a href="javascript:;" name="imageUrl" data-imageurl="'+ e.src +'">链接</a><a href="javascript:;">分组</a><a href="javascript:;">删除</a></div></div>');
                });
                $('#image-list').append(div_img.join(''));
            }
        }

        _page.$selectAll.on('click', function () {

            if(this.checked) {
                $imageList = $('input[type="checkbox"][name="image-box"]').prop('checked', 'checked');

                _page.$bulkEdit.css('cursor', '').removeClass('c-gray'),
                _page.$bulkRemove.css('cursor', '').removeClass('c-gray');
            } else {
                $('input[type="checkbox"][name="image-box"]').prop('checked', '');

                _page.$bulkEdit.css('cursor', 'not-allowed').addClass('c-gray'),
                _page.$bulkRemove.css('cursor', 'not-allowed').addClass('c-gray');
            }

        })

        _page.$bulkEdit.on('click', function () {

            var flag = $(this).hasClass('c-gray');

            if (!flag) {

                var upload_id = 0, upload_ids = [], imageItems = [],
                    album_id = _page.$categoryList.find('.active').data('album-id'),
                    imageList = $('input[name="image-box"]:checked');

                for (var i = 0; i < imageList.length; i++) {
                    upload_id = $(imageList[i]).parent().parent().parent().data('upload_id');
                    imageItems.push($(imageList[i]).parent().parent().parent());
                    upload_ids.push(upload_id);
                }

                assignGroup({
                    upload_id: upload_ids,
                    thisImageItem: imageItems
                });

            }
        })

        _page.$bulkRemove.on('click', function () {

            var flag = $(this).hasClass('c-gray');

            if (!flag) {

                var upload_id = 0, upload_ids = [], imageItems = [],
                    album_id = _page.$categoryList.find('.active').data('album-id'),
                    imageList = $('input[name="image-box"]:checked');

                for (var i = 0; i < imageList.length; i++) {
                    upload_id = $(imageList[i]).parent().parent().parent().data('upload_id');
                    imageItems.push($(imageList[i]).parent().parent().parent());
                    upload_ids.push(upload_id);
                }

                deleteImage({
                    upload_id: upload_ids,
                    thisImageItem: imageItems
                });

            }
        })
    }

    function assignGroup(data) {

        var curAlbumId = _page.$categoryList.find('.active').data('album-id');
            $content = $('<ul class="js-category-list" style="max-height: 192px;overflow-y: hidden;margin: 8px 0;"></ul>'),
            categoryList = _page.$categoryList.children();

        for( var i = 0; i < categoryList.length; i++ ) {

            $content.append('<li style="padding: 4px 2px;"><label>' +
                '<input ' + (curAlbumId == $(categoryList[i]).data('album-id') ? 'checked' : '') + ' type="radio" name="category" value="' + $(categoryList[i]).data('album-id') + '" style="vertical-align: middle; margin-right: 10px;" >' + $(categoryList[i]).data('tooltip-title') +
                '</label></li></ul>');
        }

        $.dialog({
            title: '分组',
            content: $content,
            cancel: true,
            data: { curAlbumId: curAlbumId, upload_id: data.upload_id, thisImageItem: data.thisImageItem },
            ok: function () {
                var thisImageItem = this.data.thisImageItem,
                    upload_id = this.data.upload_id,
                    curAlbumId = this.data.curAlbumId,
                    album_id = this.DOM.wrap.find(':checked').val();

                if (curAlbumId != album_id) {

                    $.post(SITE_URL + '?ctl=Seller_Album&met=edit&typ=json', { upload_id: upload_id, album_id: album_id}, function (data) {
                        if (data.status == 200) {
                            window.location.reload();
                        }
                    })
                }
            }
        })
    }

    function deleteImage (data) {

        var curAlbumId = _page.$categoryList.find('.active').data('album-id');

        $.dialog({
            title: '删除图片',
            content: '<div class="ui-popover-inner clearfix" style="min-width: auto;width: 250px;"><div>确定删除该图片？</div>' +
            '<div style="margin: 6px 0 12px 0; color: #999;">若删除，不会对目前已使用该图片的相关业务造成影响。</div>' +
            '</div>',
            cancel: true,
            data: { curAlbumId: curAlbumId, upload_id: data.upload_id, thisImageItem: data.thisImageItem },
            ok: function () {
                var thisImageItem = this.data.thisImageItem,
                    upload_id = this.data.upload_id,
                    curAlbumId = this.data.curAlbumId;

                $.post(SITE_URL + '?ctl=Seller_Album&met=remove&typ=json', { upload_id: upload_id, album_id: curAlbumId}, function (data) {
                    if (data.status == 200) {
                        window.location.reload();
                    }
                })
            }
        })
    };

    $('#image-list').on('click', '[name="imageUrl"]', function () {
        $('#copy').show();
        var imageUrl = $(this).data('imageurl');
        $('#imageUrl').val(imageUrl), $('#copyUrl').trigger('click'), $('#copy').hide();

        if (!window.Clipboard) {
            window.clipboardData.setData('text', imageUrl);
        }
        Public.tips({type:3, content: '已成功复制到剪切板'});
    });

    if (window.Clipboard) {
        var clipboard = new Clipboard('#copyUrl', {
            target: function() {
                return document.querySelector('#imageUrl');
            }
        });
    }
})