<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
$seller_menu = include_once INI_PATH . '/seller_menu.ini.php';

if (@$this->shopBase['shop_type'] == 2) {
    unset($seller_menu['12000']['sub']['120008']);
    unset($seller_menu['13000']);
    unset($seller_menu['14000']['sub']['140011']);
}
// echo '<pre>';
// print_r($seller_menu);
// die;

$User_InfoModel = new User_InfoModel();
$user_info = $User_InfoModel->getOne(Perm::$userId);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-stand|ie-comp"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1"/>
    <meta name="viewport" content="width=device-width, initial-scale=0.7, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="<?php if ($this->description) { ?><?= $this->description ?><?php } ?>"/>
    <meta name="Keywords" content="<?php if ($this->keyword) { ?><?= $this->keyword ?><?php } ?>"/>
    <title><?php if ($this->title) { ?><?= $this->title ?><?php } else { ?><?= Web_ConfigModel::value('site_name') ?><?php } ?></title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
    <link href="<?= $this->view->css ?>/seller.css?ver=<?= VER ?>" rel="stylesheet">
    <link href="<?= $this->view->css ?>/iconfont/iconfont.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css_com ?>/ztree.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css ?>/base.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css ?>/tips.css?ver=<?= VER ?>" rel="stylesheet" type="text/css">
    <link href="<?= $this->view->css ?>/select2.min.css" rel="stylesheet">
    <script type="text/javascript" src="<?= $this->view->js_com ?>/jquery.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/jquery.cookie.js"></script>
    <script src="<?= $this->view->js ?>/select2.min.js"></script>
    <script type="text/javascript">
        var IM_URL = "<?=Yf_Registry::get('im_api_url')?>";
        var IM_STATU = "<?=Yf_Registry::get('im_statu')?>";
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
    <script type="text/javascript" src="<?= $this->view->js ?>/common.js"></script>
    <script type="text/javascript" src="<?= $this->view->js ?>/decoration/common.js"></script>
    <style type="text/css" media="screen">
        /* 取消H5表单上下箭头 */
        /* 谷歌浏览器兼容性 */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none !important;
            margin: 0;
        }

        /* 火狐浏览器兼容性 */
        input[type="number"] {
            -moz-appearance: textfield;
        }

        .header .nav {
            height: 100%;
        }

        .absolute {
            position: absolute !important;
        }
    </style>
</head>
<body>
<?php include APP_PATH . '/alert_box.php'; ?>
<?php if (!empty($this->shopinfo)) { ?>
    <div class="shop-closed">
        <i class="iconfont icon-tanhao"></i>
        <dl>
            <dt><?= __('您的店铺已被平台关闭') ?></dt>
            <dd><?= __('关闭原因') ?>：<?= $this->shopinfo['shop_close_reason'] ?></dd>
            <dd><?= __('在此期间') ?>，<?= __('您的店铺以及商品将无法访问') ?>；<?= __('如果您有异议或申诉请及时联系平台管理') ?>。</dd>
        </dl>
    </div>
<?php } ?>
<div class="header">
    <div class="wrapper fn-clear clearfix">
        <div class="logo">
            <a href="index.php?ctl=Seller_Index&met=index&typ=e">
                <img src="<?php if (!empty($this->web['seller_logo'])) {
                    echo $this->web['seller_logo'];
                } ?>">
            </a>
            <span>
                <?php if (@$this->shopBase['shop_type'] == 2) { ?>
                    <?= __('供应商中心'); ?>
                <?php } else { ?>
                    <?= __('商家中心'); ?>
                <?php } ?>
            </span>
        </div>
        <div class="index-search-container">
            <div class="index-sitemap bbc_seller_bg">
                <a class="js-sitemap" href="javascript:void(0);">
                    <?= __('导航管理') ?>
                    <i class="icon-angle-down"></i>
                </a>
                <div class="js-menu-arrow sitemap-menu-arrow" style="display:none;"></div>
                <div class="js-menu sitemap-menu" style="display:none;">
                    <div class="title-bar">
                        <h2 class="bbc_seller_color">
                            <i class="icon-sitemap"></i>
                            <?= __('管理导航') ?>
                            <em><?= __('小提示') ?>：<?= __('添加您经常使用的功能到首页侧边栏') ?>，<?= __('方便操作') ?>。</em>
                        </h2>
                        <span id="closeSitemap" class="close">X</span>
                    </div>
                    <div id="quicklink_list" class="content ">
                            <?php if ($menu_row['menu_url_ctl'] != 'Seller_Index') { ?>
                                 <?php foreach ($seller_menu as $menu_row) { ?>
                                    <dl>
                                        <dt><?= $menu_row['menu_name'] ?></dt>
                                        <?php foreach ($menu_row['sub'] as $menu_level) { ?>
                                            <dd class="selected">
                                                <i nctype="btn_add_quicklink" data-quicklink-act="<?= $menu_level['menu_id'] ?>" class="icon-icon_duigou2" title="<?= __('添加为常用功能菜单') ?>"></i>
                                                <a href="<?= sprintf('%s?ctl=%s&met=%s&typ=e&%s', Yf_Registry::get('url'), $menu_level['menu_url_ctl'], $menu_level['menu_url_met'], $menu_level['menu_url_parem']); ?>">
                                                    <?= $menu_level['menu_name'] ?>
                                                </a>
                                            </dd>
                                        <?php } ?>
                                    </dl>
                                 <?php } ?>
                            <?php } ?>
                    </div>
                </div>
            </div>
            <div class="search-bar bbc_seller_border">
                <form method="get" target="_blank">
                    <input type="hidden" name="ctl" value="Goods_Goods">
                    <input type="hidden" name="met" value="goodslist">
                    <input type="hidden" name="typ" value="e">
                    <input type="text" nctype="search_text" name="keywords" placeholder="<?= __('商城商品搜索') ?>"
                           class="search-input-text">
                    <input type="submit" nctype="search_submit" class="search-input-btn pngFix" value="">
                </form>
            </div>
        </div>
        <ul class="nav">
            <li class="<?= Seller_Controller::$current_menu['model'] == 'index' ? 'cur bbc_seller_bg' : ''; ?>">
                <dt><a href="<?= Yf_Registry::get('url') ?>?ctl=Seller_Index&met=index&typ=e"><?= __('首页') ?></a></dt>
                <dd class="arrow"></dd>
            </li>
            <?php if (!empty(Seller_Controller::$menu) && is_array(Seller_Controller::$menu)) {
                foreach (Seller_Controller::$menu as $key => $menu_row) {
                    if ($key === 'statistics' && !Yf_Registry::get('analytics_statu')) {
                        continue;
                    }
                    ?>
                    <li class="<?= (Seller_Controller::$current_menu['model'] == $key) ? 'cur bbc_seller_bg' : '' ?>">
                        <a class="dropdown-toggle" href="<?= sprintf('%s?ctl=%s&met=%s&typ=e', Yf_Registry::get('url'), $menu_row['sub'][key($menu_row['sub'])]['ctl'], $menu_row['sub'][key($menu_row['sub'])]['met']); ?>">
                            <?= $menu_row['name'] ?>
                        </a>
                        <?php if ($menu_row['name'] == "<?=__('客服消息')?>" && $this->user_info['message'] > 0) { ?>
                            <i class="bbuyer_news"><?= $this->user_info['message'] ?></i>
                        <?php } ?>
                    </li>
                <?php } } ?>
            <li>
                <a href="<?= Yf_Registry::get('paycenter_api_url') ?>" target="_blank"><?= Yf_Registry::get('paycenter_api_name') ?></a>
            </li>
        </ul>
        <div class="ncsc-admin">
            <dl class="ncsc-admin-info">
                <dt class="admin-avatar">
                    <img src="<?= $user_info['user_logo'] ?>" width="32" class="pngFix" alt="">
                </dt>
                <dd class="admin-permission"><?= __('当前用户') ?></dd>
                <dd class="admin-name"><?= $user_info['user_name'] ?></dd>
            </dl>
            <div class="ncsc-admin-function"><a href="<?= Yf_Registry::get('url') ?>" title="<?= __('前往商城') ?>"><i
                            class="iconfont icon-fangzi"></i></a><a
                        href="<?= Yf_Registry::get('url') ?>?ctl=Shop&met=index&id=<?= Perm::$shopId ?>"
                        title="<?= __('前往店铺') ?>"><i class="iconfont icon-dianpu2"></i></a><a
                        href="<?= Yf_Registry::get('ucenter_api_url') ?>?ctl=User&met=getUserInfo"
                        title="<?= __('基本信息') ?>" target="_blank"><i class="iconfont icon-banshou"></i></a><a
                        href="<?= Yf_Registry::get('url') ?>?ctl=Login&met=loginout" title="<?= __('安全退出') ?>"><i
                            class="iconfont icon-tuichu"></i></a></div>
        </div>
    </div>
</div>
<?php if ('Seller_Index' == $ctl && 'index' == $met) { ?>
<div class="wrapper main">
    <div>
        <div>
            <?php }  else { ?>
            <div class="layout wrapper fn-clear">
                <?php if ($ctl == "Seller_Shop_Decoration" && $met == "decoration" && $act == "set") { ?>
                <?php } else { ?>
                    <div class="left-layout absolute">
                        <ul>
                            <?php foreach ($seller_menu[$level_row[1]]['sub'] as $menu_row) { ?>
                                <li>
                                    <a class="<?= ($menu_row['menu_id'] == $level_row[2]) ? 'active' : '' ?>"
                                       href="<?= sprintf('%s?ctl=%s&met=%s&typ=e&%s', Yf_Registry::get('url'), $menu_row['menu_url_ctl'], $menu_row['menu_url_met'], $menu_row['menu_url_parem']); ?>"><?= $menu_row['menu_name'] ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>
                <div class="<?php if (!($ctl == "Seller_Shop_Decoration" && $met == "decoration" && $act == "set")) { ?>right-layout<?php } ?>"<?php if ($ctl == "Seller_Shop_Decoration" && $met == "decoration" && $act == "set") { ?> style="float:left;background: #fff;"<?php } ?>>
                    <?php if (!($ctl == "Seller_Shop_Decoration" && $met == "decoration" && $act == "set")) { ?>
                        <div class="path">
                            <i class="iconfont icon-diannao"></i><?= __('商家管理中心') ?>
                            <i class="iconfont icon-iconjiantouyou"></i><?= $seller_menu[$level_row[1]]['menu_name'] ?>
                            <i class="iconfont icon-iconjiantouyou"></i><?= $seller_menu[$level_row[1]]['sub'][$level_row[2]]['menu_name'] ?>
                        </div>
                    <?php } ?>
                    <div class="content">
                        <?php
                        if (isset($seller_menu[$level_row[1]]['sub'][$level_row[2]]['sub'])) {
                            ?>
                            <?php if ($ctl == "Seller_Shop_Decoration" && $met == "decoration" && $act == "set") { ?>
                            <?php } else { ?>
                                <div class="tabmenu">
                                    <ul>
                                        <?php
                                        foreach ($seller_menu[$level_row[1]]['sub'][$level_row[2]]['sub'] as $menu_row) {
                                            //不应该根据名称来判断
                                            if ($menu_row['menu_url_met'] == 'combo') {
                                                //自营或者不需要收费
                                                if ((@$this->self_support_flag || @$this->selfSupportFlag)) {
                                                    continue;
                                                } else {
                                                }
                                            }

                                            ?>
                                            <li class="<?= ($menu_row['menu_id'] == $level_row[3]) ? 'active bbc_seller_bg' : '' ?>">
                                                <a href="<?= sprintf('%s?ctl=%s&met=%s&typ=e&%s', Yf_Registry::get('url'), $menu_row['menu_url_ctl'], $menu_row['menu_url_met'], $menu_row['menu_url_parem']); ?>"><?= $menu_row['menu_name'] ?></a>
                                            </li>
                                            <?php
                                        }
                                        ?>

                                    </ul>
                                </div>
                            <?php } ?>
                            <?php
                        }
                        ?>
                        <?php
                        }
                        ?>
