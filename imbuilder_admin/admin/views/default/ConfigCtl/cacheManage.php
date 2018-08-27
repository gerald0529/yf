<?php if (!defined('ROOT_PATH')) {exit('No Permission');}?>
<?php
include $this->view->getTplPath() . '/'  . 'header.php';
?>
<link href="<?=$this->view->css?>/index.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="wrapper page">
	<div class="fixed-bar">
		<div class="item-title">
			<div class="subject">
				<h3>系统工具&nbsp;</h3>
				<h5>清理缓存</h5>
			</div>
			<ul class="tab-base nc-row">
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=plugin"><span>插件管理</span></a></li>
				<li><a class="current" ><span>清理缓存</span></a></li>
				<li><a href="<?= Yf_Registry::get('url') ?>?ctl=Config&met=validator"><span>程序检测</span></a></li>
<!--				<li><a href="--><?//= Yf_Registry::get('url') ?><!--?ctl=Config&met=update"><span>更新管理中心</span></a></li>-->
<!--				<li><a href="--><?//= Yf_Registry::get('url') ?><!--?ctl=Config&met=updateShop"><span>更新商城</span></a></li>-->
			</ul>
		</div>
    </div>
	<!-- 操作说明 -->
	<p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
		<div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
			<h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
			<span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em></div>
		<ul>
			<li>是否清理缓存。</li>
		</ul>
	</div>
	
	<div class="license" align="center">
		<div id="loader"></div>
	</div>
	<div class="bot bot_reset"><a href="javascript:void(0);" class="ui-btn ui-btn-sp submit-btn" onclick="check();">清除缓存</a></div>
</div>

<script type='text/javascript' src='<?=$this->view->js_com?>/plugins/jquery.percentageloader-0.1.min.js?ver=1.12.3'></script>

<div id="container">
	<script>

		var $topLoader = null;
		var topLoaderRunning = false;
		var check_data = '';
		var kb = 0;
		var totalKb = 999;


		$(function ()
		{
			$topLoader = $("#loader").percentageLoader({
				width: 256, height: 256, value: '清理进度', controllable: true, progress: 0, onProgressUpdate: function (val)
				{
					$topLoader.setValue(Math.round(val * 100.0));
				}
			});


			$("#loader").slideDown();
		});

		function  check()
		{
			kb = 0;
			$.ajax({
				type: "POST",
				url: "./index.php?ctl=Config&met=cache&typ=json",
				data: {},
				dataType: "html",
				beforeSend: function (XMLHttpRequest)
				{
					if (topLoaderRunning)
					{
						return;
					}
					topLoaderRunning = true;
					$topLoader.setProgress(0);
					//$topLoader.setValue('0kb');
					var animateFunc = function ()
					{
						kb += 3;
						$topLoader.setProgress(kb / totalKb);
						//$topLoader.setValue(kb.toString() + 'kb');

						if (kb < totalKb)
						{
							setTimeout(animateFunc, 25);
						}
						else
						{
							topLoaderRunning = false;

							$("#loader").slideToggle(function(){
								$("#loader").slideToggle();
							});


							//
						}
					}

					setTimeout(animateFunc, 25);

					//$("#divlist").html("正在加载数据");
				},

				success: function (msg)
				{
					check_data = msg;

					if (kb < 700)
					{
						kb = 700;
					}
				},

				complete: function (XMLHttpRequest, textStatus)
				{
					kb = 999;
				},

				error: function (e, x)
				{
					kb = 999;
				}
			});
		}

		$('#recheck').click(function(e){
			//e.preventDefault();
			//check();
		});

		$('#next_step').click(function(e){

			if ($('#next_step').hasClass('button-disabled'))
			{
				e.preventDefault();
				alert('服务器环境未通过检测!');
			}
			//
		});

	</script>
</div>

<script type="text/javascript" src="<?=$this->view->js?>/controllers/config.js" charset="utf-8"></script>
<?php
include $this->view->getTplPath() . '/' . 'footer.php';
?>