<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';
?>
<link href="<?= $this->view->css ?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<style>
body{background: #fff;}
</style>
</head>
<body>
<form method="post" name="manage-form" id="manage-form" action="<?= Yf_Registry::get('url') ?>?act=goods&amp;op=goods_lockup">
    <input type="hidden" name="form_submit" value="ok">
    <input type="hidden" name="common_id_input">

    <div class="ncap-form-default">
        <dl class="row">
            <dt class="tit">
                <label class="cat_other_name" for="cat_other_name"><em>*</em>分类别名:</label>
            </dt>
            <dd class="opt">
                <input type="text" maxlength="20" value="" name="cat_other_name" id="cat_other_name" class="ui-input ui-input-ph">
                <span class="err"></span>
                <p class="notic"></p>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label>分类图片</label>
            </dt>
            <dd class="opt">
                <img id="cat_image" name="setting[cat_logo]" alt="选择图片" src="./shop_admin/static/common/images/image.png"  class="image-line" />
                <div class="image-line" style="margin-left: 10px;" id="cat_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>
                <input id="cat_logo" name="setting[cat_logo]" value="" class="ui-input w400" type="hidden"/>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label>推荐分类:</label>
            </dt>
            <dd class="opt">
                <?php if (!empty($data['cat'])): ?>
    <?php
    foreach ($data['cat'] as $key_cat => $value_cat):
        if (!empty($value_cat['details'])):
            ?>
            <dl>
                <dt><b><?php print($value_cat['cat_name']); ?></b></dt>
            </dl>

            <?php
            foreach ($value_cat['details'] as $key => $value)
            {
                ?><div style="width: 180px;float: left">
                <label>

                <input type="checkbox" name="recommend_cat" value="<?php print($key);?>"
                       id="recommend_<?php print($key); ?>"><?php print($value);?>

            <?php
            }
            ?>
            </label>
            </div>
        <?php
        endif;
    endforeach;
    ?>
<?php endif; ?>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label>推荐品牌:</label>
            </dt>
            <dd class="opt">
                <?php
if (!empty($data['brand'])):
    foreach ($data['brand'] as $k_brand => $v_brand):
        ?>
        <div style="width: 180px;float:left">
        <label>

            <input type="checkbox" value="<?php print($v_brand['brand_id']);?>" name="brand_value"
                   id="brand_<?php print($k_brand); ?>"><?php print($v_brand['brand_name']); ?>
        </label></div>
    <?php
    endforeach;
endif;
?>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label>广告图1:</label>
            </dt>
			<dd class="opt">
                <img id="adv_image" name="setting[adv_logo]" alt="选择图片" src="./shop_admin/static/common/images/image.png" class="image-line" />
                <div class="image-line" style="margin-left: 10px;" id="adv_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>
                <input id="adv_logo" name="setting[adv_logo]" value="" class="ui-input w400" type="hidden"/>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label>广告图2:</label>
            </dt>
			<dd class="opt">
                <img id="advs_image" name="setting[advs_logo]" alt="选择图片" src="./shop_admin/static/common/images/image.png" class="image-line" />
                <div class="image-line" style="margin-left: 10px;" id="advs_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>
                <input id="advs_logo" name="setting[advs_logo]" value="" class="ui-input w400" type="hidden"/>
            </dd>
        </dl>
    </div>
</form>
<script>
    //图片上传
    $(function(){

        setting_logo_upload = new UploadImage({
            thumbnailWidth: 240,
            thumbnailHeight: 160,
            imageContainer: '#adv_image',
            uploadButton: '#adv_upload',
            inputHidden: '#adv_logo'
        });

        cat_logo_upload = new UploadImage({
            thumbnailWidth: 200,
            thumbnailHeight: 160,
            imageContainer: '#cat_image',
            uploadButton: '#cat_upload',
            inputHidden: '#cat_logo'
        });

        buyer_logo_upload = new UploadImage({
            thumbnailWidth: 240,
            thumbnailHeight: 160,
            imageContainer: '#advs_image',
            uploadButton: '#advs_upload',
            inputHidden: '#advs_logo'
        });
    })
</script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/models/upload_image.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/controllers/goods/nav_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>