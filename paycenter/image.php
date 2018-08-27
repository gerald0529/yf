<?php
$seconds_to_cache = 3600*24*10;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header("Expires: $ts");
header("Cache-Control: public");
header("Pragma: cache");
header("Cache-Control: max-age=$seconds_to_cache");
header("Last-Modified: $ts");

if(isset($_SERVER['PATH_INFO']))
{
	$etag = md5($_SERVER['PATH_INFO']);

	if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $etag == $_SERVER['HTTP_IF_NONE_MATCH']) {
		header("Etag:" . $etag, true, 304);
		exit();
	}

	header('Etag: ' . $etag);
}

//http://127.0.0.1/yf_shop/image.php/shop_admin/04626264083026214_mid.jpg!300x43.jpg
define('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)));

if(isset($_SERVER['PATH_INFO']))
{
	$file = $_SERVER['PATH_INFO'];

	$file_row = explode('!', $file);

	//原图
	$image_ori = $file_row[0];

	$ext_row = pathinfo($file);

	switch ($ext_row['extension'])
	{
		case 'jpg':
		case 'jpeg':
			header("Content-type: image/jpeg");
			break;

		case 'gif':
			header("Content-type: image/gif");
			break;

		case 'png':
			header("Content-type: image/png");
			break;
		default:
			header("Content-type: image/png");
			break;
	}

	ob_start ();//开始截获输出流

	if (isset($file_row[1]))
	{
		$image_size = $file_row[1];

		//读取缩略尺寸
		$file_path = ROOT_PATH . $file;

		if (is_file($file_path))
		{
			echo file_get_contents($file_path);
		}
		else
		{
			$image_ori_path = ROOT_PATH . $image_ori;

			if (is_file($image_ori_path))
			{
				//生成缩略图
				include_once ROOT_PATH . '/libraries/Image/Resize.php';
				$Image_Resize = new Image_Resize();
				$Image_Resize->load($image_ori_path);


				//430x430q90.jpg
				$imgge_size_q_row = explode('.', $image_size);
				//$imgge_size_q_row = explode('q', $image_size);

				$imgge_size_str = $imgge_size_q_row[0];

				$imgge_size_row = explode('x', $imgge_size_str);

				$width = $imgge_size_row[0];

				$imgge_size_row[1] = isset($imgge_size_row[1]) ? intval($imgge_size_row[1]) : 10;

				$height = isset($imgge_size_row[1]) ? $imgge_size_row[1] : 1;

				$Image_Resize->resize($width, $height);
				$flag = $Image_Resize->save($file_path);

				if ($flag && is_file($file_path))
				{
					echo file_get_contents($file_path);
				}
			}
		}
	}
	else
	{
		$image_ori_path = ROOT_PATH . $image_ori;

		//原图
		echo file_get_contents($image_ori_path);
	}


	$content = ob_get_contents ();//获取输出流
	ob_end_flush ();//输出流到网页,保证第一次请求也有图片数据放回
}

?>