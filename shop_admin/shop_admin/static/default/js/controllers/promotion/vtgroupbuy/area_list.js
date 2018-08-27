var handle = {
    //修改、新增
    operate: function (oper, row_id)
    {
        if (oper == 'add'&&!row_id)
        {
            var title = _('新增地区');
            var data = {
                oper: oper,
                callback: this.callback
            };
            var met = 'areaManage';
        }
        else if(oper == 'add'&&row_id)
        {
            var title = _('新增地区');
            var data = {
                oper: oper,
                parent_id: row_id,
                callback: this.callback
            };
            var met = 'areaManage';
        }
        else
        {
            var title = sprintf(_('编辑地区 [%s]'), $("#grid").data('gridData')[row_id]['groupbuy_area_name']);
            var data = {
                oper: oper,
                rowId: row_id,
                rowData: $("#grid").data('gridData')[row_id],
                callback: this.callback
            };
            console.info($("#grid").data('gridData')[row_id]);

            var met = 'areaManage';
        }

        $.dialog({
            title: title,
            content: 'url:' + SITE_URL + '?ctl=Promotion_GroupBuy&met=' + met + '&typ=e',
            data: data,
            width:503,
            height:156,
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
            Public.ajaxPost(SITE_URL + '?ctl=Promotion_GroupBuy&met=removeArea&typ=json', {
                groupbuy_area_id: row_ids
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

    callback: function (data, oper, dialogWin)
    {
        var gridData = $("#grid").data('gridData');
        if (!gridData)
        {
            gridData = {};
            $("#grid").data('gridData', gridData);
        }

        gridData[data.id] = data;

        console.info(data.id);
        console.info(data);
        if (oper == "edit" || oper == "close" || oper == "verify")
        {
            $.each(data, function(name,value){
                $("#grid").jqGrid('setRowData', value['id'], value);
                gridData[value['id']] = value;
            });
            dialogWin && dialogWin.api.close();
        }
        else
        {
            //$("#grid").jqGrid('addRowData', data.items.id, data.items, 'first');
            $("#grid").trigger("reloadGrid");
            dialogWin && dialogWin.api.close();
        }
    },

    //操作项格式化，适用于有“修改、删除”操作的表格
    operFmatter: function (val, opt, row)
    {
		if(row.groupbuy_area_parent_id > 0)
		{
			 var html_con = '<div class="operating" data-dis="1" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-plus  ui-icon-disabled" title="添加"></span></div>';
		}
		else
		{
			var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-plus" title="添加"></span></div>';
		}
        

        return html_con;
    }
};

var grid_row = Public.setGrid();
var colModel = [{
    "name": "operate",
    "label": "操作",
    "width": 70,
    "sortable": false,
    "search": false,
    "resizable": false,
    "fixed": true,
    "align": "center",
    "title": false,
    "formatter": handle.operFmatter
}, {
    "name": "groupbuy_area_name",
    "index": "groupbuy_area_name",
    "label": "地区名称",
    "classes": "ui-ellipsis",
    "align": "left",
    "title": false,
    "width": 200
}, {
    "name": "groupbuy_area_sort",
    "index": "groupbuy_area_sort",
    "label": "排序",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "fixed": true,
    "width": 100
}];

jQuery(document).ready(function ($)
{

    function initEvent()
    {
        var match_con = $('#matchCon');

        //新增
        $('#btn-add').on('click', function (e)
        {
            e.preventDefault();
            handle.operate('add');
        });

        $('#grid').on('click', '.operating .ui-icon-plus', function (e)
        {
			if($(this).parent().attr('data-dis'))
            {
                return false;
            }
			else
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
            var id = $(this).parent().data('id');
            handle.operate('edit', id);
        });

        //违规的可以删除
        $('#grid').on('click', '.operating .ui-icon-trash', function (e)
        {
            e.preventDefault();

            var id = $(this).parent().data('id');
            handle.del(id + '');
        });
    }

    function initGrid()
    {
        $("#grid").jqGrid({
            url: SITE_URL + '?ctl=Promotion_GroupBuy&met=getArea&typ=json&is_delete=2',
            //url: SITE_URL + '?ctl=Category&met=lists&typ=json&type_number=district&is_delete=2',
            datatype: "json",
            autowidth: true,
            shrinkToFit: false,
            forceFit: true,
            width: grid_row.w,
            height: grid_row.h,
            colModel: colModel,
            hoverrows: false,
            viewrecords: false,
            cmTemplate: {
                sortable: false
            },
            gridview: true,
            scrollrows: true,
            treeGrid: true,
            ExpandColumn: "groupbuy_area_name",
            treedatatype: "json",
            treeGridModel: "adjacency",
            loadonce: false,
            indentation: "200",
            multiselect:!0,
            onselectrow: !1,
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
                    var gridData = $("#grid").data('gridData');
                    if (!gridData)
                    {
                        gridData = {};
                        $("#grid").data('gridData', gridData);
                    }

                    data = response.data;
                    for (var i = 0; i < data.items.length; i++) {
                        var item = data.items[i];
                        item['id'] = item.groupbuy_area_id;
                        gridData[item.groupbuy_area_id] = item;
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
            "treeReader": {
                "parent_id_field": "groupbuy_area_parent_id",
                "level_field": "district_level",
                "leaf_field": "is_leaf",
                "expanded_field": "expanded",
                "loaded": "loaded",
                "icon_field": "district_icon"
            }
        })
    }

    initGrid();
    initEvent();
});
