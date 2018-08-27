<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.datetimepicker.js" charset="utf-8"></script>
</head>
<body>
    <form method="post" id="manage-form" name="settingForm">
		<input type="hidden" name="report_id" id="report_id" value="<?=$data['report_id']?>">
        <div class="ncap-form-default">
			<dl class="row">
                <dt class="tit">处理结果</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<div class="onoff">
								<label title="通过" class="cb-enable selected" for="report_pass">通过</label>
								<label title="不通过" class="cb-disable" for="report_unpass">不通过</label>
								<input type="radio" value="pass" name="handle" id="report_pass" checked/>
								<input type="radio" value="unpass" name="handle" id="report_unpass"/>
							</div>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">备注</dt>
                <dd class="opt">
                    <ul class="nofloat">
						<li>
							<textarea id="report_handle_message" name="report_handle_message" class="ui-input w400"></textarea>
						</li>
                    </ul>
                </dd>
            </dl>
        </div>
    </form>
    <script type="text/javascript" charset="utf-8">

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
				fields: {
				},
				valid: function (form)
				{
					var me = this;
					// 提交表单之前，hold住表单，防止重复提交
					me.holdSubmit();
					n = "处理";
					Public.ajaxPost(SITE_URL+"?ctl=Trade_Report&typ=json&met=doReport", $_form.serialize(), function (e)
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
				ignore: "",
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
	</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>