<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'seller_header.php';
?>

<div class="tabmenu">
    <ul>
        <li ><a href="./index.php?ctl=Seller_Shop_Info&met=info&typ=e&act=category"><?=__('经营类目')?></a></li>
        <?php if($shop['shop_self_support']=="false"){ ?> 
        <li><a href="./index.php?ctl=Seller_Shop_Info&met=info&typ=e&act=info"><?=__('店铺信息')?></a></li>
        <li ><a href="./index.php?ctl=Seller_Shop_Info&met=info&typ=e&act=renew"><?=__('续签申请')?></a></li>
        <?php } ?>
        <li class="active bbc_seller_bg"><a href="./index.php?ctl=Seller_Shop_Info&met=info&typ=e&act=createQrCode"><?=__('收款码')?></a></li>
    </ul>
</div>

<form method="post" id="form">
    <div class="form-style">
        <dl>
            <dt><?= __('收款码：') ?></dt>
            <?php echo Yf_Registry::get('paycenter_api_url').'?ctl=Qr&met=index&typ=e&shopid='.$shop_id;?>
            <dd>
                <img src="<?=Yf_Registry::get('base_url')?>/shop/api/qrcode.php?data=<?php echo urlencode(Yf_Registry::get('paycenter_api_url').'?ctl=Qr&met=index&typ=e&shopid='.$shop_id);?>">
            </dd>
        </dl>
    </div>
</form>

<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/upload/upload_image.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'seller_footer.php';
?>

