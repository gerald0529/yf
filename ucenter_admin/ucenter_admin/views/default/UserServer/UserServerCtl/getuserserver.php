<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>

<style>
		body{overflow-y:hidden;}
	.manage-wrap{margin: 0 auto;width: 300px;}
	.manage-wrap .ui-input{width: 200px;font-size:14px;}
	.manage-wrap .hideFeild{position: absolute;top: 30px;left:80px;width:210px;border:solid 1px #ccc;background-color:#fff;}
	.ztreeDefault{overflow-y:auto;max-height:240px;}
	.searchbox{float: left;font-size: 14px;}
	.searchbox li{float: left;margin-right: 10px;}
	#matchCon{width:240px;font-size:12px;}
	.ui-input-ph {color: #aaa;}
	.cur #custom-assisting .ui-combo-wrap {background: #eaeaea;border-color: #ccc;}
	.cur #custom-assisting input {background: #eaeaea;font-weight: bold;}
	.ui-droplist-wrap .selected {background-color: #d2d2d2;}
	.input-txt{font-size:14px;}
	.ui-droplist-wrap .list-item {font-size:14px;}
		.ui-input{width:200px;height:30px;}
		.app{line-height:30px;}
		.appe{width:200px;height:25px;}
		
		
</style>
</head>
<body>
<div class="wrapper page">
	<!-- 操作说明 -->
   <p class="warn_xiaoma"><span></span><em></em></p><div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span><em class="close_warn iconfont icon-guanbifuzhi"></em></div>
        <ul>
            <li></li>
        </ul>
    </div>
	<div class="mod-search cf">
		<div class="fl">
            <ul class="ul-inline">
			
				<li>
					<input type="text" id="matchCon" class="ui-input ui-input-ph matchCon" value="请输入用户名查询">
				</li>
				<li>
					  <label class="app">官方指定app_id:</label>
					  <select class="appe" id="app_id" name="app_id">
							<option>请选择</option>
					  <?php foreach($applist as $key => $values){ ?>
							<option value="<?php echo $values['app_id']; ?>"><?php echo $values['app_id']; ?></option>
					  <?php }?>
					  </select>
				</li>
				<li><a name="submit" class="ui-btn mrb" id="search">查询</a></li>
			</ul>
		</div>
		
	</div>
	<div class="cf">
		<div class="grid-wrap cf">
			<h3>用户服务器信息:<span id='currentCategory'></span></h3>
			<table id="grid">
			</table>
			<div id="page"></div>
		</div>

	</div>
</div>
<script src="./ucenter_admin/static/default/js/controllers/getuserserver/server.js"></script>
<script>
/*
	$('#search').click(function(event){
		
		var app_id = $('#app_id').val();
		//alert(app_id);
		$.post("./index.php?ctl=UserServer_UserServer&met=getUserServerlist&tye=json",{"app_id":app_id},function(data){
			 console.info(data);
			//alert('成功');
		});
	});
	*/
</script>
</body>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>