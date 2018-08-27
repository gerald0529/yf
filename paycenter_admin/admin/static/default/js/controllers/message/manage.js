$(function () {

    function b() {
        var a = Public.setGrid(f, g),
            b = parent.SYSTEM.rights,
            c = !(parent.SYSTEM.isAdmin || b.AMOUNT_COSTAMOUNT),
            h = !(parent.SYSTEM.isAdmin || b.AMOUNT_INAMOUNT),
            k = !(parent.SYSTEM.isAdmin || b.AMOUNT_OUTAMOUNT),
            l = [
                {
                    name: 'user_name',
                    label: '用户名',
                    width: 100,
                    align: 'center'
                },
                {
                    name: 'nickname',
                    label: '昵称',
                    width: 100,
                    align: 'center'
                },
                {
                    name: 'user_truename',
                    label: '真实姓名',
                    width: 100,
                    align: 'center'
                },
                {
                    name: 'user_gender',
                    label: '性别',
                    width: 50,
                    align: 'center'
                },
                {
                    name: 'user_tel',
                    label: '电话',
                    width: 100,
                    align: 'center'
                },
                {
                    name: 'user_mobile',
                    label: '手机号码',
                    width: 120,
                    align: 'center'
                },
                {
                    name: 'user_qq',
                    label: 'QQ',
                    width: 120,
                    align: 'center'
                },
                {
                    name: 'user_province',
                    label: '省份',
                    width: 100,
                    align: 'center'
                },
                {
                    name: 'user_city',
                    label: '城市',
                    width: 100,
                    align: 'center'
                },
                {
                    name: 'user_reg_time',
                    label: '注册时间',
                    width: 150,
                    align: 'center'
                },
                {
                    name: 'user_lastlogin_time',
                    label: '上次登录时间',
                    width: 150,
                    align: 'center'
                },
                {
                    name: 'user_count_login',
                    label: '登录次数',
                    width: 55,
                    align: 'center'
                },
                {
                    name: 'delete',
                    label: '状态',
                    index: 'delete',
                    width: 80,
                    align: 'center',
                    formatter: i.statusFmatter
                }
            ];
        j.gridReg('grid', l),
            l = j.conf.grids.grid.colModel,

            $('#grid').jqGrid({

                url: './index.php?ctl=Message_Record&met=getFriends&typ=json',

                datatype: 'json',
                width: a.w,
                height: a.h,
                altRows: !0,
                gridview: !0,
                onselectrow: !1,
                colModel: l,
                pager: '#page',
                viewrecords: !0,
                multiselect: !1,
                cmTemplate: {
                    sortable: !1
                },
                rowNum: 100,
                rowList: [
                    100,
                    200,
                    500
                ],
                shrinkToFit: !1,
                forceFit: !0,
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
            $('#btn-import').on('click', function (a) {
                a.preventDefault(),
                Business.verifyRight('BaseData_IMPORT') && parent.$.dialog({
                    width: 560,
                    height: 300,
                    title: '批量导入',
                    content: 'url:/import.jsp',
                    lock: !0
                })
            }),
            $('#btn-export').on('click', function (a) {
                if (Business.verifyRight('INVENTORY_EXPORT')) {
                    var b = '请输入用户名查询' === $_matchCon.val() ? '' : $.trim($_matchCon.val()),
                        c = $('#currentCategory').data('id') || '';
                    $(this).attr('href', '/basedata/inventory.do?action=exporter&isDelete=2&skey=' + b + '&assistId=' + c)
                }
            }),
            $('#grid').on('click', '.operating .ui-icon-pencil', function (a) {
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
                    c = '商品图片';
                $.dialog({
                    content: '',
                    data: {
                        title: c,
                        id: b,
                        callback: function () {
                        }
                    },
                    title: c,
                    width: 775,
                    height: 470,
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
            $('#btn-disable').click(function (a) {
                a.preventDefault();
                var b = $('#grid').jqGrid('getGridParam', 'selarrrow').concat();
                return b && 0 != b.length ? void h.setStatuses(b, !0)  : void parent.Public.tips({
                    type: 1,
                    content: ' 请先选择要禁用的商品！'
                })
            }),
            $('#btn-enable').click(function (a) {
                a.preventDefault();
                var b = $('#grid').jqGrid('getGridParam', 'selarrrow').concat();
                return b && 0 != b.length ? void h.setStatuses(b, !1)  : void parent.Public.tips({
                    type: 1,
                    content: ' 请先选择要启用的商品！'
                })
            }),
            $('#hideTree').click(function (a) {
                a.preventDefault();
                var b = $(this),
                    c = b.html();
                '&gt;&gt;' === c ? (b.html('&lt;&lt;'), g = 0, $('#tree').hide(), Public.resizeGrid(f, g))  : (b.html('&gt;&gt;'), g = 270, $('#tree').show(), Public.resizeGrid(f, g))
            }),
            $('#grid').on('click', '.set-status', function (a) {
                if (a.stopPropagation(), a.preventDefault(), Business.verifyRight('INVLOCTION_UPDATE')) {
                    var b = $(this).data('id'),
                        c = !$(this).data('delete');
                    h.setStatus(b, c)
                }
            }),
            $(window).resize(function () {
                Public.resizeGrid(f, g),
                    $('.innerTree').height($('#tree').height() - 95)
            }),
            Public.setAutoHeight($('#tree')),
            $('.innerTree').height($('#tree').height() - 95)
    }

    var d = (parent.SYSTEM, Number(parent.SYSTEM.qtyPlaces), Number(parent.SYSTEM.pricePlaces)),
        e = Number(parent.SYSTEM.amountPlaces),
        f = 95,
        g = 0,
        h = {
            operate: function (a, b) {
                var a = this;
                $('.grid-wrap').on('click', '.ui-icon-pencil', function (a) {
                    a.preventDefault();
                    var b = $(this).parent().data('id');
                    parent.tab.addTabItem({
                        tabid: 'storage-adjustment',
                        text: '信息修改',
                        url: './index.php?ctl=Purchase_Information&id=' + b
                    });
                    $('#grid').jqGrid('getDataIDs');
                    parent.salesListIds = $('#grid').jqGrid('getDataIDs')
                })
            },
            del:function (a, b) {
                var e = 600;
                var c='推送消息';
                _h = 480,
                    $.dialog({
                        title: c,
                        content: 'url:./index.php?ctl=Message_Record&met=send&t='+a,
                        data: a,
                        width: e,
                        height: 300,
                        max: !1,
                        min: !1,
                        cache: !1,
                        lock: !0
                    })
            },
            /*del: function (a) {
                $.dialog.confirm('删除的商品将不能恢复，请确认是否删除？', function () {
                    Public.ajaxPost('', {
                        id: a
                    }, function (b) {
                        if (b && 200 == b.status) {
                            var c = b.data.id || [];
                            a.split(',').length === c.length ? parent.Public.tips({
                                content: '成功删除' + c.length + '个商品！'
                            })  : parent.Public.tips({
                                type: 2,
                                content: b.data.msg
                            });
                            for (var d = 0, e = c.length; e > d; d++) $('#grid').jqGrid('setSelection', c[d]),
                                $('#grid').jqGrid('delRowData', c[d])
                        } else parent.Public.tips({
                            type: 1,
                            content: '删除商品失败！' + b.msg
                        })
                    })
                })
            },*/
            setStatus: function (a, b) {
                a && Public.ajaxPost('./index.php?ctl=Message_Record&met=change&typ=json', {
                    id: a,
                    server_status: Number(b)
                }, function (c) {
                    c && 200 == c.status ? (parent.Public.tips({
                        content: '状态修改成功！'
                    }), $('#grid').jqGrid('setCell', a, 'delete', b))  : parent.Public.tips({
                        type: 1,
                        content: '状态修改失败！' + c.msg
                    })
                })
            },
            setStatuses: function (a, b) {
                if (a && 0 != a.length) {
                    var c = $('#grid').jqGrid('getGridParam', 'selarrrow'),
                        d = c.join();
                    Public.ajaxPost('./index.php?ctl=Message_Record&met=change&typ=json', {
                        id: a,
                        server_status: Number(b)
                    }, function (c) {
                        if (c && 200 == c.status) {
                            parent.Public.tips({
                                content: '状态修改成功！'
                            });
                            for (var d = 0; d < a.length; d++) {
                                var e = a[d];
                                $('#grid').jqGrid('setCell', e, 'delete', b)
                            }
                        } else parent.Public.tips({
                            type: 1,
                            content: '状态修改失败！' + c.msg
                        })
                    })
                }
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
            statusFmatter: function (a, b, c) {
                var d = a === !0 ? '未激活' : '已锁定',
                    e = a === !0 ? 'ui-label-default' : 'ui-label-success';
                return '<span class="set-status ui-label ' + e + '" data-delete="' + a + '" data-id="' + c.id + '">' + d + '</span>'
            }
        },
        j = Public.mod_PageConfig.init('goodsList');
    b(),
        c()
});
