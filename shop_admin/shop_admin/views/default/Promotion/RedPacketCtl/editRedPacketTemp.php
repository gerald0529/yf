<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>

<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/complain.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

</head>
<style>
.ncap-form-default{padding:0px !important;}
</style>
<body>
	<form id="manage-form" action="#">
	<input type="hidden" name="redpacket_t_id" id="redpacket_t_id" value="<?=($data['redpacket_t_id'])?>">
		<div class="ncap-form-default">
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_title">红包名称：</label>
            </dt>
            <dd class="opt">
                <?=$data['redpacket_t_title']?>
            </dd>
        </dl>
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_sdate">有效期：</label>
            </dt>
            <dd class="opt">
                <?=$data['redpacket_t_start_date']?> 至 <?=$data['redpacket_t_end_date']?>
            </dd>
        </dl>
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_price">面额：</label>
            </dt>
            <dd class="opt">
                <?=$data['redpacket_t_price']?>
            </dd>
        </dl>

        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_total">可发放总数：</label>
            </dt>
            <dd class="opt">
                <?=$data['redpacket_t_total']==0?'不限':$data['redpacket_t_total']?>
            </dd>
        </dl>
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_eachlimit">每人限领：</label>
            </dt>
            <dd class="opt">
                <?=$data['redpacket_t_eachlimit']?>
            </dd>
        </dl>
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_orderlimit">消费限额：</label>
            </dt>
            <dd class="opt">
                <?=$data['redpacket_t_orderlimit']?>
            </dd>
        </dl>
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_mgradelimit">会员级别：</label>
            </dt>
            <dd class="opt">
               <?=$data['redpacket_t_user_grade_label']?>
            </dd>
        </dl>
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_desc">红包描述：</label>
            </dt>
            <dd class="opt">
                <textarea id="redpacket_t_desc" name="redpacket_t_desc" class="w300"><?=$data['redpacket_t_desc']?></textarea>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label>红包图片：</label>
            </dt>
            <dd class="opt">
                <img id="redpacket_t_img_review"  alt="选择图片" src="<?=$data['redpacket_t_img']?>"  class="image-line" width="200"/>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">状态：</dt>
            <dd class="opt">
                <label for="tstate_1"><input type="radio" value="1" class="v_t_state" name="redpacket_t_state"  <?=($data['redpacket_t_state']==1 ? 'checked' : '')?>>有效</label>
                <label for="tstate_2"><input type="radio" value="2" class="v_t_state" name="redpacket_t_state"  <?=($data['redpacket_t_state']==2 ? 'checked' : '')?>>失效</label>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">是否推荐：</dt>
            <dd class="opt">
                <div class="onoff">
					<label title="是" class="cb-enable <?=($data['redpacket_t_recommend']==1 ? 'selected' : '')?> " for="redpacket_t_recommend_enable">是</label>
					<label title="否" class="cb-disable <?=($data['redpacket_t_recommend']==0 ? 'selected' : '')?>" for="redpacket_t_recommend_disabled">否</label>
					<input type="radio" value="1" name="redpacket_t_recommend" id="redpacket_t_recommend_enable" <?=($data['redpacket_t_recommend']==1 ? 'checked' : '')?> />
					<input type="radio" value="0" name="redpacket_t_recommend" id="redpacket_t_recommend_disabled" <?=($data['redpacket_t_recommend']==0 ? 'checked' : '')?> />
                </div>
            </dd>
        </dl>
    </div>
    <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn" id="submitBtn">确认提交</a></div>
	</form>

<script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/promotion/redpacket/redpacket_manage.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>