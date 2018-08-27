<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
// 当前管理员权限
$admin_rights = $this->getAdminRights();
// 当前页父级菜单 同级菜单 当前菜单
$menus = $this->getThisMenus();

?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<style>

  .ui-jqgrid tr.jqgrow .img_flied{padding: 1px; line-height: 0px;}
  .img_flied img{width: 60px; height: 60px;}

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
         <!--  <li><a  href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=common" <?=((-1 == request_int('common_state', '-1') && -1 == request_int('common_verify', '-1')) ? 'class="current"' : '')?>><span>所有商品</span></a></li>
          <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=common&common_state=10" <?=(10 == request_int('common_state', '-1') ? 'class="current"' : '')?> ><span>违规下架</span></a></li>
          <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Goods_Goods&met=common&common_verify=10" <?=(10 == request_int('common_verify', '-1') ? 'class="current"' : '')?>><span>等待审核</span></a></li>
          <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=goods&config_type%5B%5D=goods" <?=('Config' == request_string('ctl') ? 'class="current"' : '')?>><span>商品设置</span></a></li> -->
      </ul>
    </div>
  </div>
  <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
      <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
      <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em> </div>
    <ul>
      <?=$menus['this_menu']['menu_url_note']?>
    </ul>
  </div>
    
  <div class="mod-search cf" id="report-search">
    <div class="l" id="filter-menu">
      <ul class="ul-inline fix">
        <li>
          <span id="user"></span>
          <input type="text" id="common_name" name="common_name" class="ui-input ui-input-ph" placeholder="输入商品名称"   autocomplete="off" >
          <input type="text" id="common_id" name="common_id" class="ui-input ui-input-ph" placeholder="输入商品平台货号"   autocomplete="off" >
          <input type="text" id="shop_name" name="shop_name" class="ui-input ui-input-ph" placeholder="输入商品所属店铺名称"   autocomplete="off" >
        </li>
        <li id="brand" style="display: list-item;"><span class="mod-choose-input" id="filter-brand"><input type="text" class="ui-input" id="brand_id" autocomplete="off" placeholder="输入品牌名称" ><span class="ui-icon-ellipsis"></span></span></li>
        <li>
          <span id="common_state"></span>
          <span id="common_verify"></span>
          <span id="goods_cat"></span>
        </li>
      
      </ul>
    </div>
      <div class="fr">
                     <a class="ui-btn" id="search">查询<i class="iconfont icon-btn02"></i></a>
                    <!--<a class="ui-btn ui-btn-refresh" id="refresh" title="刷新"><b></b></a>-->
                    <a class="ui-btn" id="audit" <?=(10 == request_int('common_state', '-1') ? '' : 'style="display:none"')?>>上架<i class="iconfont icon-btn05"></i></a>
                    <a class="ui-btn" id="reaudit" <?=(10 == request_int('common_verify', '-1') ? '' : 'style="display:none"')?>>审核<i class="iconfont icon-btn04"></i></a>
                </div>
  </div>


    <div class="cf">
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>

    </div>
</div>
<script type="text/javascript">
  var common_state = <?=request_int('common_state', -1)?>;
  var common_verify = <?=request_int('common_verify', -1)?>;
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/goods/common_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
