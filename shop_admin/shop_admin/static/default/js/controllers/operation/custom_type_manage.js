function initField()
{
    for (x in rowData)
    {
	$("#"+x).val(rowData[x]);
    }
}

function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.custom_service_type_id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
	$_form.validator({
        fields: {
			custom_service_type_name: "required;",
			custom_service_type_sort: "required;integer[+];",
			custom_service_type_desc: "required;"
        },
        valid: function (form)
        {
			var me = this;
			// 提交表单之前，hold住表单，防止重复提交
			me.holdSubmit();
			n = "add" == t ? "新增任务" : "修改任务";
			Public.ajaxPost(SITE_URL+"?ctl=Operation_Custom&typ=json&met=" + ("add" == t ? "addType" : "editType"), $_form.serialize(), function (e)
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
				// 提交表单成功后，释放hold，如果不释放hold，就变成了只能提交一次的表单
				me.holdSubmit(false);
			})
        },
        ignore: ":hidden",
        theme: "yellow_bottom",
        timely: 1,
        stopOnError: !0
    });
}
function cancleGridEdit()
{
    null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
}
var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
initPopBtns();
initField();