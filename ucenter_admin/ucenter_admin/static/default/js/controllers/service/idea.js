
function initEvent()
{
    $_matchCon = $("#matchCon"),
        $_matchCon.placeholder(),
        $("#search").on("click", function (a)
        {
            a.preventDefault();
            var b = "输入问题编号查询" === $_matchCon.val() ? "" : $.trim($_matchCon.val());
            $("#grid").jqGrid("setGridParam", {page: 1, postData: {skey: b}}).trigger("reloadGrid")
        });

    $("#btn-add").click(function (t)
    {
        t.preventDefault();
        Business.verifyRight("INVLOCTION_ADD") && handle.operate("add")
    });

    $("#btn-refresh").click(function (t)
    {
        t.preventDefault();
        $("#grid").trigger("reloadGrid")
    });

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

    $(window).resize(function ()
    {
        Public.resizeGrid()
    })
}
function initGrid()
{
    var t = ["操作", "编号", "意见标题", "提问时间", "提问人员", "回复状态", "回复时间"], e = [{
        name: "operate",
        width: 60,
        fixed: !0,
        align: "center",
        formatter: Public.operFmatter
    },
        {name: "idea_id", index: "idea_id", width: 150},
        {name: "title", index: "title", width: 200},
        {name: "creat_time", index: "creat_time", align: "center", width: 100},
        {name: "creat_name", index: "creat_name", align: "center", width: 100},
        {name: "status", index: "status", align: "center", width: 100},
        {name: "respon_time", index: "respon_time", align: "center", width: 120}];

    $("#grid").jqGrid({
        url: "./index.php?ctl=Service_Idea&met=ideaList&typ=json&isDelete=2",
        datatype: "json",
        height: Public.setGrid().h,
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
        jsonReader: {root: "data.rows", records: "data.records", total: "data.total", repeatitems: !1, id: "vendor_id"},
        loadComplete: function (t)
        {
            if (t && 200 == t.status)
            {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.rows.length; i++)
                {
                    var a = t.rows[i];
                    e[a.idea_id] = a;
                }
                $("#grid").data("gridData", e);
                0 == t.rows.length && parent.Public.tips({type: 2, content: "没有用户意见数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取用户意见据失败！" + t.msg})
            }
        },
        loadError: function ()
        {
            parent.Public.tips({type: 1, content: "操作失败了哦，请检查您的网络链接！"})
        }
    })
}

var handle = {
    operate: function (t, e)
    {
        if ("add" == t)
        {
            var i = "新增用户意见", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "回复用户意见", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
        }
        $.dialog({
            title: i,
            content: "url:./index.php?ctl=Service_Idea&met=manage",
            data: a,
            width: 650,
            height: 420,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
    }, callback: function (t, e, i)
    {
        var a = $("#grid").data("gridData");
        if (!a)
        {
            a = {};
            $("#grid").data("gridData", a)
        }
        a[t.idea_id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.idea_id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.idea_id, t, "last");
            i && i.api.close()
        }
    }, del: function (t)
    {
        $.dialog.confirm("删除的用户意见将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost("./index.php?ctl=Service_Idea&met=remove&typ=json", {idea_id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "用户意见删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "用户意见删除失败！" + e.msg})
                }
            })
        })
    }
};
initEvent();
initGrid();