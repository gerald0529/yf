var handle = {
    //修改、新增
    operate: function (oper, row_id)
    {
        if (oper == 'add'&&!row_id)
        {
            var title = _('新增分类');
            var data = {
                oper: oper,
                callback: this.callback
            };
            var met = 'manage';
        }
        else if(oper == 'add'&&row_id)
        {
            var title = _('新增分类');
            var data = {
                oper: oper,
                parent_id: row_id,
                callback: this.callback
            };
            var met = 'manage';
        }
        else if (oper == 'editNav')
        {
            var title = sprintf(_('编辑分类导航 [%s]'), row_id);
            var data = {
                oper: oper,
                rowId: row_id,
                rowData: $("#grid").data('gridData')[row_id],
                callback: this.callback
            };
            var met = 'listCatNav&id='+row_id;
        }
        else
        {
            var title = sprintf(_('修改分类 [%s]'), $("#grid").data('gridData')[row_id]['cat_name']);
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
            content: 'url:' + SITE_URL + '?ctl=Goods_Cat&met=' + met + '&typ=e',
            data: data,
            // width: $(window).width() * 0.8,
            // height: $(window).height() * 0.9,
            width:1166,
            height: $(window).height(),
            // height:700,
            max: false,
            min: false,
            cache: false,
            lock: true
        });
    },
    //删除
    del: function (row_ids)
    {
        $.dialog.confirm('删除的将不能恢复，请确认是否删除？', function ()
        {
            Public.ajaxPost(SITE_URL + '?ctl=Goods_Cat&met=removeCat&typ=json', {
                cat_id: row_ids
            }, function (data)
            {
                if (data && data.status == 200)
                {
                    var id_arr = data.data.id || [];
                    if (row_ids.split(',').length === id_arr.length)
                    {
                        parent.Public.tips({
                            content: '成功删除' + id_arr.length + '个！'
                        });
                    }
                    else
                    {
                        parent.Public.tips({
                            type: 2,
                            content: data.data.msg
                        });
                    }
                    for (var i = 0, len = id_arr.length; i < len; i++)
                    {
                        $('#grid').jqGrid('setSelection', id_arr[i]);
                        $('#grid').jqGrid('delRowData', id_arr[i]);
                    }
                    updateGoodsCate();
                }
                else
                {
                    parent.Public.tips({
                        type: 1,
                        content: '删除失败！' + data.msg
                    });
                }
            });
        });
    },

    //下架
    close: function (row_ids)
    {
        $.dialog.confirm('删除的将不能恢复，请确认是否删除？', function ()
        {
            Public.ajaxPost(SITE_URL + '?ctl=Goods_Cat&met=remove&typ=json', {
                cat_id: row_ids
            }, function (data)
            {
                if (data && data.status == 200)
                {
                    var id_arr = data.data.id || [];
                    if (row_ids.split(',').length === id_arr.length)
                    {
                        parent.Public.tips({
                            content: '成功删除' + id_arr.length + '个！'
                        });
                    }
                    else
                    {
                        parent.Public.tips({
                            type: 2,
                            content: data.data.msg
                        });
                    }
                    for (var i = 0, len = id_arr.length; i < len; i++)
                    {
                        $('#grid').jqGrid('setSelection', id_arr[i]);
                        $('#grid').jqGrid('delRowData', id_arr[i]);
                    }
                    ;
                }
                else
                {
                    parent.Public.tips({
                        type: 1,
                        content: '删除失败！' + data.msg
                    });
                }
            });
        });
    },

    //修改状态
    setStatus: function (id, is_enable)
    {
        if (!id)
        {
            return;
        }
        Public.ajaxPost(SITE_URL + '?ctl=Goods_Cat&met=disable&typ=json', {
            cat_id: id,
            disable: Number(is_enable)
        }, function (data)
        {
            if (data && data.status == 200)
            {
                parent.Public.tips({
                    content: '状态修改成功！'
                });
                $('#grid').jqGrid('setCell', id, 'enable', is_enable);
            }
            else
            {
                parent.Public.tips({
                    type: 1,
                    content: '状态修改失败！' + data.msg
                });
            }
        });
    },
    callback: function (data, oper, dialogWin)
    {
        var gridData = $("#grid").data('gridData');
        if (!gridData)
        {
            gridData = {};
            $("#grid").data('gridData', gridData);
        }

        gridData[data.id] = data;

        if (oper == "edit" || oper == "close" || oper == "verify")
        {
            $.each(data, function(name,value){
                $("#grid").jqGrid('setRowData', value['id'], value);
                $("#grid").jqGrid('setRowData', value['id'], {ex_commission:data.ex_commission});
                gridData[value['id']] = value;
            });
            dialogWin && dialogWin.api.close();
        }
        else
        {
            /*$.each(data, function(name, value)
            {
                $("#grid").jqGrid('addRowData', value.id, value,'first');
                gridData[value['id']] = value;
            });*/
            dialogWin && dialogWin.api.close();
            $("#grid").trigger("reloadGrid");
        }
    },

    //操作项格式化，适用于有“修改、删除”操作的表格
    operFmatter: function (val, opt, row)
    {
        var nav_str = '';
        var add_str = '';
        if (1 == row.cat_level)
        {
            nav_str = '<span class="ui-icon ui-icon-search" title="编辑导航分类"></span>';
        }
        else
        {
            nav_str = '<span class="ui-icon ui-icon-search ui-icon-disabled" title="编辑导航分类"></span>';
        }

        if (4 == row.cat_level)
        {
            add_str = '<span class="ui-icon ui-icon-plus ui-icon-disabled" title="添加"></span>';
        }
        else
        {
            add_str = '<span class="ui-icon ui-icon-plus" title="添加"></span>';
        }


        var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span>' + nav_str + '<span class="ui-icon ui-icon-trash" title="删除"></span>' + add_str + '</div>';



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

    virtualFmatter: function(val, opt, row) {

        var text,cls;

        if (val == 1)
        {
            text = _('允许');
            cls = 'ui-label-success';
        }
        else
        {
            text = _('禁止');
            cls = 'ui-label-default';
        }

        return '<span class="set-status ui-label ' + cls + '" data-enable="' + val + '" data-id="' + row.id + '">' + text + '</span>';
    },

    showFmatter: function(val, opt, row) {

        if (val == 1)
        {
            text = _('SPU');
        }
        else if(val == 2)
        {
            text = _('颜色');
        }
        else
        {
            text = _('SPU');
        }

        return text;
    },

    commissionFmatter: function(val, opt, row) {

        return sprintf('%d \%', val);
    },
    excommissionFmatter:function(val,opt,row){
        if (val === undefined)
            return false;
        return sprintf('一级分佣比例：%d \% <br/>二级分佣比例：%d \% <br/>三级分佣比例：%d \% <br/> ',val[0],val[1],val[2]);
    },

    imageFmatter: function (val, opt, row)
    {
        if (row.cat_pic)
        {
            val = '<img src="' + row.cat_pic + '" style="width:100px;height:40px;">';
        }
        else
        {
            val = '&#160;';
        }

        return val;
    }
};

var grid_row = Public.setGrid();
var colModel = [{
    "name": "operate",
    "label": "操作",
    "width": 130,
    "sortable": false,
    "search": false,
    "resizable": false,
    "fixed": true,
    "align": "center",
    "title": false,
    "formatter": handle.operFmatter
}, {
    "name": "cat_displayorder",
    "index": "cat_displayorder",
    "label": "排序",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "fixed": true,
    "width": 100,
    "sortable": false
}, {
    "name": "cat_name",
    "index": "cat_name",
    "label": " 分类名称",
    "classes": "ui-ellipsis",
    "align": "left",
    "title": false,
    "width": 200,
    "sortable": false
}, {
    "name": "cat_id",
    "index": "cat_id",
    "label": "",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "fixed": true,
    "hidden": true,
    "width": 100,
    "sortable": false
},{
    "name": "type_id",
    "index": "type_id",
    "label": "类型",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "fixed": true,
    "width": 100,
    "sortable": false
}, {
    "name": "cat_commission",
    "index": "cat_commission",
    "label": "分佣比例",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "formatter": handle.commissionFmatter,
    "sortable": false,
    "width": 100,
}, {
    "name": "cat_is_virtual",
    "index": "cat_is_virtual",
    "label": "虚拟产品",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "fixed": true,
    "width": 100,
    "formatter": handle.virtualFmatter,
    "sortable": false
}, {
    "name": "cat_parent_id",
    "index": "cat_parent_id",
    "label": "父类",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "fixed": true,
    "hidden": true,
    "width": 100
}, {
    "name": "cat_pic",
    "index": "cat_pic",
    "label": "分类图片",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "width": 100,
    "formatter": handle.imageFmatter ,
    "sortable": false
}, {
    "name": "cat_is_wholesale",
    "index": "cat_is_wholesale",
    "label": "",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "fixed": true,
    "hidden": true,
    "width": 100
}, {
    "name": "cat_templates",
    "index": "cat_templates",
    "label": "",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "hidden": true,
    "width": 100,
    "sortable": false
},{
    "name": "ex_commission",
    "index": "ex_commission",
    "label": "推广佣金",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "hidden": true,
    "formatter": handle.excommissionFmatter,
    "sortable": false,
    "width": 150,
}];
jQuery(document).ready(function ($)
{
    function initEvent()
    {
        var match_con = $('#matchCon');
        //查询
        $('#search').on('click', function (e)
        {
            e.preventDefault();
            var state_id = stateCombo ? stateCombo.getValue() : -1;
            var verify_id = verifyCombo ? verifyCombo.getValue() : -1;
            $("#grid").jqGrid('setGridParam', {
                page: 1,
                postData: {
                    common_name: $('#common_name').val(),
                    cat_id: $('#cat_id').val(),
                    shop_name: $('#shop_name').val(),
                    brand_id: $('#brand_id').data('id'),
                    common_state: state_id,
                    common_verify: verify_id,
                    cat_id: categoryTree.getValue()
                }
            }).trigger("reloadGrid");

        });

        //新增
        $('#btn-add').on('click', function (e)
        {
            e.preventDefault();
            handle.operate('add');
        });
        //导入
        $('#btn-import').on('click', function (e)
        {
            e.preventDefault();

            parent.$.dialog({
                width: 600,
                height: 300,
                title: '批量导入',
                content: 'url:/import.jsp',
                lock: true
            });
        });
        $('#grid').on('click', '.operating .ui-icon-plus', function (e)
        {
            if (!$(e.target).hasClass('ui-icon-disabled'))
            {
                e.preventDefault();
                var id = $(this).parent().data('id');
                handle.operate('add', id);
            }

        });
        //
        $('#grid').on('click', '.operating .ui-icon-pencil', function (e)
        {
            e.preventDefault();
            //if (!Business.verifyRight('BU_UPDATE'))
            //{
            //    return;
            //}
            var id = $(this).parent().data('id');
            handle.operate('edit', id);
        });

        //审核
        $('#grid').on('click', '.operating .ui-icon-search', function (e)
        {

            if (!$(e.target).hasClass('ui-icon-disabled'))
            {
                e.preventDefault();
                //if (!Business.verifyRight('BU_UPDATE'))
                //{
                //    return;
                //}
                var id = $(this).parent().data('id');
                handle.operate('editNav', id);
            }
        });

        //查看SKU详情
        $('#grid').on('click', '.operating .ui-icon-config', function (e)
        {
            e.preventDefault();
            //if (!Business.verifyRight('BU_UPDATE'))
            //{
            //    return;
            //}
            var id = $(this).parent().data('id');
            handle.operate('getSku', id);
        });


        //违规下架
        $('#grid').on('click', '.operating .ui-icon-arrowthickstop-1-s', function (e)
        {
            e.preventDefault();

            var id = $(this).parent().data('id');
            handle.operate('close', id);
        });

        //违规的可以删除
        $('#grid').on('click', '.operating .ui-icon-trash', function (e)
        {
            e.preventDefault();

            var id = $(this).parent().data('id');
            handle.del(id + '');
        });


        //批量删除
        $('#btn-refresh').click(function (e)
        {
            e.preventDefault();
            $("#grid").trigger("reloadGrid")
        });
        //禁用
        $('#btn-disable').click(function (e)
        {
            e.preventDefault();
            var ids = $("#grid").jqGrid('getGridParam', 'selarrrow').concat();
            if (!ids || ids.length == 0)
            {
                parent.Public.tips({
                    type: 1,
                    content: ' 请先选择要禁用的！'
                });
                return;
            }
            handle.setStatuses(ids, true);
        });
        //启用
        $('#btn-enable').click(function (e)
        {
            e.preventDefault();
            var ids = $("#grid").jqGrid('getGridParam', 'selarrrow').concat();
            if (!ids || ids.length == 0)
            {
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

    function initGrid()
    {
        $("#grid").jqGrid({
            url: SITE_URL + '?ctl=Category&met=lists&typ=json&type_number=goods_cat&is_delete=2',
            datatype: "json",
            autowidth: true,
            shrinkToFit: false,
            forceFit: true,
            width: grid_row.w,
            height: grid_row.h,
            colModel: colModel,
            hoverrows: false,
            viewrecords: true,
            gridview: true,
            scrollrows: true,
            treeGrid: true,
            ExpandColumn: "cat_name",
            treedatatype: "json",
            treeGridModel: "adjacency",
            loadonce: true,
            subGrid: true,
            pager:"#page",
            rowNum: 100,
            rowList: [
                100,
                200,
                500
            ],
            "jsonReader": {
                root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "id"
            },
            loadComplete: function(response) {
                if (response && response.status == 200) {
                    var gridData = {};
                    data = response.data;
                    var cat_id=[];
                    for (var i = 0; i < data.items.length; i++) {
                        var item = data.items[i];
                        cat_id[i] = item.cat_id;
                    }
                    var _this = this;

                    Public.ajaxPost(SITE_URL + '?ctl=Fenxiao&met=getCatValuesList&typ=json', {cat_array: cat_id}, function (cata) {
                        if (cata.status == 200) {
                            var values = cata.data.values;
                            for (var i = 0; i < data.items.length; i++) {
                                var item = data.items[i],
                                     id = item.cat_id;
                                item['id'] = id;
                                item['ex_commission'] = values[id];
                                gridData[id] = item;
                                $(_this).jqGrid("setRowData", id, {
                                      ex_commission: values[id]
                                });
                            }

                            $("#grid").data('gridData', gridData);
                        }
                    });

                    Public.ajaxGet(SITE_URL + '?ctl=Fenxiao&met=getStatus&typ=json', {}, function (data) {
                        if (data.status == 200) {
                            if (data.data.status == 1) {
                                $("#grid").setGridParam().showCol("ex_commission");
                            }
                        }
                    });

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
            "treeReader": {
                "parent_id_field": "cat_parent_id",
                "level_field": "cat_level",
                "leaf_field": "is_leaf",
                "expanded_field": "expanded",
                "loaded": "loaded",
                "icon_field": "cat_icon"
            },
            imageFmatter: function (val, opt, row)
            {
                if (row.cat_pic)
                {
                    val = '<img src="' + row.cat_pic + '" style="width:100px;height:40px;">';
                }
                else
                {
                    val = '&#160;';
                }
                return val;
            }
        })
    }

    initGrid();
    initEvent();

});

//更新缓存数据并关闭窗口
function updateGoodsCate(){
    $.get(SITE_URL + '?ctl=Category&met=lists&typ=json&type_number=goods_cat&is_delete=2', function(a){
        if(a.status==200)
        {
            parent.SYSTEM.goodsCatInfo = a.data.items;

        }
    });
}
