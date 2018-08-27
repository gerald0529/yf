<?php if (!defined('ROOT_PATH')){exit('No Permission');}
	include_once INI_PATH . '/buyer_menu.ini.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-stand|ie-comp">
    <meta name="renderer" content="webkit">
    <meta name="description" content="<?php if($this->description){?><?=$this->description ?><?php }?>" />
	<meta name="Keywords" content="<?php if($this->keyword){?><?=$this->keyword ?><?php }?>" />
	<title><?php if($this->title){?><?=$this->title ?><?php }else{?><?= Web_ConfigModel::value('site_name') ?><?php }?></title>
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/headfoot.css"/>
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/sidebar.css"/>
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/nav.css"/>
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/base.css"/>
	<link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
	<link type="text/css" rel="stylesheet" href="<?= $this->view->css ?>/buyer.css">
	<link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
	<link href="<?= $this->view->css?>/select2.min.css" rel="stylesheet">
	<script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js"></script>
	<script src="<?= $this->view->js ?>/select2.min.js"></script>


	<!--[if lt IE 9]>
	<script src="<?=$this->view->js_com?>/html5shiv.js"></script>
	<script src="<?=$this->view->js_com?>/respond.js"></script>
	<![endif]-->

	<script type="text/javascript">
		var BASE_URL = "<?=Yf_Registry::get('base_url')?>";
		var SITE_URL = "<?=Yf_Registry::get('url')?>";
		var INDEX_PAGE = "<?=Yf_Registry::get('index_page')?>";
		var STATIC_URL = "<?=Yf_Registry::get('static_url')?>";
		var PAYCENTER_URL = "<?=Yf_Registry::get('paycenter_api_url')?>";
		var UCENTER_URL = "<?=Yf_Registry::get('ucenter_api_url')?>";

		var DOMAIN = document.domain;
		var WDURL = "";
		var SCHEME = "default";
        var IM_STATU = "<?=Yf_Registry::get('im_statu')?>";
	</script>
    <script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
</head>
<body>


<div class="bbuyer_head bbc_bg">
	<div class="wrap clearfix bbc_bg">
		<div class="bbuyer_head_fl">
			<div class="bbuyer_logo">
				<a href="<?= Yf_Registry::get('url') ?>"><img src="<?=$this->web['web_logo']?>"/></a>
			</div>
			<div class="bbuyer_others">
				<p class="mine_shopmall"><?=__('我的商城')?></p>
				<p class="back_mall_index"><a href="<?= Yf_Registry::get('url') ?>"><?=__('返回商城首页')?></a></p>
			</div>
			<div class="bbuyer_head_nav">
				<ul>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Index&met=index"><?=__('首页')?></a></li>
					<li class="set">
						<a class="user_setup"><?=__('用户设置')?><i class="iconfont icon-iconjiantouxia"></i></a>
						<div class="sub-menu">
							<dl>
								<dt><a target="_blank" href="<?= Yf_Registry::get('ucenter_api_url') ?>?ctl=User&met=security" style="color: #3AAC8A;"><?=__('安全设置')?></a></dt>
								<dd><a target="_blank" href="<?= Yf_Registry::get('ucenter_api_url') ?>?ctl=User&met=security&op=mobile"><?=__('手机绑定')?></a></dd>
								<dd><a target="_blank" href="<?= Yf_Registry::get('ucenter_api_url') ?>?ctl=User&met=security&op=email"><?=__('邮件绑定')?></a></dd>
								<dd><a target="_blank" href="<?= Yf_Registry::get('ucenter_api_url') ?>?ctl=User&met=getUserImg"><?=__('修改头像')?></a></dd>
								<dd><a target="_blank" href="<?= Yf_Registry::get('ucenter_api_url') ?>?ctl=User&met=passwd"><?=__('修改密码')?></a></dd>
							</dl>
							<dl>
								<dt><a target="_blank" href="<?= Yf_Registry::get('ucenter_api_url') ?>?ctl=User&met=getUserInfo" style="color: #EA746B"><?=__('个人资料')?></a></dt>
								<dd><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=tag"><?=__('兴趣标签')?></a></dd>
								<dd><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message&op=manage"><?=__('消息接收设置')?></a></dd>
								<dd><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_User&met=getUserGrade"><?=__('会员级别')?></a></dd>

							</dl>
							<dl>
								<dt><a target="_blank" href="<?= Yf_Registry::get('paycenter_api_url') ?>" style="color: #FF7F00"><?=__('账户财产')?></a></dt>
								<dd><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Voucher&met=voucher"><?=__('我的代金券')?></a></dd>
								<dd><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Points&met=points"><?=__('我的积分')?></a></dd>

							</dl>
							<dl>
								<dt><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=favoritesGoods"  style="color: #398EE8"><?=__('我的收藏')?></a></dt>
								<dd><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=favoritesShop"><?=__('店铺收藏')?></a></dd>
								<dd><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=favoritesGoods"><?=__('商品收藏')?></a></dd>
							</dl>
						</div>
					</li>
					<li><a href="<?=Yf_Registry::get('paycenter_api_url')?>" target="_blank"><?=Yf_Registry::get('paycenter_api_name')?></a></li>
					<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Message&met=message"><?=__('消息')?></a>
						<?php if($this->countMessage['countMessage'] > 0){?><i class="bbuyer_news"><?=$this->countMessage['countMessage']?></i><?php }?>
					</li>
					<?php if(Web_ConfigModel::value('Plugin_Directseller')){ ?>
                        <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Distribution_Buyer_Directseller&met=index&typ=e"><?=__('分销中心')?></a></li>
                    <?php } elseif(Web_ConfigModel::value('Plugin_Fenxiao')) { ?>
                    
                        <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Fenxiao&met=FenxiaoList&typ=e"><?=__('分销中心')?></a></li>
                    <?php } ?>
				</ul>
			</div>
		</div>
		<script type="text/javascript">
			$(".set").hover(function(){
				$(this).find(".sub-menu").css("display","block");
				$(this).find("i").css("transform","rotate(-180deg)");
					
			},function(){
				$(this).find(".sub-menu").css("display","none");
				$(this).find("i").css("transform","rotate(1deg)");
			}) 
		</script>
		<!-- 购物车 -->
		<div class="bbuyer_cart">
			<div class="bbc_buyer_icon">
				<i class="ci_left iconfont icon-zaiqigoumai bbc_color rel_top2"></i>
				<a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Cart&met=cart" target="_blank"><?=__('我的购物车')?></a>
				<i class="ci_right iconfont icon-iconjiantouyou"></i>
				<i class="ci-count bbc_bg" id="cart_num">0</i>
			</div>
		</div>
		<form name="form_search" id="form_search" action="">
			<div class="bbuyer_head_fr">
					<input type="hidden" name="ctl" value="Goods_Goods">
					<input type="hidden" name="met" value="goodslist">
					<input type="hidden" name="typ" value="e">
					<input type="text" class="bbuyer_inp_ser" name="keywords" placeholder="<?=__('请输入商品名字')?>" id="site_keywords">
					<input type="submit" style="display: none;" >

				<a class="bbuyer_search"  id="site_search" ><?=__('搜索')?></a>
			</div>
		</form>
	</div>
</div>

<div class="Colr">
     <div class="wrapper ">
		 <?php if ($this->ctl == 'Buyer_Index'):?>
			 <div class="Div_2 clearfix">
				 <div class="left  ">
					 <div class="_img">
						 <img src="<?php if(!empty($this->user['info']['user_logo'])){?><?=image_thumb($this->user['info']['user_logo'],108,108)?><?php }else{?><?= image_thumb($this->web['user_logo'],108,108)?><?php }?>" width="108" height="108"/>
					 </div>
					 <div class="font">
						 <table>
						 <tbody>
							 <tr ><td colspan="2" class="name"><div class="one-overflow w160"><?=$this->user['info']['user_name'];?></div></td></tr>
							 <tr><td class="fontColor"><?=__('会员级别')?>:</td><td><span class="nc-grade-mini bbc_bg  pad" ><?=$this->user['grade']['user_grade_name']?></span></td></tr>
							 <tr><td class="fontColor"><?=__('账号安全')?>:</td>
							 <td class="tiao"><span title="<?php if($this->user['info']['user_level_id']<1){?>5<?php }elseif($this->user['info']['user_level_id']==1){?>50<?php }else{?>100<?php }?>%"><i class="bbc_bg" style="width:<?php if($this->user['info']['user_level_id']<1){?>1<?php }elseif($this->user['info']['user_level_id']==1){?>50<?php }else{?>100<?php }?>%;"></i></span><a><?php if($this->user['info']['user_level_id']<1){?><?=__('低')?><?php }elseif($this->user['info']['user_level_id']==1){?><?=__('中')?><?php }else{?><?=__('高')?><?php }?></a></td>
							</tr>
							</tbody>
						 </table>
					 </div>
				 </div>
				 <div class="right  ">
					 <div class="_left">
						 <ul>
							 <li>
								 <div class="Divradius">
									 <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&status=wait_pay"><img src="<?= $this->view->img ?>/ico_dai1.png"></a></div>
								 <p class="_p">
									 <?=__('待付款')?> <strong><?=$this->count['count1'] ?></strong>
								 </p>
							 </li>
							 <li>
								 <div class="Divradius">
									 <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&status=wait_confirm_goods"><img src="<?= $this->view->img ?>/ico_dai2.png"></a></div>
								 <p class="_p">
									 <?=__('待收货')?> <strong><?=$this->count['count2'] ?></strong>
								 </p>
							 </li>
							 <li>
								 <div class="Divradius">
									 <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&status=finish"><img src="<?= $this->view->img ?>/ico_dai3.png"></a></div>
								 <p class="_p">
									 <?=__('已完成')?>
									 <strong><?=$this->count['count3'] ?></strong>
								 </p>
							 </li>
							 <li>
								 <div class="Divradius">
									 <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical&status=cancel"><img src="<?= $this->view->img ?>/ico_dai4.png"></a></div>
								 <p class="_p">
									 <?=__('已取消')?>
									 <strong><?=$this->count['count4'] ?></strong>
								 </p>
							 </li>
						 </ul>
					 </div>
					 <div class="_right">
						 <div class="b_rol">
							 <div class=" same">

								 <div class="same_1">
									 <a target="_blank" href="<?= Yf_Registry::get('paycenter_api_url') ?>" class="iconfont_a"  target="_blank"><i class="iconfont icon-iconyue ts"></i></a>
								 	<a target="_blank" href="<?= Yf_Registry::get('paycenter_api_url') ?>"  target="_blank"><?= __('余额') ?>
								 </div>
								 <a target="_blank" href="<?= Yf_Registry::get('paycenter_api_url') ?>"><div class="same_2" id="mons"  target="_blank"><?=format_money(0.00)?></div></a>

							 </div>
							 <div class="same">
								 <div class="same_1">
									 <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Points&met=points" class="iconfont_a"><i class="iconfont  icon-iconjifen ts"></i></a>
								 	 <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Points&met=points"><?= __('积分') ?></a>
								 </div>
								 <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Points&met=points"><div class="same_2"><?= $this->user['points']['user_points']; ?></div></a>
							 </div>
							 <div class="same">
								 <div class="same_1">
									 <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Voucher&met=voucher" class="iconfont_a"><i class="iconfont icon-iconquan  ts"></i></a>
									 <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Voucher&met=voucher"><?= __('代金卷') ?></a>
								 </div>
								 <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Voucher&met=voucher"><div class="same_2"><?= $this->user['voucher']; ?></div></a>
							 </div>
	                         
	                         <?php if($bt_info['baitiao_is_open']){?>
	                         <div class="same">
								 <div class="same_1">
									 <a href="<?= Yf_Registry::get('paycenter_api_url') ?>?ctl=Info&met=btinfo" class="iconfont_a" target="_blank"><i class="iconfont icon-icon_gold  ts"></i></a>
									 <a href="<?= Yf_Registry::get('paycenter_api_url') ?>?ctl=Info&met=btinfo" target="_blank"><?= __('白条') ?></a>
								 </div>
	                             <a href="<?= Yf_Registry::get('paycenter_api_url') ?>?ctl=Info&met=btinfo" target="_blank"><div class="same_2" id="bt_status_div"><?php if($bt_info['user_bt_status'] == 0){echo __('未激活');}else if($bt_info['user_bt_status'] == 1){echo  __('审核中');}else if($bt_info['user_bt_status'] == 2){echo  '￥'.$bt_info['user_credit_availability'];}else{echo __('激活失败');}?></div></a>
							 </div>
	                         <?php } ?>
	                         
						 </div>
					 </div>
				 </div>
			 </div>
		 <?php endif;?>
<script>
$(function(){
    $.ajax({
        type: "GET",
        url: SITE_URL + "?ctl=Buyer_Index&met=getUserInfoMoney&typ=json",
        data: {},
        dataType: "json",
        success: function(data){
            var html = '';
			
            $.each(data, function(commentIndex, comment){

            });

            $('#mons').html(data.data[0]);
        }
    });

	//获取购物车中的数量
	$.ajax({
		type: "GET",
		url: SITE_URL + "?ctl=Buyer_Cart&met=getCartGoodsNum&typ=json",
		data: {},
		dataType: "json",
		success: function(data){
			console.info(data);
			$('#cart_num').html(data.data.cart_count);
		}
	});

	/*$.ajax({
		type: "GET",
		url: SITE_URL + "?ctl=Buyer_Cart&met=addCartRow&typ=json",
		data: {},
		dataType: "json",
		success: function(data){
			console.info(data);
		}
	});*/



});
</script>
	
	<div class="Div3 clearfix">
	<?php
	if ('Buyer_Index'==$ctl && 'index'==$met)
	{
	?>
      <div class="left_1   visible-lg">
		<?php
			foreach ($buyer_menu[$level_row[1]]['sub'] as $menu_row)
			{
		?>
        <ul class="jiaoyizhognxin">
           <li><a href="#" class="_font"><?=$menu_row['menu_name']?></a></li>
            <?php
				if(!empty($menu_row['sub'])){
					foreach ($menu_row['sub'] as $menus_row) {
           ?>
            <li id="li_<?=$menus_row['menu_id']?>"><a href="<?=sprintf('%s?ctl=%s&met=%s', Yf_Registry::get('url'), $menus_row['menu_url_ctl'], $menus_row['menu_url_met'], $menus_row['menu_url_parem']);?>" class="_Color <?=($menus_row['menu_id'] == $level_row[1]) ? 'sel_color' : ''?>"><?=$menus_row['menu_name']?></a></li>
		   
                    <?php } ?>

       </ul>
       <?php
			}
			}
		?>

      </div>
<?php
}
else
{
?>
    <div class="left_1 visible-lg">
		<?php

			foreach ($buyer_menu[$level_row[1]]['sub'] as $menu_row)
			{
		?>
        <ul class="jiaoyizhognxin">
           <li><a href="#" class="_font"><?=$menu_row['menu_name']?></a></li>
		   <?php
				if(!empty($menu_row['sub'])){
					foreach ($menu_row['sub'] as $menus_row)
				{

			?>
           <li id="li_<?=$menus_row['menu_id']?>"><a <?php $arrnames=array("<?=__('会员信息')?>","<?=__('账户安全')?>","<?=__('密码修改')?>");if(in_array($menus_row['menu_name'], $arrnames)){ ?> target="_blank" <?php } ?>  href="<?=sprintf('%s?ctl=%s&met=%s', Yf_Registry::get('url'), $menus_row['menu_url_ctl'], $menus_row['menu_url_met'], $menus_row['menu_url_parem']);?>" class="_Color <?=($menus_row['menu_id'] == $level_row[3]) ? 'sel_color' : ''?>"><?=$menus_row['menu_name']?></a></li>
		   <?php
				}
			?>

       </ul>
       <?php
			}
			}
		?>

      </div>
	<?php
		if (@isset($buyer_menu[$level_row[1]]['sub'][$level_row[2]]['sub'][$level_row[3]]['sub']))
		{
	?>
	<div class="aright">
        <div class="member_infor_content">
           <div class="tabmenu">
		    <ul class="tab pngFix">
		   <?php
			foreach ($buyer_menu[$level_row[1]]['sub'][$level_row[2]]['sub'][$level_row[3]]['sub'] as $menu_row)
			{
			?>
                 <li id="li_<?=$menus_row['menu_id']?>" class="<?=($menu_row['menu_id'] == $level_row[4]) ? 'active' : 'normal'?>"><a  href="<?=sprintf('%s?ctl=%s&met=%s', Yf_Registry::get('url'), $menu_row['menu_url_ctl'], $menu_row['menu_url_met'], $menu_row['menu_url_parem']);?>"><?=$menu_row['menu_name']?></a></li>
            <?php
			}
			?>
             </ul>
		<?php
			}
		?>
<?php
	}
?>

<script type="text/javascript">
    //实时获取白条状态，从paycenter获取
	$.ajax({
		type: "GET",
		url: SITE_URL + "?ctl=Buyer_Index&met=getBtStatus&typ=json",
		data: {},
		dataType: "json",
		success: function(res){
            if(res.status == 200){
                if(!res.data.baitiao_is_open){
                    $('#li_1000055').hide();
                }else{
                    $('#li_1000055').show();
                }
            }
		}
	});
</script>
                   

