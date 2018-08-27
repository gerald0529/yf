<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}
?>
    
<style>
    article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section { display: block; }
    body, h1, h2, h3, h4, h5, h6, hr, p, blockquote, dl, dt, dd, ul, ol, li, pre, form, fieldset, legend, button, input, textarea, th, td { margin: 0; padding: 0; }
    body, button, input, select, textarea { font: 12px/1.5 '微软雅黑', 'Microsoft Yahei', \5b8b\4f53; color:#555; }
    h1, h2, h3, h4, h5, h6 { font-size: 100%; }
    address, cite, dfn, em, var { font-style: normal; }
    code, kbd, pre, samp { font-family: courier new, courier, monospace; }
    small { font-size: 12px; }
    ul, ol { list-style: none; }
    a { text-decoration: none; color:#555; cursor:pointer; }
    a:hover { text-decoration: none; color:#FF4564; }
    sup { vertical-align: text-top; }
    sub { vertical-align: text-bottom; }
    legend { color: #000; }
    fieldset, img { border: 0; }
    button, input, select, textarea { font-size: 100%; }
    /*table { border-collapse: collapse; border-spacing: 0; }*/
    *{font-family:"微软雅黑";}
    html{background:#fff;}
    .cf:after { clear: both; content: "."; display: block; height: 0; overflow: hidden; visibility: hidden; }
    .cf { *zoom:1; }
    .dn { display: none; }
    .fl { float: left; }
    .fr { float: right; }
    .mr0 { margin-right: 0 !important; }
    .mrb { margin-right:10px;}
    .mr10 { margin-right:10px;}
    .mb10 { margin-bottom: 10px; }
    .mb20 { margin-bottom: 20px; }
    .tc { text-align: center; }
    .tr { text-align: right; }
    .es { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .fb { font-weight: bold !important; }
    .pr { position:relative;}
    .pa { position: absolute; }
    .red{ color:#F00; }
    .vm { vertical-align:middle; }
    .f14{ font-size:14px; }
    .fwn{ font-weight:normal !important; }

    body{ background-color:#fff; }
    a{ outline:none; }
    .wrapper{ padding:5px 0 0 0px; }
    .min-w{ min-width:1150px; }
    
</style>
	<?php
	if ( $update )
	{
		ob_end_flush();
		
		
		$allow_relaxed_file_ownership = false;
		
		
		try{
			
			$result = $upgrader->upgrade( $update, array(
				'allow_relaxed_file_ownership' => $allow_relaxed_file_ownership
			) );
			
			
			$time = 2;
			$url = "<?= Yf_Registry::get('url') ?>?ctl=Config&met=update";
			
			//header("Refresh:{$time}; url={$url}");
			
		}
		catch(Exception $e)
		{
			update_feedback($e->getMessage());
			update_feedback('<p>安装失败！</p>');
			//update_feedback('<p><a class="ui-btn" id="reinstall">重新安装<i class="iconfont"></i></a></p>');
			//print_r($e->getMessage());
		}
	}
	?>