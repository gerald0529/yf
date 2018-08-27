<?php
include_once("./function.php"); 
$path="";
$str="";
$webroot=substr(dirname(__FILE__), 0, -9);
$webroot = str_replace("\\","/",$webroot);
$http_host = $_SERVER['HTTP_HOST'];
$php_url = dirname($_SERVER['PHP_SELF']) . '/';

$path=$webroot.'uploadfile/';

$m=$_GET['m']?$_GET['m']:"";
if(!empty($m))
{	
	$path.=$m.'/';	
	
}
else
{
	$path.='all/';
}
$ist="1";
switch ($ist){
	case "1":
	{	
		$path.=date('Y').'/'.date('m').'/'.date('d').'/';
		break;
	}
	case "2":
	{	
		$path.=date('Y').'/'.date('m').'/';
		break;
	}
	case "3":
	{	
		$path.=date('Y').'/';
		break;
	}
	default:
	{
		break;
	}
}
$size=array('60','120','220');
$size1=array('30');
//==============================================
if($_FILES)
{
if(is_uploaded_file($_FILES['pic']['tmp_name']))
{
	$file_name = $_FILES['pic']['name'];
	$file_size = $_FILES['pic']['size'];
	$max_size = 1024*1024;
	
	$ext_arr = explode(',','gif,jpg,jpeg,bmg,png,tbi');
	if ($file_size > $max_size) {
		echo "<script>alert('上传文件大小超过限制。');window.parent.close_win();</script>";die;
	}
	
	//获得文件扩展名
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);

	//检查扩展名
	if (in_array($file_ext, $ext_arr) === false) {
		echo "<script>alert('上传文件扩展名是不允许的扩展名。');window.parent.close_win();</script>";die;
	}
	
		
		if(!empty($_GET['watermark']))
			$watermark=false;
		else
			$watermark=true;
			
		$pn=time().".jpg";
		$pw=$_POST['pw']?$_POST['pw']:$_GET['pw'];
		$ph=$_POST['ph']?$_POST['ph']:$_GET['ph'];
		
	
		if(!file_exists($path))
		{
			mkdirs($path);
		}
		
		if($_GET['m']=='product'||$_GET['m']=='product/property')
		{	
			$size=($_GET['m']=='product/property')?$size1:$size;
			foreach($size as $key=>$val)
			{
				makethumb($_FILES['pic']['tmp_name'], $path.$pn."_".$val."X".$val.".jpg",$val,$val,false);
			}
			$info = @getimagesize($_FILES['pic']['tmp_name']);
			makethumb($_FILES['pic']['tmp_name'], $path.$pn,$info[0],$info[1]);
			$str="window.parent.load_pic();";
		}
		else
		{
			makethumb($_FILES['pic']['tmp_name'],$path.$pn,$pw,$ph,$watermark);
		}

		$pn=str_replace($webroot,'http://'.$http_host.dirname($php_url).'/',$path).$pn;

		$str.="window.parent.document.getElementById('$_GET[obj]').value='$pn';";
		$str.="if(window.parent.document.getElementById('$_GET[obj]_img')){window.parent.document.getElementById('$_GET[obj]_img').src='$pn';}";
		echo "<script>$str;window.parent.close_win();</script>";
	
	die;
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>图片上传</title>
</head>
<style>
*{font-family:Arial, Helvetica, sans-serif;}
td{font-size:12px; padding:5px;}
.btn{border:none;width:48px;height:23px;background:url(../admin/static/default/images/img/btn1.png) no-repeat #FFF;padding-bottom:1px;margin:4px 5px 0 0;}
.text{margin:0 3px;border:1px solid #BEBEBE;padding:3px;}
#preview{width:380px;height:260px;overflow:scroll;text-align:center;}
#preview img{border:1px solid #CCCCCC}
</style>
<body>
<?php if(empty($_GET['pv'])){ ?>
<form action="" method="post" enctype="multipart/form-data">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    	<td>
        <input name="pic" type="file" id="pic" style="width:200px;" /><br />
        <font style="color:#666666">(支持格式:Jpeg,Jpg Gif,Png 小于1MB)</font>
        </td>
    </tr>
  <?php if($_GET['m']!='product'){ ?>
  <tr>
    <td>
	  宽度<input name="pw" class="text" type="text" id="pw" value="<?php echo $_GET['pw'];?>" size="3" />px &nbsp;
	  高度<input name="ph" class="text" type="text" id="ph" value="<?php echo $_GET['ph'];?>" size="3" />px
	  </td>
  </tr>
  <?php } ?>
  <tr>
    <td>
      <input class="btn" type="submit" value="提交" />
      <input class="btn" type="reset" onclick="window.parent.close_win();" value="取消" />
    </td>
  </tr>
</table>
</form>
<?php
}
else
{
?>
<div id="preview"></div>
<script>
str=window.parent.document.getElementById('<?php echo $_GET['obj'];?>_img').value;
if(str=='')
	str='图片地址为空，无法预览';
else
	str='<img src='+str+'>';
document.getElementById('preview').innerHTML=str;
</script>
<?php } ?>
</body>
</html>
