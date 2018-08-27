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
	<input type="hidden" name="delivery_id" id="delivery_id" value="<?=$data['delivery_id']?>">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">物流自提服务站用户名</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<span><?=$data['user_account']?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">真实姓名</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<span><?=$data['delivery_real_name']?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
	    <dl class="row">
                <dt class="tit">手机号</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<input id="delivery_mobile" name="delivery_mobile" class="ui-input w200" type="text" value="<?=$data['delivery_mobile']?>"/>
                        </li>
                    </ul>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">座机号</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<input id="delivery_tel" name="delivery_mobile" class="ui-input w200" type="text" value="<?=$data['delivery_tel']?>"/>
                        </li>
                    </ul>
                </dd>
            </dl>
			<dl class="row">
				<dt class="tit">自提服务站名称</dt>
				<dd class="opt">
					<ul class="nofloat">
						<li>
							<input id="delivery_name" name="delivery_name" class="ui-input w200" type="text" value="<?=$data['delivery_name']?>"/>
						</li>
					</ul>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">所在地区</dt>
				<dd class="opt">
					<ul class="nofloat">
						<li>

						</li>
					</ul>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">详细地址</dt>
				<dd class="opt">
					<ul class="nofloat">
						<li>
							<input id="delivery_address" name="delivery_address" class="ui-input w200" type="text" value="<?=$data['delivery_address']?>"/>
						</li>
					</ul>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">身份证号码</dt>
				<dd class="opt">
					<ul class="nofloat">
						<li>
							<span><?=$data['delivery_identifycard']?></span>
						</li>
					</ul>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">身份证图片</dt>
				<dd class="opt">
					<ul class="nofloat">
						<li>
							<span><?=$data['delivery_identifycard_pic']?></span>
						</li>
					</ul>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">申请时间</dt>
				<dd class="opt">
					<ul class="nofloat">
						<li>
							<span><?=$data['delivery_apply_date']?></span>
						</li>
					</ul>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">登录密码</dt>
				<dd class="opt">
					<ul class="nofloat">
						<li>
							<input id="delivery_password" name="delivery_password" class="ui-input w200" type="text" />
						</li>
					</ul>
					<p class="notic">不填为不修改密码</p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">状态</dt>
				<dd class="opt">
					<ul class="nofloat">
						<li>
							<div class="onoff" id="contract_type_state">
								<label title="开启" class="cb-enable <?=($data['delivery_state_etext']=='open' ? 'selected' : '')?> " for="contract_state_enable">开启</label>
								<label title="关闭" class="cb-disable <?=($data['delivery_state_etext']=='close' ? 'selected' : '')?>" for="contract_state_disabled">关闭</label>
								<input type="radio" value="1" name="delivery_state" id="delivery_state_enable" <?=($data['delivery_state_etext']=='open' ? 'checked' : '')?> />
								<input type="radio" value="2" name="delivery_state" id="delivery_state_disabled" <?=($data['delivery_state_etext']=='close' ? 'checked' : '')?> />
							</div>
						</li>
					</ul>
				</dd>
			</dl>

			<dl class="row">
				<dt class="tit">审核状态</dt>
				<dd class="opt">
					<ul class="nofloat">
						<li>
								<input type="radio" name="delivery_check_state" value="1" <?php if($data['delivery_check_state_etext']=='passin'){ echo 'checked="true"';}?>> 审核中
								<input type="radio" name="delivery_check_state" value="2" <?php if($data['delivery_check_state_etext']=='pass'){ echo 'checked="true"';}?>> 审核通过
								<input type="radio" name="delivery_check_state" value="3" <?php if($data['delivery_check_state_etext']=='unpass'){ echo 'checked="true"';}?>> 审核不通过
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
					n = "修改";
					Public.ajaxPost(SITE_URL+"?ctl=Operation_Delivery&typ=json&met=editDelivery", $_form.serialize(), function (e)
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
	</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
