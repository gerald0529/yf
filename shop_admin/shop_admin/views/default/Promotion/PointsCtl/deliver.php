<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<style>
.manage-wrap{margin: 20px auto 10px;width: 400px;}
input[type="text"]{width:200px}
</style>
</head>
<body>
<form id="manage-form" method="post" name="deliver" action="">
    <input type="hidden" name="form_submit" value="ok">
    <div class="ncap-form-default">
        <dl class="row">
            <dt class="tit">
                <label>会员名称</label>
            </dt>
            <dd class="opt">
                <span class="points_buyername"><?=$data['p_order']['points_buyername']?></span>
                <span class="err"></span>
                <p class="notic"></p>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label>兑换单号</label>
            </dt>
            <dd class="opt">
                <span class="points_order_rid"><?=sprintf('%.0f',$data['p_order']['points_order_rid'])?></span>
                <span class="err"></span>
                <p class="notic"></p>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
              <label for="shippingcode"><em>*</em>物流单号</label>
            </dt>
            <dd class="opt">
              <input id="points_shippingcode" name="points_shippingcode" class="ui-input" value="" type="text">
              <span class="err"></span>
              <p class="notic"></p>
            </dd>
        </dl>

        <dl class="row">
            <dt class="tit">
                <label><em>*</em>配送公司</label>
            </dt>
            <dd class="opt">
                <select name="e_code" id="e_code" class="ui-select">
                    <option value="0">不使用配送公司</option>
                    <?php
                    if($data['express_list']['items']) {
                    foreach ($data['express_list']['items'] as $key => $value) {
                    ?>
                    <option value="<?=$value['express_name']?>"><?=$value['express_name']?></option>
                    <?php }
                    }?>
                </select>
                <span class="err"></span>
                <p class="notic"></p>
            </dd>
        </dl>
    </div>
</form>

<script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/promotion/points/points_order_deliver.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>