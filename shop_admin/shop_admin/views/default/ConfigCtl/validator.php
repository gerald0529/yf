<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<?php
include $this->view->getTplPath() . '/' . 'header.php';

// 当前管理员权限
$admin_rights = $this->getAdminRights();
// 当前页父级菜单 同级菜单 当前菜单
$menus = $this->getThisMenus();

?>
<link href="<?= $this->view->css ?>/index.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->view->js_com ?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
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
		<?php
		if ($data)
		{
			?>
			<ul><li><span  class="red">程序有异常!</li>
				<li>&nbsp;</li>
			<?php
			if (@$data['file']['modify'])
			{
				foreach ($data['file']['modify'] as $key=>$item)
				{
					echo sprintf('<li><span  class="red">被修改文件: %s </li>', $item);
				}
			}

			echo '<li>&nbsp;</li>';

			if (@$data['name']['increase'])
			{
				foreach ($data['name']['increase'] as $key=>$item)
				{
					echo sprintf('<li><span  class="red">新增文件: %s </li>', $item);
				}
			}

			echo '<li>&nbsp;</li>';

			if (@$data['name']['decrease'])
			{
				foreach ($data['name']['decrease'] as $key=>$item)
				{
					echo sprintf('<li><span  class="red">缺失文件: %s </li>', $item);
				}
			}
			?>
			</ul>
			<?php
		}
		else
		{
			?>
			<ul><li><span  class="green">很好,程序无异常!</li></ul>
			<?php
		}
		?>
	<form method="post" enctype="multipart/form-data" id="validator-form" name="form1">

		<div class="ncap-form-default">
			<div class="bot"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn">创建基准程序</a></div>
		</div>
	</form>
</div>

<script>


	$('#validator-form').on("click", "a.submit-btn", function (e)
	{
		parent.$.dialog.confirm('修改立马生效,是否继续？', function ()
			{

				Public.ajaxPost(SITE_URL + "?ctl=Config&typ=json&met=setStandard", {}, function (e)
				{
					if (200 == e.status)
					{
						parent.Public.tips({content: "创建基准成功！"});
					}
					else
					{
						parent.Public.tips({type: 1, content: "创建基准成功！" + e.msg})
					}

				})
			},
			function ()
			{
			});
	});



</script>

<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>

