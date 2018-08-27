$(function () {

    // function operFmatter (a, b, c) {
    //     //<span class="ui-icon ui-icon-search" title="查看"></span>
    //     var d = '<div class="operating" data-id="' + c.id + '"><span class="ui-icon ui-icon-pencil" title="查看评论"></span><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-pic" title="朋友圈图片"></span></div>';
    //     return d;
    // };

    function b() {
        var a = Public.setGrid(),
            b = parent.SYSTEM.rights,
            c = !(parent.SYSTEM.isAdmin || b.AMOUNT_COSTAMOUNT),
            h = !(parent.SYSTEM.isAdmin || b.AMOUNT_INAMOUNT),
            k = !(parent.SYSTEM.isAdmin || b.AMOUNT_OUTAMOUNT),
            l = [
                {name: 'operate',label: '操作',width: 90,fixed: !0,
                    formatter: function (a, b, c) {
                        var d = '<div class="operating" data-id="' + c.id + '"><span class="ui-icon ui-icon-search" title="查看评论"></span><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-pic" title="朋友圈图片"></span></div>';
                        return d
                    },
                    title: !1
                },
                    {name: 'user_name',label: '用户名',width: 150,align: 'center'},
                    {name: 'sns_title',label: '标题',width: 180,align: 'center'},
                    {name: 'sns_create_time',label: '发布时间',width: 200,align: 'center'},
                    {name: 'sns_content',label: '内容',width:400,align: 'left'},
                    {name: 'sns_like_count',label: '点赞人数',width: 90,align: 'center'},
                    {name: 'sns_copy_count',label: '转发人数',width: 90,align: 'center'},
                    {name: 'sns_comment_count',label: '评论人数',width: 90,align: 'center'},
                ];
        j.gridReg('grid', l),
            l = j.conf.grids.grid.colModel,

            $('#grid').jqGrid({
                // url: './admin.php?ctl=Message_Record&met=friendsList&typ=json',
                url:SITE_URL +'?ctl=User_Info&met=friendsList&typ=json',
                datatype: 'json',
                autowidth: true,
                shrinkToFit:true,
                forceFit:false,
                width: a.w,
                height: a.h,
                altRows: !0,
                gridview: !0,
                onselectrow: !1,
                multiselect: false,
                colModel: l,
                pager: '#page',
                viewrecords: !0,
                cmTemplate: {
                    sortable: !1
                },
                rowNum: 100,
                rowList: [
                    100,
                    200,
                    500
                ],


                jsonReader: {
                    root: 'data.items',
                    records: 'data.records',
                    total: 'data.total',
                    repeatitems: !1,
                    id: 'id'
                },
                loadComplete: function (a) {
                    if (a && 200 == a.status) {
                        var b = {
                        };
                        a = a.data;
                        for (var c = 0; c < a.items.length; c++) {
                            var d = a.items[c];
                            d['id'] = d.user_name;
                            b[d.user_name] = d
                        }
                        $('#grid').data('gridData', b)
                    }
                },
                loadError: function (a, b, c) {
                    parent.Public.tips({
                        type: 1,
                        content: '操作失败了哦，请检查您的网络链接！'
                    })
                },
                resizeStop: function (a, b) {
                    j.setGridWidthByIndex(a, b, 'grid')
                }
            }).navGrid('#page', {
                edit: !1,
                add: !1,
                del: !1,
                search: !1,
                refresh: !1
            }).navButtonAdd('#page', {
                caption: '',
                buttonicon: 'ui-icon-config',
                onClickButton: function () {
                    j.config()
                },
                position: 'last'
            })
    }
    function c() {
        $_matchCon = $('#matchCon'),
            $_matchCon1 = $('#matchCon1'),
            $_matchCon.placeholder(),
            f=$('#beginDate').val(),
            g=$('#endDate').val()
        $('#search').on('click', function (a) {
            a.preventDefault();
            var b = '请输入用户名查询' === $_matchCon.val() ? '' : $.trim($_matchCon.val()),
                c = $('#currentCategory').data('id');
            $('#grid').jqGrid('setGridParam', {
                page: 1,
                postData: {
                    skey: b,
                    assistId: c,
                    beginDate:f,
                    endDate:g
                }
            }).trigger('reloadGrid')
        }),
            $('#btn-add').on('click', function (a) {
                a.preventDefault(),
                Business.verifyRight('INVENTORY_ADD') && h.operate('add')
            }),
            $('#btn-print').on('click', function (a) {
                a.preventDefault()
            }),

            $('#btn-export').on('click', function (a) {
                if (Business.verifyRight('INVENTORY_EXPORT')) {
                    var b = '请输入用户名查询' === $_matchCon.val() ? '' : $.trim($_matchCon.val()),
                        c = $('#currentCategory').data('id') || '';
                    $(this).attr('href', '/basedata/inventory.do?action=exporter&isDelete=2&skey=' + b + '&assistId=' + c)
                }
            }),
            $('#grid').on('click', '.operating .ui-icon-search', function (a) {
                if (a.preventDefault(), Business.verifyRight('INVENTORY_UPDATE')) {
                    var b = $(this).parent().data('id');
                    h.operate('edit', b)
                }
            }),
            $('#grid').on('click', '.operating .ui-icon-trash', function (a) {
                if (a.preventDefault(), Business.verifyRight('INVENTORY_DELETE')) {
                    var b = $(this).parent().data('id');
                    h.del(b + '')
                }
            }),
            $('#grid').on('click', '.operating .ui-icon-pic', function (a) {
                a.preventDefault();
                var b = $(this).parent().data('id'),
                    c = '朋友圈图片';
                $.dialog({
                    content: 'url:./admin.php?ctl=Message_Record&met=picture',
                    data: {
                        title: c,
                        id: b,
                        callback: function () {
                        }
                    },
                    title: c,
                    width:600,
                    height: 400,
                    max: !1,
                    min: !1,
                    cache: !1,
                    lock: !0
                })
            }),
            $('#btn-batchDel').click(function (a) {
                if (a.preventDefault(), Business.verifyRight('INVENTORY_DELETE')) {
                    var b = $('#grid').jqGrid('getGridParam', 'selarrrow');
                    b.length ? h.del(b.join())  : parent.Public.tips({
                        type: 2,
                        content: '请选择要推送消息的用户'
                    })
                }
            }),

            $('#grid').on('click', '.set-status', function (a) {
                if (a.stopPropagation(), a.preventDefault(), Business.verifyRight('INVLOCTION_UPDATE')) {
                    var b = $(this).data('id'),
                        c = !$(this).data('delete');
                    h.setStatus(b, c)
                }
            }),
            $(window).resize(function () {
                Public.resizeGrid();
                    // $('.innerTree').height($('#tree').height() - 95)
            });
            // Public.setAutoHeight($('#tree')),
            // $('.innerTree').height($('#tree').height() - 95)
    }

    var d = (parent.SYSTEM, Number(parent.SYSTEM.qtyPlaces), Number(parent.SYSTEM.pricePlaces)),
        e = Number(parent.SYSTEM.amountPlaces),
        f = 95,
        g = 0,
        h = {
            operate: function (a, b) {
                //$('.grid-wrap').on('click', '.ui-icon-pencil', function (a) {
                //    a.preventDefault();
                    //var b = $(this).parent().data('id');
                    /*parent.tab.addTabItem({
                        tabid: 'storage-adjustment',
                        text: '评论内容',
                        url: './admin.php?ctl=Message_Record&met=comment&id=' + b
                    });
                    $('#grid').jqGrid('getDataIDs');
                    parent.salesListIds = $('#grid').jqGrid('getDataIDs')*/
                    var e = 800;
                    var c='评论内容';
                    _h = 480,
                        $.dialog({
                            title: c,
                            content: 'url:./admin.php?ctl=Message_Record&met=comment&id='+b,
                            data: {'id':b},
                            width: e,
                            height: 500,
                            max: !1,
                            min: !1,
                            cache: !1,
                            lock: !0
                        })
                //})
            },
            del:function (a) {
                _h = 480,
                    $.dialog.confirm('删除的数据将不能恢复，请确认是否删除？', function () {
                        Public.ajaxPost('./admin.php?ctl=Message_Record&met=removeFriends', {
                            id: a
                        }, function (b) {
                            if (b && 200 == b.status) {
                                parent.Public.tips({
                                    type:0,
                                    content: '删除成功！'
                                })
                            } else parent.Public.tips({
                                type: 1,
                                content: '删除失败！' + b.msg
                            })
                        })
                    })
            },

            callback: function (a, b, c) {
                var d = $('#grid').data('gridData');
                d || (d = {
                }, $('#grid').data('gridData', d)),
                    d[a.id] = a,
                    'edit' == b ? ($('#grid').jqGrid('setRowData', a.id, a), c && c.api.close())  : ($('#grid').jqGrid('addRowData', a.id, a, 'last'), c && c.resetForm(a))
            }
        },
        i = {
            money: function (a, b, c) {
                var a = Public.numToCurrency(a);
                return a || '&#160;'
            },
            currentQty: function (a, b, c) {
                if ('none' == a) return '&#160;';
                var a = Public.numToCurrency(a);
                return a
            },
            quantity: function (a, b, c) {
                var a = Public.numToCurrency(a);
                return a || '&#160;'
            },

        },
        j = Public.mod_PageConfig.init('goodsList');
    b(),
        c()

});
