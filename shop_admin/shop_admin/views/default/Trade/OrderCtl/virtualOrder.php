<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
// 当前管理员权限
$admin_rights = $this->getAdminRights();
// 当前页父级菜单 同级菜单 当前菜单
$menus = $this->getThisMenus();

?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?= $this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<script src="<?= $this->view->js_com ?>/plugins/jquery.datetimepicker.js"></script>
<style>
    .ui-icon-receive{
        background-position: -64px -142px;
    }
</style>
</head>
<body>

<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?=$menus['father_menu']['menu_name']?></h3>
                <h5><?=$menus['father_menu']['menu_url_note']?></h5>
            </div>
            <ul class="tab-base nc-row">
                <?php 
                foreach($menus['brother_menu'] as $key=>$val){ 
                    if(in_array($val['rights_id'],$admin_rights)||$val['rights_id']==0){
                ?>
                <li><a <?php if(!array_diff($menus['this_menu'], $val)){?> class="current"<?php }?> href="<?= Yf_Registry::get('url') ?>?ctl=<?=$val['menu_url_ctl']?>&met=<?=$val['menu_url_met']?><?php if($val['menu_url_parem']){?>&<?=$val['menu_url_parem']?><?php }?>"><span><?=$val['menu_name']?></span></a></li>
                <?php 
                    }
                }
                ?>
                <!-- <li><a class="current" href="javascript:void(0)"><span>虚拟订单</span></a></li> -->
            </ul>
        </div>
    </div>

    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em>
        </div>
        <ul>
            <?=$menus['this_menu']['menu_url_note']?>
            
        </ul>
    </div>
        <div class="mod-toolbar-top cf">
            <div class="left">
                <div id="assisting-category-select" class="ui-tab-select">
                    <ul class="ul-inline">
                       <!--  <li><span id="source"></span></li> -->
                        <li>
                            <input type="text" id="order_id" class="ui-input ui-input-ph con" placeholder="请输入订单编号...">
                        </li>
                        <li>
                            <input type="text" id="buyer_name" class="ui-input ui-input-ph con" placeholder="请输入买家账号...">
                        </li>
                        <li>
                            <input type="text" id="shop_name" class="ui-input ui-input-ph con" placeholder="请输入店铺名称...">
                        </li>
                        <li>
                            <input type="text" id="payment_number" class="ui-input ui-input-ph con" placeholder="请输入支付单号...">
                        </li>
                        <li>
                            <label>付款日期:</label>
                            <input type="text" value="" class="ui-input ui-datepicker-input" name="filter-fromDate" id="filter-fromDate"> - <input type="text" value="" class="ui-input ui-datepicker-input" name="filter-toDate" id="filter-toDate">
                        </li>
                        <li><a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="fr">
                <a class="ui-btn" id="btn-refresh">刷新<i class="iconfont icon-btn01"></i></a>
            </div>
             <div style="float:left;display:inline-block;padding: 0 6px 0 0;line-height: 30px;height: 30px;font-size: 12px;border-radius: 2px;vertical-align: middle;">
                <b>订单总金额：</b>￥ <span class="count_amount"></span>
            </div>
        </div>
        <div class="grid-wrap">
            <table id="grid"></table>
            <div id="page"></div>
        </div>
    </div>


<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

<script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/trade/order/order_list.js"></script>
