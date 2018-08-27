<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>

<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/Group-integral.css" />
<link rel="stylesheet" type="text/css" href="<?=$this->view->css?>/base.css"/>
<link href="<?=$this->view->css?>/tips.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?=$this->view->js?>/tuangou-index.js"></script>
<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>

<div class="bbc-breadcrumb-layout">
    <div class="bbc-breadcrumb wrapper"><i class="icon-home"></i>
        <span><a href="<?=Yf_Registry::get('url')?>"><?=__('首页')?></a></span><span class="arrow"><i class="iconfont icon-iconjiantouyou"></i></span>
        <span><a href="<?=Yf_Registry::get('url')?>?ctl=Points&met=index"><?=__('积分中心')?></a></span><span class="arrow"><i class="iconfont icon-iconjiantouyou"></i></span>
        <span><?=__('兑换礼品详情')?></span>
    </div>
</div>

<div class="bbc-container">
	<div class="bbc-detail">
		<div class="bbc-gift-picture">
			<a href="<?=$data['goods_detail']['points_goods_image']?>">
				<img src="<?=image_thumb($data['goods_detail']['points_goods_image'],320,320)?>">
			</a>
		</div>

		<div class="bbc-gift-summary">
			<div class="name">
				<h1><?=$data['goods_detail']['points_goods_name']?></h1>
			</div>
			<div class="bbc-meta">
				<dl>
					<dt><?=__('原  价')?>：</dt>
					<dd class="cost-price"><strong><?=format_money($data['goods_detail']['points_goods_price'])?></strong></dd>
				</dl>
				<dl>
					<dt><?=__('所需积分')?>：</dt>
					<dd class="points"> <strong><em class="bbc_color"><?=$data['goods_detail']['points_goods_points']?></em> <?=__('积分')?></strong><span class="bbc_btns"><?=$data['goods_detail']['points_goods_limitgrade_label']?><?=__('专享')?></span></dd>
				</dl>
            </div>
			<div class="bbc-key">
				<dl>
					<dt><?=__('兑换数量')?>：</dt>
					<dd class="bbc-figure-input">
						<input name="exnum" class="text w30" id="exnum" value="1" size="4" type="text">
						<span>（<?=__('剩余数量')?>：<?=$data['goods_detail']['points_goods_storage']?><input id="storagenum" value="<?=$data['goods_detail']['points_goods_storage']?>" type="hidden">）</span>
					</dd>
				</dl>
				<input id="limitnum" value="<?=$data['goods_detail']['points_goods_limitnum']?>" type="hidden">
			</div>
			<div class="ncs-btn">
				<?php if($data['goods_detail']['sell_state'] == Points_GoodsModel::WILLSTART){ ?><a class="no-buynow"><?=__('即将开始')?></a>
				<?php }elseif($data['goods_detail']['sell_state'] == Points_GoodsModel::ENDEXCHANGE){ ?><a class="no-buynow"><?=__('兑换结束')?></a>
				<?php }elseif($data['goods_detail']['sell_state'] == Points_GoodsModel::ONEXCHANGE){ ?>
                <a class="buynow bbc_btns" onclick="return add_to_cart();" style="cursor:pointer;"><i class="ico"></i><?=__('我要兑换')?></a>
				<?php }?>
            </div>
		</div>
		<div style=" position: absolute; z-index: 1; top: -1px; right: -1px;">
			<div class="bbc-info">
				<div class="title"><h4><?=__('商城提供')?></h4></div>
				<div class="content">
					<dl>
						<dt><?=__('礼品编号')?>：</dt>
						<dd><?=$data['goods_detail']['points_goods_serial']?></dd>
					</dl>
					<dl>
						<dt><?=__('添加时间')?>：</dt>
						<dd><?=date('Y-m-d H:i',strtotime($data['goods_detail']['points_goods_add_time']))?></dd>
					</dl>
					<dl>
						<dt><?=__('浏览人次')?>：</dt>
						<dd><?=$data['goods_detail']['points_goods_view']?></dd>
					</dl>
				</div>
				<div class="title"><h4><?=__('兑换记录')?></h4></div>
				<ul class="bbc-exchangeNote">
                    <?php if($data['order_record']['items']){
                            foreach($data['order_record']['items'] as $key=>$order_value){
                        ?>
                        <li>
                            <span style="font-size:10px;"><?=$order_value['points_order_rid']?></span>
                            <span style="font-size:10px;"><?=$order_value['points_buyername']?></span>
                            <span style="font-size:10px;"><?=$order_value['points_allpoints']?></span>

                        </li>

                    <?php } } ?>
                </ul>
			</div>
		</div>
	</div>
	
	<div class="bbc-goods-layout">
		<div class="bbc-sidebar">
			<div class="bbc-sidebar-container">
				<div class="title"><?=__('热门礼品')?></div>
				<div class="content">
					<ul class="recommend">
						<?php  
							if($data['hot_point_goods']['items'])
							{
								foreach($data['hot_point_goods']['items'] as $key=>$value)
								{
						?>
						<li>
							<div class="gift-pic">
								<a target="_blank" href="<?=Yf_Registry::get('url')?>?ctl=Points&met=detail&id=<?=$value['points_goods_id']?>" title="<?=$value['points_goods_tag']?>"> 
									<img src="<?=image_thumb($value['points_goods_image'],160,160)?>" alt="<?=$value['points_goods_tag']?>">
								</a>
							</div>
							<div class="gift-name">
								<a href="<?=Yf_Registry::get('url')?>?ctl=Points&met=detail&id=<?=$value['points_goods_id']?>" target="_blank" tile="<?=$value['points_goods_name']?>"><?=$value['points_goods_name']?></a>
							</div>
							<div class="pgoods-points">
								<span class="bbc_color"><?=$value['points_goods_points']?></span><?=__('积分')?>
								<?php if($value['points_goods_limitgrade']) 
								{
								?>
									<span class="bbc_btns"><?=$value['user_grade_limit_label']?><?=__('专享')?></span>
								<?php 
								}
								?>
							</div>
						</li>
						<?php  
								}
							}
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="bbc-goods-main">
			<div class="tabbar">
				<div class="bbc-goods-title-nav">
					<ul id="categorymenu">
						<li class="current"> <a id="tabGoodsIntro" href="#intro"><?=__('礼品介绍')?></a> </li>
					</ul>
				</div>
			</div>
			<div class="bbc-goods-info-content">
				<?=$data['goods_detail']['points_goods_body']?>
			</div>
		</div>
	</div>
</div>
<script>
	function add_to_cart()
	{
        if(<?= intval(Perm::checkUserPerm()) ?>)
        {
            var points_goods_id = <?=$data['goods_detail']['points_goods_id']?>;
            var storagenum = parseInt($("#storagenum").val());//库存数量
            var limitnum = parseInt($("#limitnum").val());//限制兑换数量
            var quantity = parseInt($("#exnum").val());//兑换数量
            //验证数量是否合法
            var checkresult = true;
            var msg = '';
            if(!quantity >=1 ){//如果兑换数量小于1则重新设置兑换数量为1
                quantity = 1;
            }
            if(limitnum > 0 && quantity > limitnum){
                checkresult = false;
                msg = '<?=__('兑换数量不能大于限兑数量')?>';
            }
            if(storagenum >= 0 && quantity > storagenum){
                checkresult = false;
                msg = '<?=__('兑换数量不能大于剩余数量')?>';
            }
            if(checkresult == false)
            {
                Public.tips.error(msg);
                return false;
            }
            else
            {
                var param = {};
                param.points_goods_id = points_goods_id;
                param.quantity = quantity;
				console.info(param)
                $.ajax({
                    url: SITE_URL + "?ctl=Points&met=addPointsCart&typ=json",
                    data:param,
                    type: "POST",
                    success:function(e){
                        if(e.status == 200)
                        {
                            window.location.href = SITE_URL + '?ctl=Points&met=pointsCart&typ=e';
                        }
                        else
                        {
                            Public.tips.error(e.msg);
                        }
                    }
                });
            }
        }
        else
        {
            window.location.href = SITE_URL + '?ctl=Login&met=login';//登录
        }
	}
</script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>