var handle = {
    //修改、新增
    operate: function (oper, row_id)
    {
        if (oper === 'add' && !row_id)
        {
            var title = _('新增分站');
            var data = {
                oper: oper,
                callback: this.callback
            };
            var met = 'subsiteManage';
        }else if(oper === 'subsiteDistrict' && row_id){
            var title = sprintf(_('设置分站地区 [%s]'), $("#grid").data('gridData')[row_id]['sub_site_name']);
            var data = {
                oper: oper,
                parent_id: row_id,
                rowData: $("#grid").data('gridData')[row_id],
                callback: this.callback
            };
            var met = 'subsiteDistrict';
        }else{   
            var title = sprintf(_('修改分站 [%s]'), $("#grid").data('gridData')[row_id]['sub_site_name']);
            var data = {
                oper: oper,
                rowId: row_id,
                rowData: $("#grid").data('gridData')[row_id],
                callback: this.callback
            };

            var met = 'subsiteManage';
        }

        $.dialog({
            title: title,
            content: 'url:' + SITE_URL + '?ctl=Subsite_Config&met=' + met + '&typ=e',
            data: data,
            max: false,
            min: false,
            width:700,
            height:$(window).height(),
            cache: false,
            lock: true,
            zIndex:999
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
        gridData[data.items.id] = data.items;

        if (oper === "edit" || oper === "close" || oper === "verify" || oper === "subsiteDistrict")
        {
            $("#grid").jqGrid('setRowData', data.items.id, data.items);

            dialogWin && dialogWin.api.close();
        }
        else
        {
            $("#grid").jqGrid('addRowData', data.items.id, data.items, 'first');
            dialogWin && dialogWin.api.close();
        }
    },

    //操作项格式化，适用于有“修改、删除”操作的表格
    operFmatter: function (val, opt, row)
    {
        // <span class="ui-icon ui-icon-plus" title="添加"></span>
        var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-plus" title="添加"></span></div>';


        return html_con;
    },

    // 分站状态项格式化 适用于启用|停用分站
    statusFmatter: function (val, opt, row)
    {
        var text = val == 1 ? '已启用' : '已停用';
        var cls = val == 1 ? 'ui-label-success' : 'ui-label-default';
        html_con =  '<span class="set-status ui-label ' + cls + '" data-enable="' + val + '" data-id="' + row.id + '">' + text + '</span>';
        return html_con;
    },

     //修改状态
    setStatus: function (id, is_enable)
    {
        if (!id)
        {
            return;
        }
        Public.ajaxPost(SITE_URL + '?ctl=Subsite_Config&met=setSubsiteState&typ=json', {
            subsite_id: id,
            enable: Number(is_enable)
        }, function (data)
        {
            if (data && data.status == 200)
            {
                parent.Public.tips({content: '状态修改成功！'});
                $('#grid').jqGrid('setCell', id, 'sub_site_is_open', is_enable);
            }
            else
            {
                parent.Public.tips({type: 1, content: '状态修改失败！' + data.msg});
            }
        });
    }


};

var grid_row = Public.setGrid();
var colModel = [{
    "name": "operate",
    "label": "操作",
    "width": 60,
    "sortable": false,
    "search": false,
    "resizable": false,
    "fixed": true,
    "align": "center",
    "title": false,
    "formatter": handle.operFmatter
}, {
    "name": "subsite_id",
    "index": "subsite_id",
    "label": "分站id",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "fixed": true,
    "width": 100
}, {
    "name": "sub_site_name",
    "index": "sub_site_name",
    "label": "分站名称",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "width":200
}, /*{
    "name": "sub_site_parent_id",
    "index": "sub_site_parent_id",
    "label": "父id",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "fixed": true,
    "width": 60
}, */{
    "name": "sub_site_domain",
    "index": "sub_site_domain",
    "label": "分站域名前綴",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "fixed": true,
    "width": 160
}, {
    "name": "sub_site_is_open",
    "index": "sub_site_is_open",
    "label": "分站状态",
    "classes": "ui-ellipsis",
    "align": "center",
    "title": false,
    "width": 100,
    "formatter": handle.statusFmatter
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
            e.preventDefault();
            var id = $(this).parent().data('id');
            handle.operate('subsiteDistrict', id);
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

        //设置状态
        $('#grid').on('click', '.set-status', function (e)
        {
            e.stopPropagation();
            e.preventDefault();

            var id = $(this).data('id'),
                is_enable = Number(!$(this).data('enable'));
            handle.setStatus(id, is_enable);
        });

    }

    function initGrid()
    {

        $("#grid").jqGrid({
            url: SITE_URL + '?ctl=Subsite_Config&met=getSubsiteList&typ=json&is_delete=2',
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
            ExpandColumn: "sub_site_name",
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
                        item['id'] = item.subsite_id;
                        gridData[item.subsite_id] = item;
                    }

                    $("#grid").data('gridData', gridData);
                } else {
                    var msg = response.status === 250 ? '没有数据哦！' : response.msg;
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
                "parent_id_field": "sub_site_parent_id",
                "level_field": "subsite_level",
                "leaf_field": "is_leaf",
                "expanded_field": "expanded",
                "loaded": "loaded",
                "icon_field": "subsite_icon"
            }
        })
    }

    initGrid();
    initEvent();
});
