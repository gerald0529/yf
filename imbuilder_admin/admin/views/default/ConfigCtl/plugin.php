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
				<h3>系统工具&nbsp;</h3>
				<h5>插件管理设定</h5>
			</div>
			<ul class="tab-base nc-row">
				<li><a class="current"><span>插件管理</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=cacheManage"><span>清理缓存</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=validator"><span>程序检测</span></a></li>
<!--                <li><a href="--><?//= Yf_Registry::get('url') ?><!--?ctl=Config&met=update"><span>更新管理中心</span></a></li>-->
<!--                <li><a href="--><?//= Yf_Registry::get('url') ?><!--?ctl=Config&met=updateShop"><span>更新商城</span></a></li>-->
			</ul>
		</div>
    </div>


    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
        <ul>
            <li>是否启用插件。</li>
        </ul>
    </div>
    
    <div class="cf">
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>

    </div>
</div>
<?php
if (isset($data['plugin_rows']))
{
	foreach ($data['plugin_rows'] as $key => $plugin_row)
	{
		$data['plugin_rows'][$key]['id'] = $data['plugin_rows'][$key]['plugin_id'];

		if (isset($data[$plugin_row['plugin_id']]))
		{
			$data['plugin_rows'][$key]['plugin_state'] = intval($data[$plugin_row['plugin_id']]['config_value']);
		}
		else
		{
			$data['plugin_rows'][$key]['plugin_state'] = 0;
		}
	}
}
?>
<script type="text/javascript">
    var plugin_data = <?= encode_json(isset($data['plugin_rows']) ? $data['plugin_rows'] : array()) ?>;
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/plugin_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>