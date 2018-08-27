<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
    <form method="post" id="manage-form" name="settingForm">
        <input type="hidden" name="contract_id" id="contract_id" value="<?=$data['contract_id']?>">

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">店铺名称</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<span><?=$data['shop_name']?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">项目名称</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<span><?=$data['contract_type_name']?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
	    <dl class="row">
                <dt class="tit">增减类型</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
								<input id="type" name="type" class="ui-input w200" type="hidden"/>
								<span id="type_combo"></span>
                        </li>
                    </ul>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">金额</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							 <input id="cash" name="cash" class="ui-input w200" type="text" />
                        </li>
                    </ul>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">原因描述</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<textarea name="contract_log_desc" class="ui-input w400" id="contract_log_desc"></textarea>
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
				rules: {
						money: [/^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(.[0-9]{1,2})?$/, '请输入金额'],
						notempty: [/^.*[^\s]+.*$/, '请选择'],
				},
				fields: {
						type:"required;notempty",
						cash:"required;money;",
						contract_log_desc:"required"		
				},
				valid: function (form)
				{
					var me = this;
					// 提交表单之前，hold住表单，防止重复提交
					me.holdSubmit();
					n = "回复";
					Public.ajaxPost(SITE_URL+"?ctl=Operation_Contract&typ=json&met=editCash", $_form.serialize(), function (e)
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
		var typeCombo = Business.categoryCombo($('#type_combo'), {
            editable: false,
            extraListHtml: '',
			data:[{id: "", name: '选择类别'},{id: "increase", name: '增加'},{id: "decrease", name: '减少'}],
            defaultSelected: null,
            trigger: true,
            width: 200,
            callback: {
                onChange: function (data)
                {
                    $('#type').val(this.getValue());
                }
            }
        }, 'type');

        typeCombo.selectByValue("");
	</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>