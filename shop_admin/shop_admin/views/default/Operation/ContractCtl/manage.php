<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
    <link href="<?= $this->view->css_com ?>/webuploader.css" rel="stylesheet" type="text/css">
</head>
<body>
    <form method="post" id="manage-form" name="settingForm">
        <input type="hidden" name="contract_type_id" id="contract_type_id">

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">消费者保障服务</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
			     <input id="contract_type_name" name="contract_type_name" class="ui-input w200" type="text" />
                        </li>
                    </ul>
                    <p class="notic">项目名称不能为空且不能大于50个字符</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">保证金</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
			     <input id="contract_type_cash" name="contract_type_cash" class="ui-input w200" type="text" />
                        </li>
                    </ul>
                    <p class="notic">保证金不能为空且必须为数字</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">项目图标</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
                            <img id="setting_contract_type_logo" alt="选择图片" src="" width="120px" height="120px" class="image-line" />
                            <div class="image-line" id="setting_image_upload">上传图片<i class="iconfont icon-tupianshangchuan"></i></div>
                            <input id="contract_type_logo"  name="contract_type_logo" type="hidden"/>
                        </li>
                    </ul>
                </dd>
            </dl>
	    <dl class="row">
                <dt class="tit">项目描述</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
			      <textarea name="contract_type_desc" class="ui-input w400" id="contract_type_desc"></textarea>
                        </li>
                    </ul>
                    <p class="notic">项目描述不能为空且小于200个字符</p>
                </dd>
            </dl>
	    <dl class="row">
                <dt class="tit">说明文章链接地址</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
			     <input id="contract_type_url" name="contract_type_url" class="ui-input w200" type="text" />
                        </li>
                    </ul>
                </dd>
            </dl>
	    <dl class="row">
                <dt class="tit">排序</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
			     <input id="contract_type_sort" name="contract_type_sort" class="ui-input w200" type="text" />
                        </li>
                    </ul>
                    <p class="notic">排序应为大于1的正整数</p>
                </dd>
            </dl>
	    <dl class="row">
                <dt class="tit">状态</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
				<div class="onoff" id="contract_type_state">
					<label title="开启" class="cb-enable" for="contract_type_state_enable">开启</label>
					<label title="关闭" class="cb-disable" for="contract_type_state_disabled">关闭</label>
					<input type="radio" value="1" name="contract_type_state" id="contract_type_state_enable"/>
					<input type="radio" value="2" name="contract_type_state" id="contract_type_state_disabled"/>
				</div>
                        </li>
                    </ul>
                </dd>
            </dl>
        </div>
    </form>
    <script type="text/javascript" src="<?=$this->view->js?>/controllers/operation/contract_type_manage.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js_com?>/webuploader.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?=$this->view->js?>/models/upload_image.js" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">

        setting_logo_upload = new UploadImage({
            thumbnailWidth: 240,
            thumbnailHeight: 240,
            imageContainer: '#setting_contract_type_logo',
            uploadButton: '#setting_image_upload',
            inputHidden: '#contract_type_logo'
        });
        </script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>