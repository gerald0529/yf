<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<style>
input[type="text"]{width:200px}
</style>
</head>
<body>
<div class="ncap-form-default">
	<form id="manage-form" action="#">
		 <dl class="row row-item">
		    <dt class="tit" style="width:20%;">
                <em>*</em><label class="voucher_price" for="voucher_price">代金券面额</label>(元)
            </dt>
            <dd class="opt" style="width:75%;">
                <input type="text" value="" class="ui-input" name="voucher_price" id="voucher_price">
                <span class="err"></span>
                <p class="notic"></p>
            </dd>
         </dl>
         <dl class="row row-item">
		    <dt class="tit" style="width:20%;">
                <em>*</em><label class="voucher_price_describe" for="voucher_price_describe">描述</label>
            </dt>
            <dd class="opt" style="width:75%;">
               <textarea name="voucher_price_describe" rows="6" class="tarea w300" id="voucher_price_describe"></textarea>
                <span class="err"></span>
                <p class="notic"></p>
            </dd>
        </dl>
        <dl class="row row-item">
		    <dt class="tit" style="width:20%;">
                <em>*</em><label class="voucher_defaultpoints" for="voucher_defaultpoints">兑换积分数</label>
            </dt>
            <dd class="opt" style="width:75%;">
                <input type="text" value="" class="ui-input" name="voucher_defaultpoints" id="voucher_defaultpoints">
                <span class="err"></span>
                <p class="notic">当兑换代金券时，消耗的积分数</p>
            </dd>
        </dl>
	</form>
</div>
<script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/promotion/voucher/price_manage.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>