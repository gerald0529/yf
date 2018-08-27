<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
// 当前管理员权限
$admin_rights = $this->getAdminRights();
// 当前页父级菜单 同级菜单 当前菜单
$menus = $this->getThisMenus();


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
    
    <form method="post" id="subsite-setting-form" name="settingForm">
        <input type="hidden" name="config_type[]" value="subsite"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">是否开启城市分站</dt>
                <dd class="opt">
                    <div class="onoff">
                        <input id="subsite_is_open1" name="subsite[subsite_is_open]" value="1" type="radio" <?=($data['subsite_is_open']['config_value']==1 ? 'checked' : '')?> >
						<label title="开启" class="cb-enable <?=($data['subsite_is_open']['config_value']==1 ? 'selected' : '')?> " for="subsite_is_open1">开启</label>

                        <input id="subsite_is_open0" name="subsite[subsite_is_open]" value="0" type="radio"  <?=($data['subsite_is_open']['config_value']==0 ? 'checked' : '')?> >
						<label title="关闭" class="cb-disable <?=($data['subsite_is_open']['config_value']==0 ? 'selected' : '')?>" for="subsite_is_open0">关闭</label>
                    </div>

                    <p class="notic"></p>
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