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
<div class="manage-wrap">
    <div class="ncap-form-default" style="padding-top:0px;">
    <form id="voucher_t_info" action="" method="post">
    <input type="hidden" name="voucher_temp_id" id="voucher_t_id" value="<?=($data['voucher_t_id'])?>">
        <dl class="row">
            <dt class="tit">店铺名称：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" value="<?=($data['shop_name'])?>" readonly=""></dd>
        </dl>
        <dl class="row">
            <dt class="tit">所属店铺分类：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" value="<?=($data['voucher_t_cat_name'])?>" readonly=""></dd>
        </dl>
        <dl class="row">
            <dt class="tit">代金券名称：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" value="<?=($data['voucher_t_title'])?>" readonly=""></dd>
        </dl>
        <dl class="row">
            <dt class="tit">领取方式：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" value="<?=($data['voucher_t_access_method_label'])?>" readonly=""></dd>
        </dl>
        <dl class="row">
            <dt class="tit">有效期：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" value="<?=($data['voucher_t_end_date'])?>" readonly=""></dd>
        </dl>
         <dl class="row">
            <dt class="tit">面额：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" value="<?=($data['voucher_t_price'])?>" readonly=""></dd>
        </dl>
         <dl class="row">
            <dt class="tit">可发放总数：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" value="<?=($data['voucher_t_total'])?>" readonly=""></dd>
        </dl>
         <dl class="row">
            <dt class="tit">每人限领：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" value="<?=($data['voucher_t_eachlimit'])?>" readonly=""></dd>
        </dl>
         <dl class="row">
            <dt class="tit">消费金额：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" value="<?=($data['voucher_t_limit'])?>" readonly=""></dd>
        </dl>
         <dl class="row">
            <dt class="tit">会员级别：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" value="<?=($data['voucher_t_user_grade_limit_con'])?>" readonly=""></dd>
        </dl>
        <dl class="row">
            <dt class="tit">代金券描述：</dt>
            <dd class="opt">
                <textarea rows="6" readonly="readonly" class="readonly tarea valid" style="margin-top: 0px; margin-bottom: 0px; height: 75px;"><?=($data['voucher_t_desc'])?></textarea>
            </dd>
        </dl>
        <dl class="row">
             <dt class="tit">代金券图片：</dt>
             <dd class="opt"><img width="80" src="<?=($data['voucher_t_customimg'])?>"></dd>
        </dl>
        <!--由原先的“最后修改时间” 修改为 “添加时间”-->
        <dl class="row">
            <dt class="tit">添加时间：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" value="<?=($data['voucher_t_update_date'])?>" readonly=""></dd>
        </dl>
        <dl class="row">
            <dt class="tit">已领取：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" value="<?=($data['voucher_t_giveout'])?>" readonly=""></dd>
        </dl>
        <dl class="row">
            <dt class="tit">已使用：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" value="<?=($data['voucher_t_used'])?>" readonly=""></dd>
        </dl>
        <dl class="row">
            <dt class="tit">兑换所需积分：</dt>
            <dd class="opt"><input type="text" class="readonly txt valid" name="voucher_t_points" value="<?=($data['voucher_t_points'])?>" readonly=""></dd>
        </dl>
        <dl class="row">
            <dt class="tit">状态：</dt>
            <dd class="opt">
                <label for="tstate_1"><input type="radio" value="1" class="v_t_state" name="voucher_t_state"  <?=($data['voucher_t_state']==1 ? 'checked' : '')?>>有效</label>
                <label for="tstate_2"><input type="radio" value="2" class="v_t_state" name="voucher_t_state"  <?=($data['voucher_t_state']==2 ? 'checked' : '')?>>失效</label>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">是否推荐：</dt>
            <dd class="opt">
                <div class="onoff">
					<label title="是" class="cb-enable <?=($data['voucher_t_recommend']==1 ? 'selected' : '')?> " for="voucher_t_recommend_enable">是</label>
					<label title="否" class="cb-disable <?=($data['voucher_t_recommend']==0 ? 'selected' : '')?>" for="voucher_t_recommend_disabled">否</label>
					<input type="radio" value="1" name="voucher_t_recommend" id="voucher_t_recommend_enable" <?=($data['voucher_t_recommend']==1 ? 'checked' : '')?> />
					<input type="radio" value="0" name="voucher_t_recommend" id="voucher_t_recommend_disabled" <?=($data['voucher_t_recommend']==0 ? 'checked' : '')?> />
                </div>
                (此功能只针对于积分兑换领取方式)
            </dd>

        </dl>

        <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn" id="submitBtn">确认提交</a></div>
    </form>
    </div>
</div>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/promotion/voucher/voucher_temp_info_validator.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>