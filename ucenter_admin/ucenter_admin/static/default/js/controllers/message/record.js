var stime={
    beginDate:0,
    startDate:0
};
function initDate() {
    var a = new Date,
        b = a.getFullYear(),
        c = ("0" + (a.getMonth() + 1)).slice(-2),
        d = ("0" + a.getDate()).slice(-2);
    stime.beginDate = b + "-" + c + "-01", stime.endDate = b + "-" + c + "-" + d;
    stime.startDate = b + "-" + c + "-" + d; //启用日期
}
$(function () {
    function a(){
        this.$_matchCon = $('#matchCon'),
            this.$_matchCon1 = $('#matchCon1'),
            this.$_beginDate = $('#beginDate').val(stime.beginDate),
            this.$_endDate = $('#endDate').val(stime.endDate),
            this.$_matchCon.placeholder(),
            this.$_matchCon1.placeholder(),
            this.$_beginDate.datepicker(),
            this.$_endDate.datepicker()
    }
    function b() {
        var a = Public.setGrid(f, g),
            b = parent.SYSTEM.rights,
            c = !(parent.SYSTEM.isAdmin || b.AMOUNT_COSTAMOUNT),
            h = !(parent.SYSTEM.isAdmin || b.AMOUNT_INAMOUNT),
            k = !(parent.SYSTEM.isAdmin || b.AMOUNT_OUTAMOUNT),
            l = [
                {
                    name: 'operate',
                    label: '操作',
                    width: 35,
                    fixed: !0,
                    formatter: function (a, b, c) {
                        var d = '<div class="operating" data-id="' + c.server_id + '"><span class="ui-icon ui-icon-pencil" title="修改"><!--</span><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-pic" title="商品图片"></span>--></div>';
                        return d
                    },
                    title: !1
                },
                {
                    name: 'msg_sender',
                    label: '发送者',
                    width: 100,
                    align: 'center'
                },
                {
                    name: 'msg_receiver',
                    label: '接收者',
                    width: 120,
                    align: 'center'
                },
                {
                    name: 'date_created',
                    label: '发送时间',
                    width: 200,
                    align: 'center'
                },
                {
                    name: 'msg_content',
                    label: '消息内容',
                    width: 800,
                    align: 'center'
                }
            ];
        j.gridReg('grid', l),
            l = j.conf.grids.grid.colModel,

            $('#grid').jqGrid({

                url: './index.php?ctl=Message_Record&met=getList&typ=json',

                datatype: 'json',
                width: a.w,
                height: a.h,
                altRows: !0,
                gridview: !0,
                onselectrow: !1,
                colModel: l,
                pager: '#page',
                viewrecords: !0,
                multiselect: !0,
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
                    id: 'msg_log_id'
                },
                loadComplete: function (a) {
                    if (a && 200 == a.status) {
                        var b = {
                        };
                        a = a.data;
                        for (var c = 0; c < a.items.length; c++) {
                            var d = a.items[c];
                            d['id'] = d.msg_log_id;
                            b[d.msg_log_id] = d
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
            $_matchCon1.placeholder(),
            f=$('#beginDate').val(),
            g=$('#endDate').val()
            $('#search').on('click', function (a) {
                a.preventDefault();
                var b = '按发送人查询' === $_matchCon.val() ? '' : $.trim($_matchCon.val()),
                    e = '按接收人查询' === $_matchCon1.val() ? '' : $.trim($_matchCon1.val()),
                    c = $('#currentCategory').data('id');
                $('#grid').jqGrid('setGridParam', {
                    page: 1,
                    postData: {
                        skey: b,
                        skey1:e,
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
                    var b = '按发送人查询' === $_matchCon.val() ? '' : $.trim($_matchCon.val()),
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
                        content: '请选择需要删除的项'
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
            del: function (a) {
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
            },
            setStatus: function (a, b) {
                a && Public.ajaxPost('./index.php?ctl=Purchase_Information&met=change&typ=json', {
                    id: a,
                    server_status: Number(b)
                }, function (c) {
                    c && 200 == c.status ? (parent.Public.tips({
                        content: '开通状态修改成功！'
                    }), $('#grid').jqGrid('setCell', a, 'delete', b))  : parent.Public.tips({
                        type: 1,
                        content: '开通状态修改失败！' + c.msg
                    })
                })
            },
            setStatuses: function (a, b) {
                if (a && 0 != a.length) {
                    var c = $('#grid').jqGrid('getGridParam', 'selarrrow'),
                        d = c.join();
                    Public.ajaxPost('./index.php?ctl=Purchase_Information&met=change&typ=json', {
                        id: a,
                        server_status: Number(b)
                    }, function (c) {
                        if (c && 200 == c.status) {
                            parent.Public.tips({
                                content: '开通状态修改成功！'
                            });
                            for (var d = 0; d < a.length; d++) {
                                var e = a[d];
                                $('#grid').jqGrid('setCell', e, 'delete', b)
                            }
                        } else parent.Public.tips({
                            type: 1,
                            content: '开通状态修改失败！' + c.msg
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
                var d = a === !0 ? '未开通' : '已开通',
                    e = a === !0 ? 'ui-label-default' : 'ui-label-success';
                return '<span class="set-status ui-label ' + e + '" data-delete="' + a + '" data-id="' + c.id + '">' + d + '</span>'
            }
        },
        j = Public.mod_PageConfig.init('goodsList');
    b(),
        c(),
        initDate()
});
