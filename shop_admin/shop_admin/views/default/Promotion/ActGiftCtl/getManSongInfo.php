<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
<div id="manage-wrap" class="manage-wrap">
    <div class="ncap-form-default">
        <dl class="row">
            <dt class="tit">活动名称</dt>
            <dd class="opt" id="mansong_name"><?=$data['mansong_name']?></dd>
        </dl>
        <dl class="row">
             <dt class="tit">活动店铺</dt>
             <dd class="opt" id="store_name"><?=$data['shop_name']?></dd>
        </dl>
        <dl class="row">
            <dt class="tit">活动时间段</dt>
            <dd class="opt"><span id="start_time"><?=$data['mansong_start_time']?></span> ~ <span id="end_time"><?=$data['mansong_end_time']?></span></dd>
        </dl>
        <dl class="row">
            <dt class="tit"> 活动规则 </dt>
            <dd class="opt">
                <ul class="promotion-ms">
                    <?php foreach($data['rule'] as $key=>$rule){ ?>
                    <li>
                        <span> 单笔订单满 <strong><?=$rule['rule_price']?></strong> 元 </span>
                        <span> 立减现金 <strong><?=$rule['rule_discount']?></strong> 元 </span>
                        <span> 赠送礼品 <a href="<?=Yf_Registry::get('shop_api_url')?>?ctl=Goods_Goods&met=goods&gid=<?=$rule['goods_id']?>" title="<?=$rule['goods_name']?>" target="_blank"><img style="width:40px;height:40px;vertical-align: baseline;" src="<?=$rule['goods_image']?>"></a> </span>
                    </li>
                    <?php } ?>
                </ul>
            </dd>
        </dl>
    </div>
</div>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>