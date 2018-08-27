
function initEvent()
{
    $("#btn-add").click(function (t)
    {
        t.preventDefault();
        Business.verifyRight("INVLOCTION_ADD") && handle.operate("add")
    });
    $('#grid').on('click', '.operating .ui-icon-plus', function (e)
    {
        e.preventDefault();
        var id = $(this).parent().data('id');
        handle.operate('add', id);
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
    var t = ["操作", "标题", "时间"], e = [{
        name: "operate",
        width: 100,
        fixed: !0,
        align: "center",
        formatter: Public.operFmatter
    },
        {name: "member_agreement_title", index: "member_agreement_title", align: "center",width: 200},
        {name: "member_agreement_time", index: "member_agreement_time", width: 200}];

    $("#grid").jqGrid({
        url: SITE_URL + "?ctl=Member_Agreement&met=memberAgreementList&typ=json&isDelete=2",
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
        jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "member_agreement_id"},
        loadComplete: function (t)
        {
            if (t && 200 == t.status)
            {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++)
                {
                    var a = t.items[i];
                    e[a.member_agreement_id] = a;
                }
                $("#grid").data("gridData", e);
                0 == t.items.length && parent.Public.tips({type: 2, content: "没有分类数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取分类数据失败！" + t.msg})
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
            var i = "新增分类", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "修改分类", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
        }
        $.dialog({
            title: i,
            content: 'url:'+SITE_URL + "?ctl=Member_Agreement&met=manage",
            width: $(window).width() * 0.7,
            height: $(window).height() * 0.7,
            data: a,
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
        a[t.id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.id, t, "last");
            i && i.api.close()
        }
    }, del: function (t)
    {
        $.dialog.confirm("删除的分类将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Member_Agreement&met=removeMemberAgreement&typ=json", {member_agreement_id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "分类删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "分类删除失败！" + e.msg})
                }
            })
        })
    }
};
initEvent();
initGrid();