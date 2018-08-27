<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<style>
.manage-wrap{margin: 20px auto 10px;width:450px;}
input[type="text"]{width:250px}
span.lab{display:inline-block;margin-right:10px;vertical-align: middle;}
span.lab label{margin-left:3px;}
</style>
</head>
<body>

<div class="ncap-form-default">
	<form id="manage-form" action="#">
	    <input type="hidden" name="groupbuy_id" id="groupbuy_id" value="<?=$data['groupbuy_id']?>" >
	    <dl class="row">
            <dt class="tit">
                <label class="cat_name" for="cat_name">团购名称</label>
            </dt>
            <dd class="opt">
                <span id="groupbuy_name"><?=$data['groupbuy_name']?></span>
            </dd>
        </dl>

        <dl class="row">
            <dt class="tit">
                <label class="cat_name" for="cat_name">团购商品名称</label>
            </dt>
            <dd class="opt">
                <span id="goods_name"><?=$data['goods_name']?></span>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label class="cat_name" for="cat_name">推荐位图片</label>
            </dt>
            <dd class="opt">
                <span><img src="<?=$data['groupbuy_image_rec']?>" width="200"></span>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label class="groupbuy_state" for="groupbuy_state">团购状态</label>
            </dt>
            <dd class="opt">
                <span class="lab"><input type="radio" value="1" name="groupbuy_state" <?=($data['groupbuy_state']==1 ? 'checked' : '')?> /><label>审核中</label></span>
                <span class="lab"><input type="radio" value="2" name="groupbuy_state" <?=($data['groupbuy_state']==2 ? 'checked' : '')?> /><label>正常</label></span>
                <span class="lab"><input type="radio" value="3" name="groupbuy_state" <?=($data['groupbuy_state']==3 ? 'checked' : '')?> /><label>结束</label></span>
                <span class="lab"><input type="radio" value="4" name="groupbuy_state" <?=($data['groupbuy_state']==4 ? 'checked' : '')?> /><label>审核失败</label></span>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label class="groupbuy_cat_sort" for="groupbuy_cat_sort">推荐类型</label>
            </dt>
            <dd class="opt">
            <?php if($data['groupbuy_count'] >= 5){ ?>
                <span class="lab"><input type="radio" value="0" name="groupbuy_recommend" <?=($data['groupbuy_recommend']==0 ? 'checked' : '')?> /><label>不推荐</label></span>
            <?php }?>
                <span class="lab"><input type="radio" value="1" name="groupbuy_recommend" <?=($data['groupbuy_recommend']==1 ? 'checked' : '')?> /><label>首页推荐</label></span>
            <?php if($data['groupbuy_count'] >= 5){ ?>
                <span class="lab"><input type="radio" value="2" name="groupbuy_recommend" <?=($data['groupbuy_recommend']==2 ? 'checked' : '')?> /><label>大图推荐</label></span>
            <?php }?>
            </dd>
        </dl>

        <!--<dl class="row">
            <dt class="tit">
                <label class="groupbuy_cat_sort" for="groupbuy_cat_sort">首页推荐</label>
            </dt>
            <dd class="opt">
                 <div class="onoff">
                        <label title="是" class="cb-enable <?/*=($data['groupbuy_recommend'] == 1 ? 'selected' : '')*/?> " for="recommend_enable">是</label>
                        <label title="否" class="cb-disable <?/*=($data['groupbuy_recommend'] == 0 ? 'selected' : '')*/?>" for="recommend_disabled">否</label>
                        <input type="radio" value="1" name="groupbuy_recommend" id="recommend_enable" <?/*=($data['groupbuy_recommend']==1 ? 'checked' : '')*/?> />
                        <input type="radio" value="0" name="groupbuy_recommend" id="recommend_disabled" <?/*=($data['groupbuy_recommend']==0 ? 'checked' : '')*/?> />
			    </div>
            </dd>
        </dl>-->
	</form>
</div>

<script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/promotion/groupbuy/groupbuy_manage.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>