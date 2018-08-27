<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

	<div class="basic-info">
		<dl>
			<dt>
               <p><img width="180" height="80" src="<?php if(!empty($shop_base['shop_logo'])){ ?><?=$shop_base['shop_logo']?>!180x50.jpg<?php }else{?> <?=$this->web['shop_head_logo']?>!180x50.jpg <?php } ?>" /></p>
			<a href="index.php?ctl=Seller_Shop_Setshop&met=index&typ=e&"><i class="iconfont icon-shangjiaruzhushenqing rel_top-2"></i><?=__('编辑店铺设置')?></a>
			</dt>
			<dd>
				<?php
					if(mb_strlen($shop_base['shop_name'])>45)
					{
						$shop_base['shop_name'] = substr($shop_base['shop_name'],0,45).'...';
					}
				?>
				<h3><?=$shop_base['shop_name']?></h3>
				<h5><?=__('用户名')?>：<?=$shop_base['user_name']?></h5>
			</dd>
			<dd>
<!--				<span>管理权限：<em>管理员</em></span>-->
				<span><?=__('店铺等级')?>：<em><?=$shop_base['shop_grade']?></em></span>
				<?php if($shop_base['shop_self_support'] == 'false'){ ?>
					<span><?=__('有 效 期')?>：<em><?=$shop_base['shop_end_time']?></em></span>
				<?php } ?>
			</dd>
			<dd>
				<span><?=__('最后登录')?>：<em><?=$user_base['user_login_time']?></em></span>
				<span><?=__('iP地址')?>：<em><?=$user_base['user_login_ip']?></em></span>
				<!--<span>不是您登录的？请立即 <a target="_blank" href="home.php?m=member&s=security">"更改密码"</a></span>-->
			</dd>
		</dl>
		<div class="detail-rate">
			<h5><strong><?=__('同行业相比')?></strong><?=__('店铺动态评分')?></h5>
			<ul>
				<li>
					<span> <?php if($shop_detail['com_desc_scores'] > 0){ ?><i class="iconfont  icon-jiantouxiangshang bbc_seller_color"></i><?=__('高于')?><?php }elseif($shop_detail['com_desc_scores'] < 0){ ?><i class="iconfont  icon-jiantouxiangxia "></i><?=__('低于')?><?php }else{ ?><i class="iconfont icon-jiantouxiangshang "></i><?=__('等于')?><?php }?></span><?=__('描述相符：')?><em><?=number_format($shop_detail['shop_desc_scores'],2,'.','')?><?=__('分')?></em>
				</li>
                <li>
					<span> <?php if($shop_detail['com_service_scores'] > 0){?><i class="iconfont  icon-jiantouxiangshang bbc_seller_color"></i><?=__('高于')?><?php }elseif($shop_detail['com_service_scores'] < 0){ ?><i class="iconfont  icon-jiantouxiangxia "></i><?=__('低于')?><?php }else{ ?><i class="iconfont icon-jiantouxiangshang "></i><?=__('等于')?><?php }?></span><?=__('服务态度：')?><em><?=number_format($shop_detail['shop_service_scores'],2,'.','')?><?=__('分')?></em>
				</li>
				<li>
					<span><?php if($shop_detail['com_send_scores'] > 0){ ?><i class="iconfont  icon-jiantouxiangshang bbc_seller_color"></i><?=__('高于')?><?php }elseif($shop_detail['com_send_scores'] < 0){ ?><i class="iconfont  icon-jiantouxiangxia "></i><?=__('低于')?><?php }else{ ?><i class="iconfont icon-jiantouxiangshang "></i><?=__('等于')?><?php }?></span><?=__('发货速度：')?><em><?=number_format($shop_detail['shop_send_scores'],2,'.','')?><?=__('分')?></em>
				</li>
			</ul>
		</div>
	</div>
	<div class="container fn-clear container_cj">
		<div class="m white-panel">
        	<div class="pannel_div">
            	<div class="mt">
					<h3 class="bbc_seller_border"><?=__('店铺及商品提示')?></h3>
					<h5><?=__('您需要关注的店铺信息以及待处理事项')?></h5>
				</div>
				<div class="mc">
					<div class="focus">
						<span><?=__('店铺商品发布情况')?>： <?= $goods_state_normal_num+$goods_state_offline_num+$goods_state_illegal_num ?> / <?= $shop_grade_goods_limit ? $shop_grade_goods_limit : '不限'?></span>
						<span><?=__('图片空间使用')?>： <?= $shop_album_num ?> / <?= $shop_grade_album_limit ? $shop_grade_album_limit : '不限'?></span></span>
					</div>
					<ul class="fn-clear">
						<li><a class="<?=$goods_state_normal_num?'num bbc_border bbc_color':''?>" href="./index.php?ctl=Seller_Goods&met=online&typ=e"><?=__('出售中商品')?><em class="bbc_seller_bg"><?=$goods_state_normal_num?$goods_state_normal_num:''?></em></a></li>
						<li><a class="<?=$goods_verify_waiting_num?'num num1 bbc_border bbc_color':''?>" href="./index.php?ctl=Seller_Goods&met=offline&met=verify&typ=e&op=3"><?=__('待审核商品')?><em class="bbc_seller_bg"> <?=$goods_verify_waiting_num?$goods_verify_waiting_num:''?></em></a></li>
						<li><a class="<?=$goods_state_offline_num?'num bbc_border bbc_color':''?>" href="./index.php?ctl=Seller_Goods&met=offline&typ=e&op=1" ><?=__('仓库中商品')?> <em class="bbc_seller_bg"><?=$goods_state_offline_num ? $goods_state_offline_num : ''?></em></a></li>
						<li><a class="<?=$goods_state_illegal_num?'num num1  bbc_border bbc_color':''?>" href="./index.php?ctl=Seller_Goods&met=lockup&typ=e&op=2" ><?=__('违规下架商品')?> <em class="bbc_seller_bg"><?=$goods_state_illegal_num? $goods_state_illegal_num: ''?></em></a></li>
					</ul>
				</div>
			</div>
        </div>

		<div class="m white-panel">
        	<div class="pannel_div">
				<div class="mt">
					<h3 class="bbc_seller_border"><?=__('交易提示')?></h3>
					<h5><?=__('您需要立即处理的交易订单')?></h5>
				</div>
				<div class="mc">
					<div class="focus">
						<span><?=__('近期售出')?>： <a href="./index.php?ctl=Seller_Trade_Order&met=physical&typ=e&"><?=__('交易中的订单')?></a></span>
						<span><?=__('维权提示')?>： <a href="./index.php?ctl=Seller_Service_Complain&met=index&typ=e&"><?=__('收到维权投诉')?>&nbsp;<?= $complain_unsolved_count ? $complain_unsolved_count : 0 ?> / <?= $complain_all_count ? $complain_all_count : 0?></a></span>
					</div>
					<ul id="order_num_list">
						<li><a href="./index.php?ctl=Seller_Trade_Order&met=getPhysicalNew&typ=e"><?=__('待付款订单')?></a></li>
						<li><a href="./index.php?ctl=Seller_Trade_Deliver&met=deliver&typ=e"><?=__('待发货订单')?></a></li>
						<li><a href="./index.php?ctl=Seller_Service_Return&met=orderReturn&typ=e" class=""><?=__('退款订单')?></a></li>
						<li><a href="./index.php?ctl=Seller_Service_Return&met=goodsReturn&typ=e" class=""><?=__('退货订单')?></a></li>
					</ul>
				</div>
        	</div>
		</div>

		<div class="m white-panel">
            <div class="pannel_div">
				<div class="mt">
					<h3 class="bbc_seller_border"><?=__('销售情况统计')?></h3>
					<h5><?=__('按周期统计商家店铺的订单量和订单金额')?></h5>
				</div>
				<div class="mc">
					<table width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="20%"></td>
							<td width="40%"><?=__('订单量')?></td>
							<td width="40%"><?=__('订单金额')?></td>
						</tr>
						<tr>
							<td><?=__('今日销量')?></td>
							<td><?=@$today['sales_num']?></td>
							<td><?=@format_money($today['order_sales'])?></td>
						</tr>
						<tr>
							<td><?=__('周销量')?></td>
							<td><?=@$week['sales_num']?></td>
							<td><?=@format_money($week['order_sales'])?></td>
						</tr>
						<tr>
							<td><?=__('月销量')?></td>
							<td><?=@$month['sales_num']?></td>
							<td><?=@format_money($month['order_sales'])?></td>
						</tr>
					</table>
				</div>
            </div>
		</div>

		<div class="m white-panel">
            <div class="pannel_div">
				<div class="mt">
					<h3 class="bbc_seller_border"><?=__('单品销售排名')?></h3>
					<h5><?=__('掌握30日内最热销的商品及时补充货源')?></h5>
				</div>
				<div class="mc rank">
					<table width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50"><?=__('排名')?></td>
							<td colspan="2" class="tl"><?=__('商品信息')?></td>
							<td width="70"><?=__('销量')?></td>
						</tr>
						<?php
						if(!empty($shop_top_rows)) {
							foreach ($shop_top_rows as $key => $shop_top_row) {
								?>
								<tr>
									<td><?= $key + 1 ?></td>
									<td class="tl" width="40"><a target="_blank"
																 href="<?= Yf_Registry::get('index_page') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $shop_top_row['goods_id'] ?>"><img
												width="32" src="<?= image_thumb($shop_top_row['goods_image'], 60, 60) ?>"/></a>
									</td>
									<td class="tl"><a target="_blank"
													  href="<?= Yf_Registry::get('index_page') ?>?ctl=Goods_Goods&met=goods&type=goods&gid=<?= $shop_top_row['goods_id'] ?>"><?= $shop_top_row['goods_name'] ?></a>
									</td>
									<td><?= $shop_top_row['goods_num'] ?></td>
								</tr>
								<?php
							}
						}
						?>
					</table>
				</div>
            </div>
		</div>

        <div class="m white-panel">
             <div class="pannel_div">	
            <div class="mt">
                <h3 class="bbc_seller_border"><?=__('店铺运营推广')?></h3>
                <h5><?=__('合理参加促销活动可以有效提升商品销量')?></h5>
            </div>
            <div class="mc">
                <div class="content">
                    <?php if($data['promotion_items']['groupbuy_allow_flag']){  ?>
                    <dl class="tghd">
                        <dt class="p-name"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_GroupBuy&met=index&typ=e"><?=__('团购')?></a></dt>
                        <dd class="p-ico"></dd>
                        <dd class="p-hint">
                            <i class="icon-ok-sign"></i><?php if($data['promotion_items']['groupbuy_combo_flag']){ ?><?=__('已开通')?><?php }else{ ?><?=__('未开通')?><?php } ?>
                        </dd>
                        <dd class="p-info"><?=__('参与平台发起的团购活动提高商品成交量及店铺浏览量')?></dd>
                    </dl>
                    <?php } ?>

                    <?php if($data['promotion_items']['promotion_allow_flag']){   ?>
                    <dl class="zhxs">
                        <dt class="p-name"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Increase&met=index&typ=e&op=list"><?=__('加价购')?></a></dt>
                        <dd class="p-ico"></dd>
                        <dd class="p-hint">
                            <span><i class="icon-ok-sign"></i><?php if($data['promotion_items']['promotion_increase_combo_flag']){ ?><?=__('已开通')?><?php }else{ ?><?=__('未开通')?><?php } ?></span>
                        </dd>
                        <dd class="p-info"><?=__('商品优惠套餐、多重搭配更多实惠、商家必备营销方式')?></dd>
                    </dl>
                    <dl class="xszk">
                        <dt class="p-name"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Discount&met=index&typ=e"><?=__('限时折扣')?></a></dt>
                        <dd class="p-ico"></dd>
                        <dd class="p-hint"><span>
                                        <i class="icon-ok-sign"></i><?php if($data['promotion_items']['promotion_discount_combo_flag']){ ?><?=__('已开通')?><?php }else{ ?><?=__('未开通')?> <?php } ?>
                                        </span></dd>
                        <dd class="p-info"><?=__('在规定时间段内对店铺中所选商品进行打折促销活动')?></dd>
                    </dl>
                    <dl class="mjs">
                        <dt class="p-name"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_MeetConditionGift&met=index&typ=e"><?=__('满即送')?></a></dt>
                        <dd class="p-ico"></dd>
                        <dd class="p-hint"><span>
                                        <i class="icon-ok-sign"></i><?php if($data['promotion_items']['promotion_mansong_combo_flag']){ ?><?=__('已开通')?><?php }else{ ?><?=__('未开通')?> <?php } ?>
                                        </span></dd>
                        <dd class="p-info"><?=__('商家自定义满即送标准与规则，促进购买转化率')?></dd>
                    </dl>
                    <?php  }  ?>

                    <?php if($data['promotion_items']['voucher_allow_flag']){ ?>
                    <dl class="djq">
                        <dt class="p-name"><a href="<?=Yf_Registry::get('url')?>?ctl=Seller_Promotion_Voucher&met=index&typ=e"><?=__('代金券')?></a></dt>
                        <dd class="p-ico"></dd>
                        <dd class="p-hint"><span>
                                        <i class="icon-ok-sign"></i><?php if($data['promotion_items']['voucher_combo_flag']){ ?><?=__('已开通')?><?php }else{ ?><?=__('未开通')?> <?php } ?>
                                        </span>
                        </dd>
                        <dd class="p-info"><?=__('自定义代金券使用规则并由平台统一展示供买家领取')?></dd>
                    </dl>
                    <?php } ?>
					
					<?php if(Web_ConfigModel::value('Plugin_Directseller')&&(@$this->shopBase['shop_type'] != 2)){ ?>
                    <dl class="xsy">
                        <dt class="p-name">
							<a href="<?=Yf_Registry::get('url')?>?ctl=Distribution_Seller_Setting&met=index&typ=e"><?=__('销售员')?></a>
						</dt>
                        <dd class="p-ico"></dd>
                        <dd class="p-hint">
							<span>
								<i class="icon-ok-sign"></i>
								<?=__('已开通')?>
                            </span>
                        </dd>
                        <dd class="p-info"><?=__('让客户来推广店铺，获得佣金')?></dd>
                    </dl>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>


		<?php if($phone || $email){?>
		<div class="m white-panel">
        	<div class="pannel_div">
				<div class="mt">
					<h3 class="bbc_seller_border"><?=__('平台联系方式')?></h3>
					<h5><?=__('有相关不懂得地方可以咨询平台')?></h5>
				</div>
				<div class="mc">
					<ol class="fn-clear">
						<?php if($phone){?>
						<?php foreach($phone as $k=>$v){?>
						<li><?=__('客服电话')?>：<?=$v;?></li>
						<?php }?>
						<?php }?>
						<?php if($email){?>
						<li><?=__('客服邮箱')?>：<?=$email?></li>
						<?php }?>
					</ol>
				</div>
        	</div>
        </div>
		<?php }?>
	</div>


	<script src="<?=$this->view->js?>/pinterest_grid.js"></script>
	<script type="text/javascript">
	    $(function(){
	      $(".container_cj").pinterest_grid({
	        no_columns:2,
	         padding_x:10,
	        padding_y:10,
	        margin_bottom: 50,
	        single_column_breakpoint: 700
	      });
	      
	    });
	</script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

<script>
	$(function() {
		//交易提示 初始化

		$.post(SITE_URL + '?ctl=Seller_Trade_Order&met=getOrderNum&typ=json', {}, function (data) {
			if ( data.status == 200 )
			{

				var data = data.data, order_num_list = $('#order_num_list').children();

				if ( data.wait_pay_num > 0 ) {
					$(order_num_list[0]).children('a').addClass('num  bbc_border bbc_color').append('<em class="bbc_seller_bg">' + data.wait_pay_num + '</em>');
				}

				if ( data.payed_num > 0 ) {
					$(order_num_list[1]).children('a').addClass('num  bbc_border bbc_color').append('<em class="bbc_seller_bg">' + data.payed_num + '</em>');
				}

				if ( data.refund_num > 0 ) {
					$(order_num_list[2]).children('a').addClass('num  bbc_border bbc_color').append('<em class="bbc_seller_bg">' + data.refund_num + '</em>');
				}

				if ( data.return_num > 0 ) {
					$(order_num_list[3]).children('a').addClass('num  bbc_border bbc_color').append('<em class="bbc_seller_bg">' + data.return_num + '</em>');
				}
			}
		})
	})
</script>



