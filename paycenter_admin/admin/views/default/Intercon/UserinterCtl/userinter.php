<?php if (!defined('ROOT_PATH')) exit('No Permission');?>
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

</style>
</head>
<body>
<div class="wrapper">
	
  <div id="para-wrapper">
  <div class="ilodate">互联设置</div>
    <div class="para-item">
      <h3  id="qqdata">QQ互联</h3>
      <ul class="mod-form-rows" id="establish-form">
        <li class="row-item">
          <div class="label-wrap">
            <label for="qq_status">是否开启:</label>
          </div>
          <div class="QQ ctn-wrap">
				<input type="radio"  name="qq_status" value="1" <?php if($data['qq_status'] == 1){echo 'checked';} ?> >&nbsp;是&nbsp;
				<input type="radio"  name="qq_status" value="2" <?php if($data['qq_status'] == 2){echo 'checked';} ?>>&nbsp;否&nbsp;&nbsp;
				[<a href="http://connect.qq.com/">申请API</a>]
           
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="qq_app_id">APP ID:</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="qq_app_id"  id="qq_app_id" value="<?php echo $data['qq_app_id'];?>" class="ui-input" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="qq_app_key">KEY:</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="qq_app_key" id="qq_app_key" value="<?php echo $data['qq_app_key'];?>" class="ui-input"  />
          </div>
        </li>       
      </ul>
    </div>
	
    <div class="para-item">
      <h3>微博互联</h3>
      <ul class="mod-form-rows" id="establish-form">
        <li class="row-item">
          <div class="label-wrap">
            <label for="weibo_status">是否开启:</label>
          </div>
          <div class="sina ctn-wrap">
            <input type="radio" name="weibo_status" value="1" <?php if($data['weibo_status'] == 1){echo 'checked';} ?> >&nbsp;是&nbsp;
			<input type="radio" name="weibo_status" value="2" <?php if($data['weibo_status'] == 2){echo 'checked';} ?> >&nbsp;否&nbsp;
			[<a href="http://open.weibo.com/connect">审请API</a>]
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="companyAddress">APP ID：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" class="ui-input" name="weibo_app_id" id="weibo_app_id" value="<?php echo $data['weibo_app_id'];?>" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="companyTel">KEY：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" class="ui-input" name="weibo_app_key" id="weibo_app_key" value="<?php echo $data['weibo_app_key'];?>" />
          </div>
        </li>       
      </ul>
    </div>
	
	<div class="para-item">
      <h3>微信互联</h3>
      <ul class="mod-form-rows" id="establish-form">
        <li class="row-item">
          <div class="label-wrap">
            <label for="companyName">是否开启</label>
          </div>
          <div class="weixin ctn-wrap">
            <input type="radio" name="weixin_status" value="1" <?php if($data['weixin_status'] == 1){echo 'checked';} ?> >&nbsp;是&nbsp;
			<input type="radio"  name="weixin_status" value="2" <?php if($data['weixin_status'] == 2){echo 'checked';} ?> >&nbsp;否&nbsp;&nbsp;
			[<a href="http://open.weixin.qq.com">审请API</a>]
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="companyAddress">APP ID：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" class="ui-input" name="weixin_app_id" id="weixin_app_id" value="<?php echo $data['weixin_app_id'];?>" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="companyTel">KEY:</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" class="ui-input" name="weixin_app_key" id="weixin_app_key" value="<?php echo $data['weixin_app_key'];?>" />
          </div>
        </li>       
      </ul>
    </div>
    
        <div class="btn-wrap"> <a name="submit" id="submit" class="ui-btn ui-btn-sp">提交</a> </div>
  </div>
</div>


<?php
include TPL_PATH . '/'  . 'footer.php';
?>
<script type="text/javascript" src="<?=$this->view->js?>/jquery.js"></script>
<script>
	$('#submit').click(function(event){
		
　　	var qq_status = $('.QQ input[name="qq_status"]:checked ').val();
		var qq_app_id = $('#qq_app_id').val();
		var qq_app_key = $('#qq_app_key').val();
		
		var weibo_status = $('.sina input[name="weibo_status"]:checked ').val();
		var weibo_app_id = $('#weibo_app_id').val();
		var weibo_app_key = $('#weibo_app_key').val();
		
		var weixin_status = $('.weixin input[name="weixin_status"]:checked ').val();
		var weixin_app_id = $('#weixin_app_id').val();
		var weixin_app_key = $('#weixin_app_key').val();
		
		if(qq_status == 1)
		{
			if(qq_app_id && qq_app_key)
			{
				flag1=1;
			}else{
				flag1=0;
				
				alert("失败,请填写申请的QQ的APPID和KEY");
				qq_status == NULL;
				
			}
			
		}
		if(weibo_status == 1)
		{
			if(weibo_app_id && weibo_app_key)
			{
				flag1=1;
			}else{
				flag1=0;
				alert("失败,请填写申请的新浪微博的APPID和KEY");
				weibo_status == NULL;
			}
		}
		if(weixin_status == 1)
		{
			if(weixin_app_id && weixin_app_key)
			{
				flag1=1;
			}else{
				flag1=0;
				alert("失败,请填写申请的微信的APPID和KEY");
				weixin_status == NULL;
			}
		}

		$.post("./index.php?ctl=Intercon_Userinter&met=getload",{"qq_status":qq_status,"qq_app_id":qq_app_id,"qq_app_key":qq_app_key,"weibo_status":weibo_status,"weibo_app_id":weibo_app_id,"weibo_app_key":weibo_app_key,"weixin_status":weixin_status,"weixin_app_id":weixin_app_id,"weixin_app_key":weixin_app_key},function(data){
			  console.info(data);
			  if(data.status == 200){
					alert("提交成功");
				}else{
					alert("提交失败");
				}
		});
	});
	
</script>
