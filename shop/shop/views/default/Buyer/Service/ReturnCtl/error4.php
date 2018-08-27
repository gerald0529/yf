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
                <h1 class="da-error-heading"><?=isset($_REQUEST['msg']) ? $_REQUEST['msg'] : __('白条支付的订单，请联系商家，进行线下退货或退款！')?></h1>
                <p> <a onclick="history.go(-1);"><?=__('点击返回')?></a></p>
            </div>
        </div>
    </div>
</div>
<?php
include $this->view->getTplPath() . '/' . 'buyer_footer.php';
?>
