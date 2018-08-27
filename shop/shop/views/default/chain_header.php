<?php if (!defined('ROOT_PATH')){exit('No Permission');}
$chain_menu = include_once INI_PATH . '/chain_menu.ini.php';
$Chain_BaseModel = new Chain_BaseModel();
$chain_base = $Chain_BaseModel->getOne(Perm::$chainId);
$chain_area[]=$chain_base['chain_province'];
$chain_area[]=$chain_base['chain_city'];
$chain_area[]=$chain_base['chain_county'];
$chain_base['chain_area']=implode(' ',$chain_area);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="renderer" content="webkit|ie-stand|ie-comp" />
    <meta name="viewport" content="width=device-width, initial-scale=0.7, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="<?php if($this->description){?><?=$this->description ?><?php }?>" />
    <meta name="Keywords" content="<?php if($this->keyword){?><?=$this->keyword ?><?php }?>" />
    <title><?php if($this->title){?><?=$this->title ?><?php }else{?><?= Web_ConfigModel::value('site_name') ?><?php }?></title>
    <link href="<?= $this->view->css?>/seller.css" rel="stylesheet">
    <link href="<?= $this->view->css?>/iconfont/iconfont.css>" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css?>/ztree.css" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css?>/base.css" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css?>/tips.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?= $this->view->js_com?>/jquery.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com?>/plugins/jquery.cookie.js"></script>
    <link href="<?= $this->view->css?>/select2.min.css" rel="stylesheet">
    <script src="<?= $this->view->js ?>/select2.min.js"></script>
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
    
        var SYSTEM = SYSTEM || {};
        SYSTEM.skin = 'green';
        SYSTEM.isAdmin = true;
        SYSTEM.siExpired = false;
    </script>
	<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
</head>
<body>
<div class="header">
    <div class="wrapper fn-clear clearfix">
        <div class="logo">
            <a href="index.php?ctl=Chain_Goods&met=goods&typ=e"><img src="<?php if(!empty($this->web['seller_logo'])){echo $this->web['seller_logo'];}?>"></a>
            <span><?=__('门店管理')?></span>
        </div>
        <ul class="nav" style="width:320px;">
            <?php
            foreach ($chain_menu as $menu_row)
            {
                ?>
                <li class="<?= ($menu_row['menu_id'] == $level_row[1]) ? 'cur bbc_seller_bg' : '' ?>">
                    <a class="dropdown-toggle"
                       href="<?= sprintf('%s?ctl=%s&met=%s&typ=e&%s', Yf_Registry::get('url'), $menu_row['menu_url_ctl'], $menu_row['menu_url_met'], $menu_row['menu_url_parem']); ?>">
                        <?= $menu_row['menu_name'] ?><i></i>
                    </a>
                </li>
            <?php
            }
            ?>
        </ul>
        <div class="ncsc-admin">
            <dl class="ncsc-admin-store">
                <dt class=""><?=$chain_base['chain_name']?></dt>
                <dd class=""><?=$chain_base['chain_area']?></dd>
                <dd class=""><?=$chain_base['chain_mobile']?></dd>
            </dl>
            <div class="pic"><img src="<?=$chain_base['chain_img']?>"></div>
        </div>
    </div>
</div>