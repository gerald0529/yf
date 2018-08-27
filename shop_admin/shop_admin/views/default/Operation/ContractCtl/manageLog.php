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
        <input type="hidden" name="contract_log_id" id="contract_log_id" value="<?=$data['contract_log_id']?>">
		<input type="hidden" name="contract_log_type" id="contract_log_type" value="<?=$data['contract_log_type']?>">
		<input type="hidden" name="contract_id" id="contract_id" value="<?=$data['contract_id']?>">

        <div class="ncap-form-default">
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
                <dt class="tit">申请时间</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<span><?=$data['contract_log_date']?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
			<?php if($data['contract_log_state_etext']=='cash_check'){ ?>
			<dl class="row">
                <dt class="tit">付款凭证</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<span><?=$data['contract_cash_pic']?></span>
                        </li>
                    </ul>
                </dd>
            </dl>
			<?php } ?>
	    <dl class="row">
                <dt class="tit">状态</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<?php if($data['contract_log_type_etext']=='join'){ ?>
								<?php if($data['contract_log_state_etext']=='incheck'){ ?>
								<input type="radio" name="contract_log_state" value="2"> 保证金等待审核
								<input type="radio" name="contract_log_state" value="4"> 审核不通过
								<?php }elseif($data['contract_log_state_etext']=='cash_check' || $data['contract_log_state_etext']=='cash_incheck'){?>
								<input type="radio" name="contract_log_state" value="3"> 保证金审核通过
								<input type="radio" name="contract_log_state" value="4"> 保证金审核失败
								<?php }else{ ?>
								<span><?=$data['contract_log_state_text']?><input type="hidden" name="contract_log_state" value="<?=$data['contract_log_state']?>"></span>
								<?php } ?>
							<?php }elseif($data['contract_log_type_etext']=='quit'){?>
								<?php if($data['contract_log_state_etext']=='incheck' || !$data['contract_log_state_etext']){ ?>
								<input type="radio" name="contract_log_state" value="3"> 审核通过
								<input type="radio" name="contract_log_state" value="4"> 审核不通过
								<?php }else{ ?>
                                <span><?=$data['contract_log_state_text']?><input type="hidden" name="contract_log_state" value="<?=$data['contract_log_state']?>"></span>
								<?php } ?>
							<?php } ?>
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
					n = "审核";
					Public.ajaxPost(SITE_URL+"?ctl=Operation_Contract&typ=json&met=editLog", $_form.serialize(), function (e)
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