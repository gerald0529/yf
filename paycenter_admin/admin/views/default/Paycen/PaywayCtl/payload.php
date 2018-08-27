<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>管理系统</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="./admin/static/default/css/pay/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="./admin/static/default/js/jquery.js"></script>
<script type="text/javascript" src="./admin/static/default/js/pay/main.js"></script>
</head>
<body>
  <form action="" method="get">
  	<div class="wrapper page">
  		<p class="warn_xiaoma"><span></span><em></em></p>
		<div class="explanation" id="explanation">
	        <div class="title" id="checkZoom"><i class="iconfont icon-lamp"></i>
	            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
	            <span id="explanationZoom" title="收起提示"></span><em class="close_warn">X</em>
	        </div>
	        <ul>
	            <li></li>
	            <li></li>
	       	</ul>	
    	</div>
		<div class="fixed-bar">
		    <div class="item-title">
		      <div class="subject">
		        <h3>支付方式设置</h3>
		        <h5>支付方式总览及相关设置</h5>
		      </div>
			   	<ul class="tab-base nc-row">
	                <li><a class="current"><span>支付方式设置</span></a></li>
	            </ul>
		    </div>
		</div>
  		<div class="ncap-form-default">
			<div class="bigbox clearfix">
				<div class="bigboxbody">
				  <table width="100%" border="0" cellpadding="2" cellspacing="0" >
			        <tr class="theader">
						<td width="10%">名称</td>
						<td >描述</td>
						<td width="11%" align="center" >管理</td>
			        </tr>
					<?php 
					$array = $paylist;
					foreach ($array as $value){
					?>
			        <tr onMouseOver="mouseOver(this)" onMouseOut="mouseOut(this,'odd')">
					
						<input type="hidden" name="payment_channel_id" value="">
						<td><?php echo $value['payment_channel_code'] ?></td>
						<td align="left" name="payment_channel_name"><?php echo $value['payment_channel_name']; ?></td>
						<td align="center">
						
							<?php
							//echo $value['payment_channel_status'];
								if($value['payment_channel_status'] == 0)
								{
									echo '<a href="./admin.php?ctl=Paycen_Payway&met='.$value['payment_channel_code'].'&typ=e&paytype='.$value['payment_channel_code'].'">
									
									<img src="./admin/static/default/images/pay/stop.png" id="editpay" name="payment_channel_status">
									</a>';
								}else{
									echo '<a href="./admin.php?ctl=Paycen_Payway&met='.$value['payment_channel_code'].'&typ=e&paytype='.$value['payment_channel_code'].'" name="payment_channel_id">
									<img src="./admin/static/default/images/pay/edit.png" id="editpay" name="payment_channel_status">
									</a>';
									echo '<a href="./admin.php?ctl=Paycen_Payway&met=editPayStatus&typ=e&paytype='.$value['payment_channel_code'].'&payment_channel_id='.$value['payment_channel_id'].'" >
									<img src="./admin/static/default/images/pay/start.png" id="editpaystatus" >
									</a>';
								}
							?>
						</td>
					
					</tr>
					<?php
						}
					?>
			      </table>

				</div>
			</div>
		</div>
	</div>
<script type="text/javascript" src="<?=$this->view->js?>/jquery.js"></script>
<script>
	$(".click").click(function (){
		window.location.href='./admin.php?ctl=Paycen_Payway&met=editpayload&typ=e';
	});
	$(".editpaystatus").click(function(){
alert('1');
                window.location.href = "./index.php?ctl=Paycen_Payway&met=payload";
         
	})
</script>

</form>
</body>



<?php
include TPL_PATH . '/'  . 'footer.php';
?>