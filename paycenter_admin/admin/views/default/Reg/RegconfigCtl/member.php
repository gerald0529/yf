<?php if (!defined('ROOT_PATH')) exit('No Permission');?><?php
header("Content-type:text/html;charset=utf8"); 
?>
<?php
include TPL_PATH . '/'  . 'header.php';
?>
<style>
#para-wrapper{font-size:14px; }
#para-wrapper .para-item{margin-bottom:30px;}
#para-wrapper .para-item h3{font-size:14px;font-weight:bold;margin-bottom:10px;}

.mod-form-rows .label-wrap { width:180px; }
.para-item .ui-input{width:220px;font-size:14px;}

.subject-para .ui-input{width:40px;}

.code-length .ui-spinbox-wrap{margin-right:0;}

.books-para input{margin-top:-3px;}

#currency{width: 68px;}
.ui-droplist-wrap .list-item {font-size:14px;}

.ilodate{width:800px;line-height:40px;margin-top:5px;margin-bottom:10px;font-weight:bold;color:#555555;font-size:25px;}

.tip{color: #a9a9a8;line-height: 180%;word-break: break-all; margin-left:200px;}

</style>

</head>
<body>
<div class="wrapper">
	
  <div id="para-wrapper">
  <div class="ilodate">会员登录设置：</div>
    <div class="para-item">
      <ul class="mod-form-rows" id="establish-form">
        <li class="row-item">
          <div class="label-wrap">
            <label for="zend_status_type">允许新用户注册：</label>
          </div>
          <div class="mb6 ctn-wrap">
				<input type="radio"  name="zend_status_type" value="1" <?php if($data['zend_status_type'] == 1){echo 'checked';} ?> >&nbsp;是&nbsp;
				<input type="radio"  name="zend_status_type" value="2" <?php if($data['zend_status_type'] == 2){echo 'checked';} ?>>&nbsp;否&nbsp;&nbsp;
				<span class="tip">设置是否允许游客注册成为会员。</span>
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="zend_closecon">关闭注册提示信息：</label>
          </div>
          <div class="ctn-wrap">
				<textarea name="zend_closecon" id="zend_closecon" class="w245" style="width: 254px; height: 25px;" value=""><?php echo $data['zend_closecon'];?></textarea>
				<span class="tip" style="margin-left:25px;">当站点关闭注册时的提示信息</span>
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="zend_censoruser" style="line-height:80px;">受保护用户名：</label>
          </div>
          <div class="ctn-wrap">
			<textarea name="zend_censoruser" id="zend_censoruser" class="w245" style="width: 254px; height: 74px;" value=""><?php  echo  $data['zend_censoruser'];?></textarea>
			<span class="tip" style="margin-left:25px;">用户在注册时无法使用这些用户名。每个用户名一行</span>
		  </div>
        </li>
		<li class="row-item">
          <div class="label-wrap">
            <label for="zend_pwlength">密码最小长度：</label>
          </div>
          <div class="ctn-wrap">
			<input maxlength="3" id="zend_pwlength" type="text" class="w250" name="zend_pwlength"  onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" value="<?php echo $data['zend_pwlength'];?>"/>
			<span class="tip" style="margin-left:30px;">新用户注册时密码最小长度5，0或不填为不限制</span>
		  </div>
        </li>
		<li class="row-item">
          <div class="label-wrap">
            <label for="zend_pwlength">强制密码复杂度：</label>
          </div>
          <div class="pw ctn-wrap">
				<input  type="checkbox" name="zend_strongpw1" class="zend_strongpw1" value="1" <?php if($data['zend_strongpw'][0] == 1) echo 'checked' ?>  />&nbsp;数字
				<input  type="checkbox" name="zend_strongpw2" class="zend_strongpw2" value="2" <?php if($data['zend_strongpw'][1] == 2) echo 'checked' ?> />&nbsp;小写字母
				<input  type="checkbox" name="zend_strongpw3" class="zend_strongpw3" value="3" <?php if($data['zend_strongpw'][2] == 3) echo 'checked' ?>  />&nbsp;大写字母
				<input  type="checkbox" name="zend_strongpw4" class="zend_strongpw4" value="4" <?php if($data['zend_strongpw'][3] == 4) echo 'checked' ?> />&nbsp;符号
				<span class="tip" style="margin-left:32px;">新用户注册时密码中必须存在所选字符类型，不选则为无限制</span>     
		  </div>
        </li>
		<li class="row-item">
          <div class="label-wrap">
            <label for="zend_pwlength">新用户注册验证：</label>
          </div>
          <div class="reg ctn-wrap" style="height:50px;">
			<input type="radio" name="zend_user_reg" value="1" <?php if($data['zend_user_reg'] == 1){echo 'checked';} ?> />&nbsp;无
			<input type="radio" name="zend_user_reg" value="2" <?php if($data['zend_user_reg'] == 2){echo 'checked';} ?> />&nbsp;Email 验证
			<input type="radio" name="zend_user_reg" value="3" <?php if($data['zend_user_reg'] == 3){echo 'checked';} ?> />&nbsp;手机验证
			<span class="tip" style="width:730px;margin-left:82px;display:block;float:right;">选择"无"用户可直接注册成功；选择"Email 验证"将向用户注册 Email 发送一封验证邮件以确认邮箱的有效性；选择"手机 验证"将向用户注册手机发送一条短信验证码验以确认手机的有效性。</span>
		  </div>
        </li>
		
		<li class="row-item">
          <div class="label-wrap">
            <label for="zend_pwlength">同一 IP 注册间隔限制：</label>
          </div>
          <div class="ctn-wrap">
			<input maxlength="3" type="text" id="zend_regctrl" class="w250" name="zend_regctrl" value="<?php echo $data['zend_regctrl'];?>" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"/>
			<span class="tip" style="margin-left:30px;">同一 IP 在本时间间隔内将只能注册一个帐号，0 为不限制</span>
		  </div>
        </li>
		<li class="row-item">
          <div class="label-wrap">
            <label for="zend_pwlength">24小时注册的最大次数：</label>
          </div>
          <div class="ctn-wrap">
			<input maxlength="3" type="text" id="zend_regfloodctrl" class="w250" name="zend_regfloodctrl" value="<?php echo $data['zend_regfloodctrl']; ?>" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))"/>
			<span class="tip" style="margin-left:30px;">同一 IP 地址在 24 小时内尝试注册的次数限制，建议在 30 - 100 范围内取值，0 为不限制</span>
		  </div>
        </li>
		<li class="row-item">
          <div class="label-wrap">
            <label for="zend_pwlength">IP注册间隔限制(小时)：</label>
          </div>
          <div class="ctn-wrap">
			<input maxlength="3" type="text" id="zend_ipregctrltime" class="w250" name="zend_ipregctrltime" value="<?php echo $data['zend_ipregctrltime']; ?>" onkeyup="value=value.replace(/[^\d]/g,'')" onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))" />
			<span class="tip" style="margin-left:30px;">同一 IP 地址在 24 小时内尝试注册的次数限制，建议在 30 - 100 范围内取值，0 为不限制</span>
		  </div>
        </li>
		<li class="row-item" style="height:60px;">
          <div class="label-wrap">
            <label for="zend_pwlength" style="line-height:60px;">限时注册的 IP 列表：</label>
          </div>
          <div class="ctn-wrap">
			<textarea name="zend_ipregctrl" id="zend_ipregctrl" class="w245" value="" rows="3" style="width: 252px; height: 66px;"><?php echo $data['zend_ipregctrl'];?></textarea>
			<span class="tip" style=";margin-left:30px;">当用户处于本列表中的 IP 地址时，在限时注册IP注册间隔限制内将至多只允许注册一个帐号。每个 IP 一行。</span>
		  </div>
        </li>
		<li class="row-item">
          <div class="label-wrap">
            <label for="zend_pwlength" style="line-height:200px;font-weight:600;">注册协议：</label>
          </div>
          <div class="ctn-wrap">
			<textarea name="zend_association" id="zend_association" value="" style="width: 832px; height: 231px;"><?php echo $data['zend_association'];?></textarea>
			
		  </div>
        </li>
      </ul>
    </div>


        <div class="btn-wrap"> <a name="submit" id="submit" class="ui-btn ui-btn-sp">提交</a> </div>
        <!--</form>-->
	</div>
</div>
<script type="text/javascript" src="<?=$this->view->js?>/jquery.js"></script>
<script>
	$('#submit').click(function(event){
		var zend_status_type = $('.mb6 input[name="zend_status_type"]:checked ').val();
		var zend_closecon = $('#zend_closecon').val();
		var zend_censoruser = $('#zend_censoruser').val();
		var zend_pwlength = $('#zend_pwlength').val();
		
		var zend_strongpw1 = $('.pw input[name="zend_strongpw1"]:checked').val();	
		var zend_strongpw2 = $('.pw input[name="zend_strongpw2"]:checked').val();	
		var zend_strongpw3 = $('.pw input[name="zend_strongpw3"]:checked').val();	
		var zend_strongpw4 = $('.pw input[name="zend_strongpw4"]:checked').val();
		

		//alert(zend_strongpw);
		var zend_user_reg = $('.reg input[name="zend_user_reg"]:checked ').val();
		//var zend_user_reg_verf = $('.vref input[name="zend_user_reg_verf"]:checked ').val();
		//var zend_openbbs = $('.open input[name="zend_openbbs"]:checked ').val();
		var zend_regctrl = $('#zend_regctrl').val();
		var zend_regfloodctrl = $('#zend_regfloodctrl').val();
		var zend_ipregctrltime = $('#zend_ipregctrltime').val();
		var zend_ipregctrl = $('#zend_ipregctrl').val();
		var zend_association = $('#zend_association').val();
		
		
		$.post("./index.php?ctl=Reg_Regconfig&met=reg_member",{'zend_status_type':zend_status_type,'zend_closecon':zend_closecon,'zend_censoruser':zend_censoruser,'zend_pwlength':zend_pwlength,'zend_strongpw1':zend_strongpw1,'zend_strongpw2':zend_strongpw2,'zend_strongpw3':zend_strongpw3,'zend_strongpw4':zend_strongpw4,'zend_user_reg':zend_user_reg,'zend_regctrl':zend_regctrl,'zend_regfloodctrl':zend_regfloodctrl,'zend_ipregctrltime':zend_ipregctrltime,'zend_ipregctrl':zend_ipregctrl,'zend_association':zend_association},function(data){
			console.info(data);
				if(data.status == 200){
					alert("提交成功");
				}else{
					alert("提交失败");
				}
				
		});
	});
</script>
<?php
include TPL_PATH . '/'  . 'footer.php';
?>