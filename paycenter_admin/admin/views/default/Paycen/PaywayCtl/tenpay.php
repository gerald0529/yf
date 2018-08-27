<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
<style>
#para-wrapper{font-size:12px; }
#para-wrapper .para-item{margin-bottom:30px;}
#para-wrapper .para-item h3{font-size:14px;font-weight:bold;margin-bottom:10px;}

.mod-form-rows .label-wrap { width:180px; }
.para-item .ui-input{width:220px;font-size:12px;}

.subject-para .ui-input{width:40px;}

.code-length .ui-spinbox-wrap{margin-right:0;}

.books-para input{margin-top:-3px;}

#currency{width: 68px;}
.ui-droplist-wrap .list-item {font-size:12px;}

.ilodate{width:800px;line-height:40px;margin-top:5px;margin-bottom:10px;font-weight:bold;color:#555555;font-size:25px;}
.ui-input{width:258px;height:30px;}
.ui-input1{width:230px;height:100px;}
</style>

<body>
<div class="wrapper">
	
<div id="para-wrapper">
  <div class="ilodate">支付设置（修改）</div>
    <div class="para-item">
		<ul class="mod-form-rows" id="establish-form">
		<input type="hidden" name="payment_channel_id" id="payment_channel_id" value="<?php echo $payways['payment_channel_id']; ?>">
			<li class="row-item">
				<div class="label-wrap">
					<label for="payment_channel_code">名称:</label>
				</div>
				<div class="ctn-wr6ap">
					<input type="text" name="payment_channel_code"  id="payment_channel_code" value="<?php echo $payways['payment_channel_code']; ?>" class="ui-input" />
				</div>
			</li>
			<li class="row-item">
				<div class="label-wrap">
					<label for="payment_channel_name">描述:</label>
				</div>
				<div class="ctn-wrap">
					<input type="text" name="payment_channel_name" id="payment_channel_name" value="<?php echo $payways['payment_channel_name']; ?>" class="ui-input1"  />
				</div>
			</li>
			<li class="row-item">
				<div class="label-wrap">
					<label for="payment_channel_config_alipay_account">开启邮箱:</label>
				</div>
				<div class="ctn-wrap">
					<input type="text" name="tenpay_account" id="tenpay_account" value="<?php echo $list_config['tenpay_account']; ?>" class="ui-input"  />
				</div>
			</li>
			<li class="row-item">
				<div class="label-wrap">
					<label for="payment_channel_config_alipay_key">KEY:</label>
				</div>
				<div class="ctn-wrap">
					<input type="text" name="tenpay_key" id="tenpay_key" value="<?php echo $list_config['tenpay_key']; ?>" class="ui-input"  />
				</div>
			</li>
			<li class="row-item">
				<div class="label-wrap">
					<label for="payment_channel_config_alipay_partner">密钥:</label>
				</div>
				<div class="ctn-wrap">
					<input type="text" name="tenpay_partner" id="tenpay_partner" value="<?php echo $list_config['tenpay_partner']; ?>" class="ui-input"  />
				</div>
			</li>·
		</ul>
	</div>
	<div class="btn-wrap"> <a name="submit" id="submit" class="ui-btn ui-btn-sp">提交</a>
	</div>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/jquery.js"></script>
<script>
	$('#submit').click(function(event){
		var payment_channel_id = $('#payment_channel_id').val();
		var payment_channel_code = $('#payment_channel_code').val();
		var payment_channel_name = $('#payment_channel_name').val();
		var payment_channel_config_alipay_account = $('#tenpay_account').val();
		var payment_channel_config_alipay_key = $('#tenpay_key').val();
		var payment_channel_config_alipay_partner = $('#tenpay_partner').val();
		//alert(payment_channel_config_alipay_partner);
		$.post("./index.php?ctl=Paycen_Payway&met=editPayLoad",{"payment_channel_id":payment_channel_id,"payment_channel_code":payment_channel_code,"payment_channel_name":payment_channel_name,"alipay_account":payment_channel_config_alipay_account,"alipay_key":payment_channel_config_alipay_key,"alipay_partner":payment_channel_config_alipay_partner},function(data)
		{
			console.info(data);
			if(data.status == 200){
				alert("提交成功");
				$(function () {
                    window.location.href = "./index.php?ctl=Paycen_Payway&met=payload";
                })
			}else{
				alert("提交失败");
			}
		});
	});
</script>


</body>

<?php
include TPL_PATH . '/'  . 'footer.php';
?>