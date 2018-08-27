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
                    name: 'user_name',
                    label: '运营商标记',
                    width: 250,
                    align: 'center'
                },
                {
                    name: 'app_id',
                    label: '应用在官方平台的app_id',
                    width: 450,
                    align: 'center'
                },
                {
                    name: 'server_id',
                    label: '服务器id',
                    width: 200,
                    align: 'center'
                },
                {
                    name: 'active_time',
                    label: '激活时间',
                    width: 250,
                    align: 'center'
                }
            ];
        j.gridReg('grid', l),
            l = j.conf.grids.grid.colModel,

            $('#grid').jqGrid({

                url: './index.php?ctl=UserServer_UserServer&met=getUserServerlist&typ=json',

                datatype: 'json',
                width: '600px',
                height: '460px',
                //altRows: !0,
                gridview: !0,
                onselectrow: !1,
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
                shrinkToFit: !1,
                forceFit: !0,
                jsonReader: {
                    root: 'data.items',
                    records: 'data.records',
                    total: 'data.total',
                    repeatitems: !1,
                    id: 'user_name'
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
            });
    }

    function c() {
        $_matchCon = $('#matchCon'),
        $_app_id = $('#app_id'),
            $_matchCon.placeholder(),
            f=$('#beginDate').val(),
            g=$('#endDate').val(),
            $('#search').on('click', function (a) {
				//alert($_app_id.val());
                a.preventDefault();
                var b = '请输入用户名查询' === $_matchCon.val() ? '' : $.trim($_matchCon.val()),
				   e = '请选择' === $_app_id.val() ? '' : $.trim($_app_id.val()),
                    c = $('#currentCategory').data('user_name');
                $('#grid').jqGrid('setGridParam', {
                    page: 1,
                    postData: {
                        skey: b,
						app_id:e,
                        username: c,
                        //beginDate:f,
                        //endDate:g
                    }
                }).trigger('reloadGrid')
            }),

				$('#grid').on('click', '.operating .ui-icon-pencil', function (a) {
					if (a.preventDefault(), Business.verifyRight('INVENTORY_UPDATE')) {
						var b = $(this).parent().data('id');
						h.operate('edit', b)
					}
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
                        c = $('#currentCategory').data('user_name') || '';
                    $(this).attr('href', 'index.php?ctl=UserServer_UserServer&met=getUserServerlist&skey=' + b + '&username=' + c)
                }
            });
			
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
            $('.innerTree').height($('#tree').height() - 95),

			uiHandle = {
            init:function(){
                this.comboControl();
            },
            comboControl:function(){
                _page.$storeNameCombo = Business.storeCombo(_page.$app_id, {
                    width: 100,
                    addOptions: {
                        text: 'app_id',
                        value: 'app_id'
                    },
                    callback: {//下拉框的值变化会会触发查询按钮
                        onChange: function(data) {
                            $('#search').trigger("click");
                        }
                    }
                });
            }
        }
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
        c()
      
});
