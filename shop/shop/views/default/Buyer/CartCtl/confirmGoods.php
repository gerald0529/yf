<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'site_nav.php';
?>
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/shop-cart.css" />
	<script type="text/javascript" src="<?=$this->view->js?>/alert.js"></script>
	<script  type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.dialog.js"></script>
	<link type="text/css" rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css">
	<link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
	<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>

	<div class="cart-head">
		<div class="wrap">
			<div class="head_cont clearfix">
				<div class="nav_left" style="float:none;">
					<a href="index.php" class=""><img src="<?=$this->web['web_logo']?>"/></a>
					<a href="#" class="download iconfont"></a>
				</div>
			</div>
		</div>
	</div>
	
	<div class="wrap">
		<div class="shop_cart_head clearfix">
			<div class="cart_head_left">
				<h4><?=__('确认订单')?></h4>
				
			</div>
			<div class="cart-head-module clearfix">
				<p class="tips-p"><span><i class="iconfont icon-orders-tips"></i></span><?=__('请仔细核对收货,发货等信息,以确保物流快递能准确投递')?>.</p>
				<ul class="cart_process">
					<li class="mycart">
						<div class="fl">
							<i class="iconfont icon-wodegouwuche bbc_color"></i>
							<h4><?=__('我的购物车')?><h4>
						</div>
					
					</li>
					<li class="mycart process_selected1">
						<div class="fl to"></div>
						<div class="fl">
							<i class="iconfont icon-iconquerendingdan bbc_color"></i>
							<h4 class=""><?=__('确认订单')?><h4>
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

		<ul class="receipt_address clearfix">
		<div id="address_list">
		<?php if(isset($goods_info['address'])){$total = 0; $total_dian_rate = 0; foreach ($goods_info['address'] as $key => $value) {
		?>
			<li class="<?php if(!$address_id && $value['user_address_default'] == 1){?>add_choose<?php }?><?php if($address_id && $value['user_address_id'] == $address_id){?>add_choose<?php }?> " id="addr<?=($value['user_address_id'])?>">
				<input type ="hidden" id="address_id" value="<?=($value['user_address_id'])?>">
				<input type="hidden" id="user_address_province_id" value="<?=($value['user_address_province_id'])?>">
				<input type="hidden" id="user_address_city_id" value="<?=($value['user_address_city_id'])?>">
				<input type="hidden" id="user_address_area_id" value="<?=($value['user_address_area_id'])?>">
				<div class="editbox">
					<a class="edit_address" data_id="<?=($value['user_address_id'])?>"><?=__('编辑')?></a>
					<a class="del_address" data_id="<?=($value['user_address_id'])?>"><?=__('删除')?></a>
				</div>
				<h5><?=($value['user_address_contact'])?></h5>
				<p class="addr-len"><?=($value['user_address_area'])?> <?=($value['user_address_address'])?></p><span class="phone"><?=($value['user_address_phone'])?></span>
			</li>
			<?php }}?>
		</div>
			<div class="add_address">
				<a><?=__('+')?></a>
			</div>
		</ul>

		<h4 class="confirm"><?=__('支付方式')?></h4>
			<div class="pay_way pay-selected" pay_id="1">
				<i></i><?=__('在线支付')?>
			</div>
			<div class="pay_way" pay_id="2">
				<i></i><?=__('货到付款')?>
			</div>

		<h4 class="confirm"><?=__('确认商品信息')?></h4>
		<div class="cart_goods">
			<ul class='cart_goods_head clearfix'>
				<li class="price_all"><?=__('小计')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
				<li class="confirm_sale"><?=__('优惠')?></li>
				<li class="goods_num"><?=__('数量')?></li>
				<li class="goods_price"><?=__('单价')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
				<li class="goods_name"><?=__('商品')?></li>
				<li class="cart_goods_all"></li>
			</ul>

			<ul class="cart_goods_list clearfix">
				<li>
					<div class="bus_imfor clearfix">
						<p class="bus_name">
							<span>
								<i class="iconfont icon-icoshop"></i>
								<a href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?=($goods_info['shop']['shop_id'])?>"><?=($goods_info['shop']['shop_name'])?></a>
								<?php if($goods_info['shop']['shop_self_support'] == 'true'){ ?>
									<span><?=__('自营店铺')?></span>
								<?php } ?>
							</span>
						</p>

					</div>
					<table>
						<tbody class="rel_good_infor rel_good_infor2">
						
							<tr>
								<td class="goods_sel"></td>
								<td class="goods_img"><img src="<?=($goods_info['base']['goods_image'])?>"/></td>
								<td class="goods_name_reset">
									<a  target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($goods_info['base']['goods_id'])?>"><?=($goods_info['base']['goods_name'])?></a>
									<p>
										<?php if(!empty($goods_info['base']['spec'])){foreach($goods_info['base']['spec'] as $sk => $sv){ ?>
											<?=($sv)?> &nbsp;&nbsp;
										<?php }}?>
									</p>
								</td>

								<td class="goods_price">
<!--									--><?php //if($goods_info['base']['goods_market_price'] > 0){?><!--<p class="ori_price">--><?//=($goods_info['base']['goods_market_price'])?><!--</p>--><?php //}?>
									<p class="now_price"><?=($goods_info['base']['goods_price'])?></p>

								</td>
								<td class="goods_num">
									<span><?=($goods_info['base']['goods_num'])?></span>
								</td>
								<td class="confirm_sale"><?=$goods_info['base']['rate_price']?></td>
								<td class="price_all">
									<span class="subtotal"><?=($goods_info['base']['sumprice'])?></span>
									<?php if(!$goods_info['common']['buy_able']){?><p class="colred"><?=__('无货')?></p><?php }?>
								</td>
							</tr>
							
						</tbody>
					</table>
					
					
					
			<div class="goods_remark clearfix">
				<div class="tlr bgf z-bor clearfix">
					<div class="inline fl mw-800">
						<p class="remarks mb10"><span><?=__('备注：')?></span><input type="text" class="remarks_content" name="remarks" id="<?=($goods_info['shop']['shop_id'])?>" placeholder="<?=__('限45个字（定制类商品，请将购买需求在备注中做详细说明）')?>"><?=__('提示：请勿填写有关支付、收货、发票方面的信息')?>
						</p>
					</div>
				</div>
				<div class="order_total">
					<p class="clearfix mt10">
						<span><?=__('商品金额')?></span>
						<i class="price<?=($goods_info['shop']['shop_id'])?>"><?=(number_format($goods_info['base']['sumprice'],2,'.',''))?></i>
					</p>
					<p class="clearfix trans<?=($goods_info['shop']['shop_id'])?>">
						<span><?=__('物流运费')?></span>
                        <?php if($goods_info['transport']['cost'] > 0){?>
                            <strong class="trancon<?=($goods_info['shop']['shop_id'])?>"><?=($goods_info['transport']['con'])?></strong>
                            <i class="trancost<?=($goods_info['shop']['shop_id'])?>">
                                <?=(number_format($goods_info['transport']['cost'],2))?>
                                <input type="hidden" class="shop_trancost<?=($goods_info['shop']['shop_id'])?>" value="<?=(number_format($goods_info['transport']['cost'],2))?>">
                            </i>
                        <?php }else{ ?>
                            <i class="trancost<?=($goods_info['shop']['shop_id'])?>">0</i>
                            <input type="hidden" class="shop_trancost<?=($goods_info['shop']['shop_id'])?>" value="0.00">
                        <?php }?>
					</p>


					<p class="dian_total clearfix">
						<span class=""><?=__('本店合计')?></span>
						<em></em>
						<i class="sprice<?=($goods_info['shop']['shop_id'])?>">
							<?php
							echo number_format($goods_info['shop']['sprice'],2,'.','');
							?>
						</i>
					</p>

					

					<?php if(isset($goods_info['distributor_rate'])){?>
						<p class="clearfix">
						<span><?=__('分销商优惠')?></span>
						<i><?=number_format($goods_info['distributor_rate'],2,'.','')?></i>
						</p>
					<?php }?>
					
					
				</div>
			<div class="frank clearfix">
				<div class="invoice tl clearfix mt10">
					<h3><?=__('发票信息')?></h3>
					<div class="invoice-cont">
						<input type="hidden" name="invoice_id" value="" id='order_invoice_id'>
						<input type="hidden" name="invoice_content" value="" id='order_invoice_content'>
						<input type="hidden" name="invoice_title" value="" id='order_invoice_title'>
						<span class="mr10"> <?=__('不开发票')?> </span><a class="invoice-edit"><?=__('修改')?></a>
					</div>
				</div>

				<p class="submit" style="text-align: center;">
					<span>
						<?=__('订单金额：')?>
                            <strong>
							<?=(Web_ConfigModel::value('monetary_unit'))?><i class="total" total_price="<?=(number_format($goods_info['shop']['sprice'],2,'.','') )?>"><?=(number_format($goods_info['shop']['sprice'],2,'.','') )?></i>
                            </strong>
					</span>


					<span>
						<?=__('支付金额：')?>
						<strong class="common-color">
							<?=(Web_ConfigModel::value('monetary_unit'))?><i class="after_total bbc_color" after_total="<?=(number_format($goods_info['shop']['sprice'],2,'.','') )?>"><?=(number_format($goods_info['shop']['sprice'],2,'.','') )?></i>
						</strong>
					</span>
                    <input type="hidden" id="token" value="<?=$goods_info['token']?>" name="token" />
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
    <script type="text/javascript">
        var app_id = '<?=(Yf_Registry::get('shop_app_id'))?>';
        var buy_able = '<?=$goods_info['common']['buy_able']?>';
        var goods_id = '<?=$goods_info['base']['goods_id']?>';  
        var goods_num = '<?=$goods_info['base']['goods_num']?>'; 
        var address_id = '<?=$goods_info['address']['address_id']?>'; 
    </script>
    <script type="text/javascript" src="<?=$this->view->js?>/comfirm_goods_cart.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>