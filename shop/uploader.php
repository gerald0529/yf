<?php
$key = 'abcdgfgsgfsgfsg23132';

function array_multiksort(&$rows)
{
	foreach ($rows as $key => $row)
	{
		if (is_array($row))
		{
			array_multiksort($rows[$key]);
		}
	}

	ksort($rows, SORT_STRING);
}

$rs = array();

$formvars = $_POST;

$token = $formvars['token'];
unset($formvars['token']);

$hash_row = $formvars;
array_multiksort($hash_row, SORT_STRING);

$hash_row['key'] = $key;
$tmp_str = http_build_query($hash_row);


//可以判断请求时间是否超过某个期限, 1分钟内
if ((time() - $hash_row['rtime'] < 600) && $token == md5($tmp_str))
{
	if ($_FILES)
	{
		$filename = $_FILES['upfile']['name'];
		$tmpname  = $_FILES['upfile']['tmp_name'];

		$full_name = isset($_REQUEST['full_name']) ? $_REQUEST['full_name'] : '/' . $filename;

		$file_path = './' . $full_name;

		$dir = dirname($file_path);

		if (!file_exists($dir))
		{
			mkdir(dirname($file_path), 0777, true);
		}

		if (move_uploaded_file($tmpname, $file_path))
		{
			$rs['status'] = 200;
			$rs['msg'] = 'success';


			$path_row =   pathinfo(sprintf('http://%s%s', $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']));
			$url = sprintf('%s/image.php%s', $path_row['dirname'], $full_name);
			$rs['url'] = $url;
		}
		else
		{
			$rs['status'] = 250;
			$rs['msg'] = 'failure';
		}
	}
	else
	{
		$rs['status'] = 250;
		$rs['msg'] = 'failure';
	}
}
else
{
	$rs['status'] = 250;
	$rs['msg'] = 'key错误 或者 超时';
}



echo json_encode($rs);
?>