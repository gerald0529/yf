<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-stand|ie-comp">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
	<meta name="description" content="<?php if($this->description){?><?=$this->description ?><?php }?>" />
    <meta name="Keywords" content="<?php if($this->keyword){?><?=$this->keyword ?><?php }?>" />
	<title><?php if($this->title){?><?=$this->title ?><?php }else{?><?= Web_ConfigModel::value('site_name') ?><?php }?></title>
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/headfoot.css"/>
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/sidebar.css"/>
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/index.css"/>
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/nav.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/base.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/swiper.css"/>
	<link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
	
	<script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js"></script>
	<script type="text/javascript" src="<?= $this->view->js ?>/swiper.min.js"></script>
	 <script type="text/javascript" src="<?= $this->view->js ?>/jquery.SuperSlide.2.1.1.js"></script>
    <script type="text/javascript">
        var IM_URL = "<?=Yf_Registry::get('im_api_url')?>";
        var IM_STATU = "<?=Yf_Registry::get('im_statu')?>";
		var BASE_URL = "<?=Yf_Registry::get('base_url')?>";
		var SITE_URL = "<?=Yf_Registry::get('url')?>";
		var INDEX_PAGE = "<?=Yf_Registry::get('index_page')?>";
		var STATIC_URL = "<?=Yf_Registry::get('static_url')?>";
		var PAYCENTER_URL = "<?=Yf_Registry::get('paycenter_api_url')?>";
		var UCENTER_URL = "<?=Yf_Registry::get('ucenter_api_url')?>";
        var is_open_city = "<?= Web_ConfigModel::value('subsite_is_open');?>";
		var DOMAIN = document.domain;
		var WDURL = "";
		var SCHEME = "default";
		try
		{
			//document.domain = 'ttt.com';
		} catch (e)
		{
		}

		//updateCookieCart();
	</script>
    <script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
	<script type="text/javascript" src="<?= $this->view->js ?>/supplier_index.js"></script>
	<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.cookie.js"></script>
	<script type="text/javascript" src="<?= $this->view->js ?>/nav.js"></script>
	<script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.nicescroll.js"></script>
	<script type="text/javascript" src="<?=$this->view->js?>/decoration/common.js"></script>
	<script type="text/javascript" src="<?= $this->view->js ?>/base.js"></script>
</head>
<body>
<div class="head">
	<div class="wrapper clearfix">
		<div class="head_left">
			<div id="login_top">
				<dl class="header_select_province">
					<dt><b class="iconfont icon-dingwei2"></b><span id="area"><?=@$_COOKIE['area']?></span></dt>
					<dd>
					</dd>
				</dl>
			</div>

		</div>
		<div class="head_right">
			<dl>
                                <p></p>
				<dt><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical"><?=__('我的订单')?></a></dt>
				<dd class="rel_nav">
					<a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical"><?=__('实物订单')?></a>
					<a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=virtual"><?=__('虚拟订单')?></a>
				</dd>
			</dl>

			<dl>
				<p></p>
				<dt><a href="<?= Yf_Registry::get('paycenter_api_url') ?>?ctl=Info&met=recordlist" target="_blank"><span class="iconfont icon-paycenter bbc_color"></span><?=Yf_Registry::get('paycenter_api_name')?></a></dt>
			</dl>
			<dl>
				<p></p>
				<dt><a href="<?= Yf_Registry::get('im_url') ?>" target="_blank"><span class="iconfont icon-sns bbc_color"></span>SNS</a></dt>
			</dl>

			<dl>
                             <p></p>
				<dt><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=favoritesGoods" target="_blank"><span class="iconfont icon-taoxinshi bbc_color"></span><?=__('我的收藏')?></a></dt>
				<dd class="rel_nav">
					<a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=favoritesShop"><?=__('店铺收藏')?></a>
					<a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=favoritesGoods"><?=__('商品收藏')?></a>
					<a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Favorites&met=footprint"><?=__('我的足迹')?></a>
				</dd>
			</dl>
			<dl>
                             <p></p>
				<dt>
					<a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Cart&met=cart"><span class="iconfont icon-zaiqigoumai bbc_color"></span><?=__('购物车')?></a>
				</dt>
			</dl>
			<dl>
                             <p></p>
				<dt><a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Custom&met=index" target="_blank"><?=__('客服中心')?></a></dt>
				<dd class="rel_nav">
					<a href="<?= Yf_Registry::get('url') ?>?ctl=Article_Base&met=index&article_id=2"><?=__('帮助中心')?></a>
					<a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Service_Return&met=index"><?=__('售后服务')?></a>
			</dl>
			<dl>
				<dt><span class="iconfont icon-shoujibangding bbc_color"></span><a href="#"><?=__('手机版')?></a></dt>
				<dd class="rel_nav  phone-code">
					<img src="<?=Yf_Registry::get('base_url')?>/shop/api/qrcode.php?data=<?=urlencode(Yf_Registry::get('shop_wap_url'))?>" width="150" height="150"/></dd>
			</dl>
<!--			<dl>
				<dt><a href="#">商家支持</a></dt>
				<dd class="rel_nav"><a href="#">代付款订单</a><a href="#">代付款订单</a><a href="#">代付款订单</a></dd>
			</dl>-->
		</div>
	</div>
</div>