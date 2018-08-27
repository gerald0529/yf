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
            <?php include dirname(__FILE__).'/comm_api_menu.php';?>
        </div>
    </div>
    <?php

  ?>
    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <?=$menus['this_menu']['menu_url_note']?>
        </ul>
    </div>
    <?php 

    $im_s = @json_decode(file_get_contents(Yf_Registry::get('shop_api_url')."?ctl=Api_Wap&met=version_im&typ=json"),true);
    $im_s = @$im_s['data']['im'];
    ?>
    <form method="post" id="im-shop_api-setting-form" name="imSettingForm">
        <input type="hidden" name="config_type[]" value="im_api"/>

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">IM状态</dt>
                <dd class="opt">
                    <div class="onoff">
                        <input   id="im_statu1" name="im_api[im_statu]"  value="1" type="radio" <?=(@Yf_Registry::get('im_statu')==1 ? 'checked' : '')?>>
						<label <?php if($im_s!=1){?>onclick="return false;"<?php }?>  title="开启" class="cb-enable <?=(@(Yf_Registry::get('im_statu')==1 && $im_s==1) ? 'selected' : '')?> " for="im_statu1" id="im_statu01">开启</label>

                        <input id="im_statu0" name="im_api[im_statu]"  value="0" type="radio"
                         <?=(@(Yf_Registry::get('im_statu')==0 ||$im_s!=1 )? 'checked' : '')?>>
						<label  <?php if($im_s!=1){?>onclick="return false;"<?php }?>   title="关闭" class="cb-disable <?=(@(Yf_Registry::get('im_statu')==0 ||$im_s!=1 ) ? 'selected' : '')?>" for="im_statu0" id="im_statu00">关闭</label>
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="site_name">IM URL</label>
                </dt>
                <dd class="opt">
                    <input <?php if($im_s!=1){?>disabled<?php }?>  id="im_api_url" name="im_api[im_url]" value="<?=@Yf_Registry::get('im_url')?>" class="w400 ui-input " type="text"/>

<!--                    <p class="notic">IM又称大用户中心,是我们开发的用于整合多个子系统用户的独立用户中心系统,实现用户的单点登录和登出,用户的统一化管理。</p>-->
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="site_name">IM API URL</label>
                </dt>
                <dd class="opt">
                    <input <?php if($im_s!=1){?>disabled<?php }?>   id="im_api_url" name="im_api[im_api_url]" value="<?=Yf_Registry::get('im_api_url')?>" class="w400 ui-input " type="text"/>

<!--                    <p class="notic">IM又称大用户中心,是我们开发的用于整合多个子系统用户的独立用户中心系统,实现用户的单点登录和登出,用户的统一化管理。</p>-->
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="site_name">Im后台 URL</label>
                </dt>
                <dd class="opt">
                    <input <?php if($im_s!=1){?>disabled<?php }?>   id="im_admin_api_url" name="im_api[im_admin_api_url]" value="<?=Yf_Registry::get('im_admin_api_url')?>" class="w400 ui-input " type="text"/>

                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="site_name">IM key</label>
                </dt>
                <dd class="opt">
                    <input <?php if($im_s!=1){?>disabled<?php }?>   id="im_api_key" name="im_api[im_api_key]" value="<?=Yf_Registry::get('im_api_key')?>" class="ui-input w400" type="text"/>

                    <p class="notic">请填写商城系统与IM通讯的Key值,此外的值要与IM后台应用的值保持一致</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="site_name">SNS URL</label>
                </dt>
                <dd class="opt">
                    <input <?php if($im_s!=1){?>disabled<?php }?>   id="sns_api_url" name="im_api[sns_api_url]" value="<?=Yf_Registry::get('sns_api_url')?>" class="w400 ui-input " type="text"/>

                    <p class="notic"></p>
                </dd>
            </dl>
            <div class="bot"><a
             href="javascript:void(0);" <?php if($im_s!=1){?>disabled style="background-color: #96a6a6;border-color: #808b8d;color: #fff;"<?php }?>   class="ui-btn ui-btn-sp im-submit-btn">确认提交</a></div>
        </div>
    </form>

</div>

<script type="text/javascript">

$(function(){
    <?php if($im_s!=1){?> 

    $('#explanation ul li').html("您未购买IM权限，请联系远丰商务部");
     
    $('a.im-submit-btn,label').on("click",function(){
        Public.tips({type: 1, content: ' 请联系客服开通IM服务'});
        $('#im_statu01').removeClass('selected');
        $('#im_statu00').addClass('selected');
        return false;
    });
    <?php }?>
});
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>