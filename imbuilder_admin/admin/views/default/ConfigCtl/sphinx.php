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
<div class="wrapper page">
	<div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>基础设置&nbsp;</h3>
                <h5>商城设置-Sphinx搜索引擎设置</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=site&config_type%5B%5D=site"><span>站点设置</span></a></li>

                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=upload&config_type%5B%5D=upload"><span>上传设置</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=msgAccount&config_type%5B%5D=email&config_type%5B%5D=sms">邮件设置</a></li>
<!--                <li><a href="--><?//= Yf_Registry::get('url') ?><!--?ctl=Config&met=im&config_type%5B%5D=im">IM设置</a></li>-->
<!--                <li><a href="--><?//= Yf_Registry::get('url') ?><!--?ctl=Config&met=district"><span>地区设置</span></a></li>-->
                <li><a class="current" ><span>搜索引擎</span></a></li>
                <li><a href="<?= Yf_Registry::get('url') ?>?ctl=Base_FilterKeyword&met=index"><span>敏感词设置</span></a></li>
<!--                <li><a href="--><?//= Yf_Registry::get('url') ?><!--?ctl=Config&met=licence&config_type%5B%5D=licence"><span>授权证书</span></a></li>-->
            </ul>
        </div>
    </div>

    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
        <li>Sphinx 及 Scws 扩展 如果没有安装, 则开启Sphinx也不起作用! </li>
		<?=($data['sphinx_ext']==1 ? '' : '<li class="red">Sphinx 扩展尚未安装</li>')?>
		<?=($data['scws_ext']==1 ? '' : '<li class="red">Scws  扩展尚未安装</li>')?>
        </ul>
    </div>

    <form method="post" enctype="multipart/form-data" id="shop-sphinx-form" name="shop-sphinx-form">
        <input type="hidden" name="config_type[]" value="sphinx"/>

        <div class="ncap-form-default">


            <dl class="row">
                <dt class="tit">Sphinx状态</dt>
                <dd class="opt">
                    <div class="onoff">
                        <input id="sphinx_statu1" name="sphinx[sphinx_statu]"  value="1" type="radio" <?=($data['sphinx_statu']['config_value']==1 ? 'checked' : '')?>>
						<label title="开启" class="cb-enable <?=($data['sphinx_statu']['config_value']==1 ? 'selected' : '')?> " for="sphinx_statu1">开启</label>

                        <input id="sphinx_statu0" name="sphinx[sphinx_statu]"  value="0" type="radio" <?=($data['sphinx_statu']['config_value']==0 ? 'checked' : '')?>>
						<label title="关闭" class="cb-disable <?=($data['sphinx_statu']['config_value']==0 ? 'selected' : '')?>" for="sphinx_statu0">关闭</label>
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>


            <dl class="row">
                <dt class="tit">
                    <label for="sphinx_search_host">sphinx_search_host</label>
                </dt>
                <dd class="opt">
                    <input id="sphinx_search_host" name="sphinx[sphinx_search_host]" value="<?=($data['sphinx_search_host']['config_value'])?>" class="ui-input w400" type="text"/>

                    <p class="notic"></p>
                </dd>
            </dl>
			<dl class="row">
                <dt class="tit">
                    <label for="sphinx_search_port">sphinx_search_port</label>
                </dt>
                <dd class="opt">
                    <input id="sphinx_search_port" name="site[sphinx_search_port]" value="<?=($data['sphinx_search_port']['config_value'])?>" class="ui-input w400" type="text"/>

                    <p class="notic">网站名称，将显示在前台顶部欢迎信息等位置</p>
                </dd>
            </dl>
            <div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
</script>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>