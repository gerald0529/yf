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
	
    
       $('.grid-wrap').on('click', '.ui-icon-pencil', function(e){
            e.preventDefault();
            var e = $(this).parent().data("id");
             $.dialog({
                title: '设置入驻信息',
                content: "url:"+SITE_URL + '?ctl=Shop_Help&met=getHelpRow&shop_help_id=' + e,
                data:{callback: testF},
                width: 1024,
                height: $(window).height(),
                max: !1,
                min: !1,
                cache: !1,
                lock: !0
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
    var t = ["操作", "标题", "更新时间","排序"], e = [{
        name: "operate",
        width: 50,
        fixed: !0,
        align: "center",
        formatter: operFmattershop
    },
	{name: "help_title", index: "help_title",  align: "center",width: 200},
        {name: "update_time", index: "update_time", align: "center",width: 200},
        {name: "help_sort", index: "help_sort", align: "center",width: 100}

        ];
	$("#grid").jqGrid({
        url: SITE_URL + "?ctl=Shop_Help&met=helpList&typ=json",
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
		jsonReader: {root: "data.items", records: "data.records", total: "data.total", repeatitems: !1, id: "shop_help_id"},
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
                0 == t.items.length && parent.Public.tips({type: 2, content: "没有店铺入驻数据！"})
            }
            else
            {
                parent.Public.tips({type: 2, content: "获取店铺入驻数据失败！" + t.msg})
            }
        },
        loadError: function ()
        {
            parent.Public.tips({type: 1, content: "操作失败了哦，请检查您的网络链接！"})
        }
    })
}

function testF(){ 
    window.location.reload(); 
}

function operFmattershop(val, opt, row) {
    var html_con = '<div class="operating" data-id="' + row.shop_help_id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span></div>';
    return html_con;
};


initEvent();
initGrid();
