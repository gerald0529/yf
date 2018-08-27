<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
// 当前管理员权限
$admin_rights = $this->getAdminRights();
// 当前页父级菜单 同级菜单 当前菜单
$menus = $this->getThisMenus();
$sub_site_suffix = isset(Perm::$row['sub_site_id']) && Perm::$row['sub_site_id'] > 0 ? '_'.Perm::$row['sub_site_id']  : '';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
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
        </ul> 
      </div>
  </div>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <?=$menus['this_menu']['menu_url_note']?>
        </ul>
    </div>
    
    <form method="post" id="selfshop-setting-form" name="settingForm">
        <input type="hidden" name="config_type[]" value="sub_site_self_shop"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">是否在商城显示</dt>
                <dd class="opt">
                    <div class="onoff">
                        <input id="self_shop_show1" name="sub_site_self_shop[self_shop_show<?=$sub_site_suffix?>]" value="1" type="radio" <?=($data['self_shop_show'.$sub_site_suffix]['config_value']==1 ? 'checked' : '')?> >
						<label title="开启" class="cb-enable <?=($data['self_shop_show'.$sub_site_suffix]['config_value']==1 ? 'selected' : '')?> " for="self_shop_show1">开启</label>

                        <input id="self_shop_show0" name="sub_site_self_shop[self_shop_show<?=$sub_site_suffix?>]" value="0" type="radio"  <?=($data['self_shop_show'.$sub_site_suffix]['config_value']==0 ? 'checked' : '')?> >
						<label title="关闭" class="cb-disable <?=($data['self_shop_show'.$sub_site_suffix]['config_value']==0 ? 'selected' : '')?>" for="self_shop_show0">关闭</label>
                    </div>

                    <p class="notic">开启后，自营店铺和自营商品将在商城显示，如果开启分站，该设置仅对当前分站有效</p>
                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>


<script type="text/javascript">
</script>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>