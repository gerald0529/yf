var queryConditions = {
        matchCon: ''
    },
    hiddenAmount = !1,
    SYSTEM = system = parent.SYSTEM,
    THISPAGE = {
        init: function (a) {
            SYSTEM.isAdmin !== !1 || SYSTEM.rights.AMOUNT_COSTAMOUNT || (hiddenAmount = !0),
                this.mod_PageConfig = Public.mod_PageConfig.init('adjustmentList'),
                this.initDom(),
                this.loadGrid(),
                this.addEvent()
        },
        initDom: function () {
            this.$_matchCon = $('#matchCon'),
                this.$_beginDate = $('#beginDate').val(system.beginDate),
                this.$_endDate = $('#endDate').val(system.endDate),
                this.$_matchCon.placeholder(),
                this.$_beginDate.datepicker(),
                this.$_endDate.datepicker(),
                this.storageCombo = $('#storageA').combo({
                    data: function () {
                        return parent.parent.SYSTEM.storageInfo
                    },
                    text: 'name',
                    value: 'id',
                    width: 112,
                    defaultSelected: 0,
                    addOptions: {
                        text: '(所有)',
                        value: - 1
                    },
                    cache: !1
                }).getCombo()
        },
        loadGrid: function () {
            function a(a, b, c) {
                var d = '<div class="operating" data-id="' + c.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
                return d
            }
            var b = Public.setGrid();
            queryConditions.beginDate = this.$_beginDate.val(),
                queryConditions.endDate = this.$_endDate.val();
            var c = [
                {
                    name: 'operating',
                    label: '操作',
                    width: 60,
                    fixed: !0,
                    formatter: a,
                    align: 'center'
                },
                {
                    name: 'company_name',
                    label: '公司名称',
                    width: 100,
                    align: 'center'
                },
                {
                    name: 'company_phone',
                    label: '公司电话',
                    width: 120,
                    align: 'center'
                },
                {
                    name: 'contacter',
                    label: '联系人',
                    width: 150,
                    align: 'center'
                },
                {
                    name: 'sign_time',
                    label: '签约时间',
                    width: 150,
                    align: 'center'
                },
                {
                    name: 'account_num',
                    label: '帐号个数',
                    width: 50,
                    align: 'center',
                    width:50
                },
                {
                    name: 'user_name',
                    label: '用户帐号',
                    width: 150
                },
                {
                    name: 'db_name',
                    label: '数据库名',
                    width: 100
                },
                {
                    name: 'upload_path',
                    label: '附件存放地址',
                    width: 200
                },
                {
                    name: 'business_agent',
                    label: '业务代表',
                    width: 100
                },
                {
                    name: 'price',
                    label: '费用',
                    width: 100
                },
                {
                    name: 'effective_date_start',
                    label: '开始有效时间',
                    width: 150
                },
                {
                    name: 'effective_date_end',
                    label: '结束有效时间',
                    width: 150
                },
                {
                    name: 'server_status',
                    label: '状态',
                    index: 'delete',
                    width: 80,
                    align: 'center',
                    formatter: i.statusFmatter
                }

            ];
            this.mod_PageConfig.gridReg('grid', c),
                c = this.mod_PageConfig.conf.grids.grid.colModel,
                $('#grid').jqGrid({
                    url: './admin.php?ctl=Purchase_Information&met=getList&typ=json',
                    postData: queryConditions,
                    datatype: 'json',
                    autowidth: !0,
                    height: b.h,
                    altRows: !0,
                    gridview: !0,
                    multiselect: !0,
                    colModel: c,
                    cmTemplate: {
                        sortable: !1,
                        title: !1
                    },
                    page: 1,
                    sortname: 'number',
                    sortorder: 'desc',
                    pager: '#page',
                    rowNum: 100,
                    rowList: [
                        100,
                        200,
                        500
                    ],
                    viewrecords: !0,
                    shrinkToFit: !1,
                    forceFit: !0,
                    jsonReader: {
                        root: 'data.rows',
                        records: 'data.records',
                        repeatitems: !1,
                        id: 'id'
                    },
                    loadError: function (a, b, c) {
                    },
                    ondblClickRow: function (a, b, c, d) {
                        $('#' + a).find('.ui-icon-pencil').trigger('click')
                    },
                    resizeStop: function (a, b) {
                        THISPAGE.mod_PageConfig.setGridWidthByIndex(a, b, 'grid')
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
                        THISPAGE.mod_PageConfig.config()
                    },
                    position: 'last'
                })
        },
        reloadData: function (a) {
            $('#grid').jqGrid('setGridParam', {
                url: './admin.php?ctl=Purchase_Information&met=getList&typ=json',
                datatype: 'json',
                postData: a
            }).trigger('reloadGrid')
        },
        addEvent: function () {
            var a = this;
            $('.grid-wrap').on('click', '.ui-icon-pencil', function (a) {
                a.preventDefault();
                var b = $(this).parent().data('id');
                parent.tab.addTabItem({
                    tabid: 'storage-adjustment',
                    text: '购买信息',
                    url: './admin.php?ctl=Purchase_Information&id=' + b
                });
                $('#grid').jqGrid('getDataIDs');
                parent.salesListIds = $('#grid').jqGrid('getDataIDs')
            }),
                $('.grid-wrap').on('click', '.ui-icon-trash', function (a) {
                    if (a.preventDefault(), Business.verifyRight('CADJ_DELETE')) {
                        var b = $(this).parent().data('id');
                        $.dialog.confirm('您确定要删除这条信息记录么？', function () {
                            Public.ajaxPost('/scm/invOi.do?action=deleteCbtz', {
                                id: b
                            }, function (a) {
                                if (200 === a.status && a.msg && a.msg.length) {
                                    var b = '<p>操作成功！</p>';
                                    for (var c in a.msg) 'function' != typeof a.msg[c] && (c = a.msg[c], b += '<p class="' + (1 == c.isSuccess ? '' : 'red') + '">成本调整单［' + c.id + '］删除' + (1 == c.isSuccess ? '成功！' : '失败：' + c.msg) + '</p>');
                                    parent.Public.tips({
                                        content: b
                                    })
                                } else parent.Public.tips({
                                    type: 1,
                                    content: a.msg
                                });
                                $('#search').trigger('click')
                            })
                        })
                    }
                }),
                $('.wrapper').on('click', '#btn-batchDel', function (a) {
                    if (!Business.verifyRight('CADJ_DELETE')) return void a.preventDefault();
                    var b = $('#grid').jqGrid('getGridParam', 'selarrrow'),
                        c = b.join();
                    return c ? void $.dialog.confirm('您确定要删除选中的购买信息记录么？', function () {
                        Public.ajaxPost('/scm/invOi.do?action=deleteCbtz', {
                            id: c
                        }, function (a) {
                            if (200 === a.status && a.msg && a.msg.length) {
                                var b = '<p>操作成功！</p>';
                                for (var c in a.msg) 'function' != typeof a.msg[c] && (c = a.msg[c], b += '<p class="' + (1 == c.isSuccess ? '' : 'red') + '">成本调整单［' + c.id + '］删除' + (1 == c.isSuccess ? '成功！' : '失败：' + c.msg) + '</p>');
                                parent.Public.tips({
                                    content: b
                                })
                            } else parent.Public.tips({
                                type: 1,
                                content: a.msg
                            });
                            $('#search').trigger('click')
                        })
                    })  : void parent.Public.tips({
                        type: 2,
                        content: '请先选择需要删除的项！'
                    })
                }),
                $('#search').click(function () {
                    queryConditions.matchCon = '请输入单据号或客户名或备注' === a.$_matchCon.val() ? '' : a.$_matchCon.val(),
                        queryConditions.beginDate = a.$_beginDate.val(),
                        queryConditions.endDate = a.$_endDate.val(),
                        queryConditions.locationId = a.storageCombo.getValue(),
                        THISPAGE.reloadData(queryConditions)
                }),
                $('#grid').on('click', '.set-status', function (a) {

                }),
                $('#moreCon').click(function () {
                    queryConditions.matchCon = a.$_matchCon.val(),
                        queryConditions.beginDate = a.$_beginDate.val(),
                        queryConditions.endDate = a.$_endDate.val(),
                        $.dialog({
                            id: 'moreCon',
                            width: 480,
                            height: 300,
                            min: !1,
                            max: !1,
                            title: '高级搜索',
                            button: [
                                {
                                    name: '确定',
                                    focus: !0,
                                    callback: function () {
                                        var b = this.content.handle(queryConditions);
                                        THISPAGE.reloadData(b),
                                        '' !== b.matchCon && a.$_matchCon.val(b.matchCon),
                                            a.$_beginDate.val(b.beginDate),
                                            a.$_endDate.val(b.endDate)
                                    }
                                },
                                {
                                    name: '取消'
                                }
                            ],
                            resize: !1,
                            content: 'url:/storage/other-search.jsp?type=other',
                            data: queryConditions
                        })
                }),
                $('#add').click(function (a) {
                    a.preventDefault(),
                    Business.verifyRight('CADJ_ADD') && parent.tab.addTabItem({
                        tabid: 'storage-adjustment',
                        text: '购买信息',
                        url: '/scm/invOi.do?action=initOi&type=cbtz'
                    })
                }),
                $(window).resize(function () {
                    Public.resizeGrid()
                }),
                $('.wrapper').on('click', '#export', function (a) {
                    if (!Business.verifyRight('CADJ_EXPORT')) return void a.preventDefault();
                    var b = $('#grid').jqGrid('getGridParam', 'selarrrow'),
                        c = b.join(),
                        d = c ? '&id=' + c : '';
                    for (var e in queryConditions) queryConditions[e] && (d += '&' + e + '=' + queryConditions[e]);
                    var f = '/scm/invOi.do?action=exportInvCadj' + d;
                    $(this).attr('href', f)
                })
        }
    };

$(function () {
    THISPAGE.init()
});
