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
       //跳转到店铺认证信息页面
        $('#grid').on('click', '.to-shop', function(e) {
            e.stopPropagation();
            e.preventDefault();
            var shop_id = $(this).attr('data-id');
            $.dialog({
                title: '查看店铺信息',
                content: "url:"+SITE_URL + '?ctl=Shop_Manage&met=getShoplist&shop_id=' + shop_id,
                width: 1000,
                height: $(window).height(),
                max: !1,
                min: !1,
                cache: !1,
                lock: !0
            })
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
    var t = ["操作","二级域名",  "店铺名称","店主名称","编辑次数"], e = [{
        name: "operate",
        width: 50,
        fixed: !0,
        align: "center",
        formatter: operFmattershop
    }, 
        {name: "shop_sub_domain", index: "shop_sub_domain", align: "center", width: 100},
        {name: "shop_name", index: "shop_name", width:200,align:'center',"formatter": handle.linkShopFormatter},
        {name: "user_name", index: "user_name", align: "center", width: 100},
	{name: "shop_edit_domain", index: "shop_edit_domain", align: "center",width: 100}
      
        ];
	$("#grid").jqGrid({
        url: SITE_URL + "?ctl=Shop_Domain&met=shopIndex&typ=json",
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
                    e[a.id] = a;
                }
                $("#grid").data("gridData", e);
                0 == t.items.length && parent.Public.tips({type: 2, content: "没有二级域名数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取二级域名数据失败！" + t.msg})
            }
        },
        loadError: function ()
        {
            parent.Public.tips({type: 1, content: "操作失败了哦，请检查您的网络链接！"})
        }
    })
}

var handle = {
    linkShopFormatter: function(val, opt, row) {
        return '<a href="javascript:void(0)"><span class="to-shop" data-id="' + row.shop_id + '">' + val + '</span></a>';
    },
    imgFmt: function (val, opt, row)
    {
        if (row.level == 0 && val)
        {
            val = '<img src="' + val + '">';
        }
        else
        {
            if (row.shop_logo)
            {
                val = '<img src="' + row.shop_logo + '">';
            }
            else
            {
                val = '&#160;';
            }
        }
        return val;
    },
    operate: function (t, e)
    {         
        if ("add" == t)
        {
            var i = "新增域名", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "修改域名", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
           
        }

        $.dialog({
            title: i,
            content: "url:"+ SITE_URL + "?ctl=Shop_Domain&met=getShopDomain&shop_id="+e,
            data: a,
            width: 550,
            height: 220,
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
        
       
       
    }, 
};

function operFmattershop(val, opt, row) {
    var html_con = '<div class="operating" data-id="' + row.shop_id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span></div>';
    return html_con;
};
initEvent();
initGrid();

$(function(){
    $source = $("#source").combo({
        data: [{
            id: "0",
            name: "投诉人"
        },{
            id: "1",
            name: "被投商家"
        }],
        value: "id",
        text: "name",
        width: 110
    }).getCombo();


    
});