<?php if (!defined('ROOT_PATH')){exit('No Permission');}?>
<script type="text/javascript">
    $(document).ready(function() {
        $("body").iealert();

        $.get("<?php echo Yf_Registry::get('shop_api_url');?>?ctl=Api_Version&met=index",function(ver){  
	        	$('#ver_footer').html(ver); 
        });
    });


</script>
<div id='ver_footer' style="display: none;"></div>
</body>
</html>