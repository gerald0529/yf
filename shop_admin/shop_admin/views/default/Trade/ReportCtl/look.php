<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>


<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/complain.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="wrapper page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>举报详情</h3>
            </div>
        </div>
    </div>

    <div class="ncap-order-style">
        <div class="ncap-form-default">
            <div class="title">
                <h3>举报信息</h3>
            </div>
            <dl class="row">
                <dt class="tit">举报商品：</dt>
                <dd class="opt"><?=($data['goods_name'])?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">举报类型：</dt>
                <dd class="opt"><?=($data['report_type_name'])?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">举报主题：</dt>
                <dd class="opt"><?=($data['report_subject_name'])?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">举报备注：</dt>
                <dd class="opt"><?=($data['report_message'])?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">举报人：</dt>
                <dd class="opt"><?=($data['user_account'])?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">被举报店铺：</dt>
                <dd class="opt"><?=($data['shop_name'])?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">举报证据：</dt>
                <dd class="opt"> 
                <?php if($data['pic']){
                    foreach ($data['pic'] as $key => $value) {?>
                     <img width="100" src="<?=($value)?>">
                <?php }}else{?>
                    暂无图片
                <?php }?> 
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">举报时间：</dt>
                <dd class="opt"><?=($data['report_date'])?></dd>
            </dl>
            <?php if($data['state_etext']=='done'){?>
            <dl class="row">
                <dt class="tit">处理结果：</dt>
                <dd class="opt"><?=($data['handle_text'])?></dd>
            </dl>
            <dl class="row">
                <dt class="tit">处理备注：</dt>
                <dd class="opt"><?=($data['report_handle_message'])?></dd>
            </dl>
            <?php } ?>
        </div>
</div>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>