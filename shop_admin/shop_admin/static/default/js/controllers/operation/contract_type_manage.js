function initField()
{
    for (x in rowData)
    {
	$("#"+x).val(rowData[x]);
    }
    if(rowData.contract_type_state==1){
	$("#contract_type_state .cb-enable").addClass("selected");
	$("#contract_type_state_enable").attr("checked","checked");
    }else{
	$("#contract_type_state .cb-disable").addClass("selected");
	$("#contract_type_state_disabled").attr("checked","checked");
    }
	$("#setting_contract_type_logo").attr("src",rowData.contract_type_logo);
}

function initPopBtns()
{
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm", name: t[0], focus: !0, callback: function ()
        {
            postData(oper, rowData.contract_type_id);
            return cancleGridEdit(),$("#manage-form").trigger("validate"), !1
        }
    }, {id: "cancel", name: t[1]})
}
function postData(t, e)
{
	$_form.validator({
		rules: {
			money: [/^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(.[0-9]{1,2})?$/, '请输入金额']
		},
        fields: {
			contract_type_name: "required;",
			contract_type_cash: "required;money;",
			contract_type_logo: "required;",
			contract_type_desc: "required;",
			contract_type_sort: "required;integer[+];",
			contract_type_state: "required;",
        },
        valid: function (form)
        {
			var me = this;
			// 提交表单之前，hold住表单，防止重复提交
			me.holdSubmit();
			n = "add" == t ? "新增任务" : "修改任务";
			Public.ajaxPost(SITE_URL+"?ctl=Operation_Contract&typ=json&met=" + ("add" == t ? "addType" : "editType"), $_form.serialize(), function (e)
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