<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-stand|ie-comp">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
	<meta name="description" content="<?php if($this->description){?><?=$this->description ?><?php }?>" />
    <meta name="Keywords" content="<?php if($this->keyword){?><?=$this->keyword ?><?php }?>" />
	<title><?php if($this->title){?><?=addslashes($this->title) ?><?php }else{?><?= addslashes(Web_ConfigModel::value('site_name')) ?><?php }?></title>
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/headfoot.css"/>
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/sidebar.css"/>
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/index.css"/>
	<link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/nav.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/base.css"/>
    <link rel="stylesheet" type="text/css" href="<?= $this->view->css ?>/swiper.css"/>
	<link rel="stylesheet" href="<?= $this->view->css ?>/iconfont/iconfont.css">
	<script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js"></script>
	<script type="text/javascript" src="<?= $this->view->js ?>/swiper.min.js"></script>
	<script type="text/javascript" src="<?= $this->view->js ?>/jquery.SuperSlide.2.1.1.js"></script>
	<link href="<?= $this->view->css ?>/select2.min.css" rel="stylesheet">
	<script type="text/javascript" src="<?= $this->view->js ?>/select2.min.js"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.cookie.js"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.nicescroll.js"></script>
    <script type="text/javascript">
        var IM_URL = "<?=Yf_Registry::get('im_api_url')?>";
        var IM_STATU = "<?=Yf_Registry::get('im_statu')?>";
        var BASE_URL = "<?=Yf_Registry::get('base_url')?>";
        var protocolStr = document.location.protocol;  
        var SITE_URL = "<?=Yf_Registry::get('url')?>";
        if(protocolStr === "http:") {  
            SITE_URL = SITE_URL.replace(/https:/,'http:');
        } else if(protocolStr === "https:") {  
            SITE_URL = SITE_URL.replace(/http:/,'https:');
        } else {  
            SITE_URL = SITE_URL.replace(/https:/,'http:');
        } 

		var INDEX_PAGE = "<?=Yf_Registry::get('index_page')?>";
		var STATIC_URL = "<?=Yf_Registry::get('static_url')?>";
		var PAYCENTER_URL = "<?=Yf_Registry::get('paycenter_api_url')?>";
		var UCENTER_URL = "<?=Yf_Registry::get('ucenter_api_url')?>";
        var is_open_city = "<?= Web_ConfigModel::value('subsite_is_open');?>";
		var DOMAIN = document.domain;
		var WDURL = "";
		var SCHEME = "default";
        var MASTER_SITE_URL = "<?=Yf_Registry::get('shop_api_url')?>"; 
    </script>

	<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
	<script type="text/javascript" src="<?= $this->view->js ?>/index.js"></script>
	<script type="text/javascript" src="<?= $this->view->js ?>/nav.js"></script>
	<script type="text/javascript" src="<?=$this->view->js?>/decoration/common.js"></script>
	<script type="text/javascript" src="<?= $this->view->js ?>/base.js"></script>
	
	<script type="text/javascript">

        //分站定位
        if(!getCookie('isset_local_subsite') && !getCookie('sub_site_id')){
            //没有设置过分站，就调用该方法
            getLocalSubsite();
        }

        function getLocalSubsite(){
            $.ajax({
                type: "GET",
                url: SITE_URL + "?ctl=Base_District&met=getLocalSubsite&typ=json",
                data: {},
                dataType: "json",
                success: function(data){
                    if(data.status == 200){
                        $.cookie('isset_local_subsite',1);
                        $.cookie("sub_site_id",data.data.subsite_id);
                        $.cookie('sub_site_name',data.data.sub_site_name);
                        $.cookie('sub_site_logo',data.data.sub_site_logo);
                        $.cookie('sub_site_copyright',data.data.sub_site_copyright);
                        window.location.reload();
                    }
                }
            });
        }
	</script>
</head>
<body>
<div class="head">
	<div class="wrapper clearfix">
		<div class="head_left">
			<!-- 3.2.0新增消息显示 -->
			<!-- <div class="fl news-tips mr20 p-relative"><a href="javascript:;"><i class="iconfont icon-news fz20 middle"></i><b>5</b></a></div> -->
			<div class="fl" id="login_top">
				<dl class="header_select_province">
                    <dt><b class="iconfont icon-dingwei2"></b><span id="area">
                        <?php
                        $show_area_name = '';
                        if(Web_ConfigModel::value('subsite_is_open')){
                            $show_area_name = isset($_COOKIE['sub_site_name']) ? $_COOKIE['sub_site_name'] : __('全部');
                        }else{
                            $show_area_name = isset($_COOKIE['area']) ? $_COOKIE['area'] :  __('上海');
                            setcookie("area",$show_area_name);
                            $_COOKIE['area'] = $show_area_name;
                        }
                        echo $show_area_name;
                        ?></span></dt>
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
				<dt><a href="<?= Yf_Registry::get('paycenter_api_url') ?>" target="_blank"><span class="iconfont icon-paycenter bbc_color"></span><?=Yf_Registry::get('paycenter_api_name')?></a></dt>
			</dl>
            
            
            <?php if(Yf_Registry::get('im_statu')){ ?>
			<dl>
				<p></p>
				<dt><a href="<?= Yf_Registry::get('sns_api_url') ?>" target="_blank"><span class="iconfont icon-sns bbc_color"></span>SNS</a></dt>
			</dl>
            <?php } ?>
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
				<!--<dd class="rel_nav  phone-code rel_nav-z" style=" width: 180px !important;">-->
					<!--<div class="rel_nav-top clearfix">
						<img src="<?=Yf_Registry::get('base_url')?>/shop/api/qrcode.php?data=<?=urlencode(Yf_Registry::get('shop_wap_url'))?>" class="fl rel_nav-img"  width="60" height="60"/>
						<div class="fl rel_nav-p">远丰商城APP</div>
						<div class="rel_nav-top-logo fl rel_nav-logo1"></div>
						<div class="rel_nav-top-logo fl ml6 rel_nav-logo2"></div>
					</div>
					<div class="rel_nav-top clearfix">
						<img src="<?=Yf_Registry::get('base_url')?>/shop/api/qrcode.php?data=<?=urlencode(Yf_Registry::get('shop_wap_url'))?>" class="fl rel_nav-img"  width="60" height="60"/>
						<div class="fl rel_nav-p ml6 rel_nav-p2">关注远丰公众号</div>
					</div>-->
				<!--</dd>-->
				<dd class="rel_nav  phone-code">
					<img src="<?=Yf_Registry::get('base_url')?>/shop/api/qrcode.php?data=<?=urlencode(Yf_Registry::get('shop_wap_url'))?>" width="150" height="150"/></dd>
			</dl>
<!--			<img src="<?=Yf_Registry::get('base_url')?>/shop/api/qrcode.php?data=<?=urlencode(Yf_Registry::get('shop_wap_url'))?>" class="rel_nav-logo3"  width="60" height="60"/>-->
		</div>
	</div>
</div>
