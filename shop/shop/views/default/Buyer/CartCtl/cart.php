<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'site_nav.php';
?>

<script type="text/javascript" src="<?=$this->view->js?>/cart.js"></script>
<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/shop-cart.css" />
<link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
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
<div class="wrap wrap_w">
	<div class="shop_cart_head clearfix">
		<div class="cart_head_left">
			<h4><?=__('我的购物车')?></h4>
			
		</div>
		<div class="cart-head-module clearfix">
			<p class="tips-p"><span><i class="iconfont icon-orders-tips"></i></span><?=__('查看购物车商品清单,增加减少商品数量,并勾选想要的商品进入下一步操作。')?></p>
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

	<?php if($data['count'] == 0){?>
		<div class="cart_empty clearfix">
			<div class="cart_log">
				<img src="<?=$this->view->img?>/img_sc_icon2.png"/>
			</div>
			<div class="empty-warn">
				<p><?=__('您的购物车还是空的')?></p>
				<div>
					<a href="<?= Yf_Registry::get('url') ?>"><span class="iconfont icon-mashangqugouwu"></span><?=__('马上去购物')?></a>
					<a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical"><span class="iconfont icon-dingdan rel_top2"></span><?=__('查看自己的订单')?></a>
				</div>
			</div>
		</div>
	<?php }else{?>
	<ul class="cart_goods_type clearfix">
		<li>
            <a class="goods_selected bbc_bg">
                <?=__('全部商品')?>
                <i>(<?=($data['count'])?>)</i>
            </a>
        </li>
		<?php unset($data['count']);?>

	</ul>

	<div class="cart_goods">
		<ul class='cart_goods_head clearfix'>
			<li class="done"><?=__('操作')?></li>
			<li class="price_all"><?=__('小计')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
			<li class="goods_num"><?=__('数量')?></li>
			<li class="goods_price"><?=__('单价')?>(<?=(Web_ConfigModel::value('monetary_unit'))?>)</li>
			<li class="goods_name"><?=__('商品')?></li>
			<li class="cart_goods_all cart-checkbox " style="float:left;"><input class="checkall" type="checkbox"  data-type="all"><div class="select_all"><?=__('全选')?></div></li>
		</ul>
		<form id="form" action="?ctl=Buyer_Cart&met=confirm" method='post'>
		<ul class="cart_goods_list clearfix">
			<?php
            $arr = array();
            foreach($data as $key=>$val){
				?>
				<li class="carts_content">
					<div class="bus_imfor clearfix">
						<p class="bus_name">
							<input class="checkshop checkitem" type="checkbox" data-type="all">
							<span><i class="iconfont icon-icoshop"></i><a class="hover-cor3a" href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?=($key)?>"><?=($val['shop_name'])?></a><?php if($val['shop_self_support'] == 'true'){?> <em><?=__('自营店铺')?></em><?php }?></span>
						</p>
					</div>
					<table id="table_list" class="table_list">
						<tbody class="rel_good_infor">
						<?php
                        foreach($val['goods'] as $k=>$v){ ?>
                            <?php

                                if($v['buy_limit'] && !$v['IsHaveBuy'])
                                {
                                    $data_max = $v['buy_residue'];
                                }
                                else
                                {
                                    $data_max = $v['goods_base']['goods_stock'];
                                }

                                if(strtotime($v['goods_base']['groupbuy_starttime']) < time() && strtotime($v['goods_base']['groupbuy_endtime']) > time()){
                                    $data_min = $v['goods_base']['lower_limit'] ?: 1 ;
                                }else{
                                    $data_min = 1 ;
                                }
                                if($data_min > $v['goods_num'] ){
                                    $arr[$v['cart_id']] = $data_min;
                                }

                            ?>

							<tr class="row_line">
								<td class="goods_sel cart-checkbox">
									<p>
                                        <?php if($data_max >= $data_min){?>

										<input class="checkitem" type="checkbox" data-nums_id='nums_<?=($v['cart_id'])?>' name="product_id[]" value="<?=($v['cart_id'])?>" <?php if($v['IsHaveBuy']){?> disabled title="您已达限购数量" <?php }?> >
                                        <?php } ?>
									</p>
								</td>
								<td class="goods_img posr"> <?php if($v['goods_base']['is_del'] ==2 ){ ?><p class="old-Failed">此商品<br/>已失效</p> <?php } ?><img src="<?=($v['goods_base']['goods_image'])?>"/></td>
								<td class="goods_name_reset">
                                    <?php if($v['goods_base']['is_del'] ==2){ ?>
                                        <a class='hover-cor3a' href='javascript:void(0)'><?=($v['goods_base']['goods_name'])?><p style="color: red">该商品已下架或删除</p> </a>
                                    <?php    }else{?>
                                        <a class='hover-cor3a' target="_blank" href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?=($v['goods_base']['goods_id'])?>"><?=($v['goods_base']['goods_name'])?></a>
                                    <?php    } ?>


								<?php if(isset($v['goods_base']['promotion_type'])): ?>
									<p class="sal_price">
										<?php if($v['goods_base']['promotion_type'] == 'groupbuy' && $v['goods_base']['down_price'] && $v['goods_base']['groupbuy_starttime'] <= date('Y-m-d H:i:s',time())) : ?>
												<?=__('团购,直降：')?><?=format_money($v['goods_base']['down_price'])?>
										<?php endif;?>

										<?php if($v['goods_base']['promotion_type'] == 'xianshi' && $v['goods_base']['down_price']): ?>
												<?=__('限时折扣,直降：')?><?=format_money($v['goods_base']['down_price'])?>
										<?php endif;?>
									</p>
								<?php endif; ?>



								<p>
									<?php if(!empty($v['goods_base']['spec'])){foreach($v['goods_base']['spec'] as $sk => $sv){ ?>
									<?=($sv)?>&nbsp;&nbsp;
									<?php }}?>
								</p>

								</td>

								<td class="goods_price">
									<?php if($v['old_price'] > 0){?><p class="ori_price"><?=($v['old_price'])?></p><?php }?>
									<p class="now_price"><?=($v['now_price'])?></p>
								</td>
								<td class="goods_num">
                                  <!--商品数量增/减-->
									<p class="clearfix">
										<a class="<?php if($v['goods_num'] == 1){?>no_<?php }?>reduce"   ><?=__('-')?></a>
										<input id="nums" name="nums_<?=($v['cart_id'])?>" data-id="<?=($v['cart_id'])?>" data-max="<?=($data_max)?>" data-min="<?=$data_min?>" value="<?php echo $v['goods_num']; ?>" data-limit="<?=$v['buy_limit']?>">
										<a class="<?php if($data_max <= 1){?>no_<?php }?>add" ><?=__('+')?></a>
									</p>
                                   
                                    <p>
                                        <?php
                                        if($v['buy_limit'])
                                        {
                                           echo '限购'.$v['buy_limit'].'件';
                                        
                                        }
                                        ?>
                                    </p>
                                    
								</td>
								<td class="price_all cell<?=($v['cart_id'])?>">
									<span class="subtotal"><?php echo $v['sumprice'];?></span>
								</td>
								<td class="done del"><a data-param="{'ctl':'Buyer_Cart','met':'delCartByCid','id':'<?=($v['cart_id'])?>'}"><?=__('删除')?></a></td>
							</tr>
						<?php }?>
						</tbody>

					</table>
				</li>
			<?php }?>
            <?php
            if(!empty($arr)){
                ?>
                <input type="hidden"  data-info=<?php echo json_encode($arr);?> id="infoss">
            <?php
            }
            ?>
		</ul>
		</form>
	</div>
	<div class="pay_fix wrap3">
		<div class="wrap cart-checkbox">
			<div class="clearfix cart_pad">
				<input class="checkall" type="checkbox" data-type="all">
				<div class="select_all"><?=__('全选')?></div>
				<div  class="delete"><?=__('删除')?></div>
				<a class="submit-btn-disabled submit-btn bbc_btns"><?=__('去付款')?><span class="iconfont icon-iconjiantouyou"></span></a>
				<div class="cart-sum">
					<span><?=__('合计(不含运费)：')?></span>
					<strong class="price common-color"><?=(Web_ConfigModel::value('monetary_unit'))?><em class="subtotal subtotal_all common-color">0.00</em></strong>
				</div>
			</div>
		</div>
	</div>
	<!--<div class="pages">
        <div>
            <span class="goods_prev"><</span>
            <a href="#" class="active1">1</a><a href="#">2</a><a href="#">3</a>
            <span class="goods_next">></span>
        </div>
    </div>-->
    <!--你可能还喜欢-->
    <!--<div  style="" class="foot-wrapper">
		<p>你可能还喜欢</p>
		<ul class="clearfix">
			<li class="fl">
				<div class='wrapper-img'></div>
    			<p class='wrapper-p1' style="">￥321.059</p>
    			<p class='wrapper-p2' style="">艾莱伊中长款加厚修身</p>
    			<div class="wrapper-btn">去看看</div>
			</li>
		</ul>	
	</div>-->
    
    

	<?php }?>
</div>

<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>