<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>

<style>
    .webuploader-pick{ padding:1px; }
    
</style>
</head>
<body>
<div class="">
    <form method="post" enctype="multipart/form-data" id="user-edit-form" name="form">
        <input type="hidden" name="user_id" value="<?=($data['user_id'])?>"/>

        <div class="ncap-form-default">
			<dl class="row">
                <dt class="tit">
                    <label>会员</label>
                </dt>
                <dd class="opt">
					<input id="user_name" name="user_name"  readonly value="<?=($data['user_name'])?>" class="ui-input w400" type="text"/>
                    <p class="notic"> 会员用户名不可修改。</p>
                </dd>
            </dl>
			<!--<dl class="row">
                <dt class="tit">
                    <label>密码</label>
                </dt>
                <dd class="opt">
					<input id="user_passwd" name="user_passwd" value="" class="ui-input w400" type="password"/>
                    <p class="notic"> 留空表示不修改密码。</p>
                </dd>
            </dl>-->
			<dl class="row">
                <dt class="tit">
                    <label><em>*</em>电子邮箱</label>
                </dt>
                <dd class="opt">
					<input id="user_email" name="user_email" readonly value="<?=($data['user_email'])?>" class="ui-input w400" type="text"/>
                    <p class="notic"> 请输入常用的邮箱，将用来找回密码、接受订单通知等。</p>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">
                    <label>真实姓名</label>
                </dt>
                <dd class="opt">
					<input id="user_realname" readonly name="user_realname" value="<?=($data['user_realname'])?>" class="ui-input w400" type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>
			<dl class="row">
				<dt class="tit">
					  <label>性别</label>
				</dt>
				<dd class="opt">
				  <input type="radio" id="user_sex2" name="user_sex" value="2" <?=($data['user_sex']==2 ? 'checked' : '')?>>
				  <label for="user_sex2">保密</label>
				  <input type="radio" id="user_sex0" name="user_sex" value="0" <?=($data['user_sex']==0 ? 'checked' : '')?>>
				  <label for="user_sex0">女</label>
				  <input type="radio" id="user_sex1" name="user_sex" value="1" <?=($data['user_sex']==1 ? 'checked' : '')?>>
				  <label for="user_sex1">男</label>
				  <span class="err"></span>
				</dd>
			</dl>
			<dl class="row">
                <dt class="tit">
                    <label>QQ</label>
                </dt>
                <dd class="opt">
					<input id="user_qq" name="user_qq" readonly value="<?=($data['user_qq'])?>" class="ui-input w400" type="text"/>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>头像</label>
                </dt>
                <dd class="opt">
                    <img id="user_logo_image" name="user_logo" alt="选择图片" src="<?=($data['user_logo'])?>" width="120px" height="120px" />

                    <div class="image-line upload-image"  id="user_logo_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>

                    <input id="user_logo"  name="user_logo" value="<?=($data['user_logo'])?>" class="ui-input w400" type="hidden"/>
                    <div class="notic">默认会员头像图片请使用120*120像素jpg/gif/png格式的图片</div>
                </dd>
            </dl>
			<!--<dl class="row">
				<dt class="tit">
				<label>举报商品</label>
				</dt>
				<dd class="opt">
				  <div class="onoff">
					<label title="允许" class="cb-enable <?=($data['user_report']==1 ? 'selected' : '')?> " for="user_report_enable">允许</label>
					<label title="禁止" class="cb-disable <?=($data['user_report']==0 ? 'selected' : '')?>" for="user_report_disabled">禁止</label>
					<input type="radio" value="1" name="user_report" id="user_report_enable" <?=($data['user_report']==1 ? 'checked' : '')?> />
					<input type="radio" value="0" name="user_report" id="user_report_disabled" <?=($data['user_report']==0 ? 'checked' : '')?> />
				  </div>
				  <p class="notic">如果禁止该项则会员不能在商品详情页面进行举报。</p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
				<label>允许购买商品</label>
				</dt>
				<dd class="opt">
				  <div class="onoff">
					<label title="允许" class="cb-enable <?=($data['user_buy']==1 ? 'selected' : '')?> " for="user_buy_enable">允许</label>
					<label title="禁止" class="cb-disable <?=($data['user_buy']==0 ? 'selected' : '')?>" for="user_buy_disabled">禁止</label>
					<input type="radio" value="1" name="user_buy" id="user_buy_enable" <?=($data['user_buy']==1 ? 'checked' : '')?> />
					<input type="radio" value="0" name="user_buy" id="user_buy_disabled" <?=($data['user_buy']==0 ? 'checked' : '')?> />
				  </div>
				  <p class="notic">如果禁止该项则会员不能在前台进行下单操作。</p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
				<label>允许发表言论</label>
				</dt>
				<dd class="opt">
				  <div class="onoff">
					<label title="允许" class="cb-enable <?=($data['user_talk']==1 ? 'selected' : '')?> " for="user_talk_enable">允许</label>
					<label title="禁止" class="cb-disable <?=($data['user_talk']==0 ? 'selected' : '')?>" for="user_talk_disabled">禁止</label>
					<input type="radio" value="1" name="user_talk" id="user_talk_enable" <?=($data['user_talk']==1 ? 'checked' : '')?> />
					<input type="radio" value="0" name="user_talk" id="user_talk_disabled" <?=($data['user_talk']==0 ? 'checked' : '')?> />
				  </div>
				  <p class="notic">如果禁止该项则会员不能在前台进行下单操作。</p>
				</dd>
			</dl>-->
			<dl class="row">
				<dt class="tit">
				<label>允许会员登录</label>
				</dt>
				<dd class="opt">
				  <div class="onoff">
					<label title="允许" class="cb-enable <?=($data['user_delete']==0 ? 'selected' : '')?> " for="user_delete_enable">允许</label>
					<label title="禁止" class="cb-disable <?=($data['user_delete']==1 ? 'selected' : '')?>" for="user_delete_disabled">禁止</label>
					<input type="radio" value="0" name="user_delete" id="user_delete_enable" <?=($data['user_delete']==0 ? 'checked' : '')?> />
					<input type="radio" value="1" name="user_delete" id="user_delete_disabled" <?=($data['user_delete']==1 ? 'checked' : '')?> />
				  </div>
				  <p class="notic">如果禁止该项则会员不能在前台登录。</p>
				</dd>
			</dl>
			<dl class="row">
				<dt class="tit">
				  <label>积分</label>
				</dt>
				<dd class="opt"><strong class="red"><?=$data['user_points'];?></strong>&nbsp;积分 </dd>
			</dl>
			<dl class="row">
				<dt class="tit">
				  <label>经验值</label>
				</dt>
				<dd class="opt"><strong class="red"><?=$data['user_growth'];?></strong>&nbsp;经验点 </dd>
			</dl>
			<dl class="row">
				<dt class="tit">
				  <label>可用预存款</label>
				</dt>
				<dd class="opt"><strong class="red"><?=$data['user_cash'];?></strong>&nbsp;元 </dd>
			</dl>
			<dl class="row">
				<dt class="tit">
				  <label>冻结预存款</label>
				</dt>
				<dd class="opt"><strong class="red"><?=$data['user_freeze_cash'];?></strong>&nbsp;元 </dd>
			</dl>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/models/upload_image.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/user/info/info.js" charset="utf-8"></script>
<script>

	$(function(){

		var agent = navigator.userAgent.toLowerCase();

		if ( agent.indexOf("msie") > -1 && (version = agent.match(/msie [\d]/), ( version == "msie 8" || version == "msie 9" )) ) {
			setting_logo_upload = new UploadImage({
				thumbnailWidth: 120,
				thumbnailHeight: 120,
				imageContainer: '#user_logo_image',
				uploadButton: '#user_logo_upload',
				inputHidden: '#user_logo'
			});
		} else {
			$('#user_logo_upload').on('click', function () {
				$.dialog({
					title: '图片裁剪',
					content: "url: <?= Yf_Registry::get('url') ?>?ctl=Index&met=cropperImage&typ=e",
					data: { SHOP_URL: SHOP_URL, width: 120, height: 120, callback: callback },    // 需要截取图片的宽高比例
					width: '800px',
					height:$(window).height()*0.9,
					lock: true
				})
			});

			function callback ( respone , api ) {
				$('#user_logo_image').attr('src', respone.url);
				$('#user_logo').attr('value', respone.url);
				api.close();
			}
		}

	})
    //图片上传
   /*  $(function(){

        setting_logo_upload = new UploadImage({
            thumbnailWidth: 100,
            thumbnailHeight: 100,
            imageContainer: '#user_logo_image',
            uploadButton: '#user_logo_upload',
            inputHidden: '#user_logo'
        }); 

       
    })*/
</script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>