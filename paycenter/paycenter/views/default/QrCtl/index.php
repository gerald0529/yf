<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
    <div style="text-align:center">
    	<input type="hidden" name="shop_uid" value="<?php echo request_int('shop_uid')?>">
    	<input type="hidden" name="shop_u_name" value="<?php echo request_int('shop_u_name')?>">
		<span>金额：</span><input type="text" name="pay_money">

		<?php foreach($payment_channel as $key => $val){?>
			<div class="box-public box<?=($key)?>">
				<a href="javascript:;">
					<div class="mallbox-public">
						<img src="<?=($val['payment_channel_image'])?>" alt="<?=($val['payment_channel_name'])?>"/>
						<input type="radio" value="<?=($val['payment_channel_name'])?>" data-pay_type="<?=($val['payment_channel_code'])?>" name="pay_type">
					</div>
				</a>
				<span class="sel_icon"></span>
			</div>
		<?php }?>


        <span>余额</span><input type="radio" value="余额" data-pay_type="money" name="pay_type">
        <input type="button" value="支付" id="pay_btn">
    </div>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js" charset="utf-8"></script>
	<script type="text/javascript">
		$(function(){
			var SITE_URL = "<?=Yf_Registry::get('paycenter_api_url')?>";
			$('#pay_btn').click(function(){
				var pay_money = $("input[name='pay_money']").val();
				var shop_uid = $("input[name='shop_uid']").val();
				var shop_u_name = $("input[name='shop_u_name']").val();
				var pay_type = '';
				$("input[name='pay_type']").each(function(){
					if($(this).is(':checked'))
					{
						pay_type = $(this).data('pay_type');
					}
				})

				if(pay_type !== 'money')
				{
					alert(SITE_URL + "?ctl=Pay&met=qr_pay&pay_type="+ pay_type +"&pay_money=" + pay_money + "&shopid="+"<?=$shopid?>");
					window.location.href = SITE_URL + "?ctl=Pay&met=qr_pay&pay_type="+ pay_type +"&pay_money=" + pay_money + "&shopid="+"<?=$shopid?>";
				}else
				{
					$.ajax({
						type:"post",
						url:SITE_URL + "?ctl=Pay&met=qr_pay&pay_type="+pay_type+"&typ=json",
						data:{pay_money:pay_money,shopid:<?=$shopid?>},
						dataType: "json",
						success: function (data) {
							console.log(data);
						}
					})
				}

			})
		})
	</script>