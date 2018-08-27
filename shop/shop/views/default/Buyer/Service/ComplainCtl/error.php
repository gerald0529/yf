<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<link rel="stylesheet" type="text/css" href="./shop/static/default/css/404.css" media="screen" />
<div id="da-wrapper" class="fluid">
    <div id="da-content">
        <div class="da-container clearfix">
            <div id="da-error-wrapper">
                <div id="da-error-pin"></div>
                <div id="da-error-code">
                    <span><?=__('错误')?></span> </div>
                <h1 class="da-error-heading"><?=isset($_REQUEST['msg']) ? $_REQUEST['msg'] : __('抱歉，您已经投诉过该商品！')?></h1>
<!--                <p> <a href="javascript:history.go(-1)">--><?//=__('点击返回')?><!--</a></p>-->
                <p> <a href="<?= Yf_Registry::get('url') ?>?ctl=Buyer_Order&met=physical"><?=__('点击返回')?></a></p>
            </div>
        </div>
    </div>
</div>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>

