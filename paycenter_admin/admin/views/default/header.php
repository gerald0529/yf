<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=11">
<meta name="viewport" content="width=1280, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<title>Paycenter</title>

<link href="./admin/static/default/css/iconfont/iconfont.css" rel="stylesheet" type="text/css">
<link href="./admin/static/default/css/common.css?ver=20140430" rel="stylesheet" type="text/css">
<link href="./admin/static/default/css/ui.min.css?ver=20140430" rel="stylesheet">
<link href="./admin/static/default/css/pay/main.css" rel="stylesheet">
<link href="./admin/static/default/css/index.css" rel="stylesheet">
<script src="./admin/static/default/js/pay/main.js"></script>
<script src="./admin/static/default/js/libs/jquery/jquery-1.10.2.min.js"></script>
<script src="./admin/static/default/js/libs/json2.js"></script>
<script src="./admin/static/default/js/models/common.js?ver=20140430"></script>
<script src="./admin/static/default/js/libs/jquery/grid.js?ver=20140430"></script>
<script src="./admin/static/default/js/libs/jquery/plugins.js?ver=20140430"></script>
<script src="./admin/static/default/js/libs/jquery/plugins/jquery.dialog.js?self=false&ver=20140432"></script>
<script type="text/javascript">

	var BASE_URL = "<?=Yf_Registry::get('base_url')?>";
	var SITE_URL = "<?=Yf_Registry::get('url')?>";
	var INDEX_PAGE = "<?=Yf_Registry::get('index_page')?>";
	var STATIC_URL = "<?=Yf_Registry::get('static_url')?>";
	var PAYCENTER_URL =  "<?=Yf_Registry::get('paycenter_api_url')?>";



	var DOMAIN = document.domain;
	var WDURL = "";
	var SCHEME= "default";
	try{
		//document.domain = 'admin.paycenter.yuanfeng021.com';
	}catch(e){
	}
	
	var SITE_URL = "<?=Yf_Registry::get('url')?>";
</script>

