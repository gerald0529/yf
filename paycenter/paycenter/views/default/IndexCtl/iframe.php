<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1, user-scalable=no">
	<script src="<?=$this->view->js?>/jquery-1.9.1.js" type="text/javascript"></script>
    <script src="<?=$this->view->js?>/jquery.cookie.js"></script>
    
	<script>
		var SITE_URL = "<?=Yf_Registry::get('url')?>";
		var UCENTER_URL = "<?=Yf_Registry::get('ucenter_api_url')?>";
	</script>
    <script src="<?=$this->view->js?>/common.js"></script>
</head>
<body>

<script>
	$(function(){
		ucenterLogin(UCENTER_URL, SITE_URL, false);
	});
</script>
</body>

</html>