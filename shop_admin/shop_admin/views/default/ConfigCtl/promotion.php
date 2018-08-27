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
                <!-- <li><a class="current"><span>促销设定</span></a></li> -->
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
	
	<form method="post" enctype="multipart/form-data" id="promotion-setting-form" name="form1">
		<input type="hidden" name="config_type[]" value="promotion"/>
		<div class="ncap-form-default">
		  <dl class="row">
			<dt class="tit">团购</dt>
			<dd class="opt">
			  <div class="onoff">
				<label for="groupbuy_allow_1" class="cb-enable  <?=($data['groupbuy_allow']['config_value']=='1' ? 'selected' : '')?>" title="开启">开启</label>
				<label for="groupbuy_allow_0" class="cb-disable <?=($data['groupbuy_allow']['config_value']=='0' ? 'selected' : '')?>" title="关闭">关闭</label>
				<input id="groupbuy_allow_1" name="promotion[groupbuy_allow]" <?=($data['groupbuy_allow']['config_value']=='1' ? 'checked' : '')?> value="1" type="radio">
				<input id="groupbuy_allow_0" name="promotion[groupbuy_allow]" <?=($data['groupbuy_allow']['config_value']=='0' ? 'checked' : '')?> value="0" type="radio">
			  </div>
			  <p class="notic">团购功能启用后，商家通过活动发布团购商品，进行促销</p>
			</dd>
		  </dl>
		  <!-- 促销开启 -->
		  <dl class="row">
			<dt class="tit">
			  <label>商品促销</label>
			</dt>
			<dd class="opt">
			  <div class="onoff">
				<label for="promotion_allow_1" class="cb-enable  <?=($data['promotion_allow']['config_value']=='1' ? 'selected' : '')?>" title="开启">开启</label>
				<label for="promotion_allow_0" class="cb-disable <?=($data['promotion_allow']['config_value']=='0' ? 'selected' : '')?>" title="关闭">关闭</label>
				<input type="radio" id="promotion_allow_1" name="promotion[promotion_allow]" value="1" <?=($data['promotion_allow']['config_value']=='1' ? 'checked' : '')?>>
				<input type="radio" id="promotion_allow_0" name="promotion[promotion_allow]" value="0" <?=($data['promotion_allow']['config_value']=='0' ? 'checked' : '')?>>
			  </div>
			  <p class="notic">启用商品促销功能后，商家可以通过限时打折、满即送、加价购，对店铺商品进行促销</p>
			</dd>
		  </dl>
		  <dl class="row">
			<dt class="tit">积分中心</dt>
			<dd class="opt">
			  <div class="onoff">
				<label for="pointshop_isuse_1" class="cb-enable  <?=($data['pointshop_isuse']['config_value']=='1' ? 'selected' : '')?>" title="开启"><span>开启</span></label>
				<label for="pointshop_isuse_0" class="cb-disable <?=($data['pointshop_isuse']['config_value']=='0' ? 'selected' : '')?>" title="关闭"><span>关闭</span></label>
				<input id="pointshop_isuse_1" name="promotion[pointshop_isuse]" <?=($data['pointshop_isuse']['config_value']=='1' ? 'checked' : '')?> value="1" type="radio">
				<input id="pointshop_isuse_0" name="promotion[pointshop_isuse]" <?=($data['pointshop_isuse']['config_value']=='0' ? 'checked' : '')?> value="0" type="radio">
			  </div>
			  <p class="notic">积分中心启用后，网站将增加积分中心频道</p>
			</dd>
		  </dl>
		  <dl class="row">
			<dt class="tit">积分兑换</dt>
			<dd class="opt">
			  <div class="onoff">
				<label for="pointprod_isuse_1" class="cb-enable  <?=($data['pointprod_isuse']['config_value']=='1' ? 'selected' : '')?>" title="开启">开启</label>
				<label for="pointprod_isuse_0" class="cb-disable <?=($data['pointprod_isuse']['config_value']=='0' ? 'selected' : '')?>" title="关闭">关闭</label>
				<input id="pointprod_isuse_1" name="promotion[pointprod_isuse]" <?=($data['pointprod_isuse']['config_value']=='1' ? 'checked' : '')?> value="1" type="radio">
				<input id="pointprod_isuse_0" name="promotion[pointprod_isuse]" <?=($data['pointprod_isuse']['config_value']=='0' ? 'checked' : '')?> value="0" type="radio">
			  </div>
			  <p class="notic">积分兑换、积分功能以及积分中心启用后，平台发布礼品，会员的积分在达到要求时可以在积分中心中兑换礼品</p>
			</dd>
		  </dl>
		  <dl class="row">
			<dt class="tit">代金券</dt>
			<dd class="opt">
			  <div class="onoff">
				<label for="voucher_allow_1" class="cb-enable  <?=($data['voucher_allow']['config_value']=='1' ? 'selected' : '')?>" title="开启">开启</label>
				<label for="voucher_allow_0" class="cb-disable <?=($data['voucher_allow']['config_value']=='0' ? 'selected' : '')?>" title="关闭">关闭</label>
				<input id="voucher_allow_1" name="promotion[voucher_allow]" <?=($data['voucher_allow']['config_value']=='1' ? 'checked' : '')?> value="1" type="radio">
				<input id="voucher_allow_0" name="promotion[voucher_allow]" <?=($data['voucher_allow']['config_value']=='0' ? 'checked' : '')?> value="0" type="radio">
			  </div>
			  <p class="notic">代金券功能、积分功能、积分中心启用后，商家可以申请代金券活动；会员积分达到要求时可以在积分中心兑换代金券；<br>拥有代金券的会员可在代金券所属店铺内购买商品时，选择使用而得到优惠</p>
			</dd>
		  </dl>
		 
		  <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
		</div>
	</form>

    <script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
    <?php
include $this->view->getTplPath() . '/' . 'footer.php';
    ?>