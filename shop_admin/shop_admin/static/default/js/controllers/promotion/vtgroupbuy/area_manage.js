function initField()
{
    if(api.data.parent_id) //添加子分类
    {
        $.get(SITE_URL + '?ctl=Promotion_GroupBuy&met=getGroupBuyAreaName&typ=json&id=' + api.data.parent_id, function(a){
            if(a.status==200)
            {
                $("#parent_district").val(a.data.groupbuy_area_name);
                $("#parent_id").val(a.data.id);
            }
        });
    }
    if (rowData.groupbuy_area_id)//编辑分类
    {
        $("#groupbuy_area_name").val(rowData.groupbuy_area_name);
        if(rowData.groupbuy_area_parent_id) //如果有上级
        {
            $.get(SITE_URL + '?ctl=Promotion_GroupBuy&met=getGroupBuyAreaName&typ=json&id=' + rowData.groupbuy_area_parent_id, function(a){
                if(a.status==200)
                {
                    $("#parent_district").val(a.data.groupbuy_area_name);
                    $("#parent_id").val(a.data.id);
                }
            });
        }
    }
}
function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.groupbuy_area_id);
            return cancleGridEdit(), $("#manage-form").trigger("validate"), !1;
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
	$_form.validator({
		
		ignore: ":hidden",
        theme: "yellow_right",
        timely: 1,
        stopOnError: true,
		debug:true,
		messages: {
            required: "不能为空！",
        },
		fields:
		{
			groupbuy_area_name: "required;"
		},
		display: function (a)
        {
            return $(a).closest(".row-item").find("label").text().replace(":","");
        },
		valid: function (form)
        {
			var groupbuy_area_name = $.trim($("#groupbuy_area_name").val()),

				parent_district = $.trim($("#parent_id").val()),

				n = "add" == t ? "新增地区" : "修改地区";

			var params = {groupbuy_area_name: groupbuy_area_name,parent_district:parent_district};
			e ? params.groupbuy_area_id= e : '';
			Public.ajaxPost(SITE_URL +"?ctl=Promotion_GroupBuy&typ=json&met=" + ("add" == t ? "addGroupBuyArea" : "editGroupBuyArea"), params, function (e)
			{
				if (200 == e.status)
				{
					parent.parent.Public.tips({content: n + "成功！"});
					callback && "function" == typeof callback && callback(e.data, t, window)
				}
				else
				{
					parent.parent.Public.tips({type: 1, content: n + "失败！" + e.msg})
				}
			})
		}
	});
}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}

var curRow, curCol, curArrears, $grid = $("#grid"), $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
console.info(rowData);
initPopBtns();
initField();