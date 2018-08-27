$(function() {
    var searchFlag = false;
    var filterClassCombo, catorageCombo;

    var defaultPage = Public.getDefaultPage();

    var handle = {
        //修改、新增
        operate: function(oper, row_id) {
            if (oper == 'add') {
                var title = '新增';
                var data = {
                    oper: oper,
                    callback: this.callback
                };
            } else {
                var title = '修改';
                var data = {
                    oper: oper,
                    rowId: row_id,
                    callback: this.callback
                };
            }

            defaultPage.tab.addTabItem({
                tabid: "goods_type_manage",
                text: title + _('商品类型'),
                url: SITE_URL + '?ctl=Goods_Type&met=manage&typ=e' + (row_id ? '&type_id=' + row_id : ''),
                showClose: true,
                data:data
            });

            return;
            $.dialog({
                title: title,
                content: 'url:' + SITE_URL + '?ctl=Goods_Type&met=manage&typ=e',
                data: data,
                width: $(window).width() * 0.8,
                height: $(window).height() * 0.8,
                max: false,
                min: false,
                cache: false,
                lock: true
            });
        },
        //删除
        del: function(row_ids) {
            $.dialog.confirm('删除的将不能恢复，请确认是否删除？', function() {
                Public.ajaxPost(SITE_URL + '?ctl=Goods_Type&met=remove&typ=json', {
                    type_id: row_ids
                }, function(data) {
                    if (data && data.status == 200) {
                        var id_arr = data.data.type_id || [];
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
                        updateType();
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
            Public.ajaxPost(SITE_URL + '?ctl=Goods_Type&met=disable&typ=json', {
                type_id: id,
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
            Public.ajaxPost(SITE_URL + '?ctl=Goods_Type&met=disable&typ=json', {
                type_id: sel_ids,
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
            //计算期初余额字段difMoney
            data.difMoney = data.amount - data.periodMoney;

            gridData[data.id] = data;

            if (oper == "edit") {
                $("#grid").jqGrid('setRowData', data.id, data);
                dialogWin && dialogWin.api.close();
            } else {
                $("#grid").jqGrid('addRowData', data.id, data, 'first');
                dialogWin && dialogWin.resetForm(data);
            }
        },

        //操作项格式化，适用于有“修改、删除”操作的表格
        operFmatter: function(val, opt, row) {
            var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
            return html_con;
        },

        statusFmatter: function(val, opt, row) {
            var text = val == true ? '已禁用' : '已启用';
            var cls = val == true ? 'ui-label-default' : 'ui-label-success';
            return '<span class="set-status ui-label ' + cls + '" data-enable="' + val + '" data-id="' + row.id + '">' + text + '</span>';
        },

        catNameFormatter:function(val, opt, row) {
            for (i in defaultPage.SYSTEM.goodsCatInfo)
            {
                if (val == defaultPage.SYSTEM.goodsCatInfo[i].id)
                {
                    return defaultPage.SYSTEM.goodsCatInfo[i].cat_name;
                }

            }

            return '';

        }
    };

    function initDom() {
        var defaultPage = Public.getDefaultPage();
        defaultPage.SYSTEM = defaultPage.SYSTEM || {};
        defaultPage.SYSTEM.categoryInfo = defaultPage.SYSTEM.categoryInfo || {};
        catorageCombo = Business.categoryCombo($('#catorage'), {
            editable: false,
            extraListHtml: '',
            addOptions: {
                value: -1,
                text: '选择类别'
            },
            defaultSelected: 0,
            trigger: true,
            width: 120
        }, 'customertype');
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
            "name": "type_id",
            "index": "type_id",
            "label": "ID",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 100
        }, {
            "name": "type_name",
            "index": "type_name",
            "label": "类型名称",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 200
        }, {
            "name": "type_displayorder",
            "index": "type_displayorder",
            "label": "排序",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 100
        }/*, {
            "name": "cat_id",
            "index": "cat_id",
            "label": "分类id",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "fixed": true,
            "width": 80
        }, {
            "name": "cat_id",
            "index": "cat_id",
            "label": "分类名称",
            "classes": "ui-ellipsis",
            "align": "center",
            "title": false,
            "width": 80,
            "formatter": handle.catNameFormatter
        }*/];
        //mod_PageConfig.gridReg('grid', colModel);
        //colModel = mod_PageConfig.conf.grids['grid'].colModel;
        $('#grid').jqGrid({
            url: SITE_URL + '?ctl=Goods_Type&met=lists&typ=json',
            datatype: 'json',
            autowidth: true,
            shrinkToFit: false,
            forceFit: true,
            width: grid_row.w,
            height: grid_row.h,
            altRows: true,
            gridview: true,
            onselectrow: false,
            multiselect: false, //多选
            colModel: colModel,
            pager: '#grid-pager',
            viewrecords: true,
            cmTemplate: {
                sortable: true
            },
            rowNum: 20,
            rowList: [20, 50, 100],
            //scroll: 1,
            jsonReader: {
                root: "data.items",
                records: "data.records",
                total: "data.total",
                repeatitems: false,
                id: "type_id"
            },
            loadComplete: function(response) {
                if (response && response.status == 200) {
                    var gridData = {};
                    data = response.data;
                    for (var i = 0; i < data.items.length; i++) {
                        var item = data.items[i];
                        item['id'] = item.type_id;
                        gridData[item.type_id] = item;
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

        $("#btn-refresh").click(function (t)
        {
            t.preventDefault();
            $("#grid").trigger("reloadGrid")
        });


        var match_con = $('#matchCon');
        //查询
        $('#search').on('click', function(e) {
            e.preventDefault();
            var skey = match_con.val() === '输入客户编号/ 名称/ 联系人/ 电话查询' ? '' : $.trim(match_con.val());
            var category_id = catorageCombo ? catorageCombo.getValue() : -1;
            $("#grid").jqGrid('setGridParam', {
                page: 1,
                postData: {
                    skey: skey,
                    category_id: category_id
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
                width: 560,
                height: 300,
                title: '批量导入',
                content: 'url:/import.jsp',
                lock: true
            });
        });
        //修改
        $('#grid').on('click', '.operating .ui-icon-pencil', function(e) {
            e.preventDefault();
            //if (!Business.verifyRight('BU_UPDATE'))
            //{
            //    return;
            //}
            var id = $(this).parent().data('id');
            handle.operate('edit', id);
        });
        //删除
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
        //设置状态
        $('#grid').on('click', '.set-status', function(e) {
            e.stopPropagation();
            e.preventDefault();

            var id = $(this).data('id'),
                is_enable = !$(this).data('enable');
            handle.setStatus(id, is_enable);
        });
    }

    //var mod_PageConfig = Public.mod_PageConfig.init('customerList');//页面配置初始化
    initDom();
    initGrid();
    initEvent();
});

//更新缓存数据并关闭窗口
function updateType(){
    $.get(SITE_URL + '?ctl=Goods_Type&met=lists&typ=json', function(a){
        if(a.status==200)
        {
            var items = a.data.items;
            var goods_type = new Array();
            if(items != 'undefined' && items){
                for(var i in items){
                    goods_type[i] = new Object();
                    goods_type[i].id = items[i]['type_id'];
                    goods_type[i].name = items[i]['type_name'];
                }
            }
            var defaultPage = Public.getDefaultPage();
            defaultPage.SYSTEM.categoryInfo.goods_type = goods_type;
        }
    });
}

