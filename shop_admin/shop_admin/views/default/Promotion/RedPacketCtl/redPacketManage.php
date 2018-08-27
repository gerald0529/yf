<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>

<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link href="<?=$this->view->css?>/complain.css" rel="stylesheet" type="text/css">

<link href="<?=$this->view->css_com ?>/jquery/plugins/datepicker/dateTimePicker.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
<script src="<?= $this->view->js_com ?>/plugins/jquery.datetimepicker.js"></script>
</head>
<style>
.ncap-form-default{padding:0px !important;}
</style>
<body>
	<form id="manage-form" action="#">
		<div class="ncap-form-default">
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_title"><em>*</em>红包名称</label>
            </dt>
            <dd class="opt">
                <input type="text"  class="input-txt"  value="" name="redpacket_t_title" id="redpacket_t_title" class="input-txt">
                <span class="err"></span>
                <p class="notic">模版名称不能为空且不能大于30个字符</p>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">红包类型：</dt>
            <dd class="opt">
                <div>
					<input style="display: none;" type="radio" value="2" name="redpacket_t_type" id="redpacket_t_type_disabled" <?='checked'?> />
					普通红包
                </div>
            </dd>
        </dl>
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_sdate"><em>*</em>有效期</label>
            </dt>
            <dd class="opt">
                <input type="text" class="ui-input" id="redpacket_t_start_date" name="redpacket_t_start_date" data-dp="1" class="s-input-txt" readonly="readonly"> 至
                <input type="text" class="ui-input" id="redpacket_t_end_date" name="redpacket_t_end_date" data-dp="1" class="s-input-txt" readonly="readonly">
                <span class="err"></span>
                <p class="notic">会员领取红包后，将在该有效期内使用红包</p>
            </dd>
        </dl>
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_price"><em>*</em>面额</label>
            </dt>
            <dd class="opt">
                <input type="text" class="ui-input"  name="redpacket_t_price" id="redpacket_t_price" value="">&nbsp;&nbsp;元<span class="err"></span>
                <p class="notic">面额应为大于1的整数</p>
            </dd>
        </dl>

        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_total"><em>*</em>可发放总数</label>
            </dt>
            <dd class="opt">
                <input type="text" class="ui-input"  id="redpacket_t_total"  name="redpacket_t_total" value="0">
                <span class="err"></span>
                <p class="notic">填0不限制发放总数</p>
            </dd>
        </dl>
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_eachlimit"><em>*</em>每人限领</label>
            </dt>
            <dd class="opt">
                <select name="redpacket_t_eachlimit" id="redpacket_t_eachlimit">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
                <span class="err"></span>
                <p class="notic"></p>
            </dd>
        </dl>
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_orderlimit"><em>*</em>消费限额</label>
            </dt>
            <dd class="opt">
                <input type="text" class="ui-input"  value="" name="redpacket_t_orderlimit" id="redpacket_t_orderlimit">&nbsp;&nbsp;元          <span class="err"></span>
                <p class="notic">红包使用限额必须大于红包面额</p>
            </dd>
        </dl>
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_mgradelimit"><em>*</em>会员级别</label>
            </dt>
            <dd class="opt">
                <select name="redpacket_t_user_grade_limit" id="redpacket_t_user_grade_limit">
                    <?php if($data){
                        foreach($data as $key=>$grade){
                    ?>
                        <option value="<?=$grade['user_grade_id']?>"><?=$grade['user_grade_name']?></option>
                    <?php } } ?>
                </select>
                <span class="err"></span>
                <p class="notic">当会员兑换红包时，需要达到该级别或者以上级别后才能兑换领取</p>
            </dd>
        </dl>
        <dl class="row row-item">
            <dt class="tit">
                <label for="redpacket_t_desc"><em>*</em>红包描述</label>
            </dt>
            <dd class="opt">
                <textarea id="redpacket_t_desc" name="redpacket_t_desc" class="w300"></textarea>
                <span class="err"></span>
                <p class="notic">模版描述不能为空且小于200个字符</p>
            </dd>
        </dl>
        <dl class="row">
            <dt class="tit">
                <label><em>*</em>红包图片</label>
            </dt>
            <dd class="opt">
                <img id="redpacket_t_img_review"  alt="选择图片" src="./shop_admin/static/common/images/image.png"  class="image-line" />
                <div class="image-line"  id="redpacket_t_img_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>
                <input id="redpacket_t_img" name="redpacket_t_img" value="" class="ui-input w400" type="hidden"/>
                <p class="notic">上传分类图片时，请上传宽度不低于220像素，高度不低于220像素的图片</p>
            </dd>
        </dl>
    </div>
	</form>

<script type="text/javascript" src="<?= $this->view->js_com ?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js ?>/models/upload_image.js" charset="utf-8"></script>
<script>
    $(function(){
        //红包图片上传
        $(function(){
            fishing_shop_logo_upload = new UploadImage({
                thumbnailWidth: 220,
                thumbnailHeight: 220,
                imageContainer: '#redpacket_t_img_review',
                uploadButton: '#redpacket_t_img_upload',
                inputHidden: '#redpacket_t_img',
                callback:function(e)
                {
                    $("#redpacket_t_img").isValid();
                }
            });
        })

    })
</script>
<script src="<?= Yf_Registry::get('base_url') ?>/shop_admin/static/default/js/controllers/promotion/redpacket/redpacket_temp_manage.js"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>