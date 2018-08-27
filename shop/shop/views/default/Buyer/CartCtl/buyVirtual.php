<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'site_nav.php';
?>

<script type="text/javascript" src="<?=$this->view->js?>/virtual.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/shop-cart.css" />
<link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
<link href="<?= $this->view->css ?>/login.css" rel="stylesheet">
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>
<div class="cart-head">
	<div class="wrap">
		<div class="head_cont clearfix">
			<div class="nav_left">
				<a href="index.php" class=""><img src="<?=$this->web['web_logo']?>"/></a>
				<a href="#" class="download iconfont"></a>
			</div>
		</div>
	</div>
</div>
<div class="wrap wrap_w">
	<div class="shop_cart_head clearfix">
		<div class="cart_head_left">
			<h4><?=__('购买兑换码')?></h4>
			
		</div>
		<div class="cart-head-module clearfix">
			<p class="tips-p"><span><i class="iconfont icon-orders-tips"></i></span><?=__('设置购买数量')?></p>
			<ul class="cart_process">
				<li class="mycart process_selected1">
					<div class="fl">
						<i class="iconfont icon-wodegouwuche bbc_color"></i>
						<h4 class=""><?=__('我的购物车')?><h4>
					</div>
					
					
				</li>
				<li class="mycart">
					<div class="fl to"></div>
					<div class="fl">
						<i class="iconfont icon-iconquerendingdan"></i>
						<h4><?=__('确认订单')?><h4>
					</div>
					
					
				</li>
				<li class="mycart">
					<div class="fl to"></div>
					<div class="fl">
						<i class="iconfont icon-icontijiaozhifu"></i>
						<h4><?=__('支付提交')?><h4>
					</div>
					
					
				</li>
				<li class="mycart">
					<div class="fl to"></div>
					<div class="fl">
						<i class="iconfont icon-dingdanwancheng"></i>
						<h4><?=__('订单完成')?><h4>
					</div>
					
				</li>
			</ul>
		</div>
		
	</div>

	<div class="cart_goods">
		<ul class='cart_goods_head clearfix'>
			<li class="done"><?=__('操作')?></li>
			<li class="price_all"><?=__('小计')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
			<li class="goods_num"><?=__('数量')?></li>
			<li class="goods_price"><?=__('单价')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
			<li class="cart_goods_all "></li>
			<li class="goods_name"><?=__('商品')?></li>
		</ul>
		<form id="form" action="?ctl=Buyer_Cart&met=confirmVirtual" method='post'>
		<ul class="cart_goods_list clearfix">
				<li>
					<div class="bus_imfor clearfix">
						<p class="bus_name">
							<span><i class="iconfont icon-icoshop"></i><?=($data['shop_base']['shop_name'])?></span><a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?=($data['shop_base']['shop_id'])?>" class="cus_ser"></a>
					</div>
					<table id="table_list">
						<tbody class="rel_good_infor">
							<tr class="row_line">
								<td class="goods_img"><img src="<?=($data['goods_base']['goods_image'])?>"/></td>
								<td class="goods_name" style="width: 536px;">
									<a  target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($data['goods_base']['goods_id'])?>"><?=($data['goods_base']['goods_name'])?></a>

									<?php if(isset($data['goods_base']['goods_base']['promotion_type'])): ?>
										<p class="sal_price">
										<?php if($data['goods_base']['goods_base']['promotion_type'] == 'groupbuy' && $data['goods_base']['goods_base']['down_price']): ?>
											<?=__('团购,直降：')?><?=format_money($data['goods_base']['goods_base']['down_price'])?>
										<?php endif;?>

										<?php if($data['goods_base']['goods_base']['promotion_type'] == 'xianshi' && $data['goods_base']['goods_base']['down_price']): ?>
											<?=__('限时折扣,直降：')?><?=format_money($data['goods_base']['goods_base']['down_price'])?>
										<?php endif;?>
										</p>
									<?php endif; ?>

									<p>
										<?php if(!empty($data['goods_base']['spec'])){foreach($data['goods_base']['spec'] as $sk => $sv){ ?>
											<?=($sv)?> &nbsp;&nbsp;
										<?php }}?>
									</p>
									<?php
									//判断当前活动是否开始并且是否设置了最低限购的数量
									if($data['goods_base']['promotion_price'] && strtotime($data['goods_base']['groupbuy_starttime']) <= time() && strtotime($data['goods_base']['groupbuy_endtime']) > time())
									{
										$promotion_price = $data['goods_base']['promotion_price'];
									}
									else
									{
										$promotion_price = $data['goods_base']['now_price'];
									}
									if(isset($data['goods_base']['lower_limit']) && strtotime($data['goods_base']['groupbuy_starttime']) <= time() && strtotime($data['goods_base']['groupbuy_endtime']) > time())
									{
										$lower_limit = $data['goods_base']['lower_limit'];
									}
									else
									{
										$lower_limit = 1;
									}
									//判断当前商品数量是否低于最低限购的数量
									if($lower_limit > $data['goods_base']['cart_num'])
									{
										$data['goods_base']['cart_num'] = $lower_limit;
									}
									//判断当前商品数量是否大于最高限购的数量
									if($data['goods_base']['upper_limit'] && $data['goods_base']['upper_limit']<$data['goods_base']['cart_num'])
									{
										$data['goods_base']['cart_num'] = $data['goods_base']['upper_limit'];
									}
									?>
									<p>
										<input type="hidden" id="goods_id" name="goods_id" value="<?=($data['goods_base']['goods_id'])?>">
<!--										<input type="hidden" id="goods_price" value="--><?//=($data['goods_base']['now_price'])?><!--">-->
										<input type="hidden" id="goods_price" value="<?=$promotion_price?>">
									</p>
								</td>
								<td class="goods_price">
									<?php if($data['goods_base']['old_price'] > 0){?><p class="ori_price"><?=($data['goods_base']['old_price'])?></p><?php }?>
									<p class="now_price"><?=$promotion_price?></p>
								</td>
								<td class="goods_num">
									<?php
									if($data['buy_limit'])
									{
										$data_max = $data['buy_residue'];
									}
									else
									{
										$data_max = $data['goods_base']['goods_stock'];
									}
									?>
									<a class="<?php if($data['goods_base']['cart_num'] == 1){?>no_<?php }?>reduce" ><?=__('-')?></a>
									<input id="nums" name="nums" data-id="<?=($data['goods_base']['goods_id'])?>" data-min="<?=$lower_limit?>" data-max="<?=($data_max)?>" value="<?=($data['goods_base']['cart_num'])?>">
									<a class="<?php if($data_max <= 1){?>no_<?php }?>add" ><?=__('+')?></a>
								</td>
								<td class="price_all cell<?=($data['goods_base']['goods_id'])?>">

									<span class="subtotal"><?=(number_format($promotion_price * $data['goods_base']['cart_num'],2,'.',''))?></span>
								</td>
								<td class="done del"><a onclick="collectGoods(<?=($data['goods_base']['goods_id'])?>)"><i class="iconfont icon-wenjianjia rel_top2" style="font-size: 20px;"></i><?=__('加入收藏夹')?></a></td>
							</tr>
						</tbody>
					</table>
				</li>
		</ul>
		</form>
	</div>
	<div class="pay_fix wrap3">
		<div class="wrap wrap2 cart-checkbox">
			<a class="submit-btn bbc_btns"><?=__('去付款')?><span class="iconfont icon-iconjiantouyou"></span></a>
			<div class="cart-sum">
				<span><?=__('合计：')?></span>
				<strong class="price"><?=(Web_ConfigModel::value('monetary_unit'))?><em class="subtotal subtotal_all"><?=(number_format($promotion_price * $data['goods_base']['cart_num'],2,'.',''))?></em></strong>
			</div>

		</div>
	</div>

</div>

<!-- 登录遮罩层 -->
<div id="login_content" style="display:none;">
</div>

<script>
	//收藏商品
	window.collectGoods = function(e){
		if ($.cookie('key'))
		{
			$.post(SITE_URL  + '?ctl=Goods_Goods&met=collectGoods&typ=json',{goods_id:e},function(data)
			{
				if(data.status == 200)
				{
					Public.tips.success(data.data.msg);
				}
				else
				{
					Public.tips.error(data.data.msg);
				}
			});
		}
		else
		{
			$("#login_content").show();
			load_goodseval(SITE_URL  + '?ctl=Index&met=fastLogin','login_content');
		}

	}
</script>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>