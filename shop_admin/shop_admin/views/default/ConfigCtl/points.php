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
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
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
    
    <form method="post" enctype="multipart/form-data" id="points-setting-form" name="form">
        <input type="hidden" name="config_type[]" value="points"/>

        <div class="ncap-form-default">
			<div class="title">
				<h3>会员日常获取积分设定</h3>
			</div>
            <dl class="row">
                <dt class="tit">
                    <label>会员注册</label>
                </dt>
                <dd class="opt">
					<input id="points_reg" name="points[points_reg]" value="<?=($data['points_reg']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>会员每天登陆</label>
                </dt>
                <dd class="opt">
					<input id="points_login" name="points[points_login]" value="<?=($data['points_login']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">
                    <label>订单商品评论</label>
                </dt>
                <dd class="opt">
					<input id="points_evaluate" name="points[points_evaluate]" value="<?=($data['points_evaluate']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>
			<div class="title">
				<h3>会员购物并付款时积分获取设定</h3>
			</div>
			<dl class="row">
                <dt class="tit">
                    <label>消费额与赠送经积分比例</label>
                </dt>
                <dd class="opt">
					<input id="points_recharge" name="points[points_recharge]" value="<?=($data['points_recharge']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic">例:设置为10，表明消费10单位货币赠送1积分</p>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">
                    <label>每订单最多赠送积分</label>
                </dt>
                <dd class="opt">
					<input id="points_order" name="points[points_order]" value="<?=($data['points_order']['config_value'])?>" class="ui-input w400" type="text"/>
                    <p class="notic">例:设置为100，表明每笔订单最多赠送100积分</p>
                </dd>
            </dl>
          <div class="bot"> <a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<script>
  
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>