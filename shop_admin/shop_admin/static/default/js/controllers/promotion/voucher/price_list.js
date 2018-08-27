
function initEvent()
{	
	$("#btn-add").click(function (t)
    {
        t.preventDefault();
        //Business.verifyRight("INVLOCTION_ADD") && handle.operate("add")
        handle.operate("add");
    });
    
    $("#btn-refresh").click(function (t)
    {
        t.preventDefault();
        $("#grid").trigger("reloadGrid")
    });
	
    $("#grid").on("click", ".operating .ui-icon-pencil", function (t)
    {
        t.preventDefault();
        var e = $(this).parent().data("id");
        handle.operate("edit", e)
    });
	
    $("#grid").on("click", ".operating .ui-icon-trash", function (t)
    {
        t.preventDefault();
        var e = $(this).parent().data("id");
        handle.del(e)
    });
	
    $(window).resize(function ()
    {
        Public.resizeGrid()
    })
}
function initGrid()
{
    var t = ["操作", "代金券面额（元）", "描述","兑换积分数"],
	e = [{
        name: "operate",
        width: 50,
        fixed: !0,
        align: "center",
        formatter: operFmatter
    }, 
	{name: "voucher_price", index: "voucher_price", align: "center",width: 150},
	{name: "voucher_price_describe", index: "voucher_price_describe",align: "center",width: 100},
	{name: "voucher_defaultpoints", index: "voucher_defaultpoints",align: "center",width: 100}
	];
    
	$("#grid").jqGrid({
        url: SITE_URL + '?ctl=Promotion_Voucher&met=getPriceList&typ=json',
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
		jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "voucher_price_id"},
        loadComplete: function (t)
        {
            if (t && 200 == t.status)
            {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++)
                {
                    var a = t.items[i];
                    e[a.voucher_price_id] = a;
                }
                $("#grid").data("gridData", e);
				
                0 == t.items.length && parent.Public.tips({type: 2, content: "没有类型数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取类型数据失败！" + t.msg})
            }
        },
        loadError: function ()
        {
            parent.Public.tips({type: 1, content: "操作失败了哦，请检查您的网络链接！"})
        }
    })
}
function operFmatter (val, opt, row) 
{
	var html_con = '<div class="operating" data-id="' + row.voucher_price_id + '"><span class="ui-icon ui-icon-pencil" title="编辑"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
	return html_con;
};
		
var handle = {
    operate: function (t, e)
    {
        if ("add" == t)
        {
            var i = "店铺代金券 - 面额设置", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "店铺代金券 - 面额设置", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
        }
        $.dialog({
            title: i,
            content: "url:"+SITE_URL + '?ctl=Promotion_Voucher&met=priceManage',
            data: a,
            width: 550,
            //height: 300,
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
        a[t.voucher_price_id] = t;
        if ("edit" == e)
        {
            $("#grid").jqGrid("setRowData", t.voucher_price_id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.voucher_price_id, t, "last");
            i && i.api.close()
        }
    }, del: function (t)
    {
        $.dialog.confirm("删除的类型将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + '?ctl=Promotion_Voucher&met=priceRemove&typ=json', {voucher_price_id: t}, function (e)
            {
                //alert(JSON.stringify(e));
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "代金券面额删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "代金券面额删除失败！" + e.msg})
                }
            })
        })
    }
};
initEvent();
initGrid();