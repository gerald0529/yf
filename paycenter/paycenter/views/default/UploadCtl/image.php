<!doctype html>
<html>
<head>

<!-- webuploader -->
<link href="<?= $this->view->css ?>/base.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script src="<?=$this->view->js_com?>/jquery.js"></script>
<script src="<?=$this->view->js_com?>/webuploader.js"></script>
<link href="<?=$this->view->css_com?>/upload/image.css" rel="stylesheet">
<link href="<?= $this->view->css ?>/base.css?ver=<?=VER?>" rel="stylesheet" type="text/css">
<script type="text/javascript">
	var BASE_URL = "<?=Yf_Registry::get('base_url')?>";
	var SITE_URL = "<?=Yf_Registry::get('url')?>";
	var INDEX_PAGE = "<?=Yf_Registry::get('index_page')?>";
	var STATIC_URL = "<?=Yf_Registry::get('static_url')?>";

	var DOMAIN = document.domain;
	var WDURL = "";
	var SCHEME = "default";
	try
	{
		//document.domain = 'ttt.com';
	} catch (e)
	{
	}

	var SYSTEM = SYSTEM || {};
	SYSTEM.skin = 'green';
	SYSTEM.isAdmin = true;
	SYSTEM.siExpired = false;
</script>
</head>
<div class="widget-attachment modal hide in" aria-hidden="false" style="display: block; top: 0px;border-style: none;">
	<div class="js-main-region">
		<div>

  	<div class="modal-body" id="image-list">
		    <div class="category-list-region js-category-list-region">
		        <ul class="category-list"></ul>
		    </div>

		    <div class="attachment-list-region js-attachment-list-region">
    			<ul class="image-list">

	        	<div class="attachment-pagination js-attachment-pagination">
	        		<div class="ui-pagination page">
	    				<span class="ui-pagination-total"></span>
					</div>
				</div>
	            <a href="javascript:;" class="ui-btn ui-btn-success js-show-upload-view bbc_seller_btns" style="position: absolute; left: 180px; bottom: 16px;">上传图片</a>
            </div>
            
		</div>

		
		
		<div class="modal-body" style="min-height: 400px; height: auto;display: none;"  id="upload">
    
    <div class="network-image-region">
        <div class="title">网络图片：</div>
        <div class="content">
            <div class="input-append">
                <input type="text" placeholder="请添加网络图片地址" class="js-network-image-url span4">
                <button class="btn js-network-image-confirm"  type="button" data-loading-text="提取中...">提取</button>
            </div>
            <div class="image-preview">
                <img src="" class="js-network-image-preview">
            </div>
        </div>
    </div>
    
    <div class="local-image-region">
        <div class="title">本地图片：</div>
        <div class="content">
            <div class="js-image-preview-region"><ul class="upload-local-image-list image-list ui-sortable"></ul></div>
            <div class="js-add-local-attachment add-local-image-button pull-left">+</div>
            <div class="c-gray" style="clear: both; padding-top: 20px;">
               	 支持jpg、gif、png、jpeg、bmp五种格式, 大小不超过2 MB
            </div>
        </div>
    </div>
</div>

<div class="modal-footer clearfix">
    <div class="text-center">
        <button class="ui-btn ui-btn-disabled js-confirm " disabled="disabled" data-loading-text="正在上传...">确认</button>
    </div>
</div>

		</div>
	</div>
</div>
<script>
	var uploadConfig = <?=encode_json($this->config)?>;

</script>
<script src="<?=$this->view->js_com?>/upload/image.js"></script>
</body>
</html>
