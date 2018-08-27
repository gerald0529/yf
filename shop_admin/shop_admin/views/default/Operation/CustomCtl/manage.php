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
	<input type="hidden" name="custom_service_type_id" id="custom_service_type_id">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">咨询类别名称</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
			     <input id="custom_service_type_name" name="custom_service_type_name" class="ui-input w200" type="text" />
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">咨询类别排序</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
			     <input id="custom_service_type_sort" name="custom_service_type_sort" class="ui-input w200" type="text" />
                        </li>
                    </ul>
                    <p class="notic">类型按由小到大顺序排列</p>
                </dd>
            </dl>
	    <dl class="row">
                <dt class="tit">咨询类别备注</dt>
                <dd class="opt">
                    <ul class="nofloat">
                        <li>
			    <textarea id="custom_service_type_desc" name="custom_service_type_desc" class="ui-input w400"></textarea>
                        </li>
                    </ul>
                </dd>
            </dl>
        </div>
    </form>
    <script type="text/javascript" src="<?=$this->view->js?>/controllers/operation/custom_type_manage.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>
