<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<?php if($this->user_info['user_email'] || $this->user_info['user_mobile']){?>
<form>
	<div class="wrap" style="width:95%;">
		<div class="recharge3-content-top content-public">
			<div class="box clearfix">
				<div class="box-public">
					<div class="mallbox-public content-top account_mes">
						<h4>账户充值</h4>
					</div>
				</div>
			</div>
		</div>
		<script>
			$(document).ready(function(){
				$("input[name='choice_btn']").click(function(){
					$("." + $(this).attr("id") +"_box").siblings().hide();
					$("." + $(this).attr("id") +"_box").show();

				})
			})
		</script>
		<div class="accout_pay_sel"><label><input type="radio" name="choice_btn" id="money" checked><span class="exchage" id="money"><?=_('账户充值')?></span></label><label><input type="radio" name="choice_btn" id="card"><span class="exchage" id="card"><?=_('购物卡充值')?></span></label></div>
		<div>
			<div class="recharge3-content-center content-public money_box">
				<p class="recharge_mon">
					<span class="spanmt"><?=_('充值金额 :')?>&nbsp;</span>
					<input type="text" class="text text-1 deposit_amount" onKeyUp="amount(this)" />
					<p class="err_msg" id="err_msg_money" style="margin-left:73px;"></p>
				</p>

				<div class="pc_trans_btn clearfix"><a id="deposit_btn" class="btn_big btn_active fl submit_disable"><?=('确认信息并充值')?></a><span class="onright"><a target="_blank" href="./index.php?ctl=Info&met=recordlist&type=3&typ=e"><?=_('充值记录')?></a></span></div>
			</div>

			<div class="recharge3-content-center content-public card_box" style="display: none;">
				<p class="recharge_mon">
					<span class="spanmt"><?=_('购&nbsp;物&nbsp;卡&nbsp;号 :')?>&nbsp;</span>
					<input type="text" class="text text-1 card_code" />
				</p>
				<p class="recharge_mon">
					<span class="spanmt"><?=_('购物卡密码 :')?>&nbsp;</span>
					<input type="text" class="text text-1 card_password" onblur="checkPassword()"/>
					<p class="err_msg" id="err_msg_card" style="margin-left:86px;"></p>
				</p>

				<div class="pc_trans_btn clearfix"><a id="deposit_card_btn" class="btn_big btn_active fl submit_disable"><?=('确认充值')?></a><span class="onright"><a target="_blank" href="./index.php?ctl=Info&met=recordlist&type=3&typ=e"><?=_('充值记录')?></a></span></div>
			</div>
		</div>
	</div>
</form>	
<?php }else{?>
    <div class="security-tips">充值前必须先进行邮箱绑定或手机绑定，点击这里进行<a href="<?=Yf_Registry::get('ucenter_api_url')?>?ctl=User&met=security&op=mobile">手机绑定</a>或<a href="<?=Yf_Registry::get('ucenter_api_url')?>?ctl=User&met=security&op=email">邮箱绑定</a></div>
<?php }?>	
	<div class="recharge2-content-bottom content-public wrap" style="width:95%;">
		<div class="theme" style="margin-top:60px;">
			<span class="title">充值遇到问题</span>
		</div>
		<div class="content">
			<div class="one">
				<h3> 1.我还能用信用卡进行网购么？ </h3>
				<p class="texts">答：您在带有信用卡小标识的店铺购物，可以直接使用信用卡快捷（含卡通）、网银进行信用卡支付，支付限额为您的卡面额度。在没有信用卡标识的店铺购物 时，您可以使用信用卡快捷（含卡通）、网银进行信用卡支付，月累计支付限额不超过500元。
				</p>
			</div>
			<div class="one">
				<h3>2.没有网上银行，怎么用银行卡充值？</h3>
				<p class="texts">答：储蓄卡用户，请使用储蓄卡快捷支付充值，开通后只需输入网付宝支付密码，即可完成充值。</p>
			</div>
			<div class="one">
				<h3>3.怎样在网上开通储蓄卡快捷支付(含卡通)？ </h3>
				<p class="texts">答：已支持国内大部分主流银行在线开通。在网付宝填写信息后，根据页面引导在网上银行完成开通。</p>
			</div>
		</div>
	</div>

<script>
	function amount(th){
		var regStrs = [
			['^0(\\d+)$', '$1'], //禁止录入整数部分两位以上，但首位为0
			['[^\\d\\.]+$', ''], //禁止录入任何非数字和点
			['\\.(\\d?)\\.+', '.$1'], //禁止录入两个以上的点
			['^(\\d+\\.\\d{2}).+', '$1'] //禁止录入小数点后两位以上
		];
		for(i=0; i<regStrs.length; i++){
			var reg = new RegExp(regStrs[i][0]);
			th.value = th.value.replace(reg, regStrs[i][1]);
		}
	}

	function checkAmount()
	{
		var deposit_amount = $(".deposit_amount").val();

		if(deposit_amount <= 0)
		{
			$("#err_msg_money").html("充值金额不可小于0元");

			$("#deposit_btn").addClass("submit_disable");
			$("#deposit_btn").removeClass("submit_able");
		}
		else if(deposit_amount >= 10000000)
		{
			$("#err_msg_money").html("充值金额不可大于10000000元");

			$("#deposit_btn").addClass("submit_disable");
			$("#deposit_btn").removeClass("submit_able");
		}
		else
		{
			$("#err_msg_money").html("");

			$("#deposit_btn").removeClass("submit_disable");
			$("#deposit_btn").addClass("submit_able");
		}
	}

	function depositSubmit(e)
	{
		if(e.hasClass("submit_able"))
		{
			var deposit_amount = $(".deposit_amount").val();
			var url = SITE_URL +'?ctl=Info&met=addDeposit&typ=json';

			var data = {deposit_amount:deposit_amount};
			$.post(url,data, function (data){
				console.info(data);
				if(data.status == 200)
				{
					window.location.href = SITE_URL + "?ctl=Info&met=pay&act=deposit&uorder=" + data.data.uorder;
				}
			})
		}

	}

	$("#deposit_btn").click(function(){
		checkAmount();
		depositSubmit($(this));
	});

	function checkPassword()
	{
		var card_code = $(".card_code").val();
		var card_password = $(".card_password").val();

		if(card_code && card_password)
		{
			var flag = false;
			$.ajaxSetup({
				async : false
			});
			$.post(SITE_URL +'?ctl=Info&met=checkCardPasswor&typ=json',{card_code:card_code,card_password:card_password},
				function(data){
					console.info(data);
					if(data.status == 250)
					{
						$("#err_msg_card").html(data.msg);
						$("#deposit_card_btn").addClass("submit_disable");
						$("#deposit_card_btn").removeClass("submit_able");

						return false;
					}
					else
					{
						$("#err_msg_card").html("");

						$("#deposit_card_btn").addClass("submit_able");
						$("#deposit_card_btn").removeClass("submit_disable");

						flag  = true;
						return true;

					}
				}
			);

			return flag;
		}
		else
		{
			$("#err_msg_card").html("");

			$("#deposit_card_btn").addClass("submit_disable");
			$("#deposit_card_btn").removeClass("submit_able");

			return false;
		}

		return false;
	}


	$("#deposit_card_btn").click(function(){

		var checked =  checkPassword();

		if($(this).hasClass("submit_able") && checked === true)
		{
			var card_code = $(".card_code").val();
			data = {card_code:card_code}

			//将选用的付款方式保存如数据库
			$.post(SITE_URL + "?ctl=Info&met=depositCard&typ=json" ,data,
				function(data){
					console.info(data);
					if(data.status == 200)
					{
						alert_box(data.msg);
						setTimeout(function(){
							window.location.href = SITE_URL + "?ctl=Info&met=index";
						},2000);
						
					}
					else
					{
						alert_box(data.msg);
					}
				}
			);
		}

	});
</script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>