<?php
/**
 * Created by PhpStorm.
 * User: 新泽
 * Date: 2015/2/22
 * Time: 9:53
 */
?>
<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>

<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>

<style>
body{background: #fff;}
.manage-wrap{margin: 20px auto 10px;width: 300px;}
.manage-wrap .ui-input{width: 200px;font-size:14px;}
</style>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">
	<form id="manage-form" action="#">
		<ul class="mod-form-rows">
			<li class="row-item">
				<div class="label-wrap"><label for="user_account">用户:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="user_account" id="user_account" disabled="true"></div>
			</li>
			<li class="row-item">
				<div class="label-wrap"><label for="user_password">密码:</label></div>
				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="user_password" id="user_password"></div>
			</li>
		</ul>
	</form>
</div>
<!--<script src="./shop_admin/static/default/js/controllers/user/base/manage.js"></script>-->
<script type="text/javascript">
	var curRow, curCol, curArrears, $grid = $("#grid"), $_form = $("#manage-form"), api = frameElement.api, oper = api.data.oper, rowData = api.data.rowData || {}, callback = api.data.callback;

	initPopBtns();
	initField();

	function initField()
	{
		if (rowData.id)
		{
			$('#user_account').val(rowData.user_name);
		}
    }

	function initPopBtns()
	{
		var btn = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
		api.button({
			id: "confirm", name: btn[0], focus: !0, callback: function ()
			{
				postData(oper, rowData.id);
				return cancleGridEdit(), $_form.trigger("validate"), !1;
			}
		}, {id: "cancel", name: btn[1]})
	}

	function postData(oper, id)
	{
		$("#manage-form").validator({
			ignore: ':hidden',
			theme: 'yellow_bottom',
			timely: 1,
			stopOnError: true,
			fields: {
				'user_password': 'required;'
			},
			valid: function (form)
			{
				var me = this;
				// 提交表单之前，hold住表单，防止重复提交
				me.holdSubmit();

				parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
					{
						 var data = {user_id: rowData.id, user_password: $("#user_password").val()};

						 var n = "add" == oper ? _("新增") : _("修改");

						Public.ajaxPost(SITE_URL + "?ctl=User&typ=json&met=" + ("add" == oper ? "add" : "editUserPassword"), data, function (resp)
						{
							if (200 == resp.status)
							{
								parent.parent.Public.tips({content: n + "成功！"});
								callback && "function" == typeof callback && callback(resp.data, oper, window)
							}
							else
							{
								parent.parent.Public.tips({type: 1, content: n + "失败！" + resp.msg})
							}

							// 提交表单成功后，释放hold，如果不释放hold，就变成了只能提交一次的表单
							me.holdSubmit(false);
						})
					},
					function ()
					{
						me.holdSubmit(false);
					});
			},
		}).on("click", "a.submit-btn", function (e)
		{
			$(e.delegateTarget).trigger("validate");
		});
	}

	function cancleGridEdit()
	{
		null !== curRow && null !== curCol && ($grid.jqGrid("saveCell", curRow, curCol), curRow = null, curCol = null)
	}

	//设置表单元素回车事件
	function bindEventForEnterKey()
	{
		Public.bindEnterSkip($_form, function()
		{
			$('#grid tr.jqgrow:eq(0) td:eq(0)').trigger('click');
		});
	}
</script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>