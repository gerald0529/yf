//消息模板

$(function ()
{
    var searchFlag = false;
    var filterClassCombo, catorageCombo;
    var handle = {
        //修改、新增
        operate: function (oper, row_id)
        {
            var new_msg_tpl_data = '';
            if (oper == 'add')
            {
                var title = '新增';
                var data = {oper: oper, callback: this.callback};
            }
            else
            {
                for(var i in msg_tpl_data){
                    if(msg_tpl_data[i].id == row_id){
                        new_msg_tpl_data = msg_tpl_data[i];
                    }
                }
                var title = '修改';
                var data = {oper: oper, rowData: new_msg_tpl_data, callback: this.callback};
            }
            $.dialog({
                title: title,
                content: 'url:' + SITE_URL + '?ctl=Config&met=manageMsgTpl&typ=e',
                data: data,
                max: false,
                min: false,
                cache: false,
                lock: true
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
            console.log(data);
            if (oper == "edit")
            {
                // $("#grid").jqGrid('setRowData', data.id, data);
                window.location.reload();
                dialogWin && dialogWin.api.close();
            }
            else
            {
                $("#grid").jqGrid('addRowData', data.id, data, 'first');
                dialogWin && dialogWin.resetForm(data);
            }
        },

        //操作项格式化，适用于有“修改、删除”操作的表格
        operFmatter: function (val, opt, row)
        {
            var html_con = '<div class="operating" data-id="' + row.config_key + '"><span class="ui-icon ui-icon-pencil" title="修改"></span></div>';
            return html_con;
        }
    };

    function initDom()
    {
        var defaultPage = Public.getDefaultPage();
        defaultPage.SYSTEM = defaultPage.SYSTEM || {};
    };

    function initGrid()
    {
        var grid_row = Public.setGrid();
        var colModel = [
            {
                "name": "operate",
                "label": "操作",
                "width": 40,
                "sortable": false,
                "search": false,
                "resizable": false,
                "fixed": true,
                "align": "center",
                "title": false,
                "formatter": handle.operFmatter
            },
            {
                "name": "config_key",
                "index": "config_key",
                "label": "类型Id",
                "classes": "ui-ellipsis",
                "title": false, hidden: true,
                "width": 100
            },
            {

                "name": "config_value",
                "index": "config_value",
                "label": "模板名称",
                "classes": "ui-ellipsis",
				"align": "center",
                "title": false,
                "fixed": true,
                "width": 200

            },
            {

                "name": "config_comment",
                "index": "config_comment",
                "label": "模板描述",
                "classes": "ui-ellipsis",
                "align": "center",
                "title": false,
                "fixed": true,
                "width": 500

            }];

        $('#grid').jqGrid({
            datatype: 'local',
            autowidth: true,
            shrinkToFit: false,
            forceFit: false,
            width: grid_row.w,
            height: grid_row.h,
            altRows: true,
            gridview: true,
            onselectrow: false,
            multiselect: false,//多选
            colModel: colModel,
            cmTemplate: {sortable: false},
            sortname: "config_key",//指定默认排序的列
            sortorder: "asc",//指定默认排序方式
            //分页
            pager: '#grid-pager',
            viewrecords: true,
            rowNum: 100,
            rowList: [100, 200, 500],
            prmNames: {//向后台传递的参数,重新命名
                //page:"page.currentPage",
                //rows:"page.pageSize"
            },
            //scroll: 1,
            jsonReader: {
                root: "data.items",
                records: "data.records",
                total: "data.total",
                repeatitems: false,
                id: "config_key"
            },
            resizeStop: function (newwidth, index)
            {
                //mod_PageConfig.setGridWidthByIndex(newwidth, index, 'grid');
            }
        }).navGrid('#page', {
            edit: false,
            add: false,
            del: false,
            search: false,
            refresh: false
        });

        for (var i = 0; i <= msg_tpl_data.length; i++)
        {
            $("#grid").jqGrid('addRowData', i + 1, msg_tpl_data[i]);
        }

        //Public.autoGrid($('#grid'));
    }

    function initEvent()
    {
        var match_con = $('#matchCon');
        //查询
        $('#search').on('click', function (e)
        {
            e.preventDefault();
            var skey = match_con.val() === '输入客户编号/ 名称/ 联系人/ 电话查询' ? '' : $.trim(match_con.val());
            var category_id = catorageCombo ? catorageCombo.getValue() : -1;
            $("#grid").jqGrid('setGridParam', {
                page: 1, postData: {
                    skey: skey,
                    category_id: category_id
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
                width: 560,
                height: 300,
                title: '批量导入',
                content: 'url:/import.jsp',
                lock: true
            });
        });
        //修改
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
        //删除
        $('#grid').on('click', '.operating .ui-icon-trash', function (e)
        {
            e.preventDefault();

            var id = $(this).parent().data('id');
            handle.del(id + '');
        });
        //批量删除
        $('#btn-batchDel').click(function (e)
        {
            e.preventDefault();

            var ids = $('#grid').jqGrid('getGridParam', 'selarrrow');
            ids.length ? handle.del(ids.join()) : parent.Public.tips({type: 2, content: '请选择需要删除的项'});
        });
        //禁用
        $('#btn-disable').click(function (e)
        {
            e.preventDefault();
            var ids = $("#grid").jqGrid('getGridParam', 'selarrrow').concat();
            if (!ids || ids.length == 0)
            {
                parent.Public.tips({type: 1, content: ' 请先选择要禁用的！'});
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
                parent.Public.tips({type: 1, content: ' 请先选择要启用的！'});
                return;
            }
            handle.setStatuses(ids, false);
        });
        //设置状态
        $('#grid').on('click', '.set-status', function (e)
        {
            e.stopPropagation();
            e.preventDefault();

            var id = $(this).data('id'),
                is_delete = !$(this).data('delete');
            handle.setStatus(id, is_delete);
        });
    }

    //var mod_PageConfig = Public.mod_PageConfig.init('customerList');//页面配置初始化
    initDom();
    initGrid();
    initEvent();
    var aaa = $("#grid").getDataIDs();
    console.log(aaa);
});