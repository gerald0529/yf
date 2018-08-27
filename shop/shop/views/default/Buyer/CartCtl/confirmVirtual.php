<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'site_nav.php';
?>

<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/shop-cart.css" />
<script type="text/javascript" src="<?=$this->view->js?>/virtual.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/alert.js"></script>
<script  type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.dialog.js"></script>
<link type="text/css" rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css">
<link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>

	<script>

		var  rpt_list_json = <?=encode_json($data['rpt_list'])?>;
		//平台优惠券
		function iniRpt(order_total) {
			var _tmp,_hide_flag = true;
			$('#rpt').empty();
			$('#rpt').append('<option value="|0.00">-<?=__('选择使用平台优惠券')?>-</option>');
			for (i = 0; i < rpt_list_json.length; i++)
			{
				_tmp = parseFloat(rpt_list_json[i]['redpacket_t_orderlimit']);
				order_total = parseFloat(order_total);
				if (order_total > 0 && order_total >= _tmp.toFixed(2))
				{
					$('#rpt').append("<option value='" + rpt_list_json[i]['redpacket_id'] + '|' + rpt_list_json[i]['redpacket_price'] + "'>" + rpt_list_json[i]['redpacket_title'] + "</option>")
					_hide_flag = false;
				}
			}

			if (_hide_flag)
			{
				$('#rpt_panel').hide();
			}
			else {
				$('#rpt_panel').show();
			}
		}

		//清除平台红包选择
		function clanRpt()
		{
			var allTotal = parseFloat($('.after_total').html());
			var orderRpr = $('#orderRpt').html();
			if(orderRpr !== undefined)
			{
				orderRpr = Number($('#orderRpt').html());
				$('#orderRpt').html('-0.00');
				var paytotal = allTotal + orderRpr*(-1);
				$('.after_total').html(paytotal.toFixed(2));
			}

		}

		$(function(){
			var allTotal = parseFloat($('.after_total').html());

			$('#rpt').on('change',function(){
				var allTotal = parseFloat($('.after_total').html());
				var items = $(this).val().split('|');

				if (items[0] == '')
				{
					var orderRpr = Number($('#orderRpt').html());
					$('#orderRpt').html('-0.00');
					var paytotal = allTotal + Math.abs(orderRpr);
					$('.after_total').html(paytotal.toFixed(2));
				}
				else
				{
					var items = $(this).val().split('|');
					$('#orderRpt').html('-'+number_format(items[1],2));
					var paytotal = allTotal - parseFloat(items[1]);
					if (paytotal < 0) paytotal = 0;

					$('.after_total').html(paytotal.toFixed(2));
				}
			});

			if (rpt_list_json.length == 0)
			{
				$('#rpt_panel').remove();
			}

			if ($('#orderRpt').length > 0)
			{
				iniRpt(allTotal.toFixed(2));
				$('#orderRpt').html('-0.00');
			}

		});
	</script>
<div class="cart-head">
	<div class="wrap">
		<div class="head_cont clearfix">
			<div class="nav_left" style="float:none;">
				<a href="index.php" class=""><img src="<?=$this->web['web_logo']?>"/></a>
				<a class="download iconfont"></a>
			</div>
		</div>
	</div>
</div>
	<div class="wrap">
		<div class="shop_cart_head clearfix">
			<div class="cart_head_left">
				<h4><?=__('填写核对购物信息')?></h4>
				
			</div>
			<div class="cart-head-module clearfix">
				<p class="tips-p"><span><i class="iconfont icon-orders-tips"></i></span><?=__('请仔细填写手机号，以确保电子兑换码准确发到您的手机。')?></p>
				<ul class="cart_process">
					<li class="mycart">
						<div class="fl">
							<i class="iconfont icon-wodegouwuche bbc_color"></i>
								<h4><?=__('我的购物车')?></h4><h4>
							</h4>
						</div>
						
					</li>
					<li class="mycart process_selected1">
						<div class="fl to"></div>
						<div class="fl">
							<i class="iconfont icon-iconquerendingdan bbc_color"></i>
								<h4 class=""><?=__('确认订单')?></h4><h4>
							</h4>
						</div>
						
					</li>
					<li class="mycart">
						<div class="fl to"></div>
						<div class="fl">
							<i class="iconfont icon-icontijiaozhifu"></i>
								<h4><?=__('支付提交')?></h4><h4>
							</h4>
						</div>
						
					</li>
					<li class="mycart">
						<div class="fl to"></div>
						<div class="fl">
							<i class="iconfont icon-dingdanwancheng"></i>
								<h4><?=__('订单完成')?></h4><h4>
							</h4>
						</div>
					</li>
				</ul>

			</div>
			
		</div>
		<div class="ncc-receipt-info">

			<h4 class="confirm"><?=__('支付方式')?></h4>
			<div class="pay_way pay-selected" pay_id="1">
				<i></i><?=__('在线支付')?>
			</div>
    <div class="">
      <h3 class="confirm"><?=__('电子兑换码/券接收方式')?></h3>
    </div>
    <div id="invoice_list" class="ncc-candidate-items">
      <ul style="overflow: visible;">
        <li><?=__('手机号码：')?>
          <div class="parentCls">
            <input name="buyer_phone" class="inputElem text" autocomplete="off" id="buyer_phone" value="" maxlength="11" type="text" onblur="checkmobile()">
            <span id="e_consignee_mobile_error"></span>
          </div>
        </li>
      </ul>
      <p class=""><i class="icon-info-sign"></i><?=__('您本次购买的商品不需要收货地址，请正确输入接收手机号码，确保及时获得“电子兑换码”。可使用您已经绑定的手机或重新输入其它手机号码。')?></p>
    </div>
  </div>
		<h4 class="confirm contfirm_1"><?=__('虚拟服务类商品清单')?></h4>
		<div class="cart_goods">
			<ul class="cart_goods_head clearfix">
				<li class="price_all"><?=__('小计')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
				<li class="goods_num goods_num_1"><?=__('数量')?></li>
				<li class="goods_price goods_price_1"><?=__('单价')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
				<li class="goods_name goods_name_1"><?=__('商品')?></li>
			</ul>

			<!-- S 计算店铺的会员折扣和总价 -->
			<?php
			$reduced_money = 0;//满送活动优惠的金额单独赋予一个变量
			$voucher_money = 0;//代金券活动优惠的金额单独赋予一个变量
			//判断后台是否开启了会员折扣，如果开启会员折扣则判断是否为自营店铺。计算店铺的折扣
			if(!Web_ConfigModel::value('rate_service_status') ||(Web_ConfigModel::value('rate_service_status') && $data['shop_base']['shop_self_support'] == 'true'))
			{
				$dian_rate = $data['goods_base']['sumprice']*(100-$user_rate)/100;
			}
			else
			{
				$dian_rate = 0;
			}

			//扣除折扣后店铺的店铺价格（本店合计）
			$shop_all_cost = number_format($data['goods_base']['sumprice']-$dian_rate,2,'.','');

			?>
			<!-- E 计算店铺的会员折扣和总价 -->

			<ul class="cart_goods_list clearfix">
				<li>
					<div class="bus_imfor clearfix">
						<p class="bus_name">
							<span>
								<i class="iconfont icon-icoshop"></i>
								<a href="javascript:;" class="cus_ser" ><?=($data['shop_base']['shop_name'])?></a>
								<?php if($data['shop_base']['shop_self_support'] == 'true'){ ?>
									<span><?=__('自营店铺')?></span>
								<?php } ?>
							</span>


						</p>

					</div>
					<table>
						<tbody class="rel_good_infor">
							<tr>

								<td class="goods_img"><img src="<?=($data['goods_base']['goods_image'])?>"/></td>
								<td class="goods_name" style="width:536px;"><a target="_blank" href="javascript:;"><?=($data['goods_base']['goods_name'])?></a>
									<p>
											<input type="hidden" id="goods_id" value="<?=($data['goods_base']['goods_id'])?>">
											<input type="hidden" id="goods_num" value="<?=($nums)?>">
										<?php if(!empty($data['goods_base']['spec'])){foreach($data['goods_base']['spec'] as $sk => $sv){ ?>
											<?=($sv)?> &nbsp;&nbsp;
										<?php }}?>
									</p>
								</td>
								<td class="goods_price goods_price_1 ">
									<?php if($data['goods_base']['old_price'] > 0){?><p class="ori_price"><?=($data['goods_base']['old_price'])?></p><?php }?>
									<p class="now_price"><?=($data['goods_base']['now_price'])?></p>

								</td>

								<td class="goods_num goods_num_1">
									<span><?=($nums)?></span>
								</td>
								<td class="price_all">
									<span class="subtotal"><?=($data['goods_base']['sumprice'])?></span>
								</td>
							</tr>
						</tbody>
					</table>

				</li>
			</ul>
			<?php
			$cart_total_price = $data['goods_base']['now_price'] * $data['goods_base']['goods_num'];
			//							$cart_total_price = array_sum($cart_goods_price);
			?>

			<span class="shop_voucher"></span>

            <div class="goods_remark clearfix">
                <input type="hidden" id="has_physical" name="has_physical" value="<?=$data['has_physical']?>" />
                <?php if($data['has_physical'] == 1){ ?>
				<p class="remarks"><span><?=__('备注：')?></span><input placeholder="<?=__('请填写收货人姓名、地址和联系方式')?>" type="text" id="goodsremarks"></p>
                <?php }else{ ?>
                <p class="remarks"><span><?=__('备注：')?></span><input placeholder="<?=__('补充填写其他信息,如有快递不到也请留言备注')?>" type="text" id="goodsremarks"></p>
                <?php } ?>
			</div>

			<div class="tlr bgf">

			</div>

			<div class="frank clearfix">
				<p class="back_cart"><a></a></p>

				<div class="invoice-cont">
					<span>会员折扣</span>
					<div style="display:inline">
						<?php if(Web_ConfigModel::value('rate_service_status') && $data['shop_base']['shop_self_support'] == 'false'){?>
                            <span><?=__('仅限自营店铺享受会员折扣')?></span>
                        <?php }else{?>
							<input type="checkbox" name="order_discount" id="is_discount">
							<span class="isFavoritesShop pl12" id="no_discount">开启会员折扣不可使用代金券和平台红包</span>
							<?php
								$discount_price = $data['goods_base']['sumprice'] - ($user_rate*$data['goods_base']['sumprice'])/100;
							?>
							<span class="isFavoritesShop pl12" style="display:none" id="use_discount" discount_price="<?=number_format($discount_price,2,'.','')?>">减￥<?=(number_format($discount_price,2,'.',''))?></span>
                        <?php }?>
					</div>
				</div>


				<p class="submit" style="text-align: center;">
					<span>
						<?=__('订单金额：')?>
						<strong>
							<?=(Web_ConfigModel::value('monetary_unit'))?><i class="total" total_price="<?=($data['goods_base']['sumprice'])?>"><?=($data['goods_base']['sumprice'])?></i>
						</strong>
					</span>

					<?php if(!Web_ConfigModel::value('rate_service_status') ||(Web_ConfigModel::value('rate_service_status') && $data['shop_base']['shop_self_support'] == 'true'))
					{}else{$user_rate = 100;}?>
					<?php if($user_rate != 100){ ?>
						<span>
							<?=__('会员折扣：')?>
							<strong>
								<i class="rate_total isFavoritesShop">未使用</i>
							</strong>
						</span>
					<?php } ?>

					<?php if($user_rate > 0){?>
						<span>
							<?=__('支付金额：')?>
							<strong>
								<?=(Web_ConfigModel::value('monetary_unit'))?>
								<i class="after_total bbc_color" after_total="<?=(number_format($data['goods_base']['sumprice'],2,'.','') )?>"><?=(number_format($data['goods_base']['sumprice'],2,'.','') )?></i>
							</strong>
						</span>
					<?php }?>

					<a id="pay_btn" class="bbc_btns"><?=__('提交订单')?></a>
				</p>

			</div>
		</div>
	</div>

	<!-- 订单提交遮罩 -->
	<div id="mask_box" style="display:none;">
		<div class='loading-mask'></div>
		<div class="loading">
			<div class="loading-indicator">
				<img src="<?= $this->view->img ?>/large-loading.gif" width="32" height="32" style="margin-right:8px;vertical-align:top;"/>
				<br/><span class="loading-msg"><?=__('正在提交订单，请稍后...')?></span>
			</div>
		</div>
	</div>
<script>

	if(<?=($user_rate)?>)
	{
		rate = <?=($user_rate)?>;
	}
	else
	{
		rate = 100;
	}




</script>

<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>