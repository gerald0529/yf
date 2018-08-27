<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/Group-integral.css" />
<script type="text/javascript" src="<?=$this->view->js?>/tuangou-index.js"></script>
<script src="<?=$this->view->js_com?>/plugins/jquery.slideBox.min.js" type="text/javascript"></script>

<div class="wrap">
	<!--  右登陆 -->
	<div class="bbc-base-layout">
		<div class="bbc-member-left">
            <?php  if(Perm::checkUserPerm()) { ?>
                <div class="bbc-member-info">
                <div class="avatar"><img src="<?=image_thumb($data['user_info']['user_logo'],80,80)?>">
                    <div class="frame"></div>
                </div>
                <dl>
                    <dt>Hi, <?=$data['user_info']['user_name']?></dt>
                    <dd><?=__('当前等级')?>：<strong class="common-color">V<?=$data['user_info']['user_grade']?></strong></dd>
                    <dd><?=__('当前经验值')?>：<strong class="common-color"><?=$data['user_resource']['user_growth']?></strong></dd>
                </dl>
            </div>
            <div class="bbc-member-grade">
                <div class="progress-bar"><em title="V<?=$data['user_info']['user_grade']?><?=__('需经验值')?><?=$data['growth']['grade_growth_start']?>">V<?=$data['user_info']['user_grade']?></em><span title="<?=$data['growth']['grade_growth_per']?>%"><i class="bbc_bg" style="width:<?=$data['growth']['grade_growth_per']?>%;"></i></span><em title="V<?=$data['user_info']['user_grade']+1?><?=__('需经验值')?><?=$data['growth']['grade_growth_end']?>">V<?=$data['user_info']['user_grade']+1?></em></div>
                <div class="progress"><?=__('还差')?><em class="bbc_color"><?=$data['growth']['next_grade_growth']?></em><?=__('经验值即可升级成为')?>V<?=$data['user_info']['user_grade']+1?><?=__('等级会员')?></div>
            </div>
            <div class="bbc-member-point">
                <dl style="border-left: none 0;">
                    <a href="<?=Yf_Registry::get('url')?>?ctl=Buyer_Points&met=points" target="_blank">
                        <dt><strong class="bbc_color"><?=$data['user_resource']['user_points']?></strong><?=__('分')?></dt>
                        <dd><?=__('我的积分')?></dd>
                    </a>
                </dl>
                <dl>
                    <a href="<?=Yf_Registry::get('url')?>?ctl=Buyer_Voucher&met=voucher" target="_blank">
                        <dt><strong class="bbc_color"><?=$data['ava_voucher_num']?></strong><?=__('张')?></dt>
                        <dd><?=__('可用代金券')?></dd>
                    </a>
                </dl>
                <dl>
                    <a href="<?=Yf_Registry::get('url')?>?ctl=Buyer_Points&met=points&op=getPointsOrder" target="_blank">
                        <dt><strong class="bbc_color"><?=$data['points_order_num']?></strong><?=__('个')?></dt>
                        <dd><?=__('已兑换礼品')?></dd>
                    </a>
                </dl>
            </div>
            <div class="bbc-memeber-pointcart"> <a href="<?=Yf_Registry::get('url')?>?ctl=Points&met=pointsCart" class="btn bbc_bg_col"><?=__('礼品兑换购物车')?></a></div>
            <?php
            }else{
            ?>
			<div class="bbc-not-login">
				<div class="member"><a href="<?=Yf_Registry::get('url')?>?ctl=Login&met=login" style="color:#000"><?=__('立即登录')?></a>
					<p><?=__('获知会员信息详情')?></p>
				</div>
				<div class="function" style="border: none;"> <i class="voucher"> </i>
					<dl>
						<dt><?=__('店铺代金券')?></dt>
						<dd><?=__('换取店铺代金券购买商品更划算')?></dd>
					</dl>
				</div>
				<div class="function"> <i class="exchange"></i>
					<dl>
						<dt><?=__('积分兑换礼品')?></dt>
						<dd><?=__('可使用积分兑换商城超值礼品')?></dd>
					</dl>
				</div>
			</div>
            <?php } ?>
		</div>

        <div class="bbc-banner-right">
			<a href="<?=Web_ConfigModel::value('promotiom_img_url')?>" title="<?=__('积分列表页中部广告位')?>">
				<img style="width:900px;height:368px; float:right" border="0" src="<?=Web_ConfigModel::value('promotiom_img')?>" alt="">
			</a>
		</div>
    </div>


	<div class="bbc-main-layout">
        <!--积分礼品-->
		<div class="bbc-main-layout mb30">
			<div class="title">
				<h3>
                    <span class="iconfont icon-lipin bbc_color"></span><?=__('热门礼品兑换')?>
				    <span class="more"><a href="<?=Yf_Registry::get('url')?>?ctl=Points&met=pList"><?=__('更多')?><i class="iconfont icon-iconjiantouyou rel_top2"></i></a></span>
                </h3>
            </div>
			<ul class="bbc-exchange-list">
				<?php
                    if($data['points_goods'])
                    {
					foreach($data['points_goods'] as $key=>$value)
					{
				?>
				<li>
					<div class="gift-pic">
						<a target="_blank" href="<?=Yf_Registry::get('url')?>?ctl=Points&met=detail&id=<?=$value['points_goods_id']?>"><img src="<?=image_thumb($value['points_goods_image'],150,150)?>" alt="<?=$value['points_goods_name']?>"> </a>
					</div>
					<div class="gift-name">
						<a href="<?=Yf_Registry::get('url')?>?ctl=Points&met=detail&id=<?=$value['points_goods_id']?>" target="_blank" tile="<?=$value['points_goods_name']?>"><?=$value['points_goods_name']?></a>
					</div>
					<div class="exchange-rule">
                        <?php
                        if($value['points_goods_limitgrade']-1){
                            ?>
                            <span class="pgoods-grade"><img src="<?=$this->view->img?>/V<?=$value['points_goods_limitgrade']-1?>.png"></span>
                        <?php }?>
						<span class="pgoods-price"><label><?=__('参考价格')?>：</label><em><?=format_money($value['points_goods_price'])?></em></span>
						<span class="pgoods-points"><label><?=__('所需积分')?>：</label><strong  class="bbc_color"><?=$value['points_goods_points']?></strong></span>
					</div>
				</li>
				<?php
                    }
                    } ?>
			  
			</ul>
		</div>

       <!-- 代金券-->
		<div class="bbc-main-layout">
			<div class="title">
				<h3>
                    <span class="iconfont icon-daijinquan bbc_color"></span><?=__('热门代金券')?>
				    <span class="more"> <a href="<?=Yf_Registry::get('url')?>?ctl=Voucher&met=vList"><?=__('更多')?><i class="iconfont icon-iconjiantouyou rel_top2"></i></a></span>
                </h3>
            </div>
			<div style="margin-top:10px;" class="clearfix">
				<?php
                if($data['voucher'])
                {
					foreach($data['voucher'] as $key=>$value)
					{
				?>
				<div class="picture_<?=($key%3)+1?> imgs" data-id="<?=$value['voucher_t_id']?>">
					<div class="picture_1_1"> <?=$value['shop_name']?> </div>
					<div class="picture_1_2" style="margin-top:10px; ">
						<p class="bbc_color f18"><?=format_money($value['voucher_t_price'])?></p>
						<p> (<?=__('满')?><span class="bbc_color"><?=format_money($value['voucher_t_limit'])?></span><?=__('可使用')?>)</p>
						<p style="color:#000;width: 231px;"><?=__('有效期')?>：<?=date('Y-m-d',strtotime($value['voucher_t_start_date']))?> -- <?=date('Y-m-d',strtotime($value['voucher_t_end_date']))?> </p>
					</div>
					<div class="picture_1_3 point">
						<p style="color:red; font-size:16px"><?=__('需')?><?=$value['voucher_t_points']?><?=__('积分')?></p>
						<p><em class="giveout"><?=$value['voucher_t_giveout']?></em><?=__('人已经兑换')?></p>
					</div>
					<div class="picture_1_4"> 
						<a style="display:block" href="javascript:void(0);" op_type="exchangebtn" data-param='{"vid":"<?=$value['voucher_t_id']?>"}'>
							<div class="divxz divxzye bbc_btns"><p><?=__('立即兑换')?></p></div>
						</a> 
					</div>
				</div>
				<?php  
					}
                }
				?>
				
			</div>
		</div>
	</div>
</div>

<link href="<?=$this->view->css?>/tips.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/home.js"></script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>