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
        
    $("#grid").on("click", ".operating .ui-icon-trash", function (t)
    {
        t.preventDefault();
        if (Business.verifyRight("INVLOCTION_DELETE"))
        {
            var e = $(this).parent().data("id");
            handle.del(e)
        }
    });
    //编辑等级
        $("#grid").on("click", ".operating .ui-icon-pencil", function (t)
    {
        t.preventDefault();
        if (Business.verifyRight("INVLOCTION_UPDATE"))
        {
            var e = $(this).parent().data("id");
        
            handle.operate("edit", e)
        }
    });    
    //编辑模板
    $('.grid-wrap').on('click', '.ui-icon-search', function(e){
            e.preventDefault();
            var grade_id = $(this).parent().data("id");
            parent.tab.addTabItem({
            tabid: grade_id,
            text: '选择模板',
            url: SITE_URL + '?ctl=Shop_Grade&met=getTemplist&grade_id=' + grade_id,
            })
        });
	
        
    $(window).resize(function ()
    {
        Public.resizeGrid()
    })
}
function initGrid()
{
     var gridWH = Public.setGrid(), _self = this;
    var t = ["操作","级别", "等级名称", "可发布商品数","可上传图片数","可选模板套数","收费标准"], e = [{
        name: "operate",
        width: 90,
        fixed: !0,
        align: "center",
        formatter: operFmattershop
    },
	{name: "shop_grade_sort", index: "shop_grade_sort", align: "center",width:100},
	{name: "shop_grade_name", index: "shop_grade_name",  align: "center",width: 200},
        {name: "shop_grade_goods_limit", index: "shop_grade_goods_limit", align: "center",width: 100},
        {name: "shop_grade_album_limit", index: "shop_grade_album_limit", align: "center", width: 100},
        {name: "shop_grade_template_num", index: "shop_grade_template_num", align: "center", width: 100},
        {name: "shop_grade_fee", index: "shop_grade_fee", align: "center", width: 100}
        ];
	$("#grid").jqGrid({
        url: SITE_URL + "?ctl=Shop_Grade&met=shopIndex&typ=json",
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
		jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "shop_grade_id"},
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
                0 == t.items.length && parent.Public.tips({type: 2, content: "没有店铺等级数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取店铺等级数据失败！" + t.msg})
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
            var i = "新增店铺等级", a = {oper: t, callback: this.callback};
        }
        else
        {
            var i = "修改店铺等级", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback: this.callback};
           
        }
        console.info(a);
        $.dialog({
            title: i,
            content: "url:"+ SITE_URL + "?ctl=Shop_Grade&met="+t+"ShopLevel&shop_grade_id="+e,
            data: a,
            width: 620,
            height: 520,
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
            $("#grid").jqGrid("setRowData", t.shop_grade_id, t);
            i && i.api.close()
        }
        else
        {
            $("#grid").jqGrid("addRowData", t.shop_grade_id, t, "last");
            i && i.api.close()
        }
        
       
       
    }, del: function (t)
    {
        $.dialog.confirm("删除的等级将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Shop_Grade&met=delShopLevel&typ=json", {shop_grade_id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "等级删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "等级删除失败！" + e.msg})
                }
            })
        })
    },

};

function operFmattershop(val, opt, row) {
    var html_con = '<div class="operating" data-id="' + row.shop_grade_id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span><span class="ui-icon ui-icon-search" title="选择模板"></span></div>';
    return html_con;
};


initEvent();
initGrid();
