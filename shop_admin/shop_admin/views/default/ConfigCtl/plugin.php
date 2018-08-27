<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';

// 当前管理员权限
$admin_rights = $this->getAdminRights();
// 当前页父级菜单 同级菜单 当前菜单
$menus = $this->getThisMenus();

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
				<h3><?=$menus['father_menu']['menu_name']?></h3>
                <h5><?=$menus['father_menu']['menu_url_note']?></h5>
			</div>
			<ul class="tab-base nc-row">
				<?php include __DIR__.'/config_comm_menu.php';?>
			</ul>
		</div>
    </div>


    <!-- 操作说明 -->
    <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <?=$menus['this_menu']['menu_url_note']?>
           
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

	$json =  json_encode($data['plugin_rows']);
}
?>
<script type="text/javascript">
    var plugin_data = <?= $json ?>;
</script>
<script type="text/javascript" src="<?=$this->view->js?>/controllers/plugin_list.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>