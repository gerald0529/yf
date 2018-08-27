<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?=$this->view->css_com?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
</head>
<body>
    <form method="post" id="manage-form" name="settingForm">
        <input type="hidden" name="shared_bindings_id" id="shared_bindings_id" value="<?=$data['shared_bindings_id']?>">

        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">应用模板</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
							<span><?=$data['shared_bindings_name']?></span>
                        </li>
                    </ul>
                </dd>
            </dl>          
	    <dl class="row">
                <dt class="tit">是否开启接口</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
						<div class="onoff" id="shared_bindings_statu">
								<label title="开启" class="cb-enable <?=($data['shared_bindings_statu']=='1' ? 'selected' : '')?> " for="shared_bindings_statu_enable">开启</label>
								<label title="关闭" class="cb-disable <?=($data['shared_bindings_statu']=='0' ? 'selected' : '')?>" for="shared_bindings_statu_disabled">关闭</label>
								<input type="radio" value="1" name="shared_bindings_statu" id="shared_bindings_statu_enable" <?=($data['shared_bindings_statu']=='1' ? 'checked' : '')?> />
								<input type="radio" value="0" name="shared_bindings_statu" id="shared_bindings_statu_disabled" <?=($data['shared_bindings_statu']=='0' ? 'checked' : '')?> />
						</div>
                        </li>
                    </ul>
                </dd>
         </dl>
		 <?php if($data['shared_bindings_name'] != '腾讯微博'){?>
		 <dl class="row">
                <dt class="tit">
                    <label>应用标识</label>
                </dt>
                <dd class="opt">
					<textarea id="shared_bindings_appcode" class="tarea" rows="4" style="width:290px;" name="shared_bindings_appcode"><?=$data['shared_bindings_appcode']?></textarea>
                    <p class="notic"></p>
                </dd>
          </dl>	
		 <?php }?>
		 <dl class="row">
                <dt class="tit">
                    <label><em>*</em>应用标识</label>
                </dt>
                <dd class="opt">
					<input id="shared_bindings_appid" name="shared_bindings_appid" value="<?=$data['shared_bindings_appid']?>" class="ui-input w300" type="text"/>
                    <p class="notic"></p>
                </dd>
          </dl>	
		  <dl class="row">
                <dt class="tit">
                    <label><em>*</em>应用密钥</label>
                </dt>
                <dd class="opt">
					<input id="shared_bindings_key" name="shared_bindings_key" value="<?=$data['shared_bindings_key']?>" class="ui-input w300" type="text"/>
                    <p class="notic"></p>
                </dd>
          </dl>	
    </form>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/user/shared/shared_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>