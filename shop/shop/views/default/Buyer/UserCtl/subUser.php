<html>
<head>
	<title>新建子账号</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?= $this->view->css ?>/resetAddr.css">
	<script type="text/javascript" src="<?=$this->view->js_com?>/jquery.js" charset="utf-8"></script>
    <script>
        var SITE_URL = "<?php Yf_Registry::get('url')?>";
    </script>
	<link href="<?= $this->view->css_com ?>/jquery/plugins/validator/jquery.validator.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/jquery.validator.js" charset="utf-8"></script>
	<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/validator/local/zh_CN.js" charset="utf-8"></script>
	<script  type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.dialog.js"></script>
	<script type="text/javascript" src="<?=$this->view->js?>/district.js"></script>
	<link type="text/css" rel="stylesheet" href="<?= $this->view->css_com ?>/jquery/plugins/dialog/green.css">
	<link href="<?= $this->view->css ?>/tips.css" rel="stylesheet">
	<script type="text/javascript" src="<?=$this->view->js?>/common.js"></script>
	<script type="text/javascript" src="<?=$this->view->js_com?>/plugins/jquery.toastr.min.js" charset="utf-8"></script>
	<script type="text/javascript" src="<?= $this->view->js_com?>/plugins/jquery.cookie.js"></script>

</head>
<style>
.resAdd-inp div.hidden{display: none;}
</style>
<body>
	<div class="reset-address" >
		<form id="form" action="#" method="post" >
		<div class="res-cont">
			 <input type="hidden" name="sub_user_id" id="sub_user_id" value="<?php if(isset($data) && !empty($data)){ echo $data['sub_user_id']; }?>">
			<div class="resAdd-inp">
				<div><span><strong></strong><?=__('关联子账号名：')?></span></div>
				<input type="text"  name="user_name" <?php if(isset($data) && !empty($data)){echo "value=".$data['user_name'].' readonly="readonly"';}?>>
			</div>
			<div class="moren">
				<input type="checkbox" name="sub_user_active" value="1" <?php if(isset($data) && !empty($data) && $data['sub_user_active'] == 1){echo 'checked';}?>><span><?=__('是否启用')?></span>
			</div>
			<input type="submit" class="save" value="<?=__('提交')?>" />
		</div>
		</form>
	</div>
</body>
</html>
<script>
	api = frameElement.api;
	var SITE_URL = "<?php Yf_Registry::get('url')?>";

	var url = SITE_URL+"?ctl=Buyer_User&met=editSubUser&typ=json";

	//表单提交
	$('#form').validator({
		ignore: ':hidden',
		theme: 'yellow_right',
		timely: 1,
		stopOnError: false,
		fields: {
			'user_name': 'required;',
		},
		valid:function(form){

			var me = this;
            // 提交表单之前，hold住表单，防止重复提交
            me.holdSubmit();

			//表单验证通过，提交表单
			$.ajax({
			 url: url,
			 data:$("#form").serialize(),
			 success:function(a){
				 console.info(a);
                 if(a.status == 200)
                 {
					 parent.location.reload();
                     api.close();
                 }
                 else
                 {
                     Public.tips.error(a.msg);
                 }
                 // 提交表单成功后，释放hold，如果不释放hold，就变成了只能提交一次的表单
                 me.holdSubmit(false);
			 },
			 error:function ()
             {
                me.holdSubmit(false);
             }
			 
			 });
		}

	});
</script>