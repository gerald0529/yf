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

    <form id="licence-setting-form" method="post" enctype="multipart/form-data" name="settingForm">
        <input type="hidden" name="config_type[]" value="licence"/>

        <div class="ncap-form-default">

            <dl class="row">
                <dt class="tit">
                    <label for="company_name">授权名称</label>
                </dt>
                <dd class="opt">
                    <input id="company_name" name="licence[company_name]" value="<?=(@$data['licence']['company_name'])?>" class="ui-input ui-input-dis w400" disabled type="text"/>

                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="licence_domain">授权域名</label>
                </dt>
                <dd class="opt">
                    <input id="licence_domain" name="licence[licence_domain]" value="<?=(@$data['licence']['licence_domain'])?>" class="ui-input ui-input-dis w400" disabled type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="licence_effective_enddate">有效日期</label>
                </dt>
                <dd class="opt">
                    <input id="licence_effective_enddate" name="licence[licence_effective_enddate]" value="<?=(@$data['licence']['licence_effective_enddate'])?>" class="ui-input ui-input-dis w400" disabled type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="licence_key">证书信息</label>
                </dt>
                <dd class="opt">
                    <textarea name="licence[licence_key]" class="ui-input w600" id="licence_key"><?=($data['licence']['licence_key'])?></textarea>

                    <p class="notic"><a href="//www.yuanfeng021.com" target="_blank">联系获取证书</a></p>
                </dd>
            </dl>

            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">更新证书</a></div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>