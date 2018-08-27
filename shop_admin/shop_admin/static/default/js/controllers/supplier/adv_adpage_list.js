function initEvent()
{
	$_matchCon = $("#matchCon"), 
	$_matchCon.placeholder(), 

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
                    //编辑
	$('.grid-wrap').on('click', '.ui-icon-search', function(e){
            e.preventDefault();
            var page_id = $(this).parent().data("id");
             $.dialog({
                title: '查看模板',
                content: "url:"+SITE_URL + '?ctl=Supplier_Adposition&met=position&page_id=' + page_id,
                width:1236,
                // height: $(window).height() * 0.9,
                height:470,
                max: !1,
                min: !1,
                cache: !1,
                lock: !0,
                zIndex:1999
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
     var gridWH = Public.setGrid(), _self = this;
   var t = ["操作","排序", "板块名称", "色彩风格","更新时间","显示"], e = [{
        name: "operate",
        width: 100,
        fixed: !0,
        align: "center",
        formatter: operFmattershop,
    }, 
	{name: "page_order", index: "page_order", align: "center",width: 100},
	{name: "page_name", index: "page_name",  align: "center",width: 200},
        {name: "page_colorcha", index: "page_color", align: "center",width: 100},
	{name: "page_update_time", index: "page_update_time", align: "center", width: 150},
        {name: "page_statuscha", index: "page_status", align: "center", width: 100}
        ];
	$("#grid").jqGrid({
        url: SITE_URL + "?ctl=Supplier_Adpage&met=advIndex&typ=json",
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
		jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "page_id"},
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
                0 == t.items.length && parent.Public.tips({type: 2, content: "没有楼层数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取楼层数据失败！" + t.msg})
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
            var i = "新增首页楼层", a = {oper: t, callback:testF};
        }
        else
        {
            var i = "修改首页楼层", a = {oper: t, rowData: $("#grid").data("gridData")[e], callback:testF};
           
        }
        $.dialog({
            title: i,
            content: "url:"+ SITE_URL + "?ctl=Supplier_Adpage&met="+t+"Pagelist&page_id="+e,
            data: a,
            width: 676,
                // height: $(window).height() * 0.9,
                height:435,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
      
    
  
    },del: function (t)
    {
        $.dialog.confirm("删除的首页楼层将不能恢复，请确认是否删除？", function ()
        {
            Public.ajaxPost(SITE_URL + "?ctl=Supplier_Adpage&met=delPagelist&typ=json", {page_id: t}, function (e)
            {
                if (e && 200 == e.status)
                {
                    parent.Public.tips({content: "楼层删除成功！"});
                    $("#grid").jqGrid("delRowData", t)
                }
                else
                {
                    parent.Public.tips({type: 1, content: "楼层删除失败！" + e.msg})
                }
            })
        })
    },
   
};
function testF(){ 
    window.location.reload(); 
}
    function operFmattershop(val, opt, row) {
    var html_con = '<div class="operating" data-id="' + row.page_id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-search" title="查看楼层"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
    return html_con;
};

initEvent();
initGrid();
