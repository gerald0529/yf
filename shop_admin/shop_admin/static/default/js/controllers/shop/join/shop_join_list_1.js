function initEvent()
{
	$_matchCon = $("#matchCon"), 
	$_matchCon.placeholder(), 
	$("#search").on("click", function (a)
    {
        a.preventDefault();
        var b = "输入品牌名称查询" === $_matchCon.val() ? "" : $.trim($_matchCon.val());
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
    var t = ["操作","店铺id", "店主账号", "商家账号","店铺头像","店铺logo","店铺等级","开店时间","到期时间","当前状态","店铺分类","所在区域","详细地址","qq","旺旺","商家电话"], e = [{
        name: "operate",
        width: 60,
        fixed: !0,
        align: "center",
        formatter: operFmattershop
    }, 
	{name: "shop_id", index: "shop_id", align: "center",width: 100},
	{name: "user_name", index: "user_name",  align: "center",width: 100},
        {name: "shop_name", index: "shop_name", align: "center",width: 100},
        {name: "shop_portrait", index: "shop_portrait", align: "center", width: 100},
        {name: "shop_logo", index: "shop_logo", align: "center", width: 100},
        {name: "shop_grade", index: "shop_grade", align: "center", width: 100},
        {name: "shop_create_time", index: "shop_create_time", align: "center", width: 100},
        {name: "shop_end_time", index: "shop_end_time", align: "center", width: 100},
        {name: "shop_status", index: "shop_status", align: "center", width: 100},
        {name: "shop_logo", index: "shop_logo", align: "center", width: 100},
        {name: "shop_logo", index: "shop_logo", align: "center", width: 100},
        {name: "shop_logo", index: "shop_logo", align: "center", width: 100},
        {name: "shop_status", index: "shop_status", align: "center", width: 100},
        {name: "shop_status", index: "shop_status", align: "center", width: 100},
        {name: "shop_status", index: "shop_status", align: "center", width: 100},
      
        ];
	$("#grid").jqGrid({
        url: SITE_URL + "?ctl=Shop_Manage&met=shopJoin&typ=json",
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
		jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "shop_id"},
        loadComplete: function (t)
        {
            if (t && 200 == t.status)
            {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++)
                {
                    var a = t.items[i];
                    e[a.shop_id] = a;
                }
                $("#grid").data("gridData", e);
                0 == t.items.length && parent.Public.tips({type: 2, content: "没有广告页数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取广告页数据失败！" + t.msg})
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
            var i = "新增广告页", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "修改广告页", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
           
        }

        $.dialog({
            title: i,
            content: "url:"+ SITE_URL + "?ctl=Shop_Manage&met=shop_edit&act="+t+"&pageid="+e,
            data: a,
            width: 700,
            height: 500,
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
        a[t.shop_id] = t;
        if ("edit" == e)
        {
            
            $("#grid").jqGrid("setRowData", t.shop_id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.shop_id, t, "last");
            i && i.api.close()
        }
        
       
       
    }, del: function (t)
    {
        $.dialog.confirm("删除的广告页将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Shop_Manage&met=del&typ=json", {shop_id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "广告页删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "广告页删除失败！" + e.msg})
                }
            })
        })
    }
};

function operFmattershop(val, opt, row) {
    var html_con = '<div class="operating" data-id="' + row.shop_id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
    return html_con;
};
initEvent();
initGrid();

