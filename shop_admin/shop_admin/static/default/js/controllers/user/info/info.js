function initPopBtns()
{
	var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
	api.button({
		id: "confirm", name: t[0], focus: !0, callback: function ()
		{
			postData(oper, rowData.contract_type_id);
			return cancleGridEdit(),$("#user-edit-form").trigger("validate"), !1
		}
	}, {id: "cancel", name: t[1]})
}
			function postData(t, e)
			{
			$_form.validator({
				rules: {
                 qq: [/^\d{5,11}$/, '请输入正确qq'],
                
					},
				fields: {
					'user_email': 'required;email;',
					'user_qq': 'qq;'
				},
				valid: function (form)
				{
					var me = this;
					// 提交表单之前，hold住表单，防止重复提交
					me.holdSubmit();
					n = "修改";
					Public.ajaxPost(SITE_URL + '?ctl=User_Info&met=editUserInfoByUcenter&typ=json', $_form.serialize(), function (e)
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
		var curRow, curCol, curArrears, $grid = $("#grid"),  $_form = $("#user-edit-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;
		initPopBtns();
