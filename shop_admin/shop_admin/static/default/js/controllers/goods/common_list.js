$(function() {

    $('.wrapper').on('click', '#audit', function (a) {
        a.preventDefault();
        var b = $('#grid').jqGrid('getGridParam', 'selarrrow'),
            c = b.join();
        return c ? void Public.ajaxPost('./index.php?ctl=Goods_Goods&met=upGoods&typ=json', {
            id: c
        }, function (a) {
            200 === a.status ? parent.Public.tips({
                content: '上架成功！'
            })  : parent.Public.tips({
                type: 1,
                content: a.msg
            }),
                $('#search').trigger('click')
        })  : void parent.Public.tips({
            type: 2,
            content: '请先选择需要上架的项！'
        })
    });

    $('.wrapper').on('click', '#reaudit', function (a) {
        a.preventDefault();
        var b = $('#grid').jqGrid('getGridParam', 'selarrrow'),
            c = b.join();
        return c ? void Public.ajaxPost('./index.php?ctl=Goods_Goods&met=checkGoods&typ=json', {
            id: c
        }, function (a) {
            200 === a.status ? parent.Public.tips({
                content: '审核成功！'
            })  : parent.Public.tips({
                type: 1,
                content: a.msg
            }),
                $('#search').trigger('click')
        })  : void parent.Public.tips({
            type: 2,
            content: '请先选择需要审核的项！'
        })
    });

    var searchFlag = false;
    var filterClassCombo, stateCombo, verifyCombo;
    var handle = {
        //修改、新增
        operate: function(oper, row_id) {
            if (oper == 'add') {
                var title = '新增';
                var data = {
                    oper: oper,
                    callback: this.callback
                };
                var met = 'manage';
            }
            else if (oper == 'close'){
                var title = sprintf(_('违规商品 [%s] 下架理由'), row_id);
                var data = {
                    oper: oper,
                    rowId: row_id,
                    rowData: $("#grid").data('gridData')[row_id],
                    callback: this.callback
                };
                var met = 'manage';
            }
            else if (oper == 'getSku'){
                var title = sprintf(_('商品 [%s] 的SKU类表'), row_id);
                var data = {
                    oper: oper,
                    rowId: row_id,
                    rowData: $("#grid").data('gridData')[row_id],
                    callback: this.callback
                };
                var met = 'goodsManage&common_id=' + row_id;
            }
            else if (oper == 'verify'){
                var title = sprintf(_('审核商品 [%s]'), row_id);
                var data = {
                    oper: oper,
                    rowId: row_id,
                    rowData: $("#grid").data('gridData')[row_id],
                    callback: this.callback
                };
                var met = 'verifyManage&common_id=' + row_id;
            }
            else
            {
                var title = '修改';
                var data = {
                    oper: oper,
                    rowId: row_id,
                    rowData: $("#grid").data('gridData')[row_id],
                    callback: this.callback
                };
                var met = 'manage';
            }

            $.dialog({
                title: title,
                content: 'url:' + SITE_URL + '?ctl=Goods_Goods&met=' + met + '&typ=e',
                data: data,
                // width: $(window).width() * 0.8,
                 width:760,
                // height: $(window).height() * 0.9,
                height:320,
                max: false,
                min: false,
                cache: false,
                lock: true
            });
        },
        //删除
        del: function(row_ids) {
            $.dialog.confirm('删除的将不能恢复，请确认是否删除？', function() {
                Public.ajaxPost(SITE_URL + '?ctl=Goods_Goods&met=removeCommon&typ=json', {
                    common_id: row_ids
                }, function(data) {
                    if (data && data.status == 200) {
                        var id_arr = data.data.id || [];
                        if (row_ids.split(',').length === id_arr.length) {
                            parent.Public.tips({
                                content: '成功删除' + id_arr.length + '个！'
                            });
                        } else {
                            parent.Public.tips({
                                type: 2,
                                content: data.data.msg
                            });
                        }
                        for (var i = 0, len = id_arr.length; i < len; i++) {
                            $('#grid').jqGrid('setSelection', id_arr[i]);
                            $('#grid').jqGrid('delRowData', id_arr[i]);
                        };
                    } else {
                        parent.Public.tips({
                            type: 1,
                            content: '删除失败！' + data.msg
                        });
                    }
                });
            });
        },

        //下架
        close: function(row_ids) {
            $.dialog.confirm('删除的将不能恢复，请确认是否删除？', function() {
                Public.ajaxPost(SITE_URL + '?ctl=Goods_Goods&met=remove&typ=json', {
                    common_id: row_ids
                }, function(data) {
                    if (data && data.status == 200) {
                        var id_arr = data.data.id || [];
                        if (row_ids.split(',').length === id_arr.length) {
                            parent.Public.tips({
                                content: '成功删除' + id_arr.length + '个！'
                            });
                        } else {
                            parent.Public.tips({
                                type: 2,
                                content: data.data.msg
                            });
                        }
                        for (var i = 0, len = id_arr.length; i < len; i++) {
                            $('#grid').jqGrid('setSelection', id_arr[i]);
                            $('#grid').jqGrid('delRowData', id_arr[i]);
                        };
                    } else {
                        parent.Public.tips({
                            type: 1,
                            content: '删除失败！' + data.msg
                        });
                    }
                });
            });
        },

        //修改状态
        setStatus: function(id, is_enable) {
            if (!id) {
                return;
            }
            Public.ajaxPost(SITE_URL + '?ctl=Goods_Goods&met=disable&typ=json', {
                common_id: id,
                disable: Number(is_enable)
            }, function(data) {
                if (data && data.status == 200) {
                    parent.Public.tips({
                        content: '状态修改成功！'
                    });
                    $('#grid').jqGrid('setCell', id, 'enable', is_enable);
                } else {
                    parent.Public.tips({
                        type: 1,
                        content: '状态修改失败！' + data.msg
                    });
                }
            });
        },
        //批量修改状态
        setStatuses: function(ids, is_enable) {
            if (!ids || ids.length == 0) {
                return;
            }
            var arr_ids = $('#grid').jqGrid('getGridParam', 'selarrrow')
            var sel_ids = arr_ids.join();
            Public.ajaxPost(SITE_URL + '?ctl=Goods_Goods&met=disable&typ=json', {
                common_id: sel_ids,
                disable: Number(is_enable)
            }, function(data) {
                if (data && data.status == 200) {
                    parent.Public.tips({
                        content: '状态修改成功！'
                    });
                    for (var i = 0; i < ids.length; i++) {
                        var id = ids[i];
                        $('#grid').jqGrid('setCell', id, 'enable', is_enable);
                    }
                } else {
                    parent.Public.tips({
                        type: 1,
                        content: '状态修改失败！' + data.msg
                    });
                }
            });
        },
        callback: function(data, oper, dialogWin) {
            var gridData = $("#grid").data('gridData');
            if (!gridData) {
                gridData = {};
                $("#grid").data('gridData', gridData);
            }

            gridData[data.id] = data;

            console.info(data.id);
            console.info(data);
            if (oper == "edit" || oper == "close" ||  oper == "verify") {
                $("#grid").jqGrid('setRowData', data.id, data);
                //$('#grid').jqGrid('setCell', data.id, 'common_state', data.common_state);

                dialogWin && dialogWin.api.close();
            } else {
                $("#grid").jqGrid('addRowData', data.id, data, 'first');
                dialogWin && dialogWin.resetForm(data);
            }
        },

        //操作项格式化，适用于有“修改、删除”操作的表格
        operFmatter: function(val, opt, row) {
            var text = row.common_state == 10 ? '删除' : '违规下架';
            var cls = row.common_state == 10 ? 'ui-icon-trash' : 'ui-icon-circle-arrow-s';

            var verify_str = row.common_verify == 10 ? '<span class="ui-icon ui-icon-search" title="审核"></span>' : '';

            // var gid = row.goods_id[0].goods_id;
            // var url = SHOP_URL + '?ctl=Goods_Goods&met=goodspreview&typ=e&gid='+gid+'&au=ad';
            // var preview = row.common_verify == 10 ? '<a href="'+url+'" target="_blank"><span class="ui-icon ui-icon-link" title="预览"></span></a>': '';

            var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-cart" title="查看商品SKU详情"></span><span class="ui-icon ' + cls + '" title="' + text + '"></span>' + verify_str + '</div>' ;


            return html_con;
        },

        stateFmatter: function(val, opt, row) {
            var text, cls;

            if (val == 10)
            {
                text = _('违规下架');
                cls = 'ui-label-default';
            }
            else if (val == 1)
            {
                text = _('正常');
                cls = 'ui-label-success';
            }
            else
            {
                text = _('卖家下架');
                cls = 'ui-label-default';
            }

            return '<span class="set-status ui-label ' + cls + '" data-enable="' + val + '" data-id="' + row.id + '">' + text + '</span>';
        },
        imageFmatter: function (val, opt, row)
        {
            if (row.common_image)
            {
                val = '<img src="' + row.common_image + '">';
            }
            else
            {
                val = '&#160;';
            }
            return val;
        },
        verifyFmatter: function (val, opt, row)
        {
            var text, cls;

            if (val == 10)
            {
                text = _('待审核');
                cls = 'ui-label-default';
            }
            else if (val == 1)
            {
                text = _('通过');
                cls = 'ui-label-success';
            }
            else
            {
                text = _('未通过');
                cls = 'ui-label-default';
            }

            return '<span class="set-status ui-label ' + cls + '" data-enable="' + val + '" data-id="' + row.id + '">' + text + '</span>';

            return val;
        },
        stockFmatter: function (val, opt, row)
        {
            var text, cls;

            if (row.isAlarm)
            {
                text = _('库存不足');
                cls = 'ui-label-important';
            }
            else
            {
                text = _('库存充足');
                cls = 'ui-label-success';
            }

            return '<span class="ui-label ' + cls + '">' + text + '</span>';
        },
    };

    // function test(){
    //     return $("#userid").val();
    // }

    function initDom() {
        var defaultPage = Public.getDefaultPage();
        defaultPage.SYSTEM = defaultPage.SYSTEM || {};
        defaultPage.SYSTEM.categoryInfo = defaultPage.SYSTEM.categoryInfo || {};
        stateCombo = Business.categoryCombo($('#common_state'), {
            editable: false,
            extraListHtml: '',
            addOptions: {
                value: -1,
                text: '选择商品状态'
            },
            defaultSelected: 0,
            trigger: true,
            width: 120
        }, 'goods_state');


        verifyCombo = Business.categoryCombo($('#common_verify'), {
            editable: false,
            extraListHtml: '',
            addOptions: {
                value: -1,
                text: '选择审核状态'
            },
            defaultSelected: 0,
            trigger: true,
            width: 120
        }, 'goods_verify');

        stateCombo.selectByValue(common_state);
        verifyCombo.selectByValue(common_verify);
    };

    function initGrid() {
        var grid_row = Public.setGrid();
        var colModel = [{
            "name": "operate",
            "label": "操作",
            "width": 80,
            "sortable": false,
            "search": false,
            "resizable": false,
            "fixed": true,
            "align": "center",
            "title": false,
            "formatter": handle.operFmatter
        }, {
            "name": "common_id",
            "index": "common_id",
            "label": "商品SPU",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 100
        }, {
            "name": "common_name",
            "index": "common_name",
            "label": "商品名称",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width":200
        }, {
            "name": "common_price",
            "index": "common_price",
            "label": "商品价格",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "cat_id",
            "index": "cat_id",
            "label": "分类Id",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width":100
        }, {
            "name": "cat_name",
            "index": "cat_name",
            "label": "分类名称",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 200
        }, {
            "name": "shop_id",
            "index": "shop_id",
            "label": "店铺id",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 100
        }, {
            "name": "shop_name",
            "index": "shop_name",
            "label": "店铺名称",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 200
        },{
            "name": "common_image",
            "index": "common_image",
            "label": "商品主图",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100,
            "formatter": handle.imageFmatter ,
            classes:'img_flied'
        },  {
            "name": "brand_id",
            "index": "brand_id",
            "label": "品牌id",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width":100
        }, {
            "name": "brand_name",
            "index": "brand_name",
            "label": "品牌名称",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 200
        },{
            "name": "common_promotion_tips",
            "index": "common_promotion_tips",
            "label": "商品广告词",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        },{
            "name": "common_state",
            "index": "common_state",
            "label": "商品状态",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 100,
            "formatter": handle.stateFmatter
        }, {
            "name": "common_verify",
            "index": "common_verify",
            "label": "商品审核",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 100,
            "formatter": handle.verifyFmatter
        },
//        {
//            "name": "common_sell_time",
//            "index": "common_sell_time",
//            "label": "上架时间",
//            "classes": "ui-ellipsis",
//            "align": "center",
//            "title": false,
//            "fixed": true,
//            "width": 60
//        },  
        {
            "name": "common_market_price",
            "index": "common_market_price",
            "label": "市场价",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "common_cost_price",
            "index": "common_cost_price",
            "label": "成本价",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "common_stock",
            "index": "common_stock",
            "label": "商品库存",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width":100
        }, {
            "name": "common_stock_alarm",
            "index": "common_stock_alarm",
            "label": "商品预警库存",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 100,
            "formatter": handle.stockFmatter ,
        }, /*{
            "name": "common_code",
            "index": "common_code",
            "label": "商家编号",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        },{
            "name": "common_cubage",
            "index": "common_cubage",
            "label": "商品重量",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 100
        }, {
            "name": "common_commend",
            "index": "common_commend",
            "label": "商品推荐 1是，0否，默认为0",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 60
        }, {
            "name": "common_invoices",
            "index": "common_invoices",
            "label": "是否开具增值税发票",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 60
        },*/ {
            "name": "common_is_return",
            "index": "common_is_return",
            "label": "允许退货",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 100
        }];


        //mod_PageConfig.gridReg('grid', colModel);
        //colModel = mod_PageConfig.conf.grids['grid'].colModel;
        $('#grid').jqGrid({
            url: SITE_URL + '?ctl=Goods_Goods&met=listCommon&typ=json',
            postData: {
                common_state: common_state,
                common_verify: common_verify
            },
            datatype: 'json',
            autowidth: true,
            shrinkToFit: false,
            forceFit: true,
            width: grid_row.w,
            height: grid_row.h,
            altRows: true,
            gridview: true,
            /*onselectrow: false,
            multiselect: false, //多选*/
            multiselect:(common_state==10||common_verify==10)?true:false,
            multiboxonly: (common_state==10||common_verify==10)?true:false,
            colModel: colModel,
            pager: '#page',
            viewrecords: true,
            cmTemplate: {
                sortable: true
            },
            rowNum: 20,
            rowList: [20,50,100, 200, 500],
            //scroll: 1,
            jsonReader: {
                root: "data.items",
                records: "data.records",
                total: "data.total",
                repeatitems: false,
                id: "common_id"
            },
            loadComplete: function(response) {
                if (response && response.status == 200) {
                    var gridData = {};
                    data = response.data;
                    for (var i = 0; i < data.items.length; i++) {
                        var item = data.items[i];
                        item['id'] = item.common_id;
                        gridData[item.common_id] = item;
                    }

                    $("#grid").data('gridData', gridData);
                } else {
                    var msg = response.status === 250 ? (searchFlag ? '没有满足条件的结果哦！' : '没有数据哦！') : response.msg;
                    parent.Public.tips({
                        type: 2,
                        content: msg
                    });
                }
            },
            loadError: function(xhr, status, error) {
                parent.Public.tips({
                    type: 1,
                    content: '操作失败了哦，请检查您的网络链接！'
                });
            },
            resizeStop: function(newwidth, index) {
                //mod_PageConfig.setGridWidthByIndex(newwidth, index, 'grid');
            }
        }).navGrid('#page', {
            edit: false,
            add: false,
            del: false,
            search: false,
            refresh: false
        });
    }

    function initEvent() {
        var match_con = $('#matchCon');
        //查询
        $('#search').on('click', function(e) {
            e.preventDefault();
            var state_id = stateCombo ? stateCombo.getValue() : -1;
            var verify_id = verifyCombo ? verifyCombo.getValue() : -1;
            $("#grid").jqGrid('setGridParam', {
                page: 1,
                postData: {
                    common_name: $('#common_name').val(),
                    common_stock: $('#grid_common_stock').val(),
                    common_id: $('#common_id').val(),
                    shop_name: $('#shop_name').val(),
                    brand_id: $('#brand_id').data('id'),
                    common_state: state_id,
                    common_verify: verify_id,
                    cat_id: categoryTree.getValue()
                }
            }).trigger("reloadGrid");

        });

        //新增
        $('#btn-add').on('click', function(e) {
            e.preventDefault();
            handle.operate('add');
        });
        //导入
        $('#btn-import').on('click', function(e) {
            e.preventDefault();

            parent.$.dialog({
                width: 600,
                height: 300,
                title: '批量导入',
                content: 'url:/import.jsp',
                lock: true
            });
        });

        //查看详情
        $('#grid').on('click', '.operating .ui-icon-info', function(e) {
            e.preventDefault();
            //if (!Business.verifyRight('BU_UPDATE'))
            //{
            //    return;
            //}
            var id = $(this).parent().data('id');
            handle.operate('edit', id);
        });

        //审核
        $('#grid').on('click', '.operating .ui-icon-search', function(e) {
            e.preventDefault();
            //if (!Business.verifyRight('BU_UPDATE'))
            //{
            //    return;
            //}
            var id = $(this).parent().data('id');
            handle.operate('verify', id);
        });

        //查看SKU详情
        $('#grid').on('click', '.operating .ui-icon-cart', function(e) {
            e.preventDefault();
            //if (!Business.verifyRight('BU_UPDATE'))
            //{
            //    return;
            //}
            var id = $(this).parent().data('id');
            handle.operate('getSku', id);
        });


        //违规下架
        $('#grid').on('click', '.operating .ui-icon-circle-arrow-s', function(e) {
            e.preventDefault();

            var id = $(this).parent().data('id');
            handle.operate('close', id);
        });

        //违规的可以删除
        $('#grid').on('click', '.operating .ui-icon-trash', function(e) {
            e.preventDefault();

            var id = $(this).parent().data('id');
            handle.del(id + '');
        });


        //批量删除
        $('#btn-batchDel').click(function(e) {
            e.preventDefault();

            var ids = $('#grid').jqGrid('getGridParam', 'selarrrow');
            ids.length ? handle.del(ids.join()) : parent.Public.tips({
                type: 2,
                content: '请选择需要删除的项'
            });
        });
        //禁用
        $('#btn-disable').click(function(e) {
            e.preventDefault();
            var ids = $("#grid").jqGrid('getGridParam', 'selarrrow').concat();
            if (!ids || ids.length == 0) {
                parent.Public.tips({
                    type: 1,
                    content: ' 请先选择要禁用的！'
                });
                return;
            }
            handle.setStatuses(ids, true);
        });
        //启用
        $('#btn-enable').click(function(e) {
            e.preventDefault();
            var ids = $("#grid").jqGrid('getGridParam', 'selarrrow').concat();
            if (!ids || ids.length == 0) {
                parent.Public.tips({
                    type: 1,
                    content: ' 请先选择要启用的！'
                });
                return;
            }
            handle.setStatuses(ids, false);
        });
        /*
        //设置状态
        $('#grid').on('click', '.set-status', function(e) {
            e.stopPropagation();
            e.preventDefault();

            var id = $(this).data('id'),
                is_enable = !$(this).data('enable');
            handle.setStatus(id, is_enable);
        });
        */
    }
    // 初始化查询条件
    function initFilter()
    {
        //查询条件
        Business.filterBrand();

        //商品类别
        var opts = {
            width : 200,
            //inputWidth : (SYSTEM.enableStorage ? 145 : 208),
            inputWidth : 145,
            defaultSelectValue : '-1',
            //defaultSelectValue : rowData.categoryId || '',
            showRoot : true
        }

        categoryTree = Public.categoryTree($('#goods_cat'), opts);

    }

    //var mod_PageConfig = Public.mod_PageConfig.init('customerList');//页面配置初始化
    initDom();
    initGrid();
    initEvent();
    initFilter();
});