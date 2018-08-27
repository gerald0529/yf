/**
 * Created by xinze on 15/3/3.
 */
function initEvent()
{
    $("#btn-add").click(function (t)
    {
        //t.preventDefault();
        //Business.verifyRight("INVLOCTION_ADD") && handle.operate("add")
    });
    $("#btn-disable").click(function (t)
    {
        t.preventDefault();
        var e = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
        e && 0 != e.length ? handle.setStatuses(e, !0) : parent.Public.tips({type: 1, content: " 请先选择要禁用的仓库！"})
    });
    $("#btn-enable").click(function (t)
    {
        t.preventDefault();
        var e = $("#grid").jqGrid("getGridParam", "selarrrow").concat();
        e && 0 != e.length ? handle.setStatuses(e, !1) : parent.Public.tips({type: 1, content: " 请先选择要启用的仓库！"})
    });
    $("#btn-import").click(function (t)
    {
        t.preventDefault()
    });
    $("#btn-export").click(function (t)
    {
        t.preventDefault()
    });
    $("#btn-print").click(function (t)
    {
        t.preventDefault()
    });
    $("#btn-refresh").click(function (t)
    {
        t.preventDefault();
        $("#grid").trigger("reloadGrid")
    });
	
	$("#grid").on("click", ".operating .ui-icon-trash", function (t)
    {
        t.preventDefault();
        if (Business.verifyRight("INVLOCTION_DELETE"))
        {
            var e = $(this).parent().data("id");
            handle.del(e)
        }
    });
	
    /*
     $("#grid").on("click", ".operating .ui-icon-pencil", function (t)
     {
     t.preventDefault();
     if (Business.verifyRight("INVLOCTION_UPDATE"))
     {
     var e = $(this).parent().data("id");
     handle.operate("edit", e)
     }
     });
     $("#grid").on("click", ".operating .ui-icon-trash", function (t)
     {
     t.preventDefault();
     if (Business.verifyRight("INVLOCTION_DELETE"))
     {
     var e = $(this).parent().data("id");
     handle.del(e)
     }
     });
     */
    $(window).resize(function ()
    {
        Public.resizeGrid()
    })
}
function initGrid()
{
    var t = ["操作", "权限组名称"/*, "权限组内容"*/], e = [{
        name: "operate",
        width: 60,
        fixed: !0,
        align: "center",
        formatter: settingDataFormatter
    }, {name: "rights_group_name", index: "rights_group_name", width: 200, align: "center"}/*, {name: "rights_group_rights_ids", index: "rights_group_rights_ids", width: 650}*/];
    $("#grid").jqGrid({
        url: "./index.php?ctl=Rights_Group&met=rightsGroupList&typ=json",
        datatype: "json",
        height: Public.setGrid().h,
        altRows: !0,
        gridview: !0,
        colNames: t,
        colModel: e,
        autowidth: !0,
        pager: "#page",
        viewrecords: !0,
        cmTemplate: {sortable: !1, title: !1},
        page: 1,
        rowNum: 100,
        rowList: [100, 200, 500],
        shrinkToFit: !1,
        cellLayout: 8,
        //jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id:'rights_group_id'},
        jsonReader: {
            root: 'data.items',
            records: 'data.records',
            total: 'data.total',
            repeatitems: !1,
            id: 'rights_group_id'
        },
        loadComplete: function (t)
        {
            if (t && 200 == t.status)
            {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++)
                {
                    var item = t.items[i];
                    e[item.rights_group_id] = item
                }
                console.info(e)
                $("#grid").data("gridData", e)
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取职员数据失败！" + t.msg})
            }
        },
        loadError: function ()
        {
            parent.Public.tips({type: 1, content: "数据加载错误！"})
        }
    })
}

function settingDataFormatter(val, opt, row) {
    return '<div class="operating" data-id="' + row.rights_group_id + '"><a class="ui-icon ui-icon-pencil" title="详细设置授权信息" href="./index.php?ctl=Rights_Group&met=manage&rights_group_name=' + row.rights_group_name + '&rights_group_id=' + row.rights_group_id + '"></a><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
};

function statusFmatter(t, e, i)
{
    alert(222)
    var a = t === !0 ? "已禁用" : "已启用", n = t === !0 ? "ui-label-default" : "ui-label-success";
    return '<span class="set-status ui-label ' + n + '" data-delete="' + t + '" data-id="' + i.id + '">' + a + "</span>"
}
var handle = {
    operate: function (t, e)
    {
        if ("add" == t)
        {
            var i = "新增职员", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "修改职员", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
        }
        $.dialog({
            title: i,
            content: "url:./index.php?ctl=Rights_Group&met=manage",
            data: a,
            width: 400,
            height: 160,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
    },

    callback: function (t, e, i)
    {
        var a = $("#grid").data("gridData");

        if (!a)
        {
            a = {};
            $("#grid").data("gridData", a)
        }

        a[t.rights_group_id] = t;

        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.rights_group_id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.rights_group_id, t, "last");
            i && i.resetForm(t)
        }
    },

    del: function (t)
    {
        $.dialog.confirm("删除的权限组不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost("./index.php?ctl=Rights_Group&met=remove&typ=json", {rights_group_id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "删除失败！" + e.msg})
                }
            })
        })
    }, setStatus: function (t, e)
    {
        t && Public.ajaxPost("../basedata/invlocation.do?action=disable", {
            locationId: t,
            disable: Number(e)
        }, function (i)
        {
            if (i && 200 == i.status)
            {
                parent.Public.tips({content: "仓库状态修改成功！"});
                $("#grid").jqGrid("setCell", t, "delete", e)
            }
            else
            {
                parent.Public.tips({type: 1, content: "仓库状态修改失败！" + i.msg})
            }
        })
    }, setStatuses: function (t, e)
    {
        t && 0 != t.length && Public.ajaxPost("../basedata/invlocation.do?action=disable", {
            locationIds: JSON.stringify(t),
            disable: Number(e)
        }, function (i)
        {
            if (i && 200 == i.status)
            {
                parent.Public.tips({content: "仓库状态修改成功！"});
                for (var a = 0; a < t.length; a++)
                {
                    var n = t[a];
                    $("#grid").jqGrid("setCell", n, "delete", e)
                }
            }
            else
            {
                parent.Public.tips({type: 1, content: "仓库状态修改失败！" + i.msg})
            }
        })
    }
};
initEvent();
initGrid();