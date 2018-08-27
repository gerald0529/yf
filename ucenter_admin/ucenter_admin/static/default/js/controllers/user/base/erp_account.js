$(function () {

    SYSTEM = system = parent.SYSTEM;
    function initDom() {
        var defaultPage = Public.getDefaultPage();
        defaultPage.SYSTEM = defaultPage.SYSTEM || {};
        defaultPage.SYSTEM.categoryInfo = defaultPage.SYSTEM.categoryInfo || {};

        this.$_matchCon = $('#matchCon'),
            this.$_beginDate = $('#begin_date').val(system.beginDate),
            this.$_endDate = $('#end_date').val(system.endDate),
            this.$_matchCon.placeholder(),
            this.$_beginDate.datepicker(),
            this.$_endDate.datepicker()

    };

    function a() {
        Public.zTree.init($('#tree'), {
            defaultClass: 'innerTree',
            showRoot: !0,
            rootTxt: '全部'
        }, {
            callback: {
                beforeClick: function (a, b) {
                    $('#currentCategory').data('id', b.id).html(b.name),
                        $('#search').trigger('click')
                }
            }
        })
    }
    function b() {
        var a = Public.setGrid(),
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
                    align: 'center',
                    formatter: function (a, b, c) {
                        var d = '<div class="operating" data-id="' + c.server_id + '"><span class="ui-icon ui-icon-pencil" title="修改"><!--</span><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-pic" title="商品图片"></span>--></div>';
                        return d
                    },
                    title: !1
                },
                {
                    name: 'company_name',
                    label: '公司名称',
                    width: 250,
                    align: 'center'
                },
                {
                    name: 'company_phone',
                    label: '公司电话',
                    width: 100,
                    align: 'center'
                },
                {
                    name: 'contacter',
                    label: '联系人',
                    width: 70,
                    align: 'center'
                },
                {
                    name: 'sign_time',
                    label: '签约时间',
                    width: 120,
                    align: 'center'
                },
                {
                    name: 'account_num',
                    label: '帐号个数',
                    width: 50,
                    align: 'center'
                },
                {
                    name: 'user_name',
                    label: '用户帐号',
                    align: 'center',
                    width: 100
                },
                {
                    name: 'db_name',
                    label: '数据库名',
                    align: 'center',
                    width: 100
                },
                {
                    name: 'business_agent',
                    label: '业务代表',
                    align: 'center',
                    width: 50
                },
                {
                    name: 'price',
                    label: '费用',
                    align: 'center',
                    width: 50
                },
                {
                    name: 'effective_date_start',
                    label: '开始有效时间',
                    align: 'center',
                    width: 100
                },
                {
                    name: 'effective_date_end',
                    label: '结束有效时间',
                    align: 'center',
                    width: 100
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

                url: SITE_URL + '?ctl=User_Base&met=getErpUserList&typ=json',

                datatype: 'json',
                autowidth: true,
                width: a.w,
                height: Public.setGrid().h,
                altRows: !0,
                gridview: !0,
                onselectrow: !1,
                multiselect: false, //多选
                colModel:l,
                pager: '#page',
                viewrecords: !0,
                checkbox:false,
                cmTemplate: {
                    sortable: !1
                },
                rowNum: 100,
                rowList: [
                    100,
                    200,
                    500
                ],
                shrinkToFit: false,
                forceFit: true,
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
                            d['id'] = d.goods_id;
                            b[d.goods_id] = d
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
            $_matchCon.placeholder(),
            $('#search').on('click', function (a) {
                a.preventDefault();
                var b = '按商品编号，商品名称查询' === $_matchCon.val() ? '' : $.trim($_matchCon.val()),
                    c = $('#currentCategory').data('id');
                $('#grid').jqGrid('setGridParam', {
                    page: 1,
                    postData: {
                        skey: b,
                        assistId: c
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


            $('#grid').on('click', '.set-status', function (a) {
                a.preventDefault();

                var b = $(this).data('id'),
                    c = !$(this).data('delete');
                h.setStatus(b, c);
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
                if(a=='edit')
                {
                    $.dialog({
                        title: '编辑',
                        content: 'url:'+SITE_URL + "?ctl=User_Base&met=erpAccountManage&typ=e&id=" + b,
                        width: 700,
                        height: 400,
                        data: {oper:a,id:b,callback: this.callback},
                        max: !1,
                        min: !1,
                        cache: !1,
                        zIndex:999,
                        lock: !0
                    })
                }
                if(a=='add')
                {
                    $.dialog({
                        title: '新增',
                        content: 'url:'+SITE_URL + "?ctl=User_Base&met=erpAccountManage&typ=e",
                        width: 700,
                        height: 400,
                        data: {oper:a,callback: this.callback},
                        max: !1,
                        min: !1,
                        cache: !1,
                        zIndex:999,
                        lock: !0
                    })
                }

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
                a && Public.ajaxPost(SITE_URL + '?ctl=User_Base&met=change&typ=json', {
                    id: a,
                    request_app_id: 101,
                    server_status: !Number(b)
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
                    Public.ajaxPost(SITE_URL + '?ctl=User_Base&met=change&typ=json', {
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
                $("#grid").trigger("reloadGrid");
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

        initDom();
        b(),
        a(),
        c()
});
