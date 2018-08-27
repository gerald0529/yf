<?php
/*
 * @Copyright (c) 2007,涓婃捣鍙嬮偦淇℃伅绉戞妧鏈夐檺鍏徃
 * @All	rights reserved.
 *
 *
 *
 * @filename   setavatar.php
 * @category
 * @package
 * @author     Xinze <xinze@live.cn>
 * @date       2009-05-26 15:25:20
 */

/**
 * Class and Function List:
 * Function list:
 * - load()
 * - resize()
 * - cut()
 * - display()
 * - save()
 * - destroy()
 * - getxy()
 * - get_type()
 * Classes list:
 * - ImageResize
 */
class Image_Resize
{
	//源图象
	var $_img;

	//图片类型
	var $_imageType;

	//实际宽度
	var $_width;

	//实际高度
	var $_height;

	//载入图片
	function load($img_name, $img_type = '')
	{

		if (!empty($img_type))
		{
			$this->_imageType = $img_type;
		}
		else
		{
			$this->_imageType = $this->get_type($img_name);
		}

		switch ($this->_imageType)
		{
			case 'gif':

				if (function_exists('imagecreatefromgif'))
				{
					$this->_img = imagecreatefromgif($img_name);
				}
				break;

			case 'jpg':
			case 'jpeg':
				$this->_img = imagecreatefromjpeg($img_name);
				break;

			case 'png':
				$this->_img = imagecreatefrompng($img_name);
				imagesavealpha($this->_img, true);//这里很重要;
				break;

			default:
				//$this->_img = imagecreatefromstring($img_name);
				$this->_img = imagecreatefromjpeg($img_name);
				break;
		}

		$this->getxy();
	}

	//缩放图片
	function resize($width, $height, $percent = 0)
	{

		if (!is_resource($this->_img))
		{
			return false;
		}

		if (empty($width) && empty($height))
		{

			if (empty($percent))
			{
				return false;
			}
			else
			{
				$width  = round($this->_width * $percent);
				$height = round($this->_height * $percent);
			}
		}
		elseif (empty($width) && !empty($height))
		{
			$width = round($height * $this->_width / $this->_height);
		}
		else
		{
			if ($this->_height > $this->_width)
			{
				$width = round($height * $this->_width / $this->_height);
			}
			else
			{
				$height = round($width * $this->_height / $this->_width);
			}
		}
		$tmpimg = imagecreatetruecolor($width, $height);

		imagealphablending($tmpimg, false);//这里很重要,意思是不合并颜色,直接用$img图像颜色替换,包括透明色;
		imagesavealpha($tmpimg, true);//这里很重要,意思是不要丢了$thumb图像的透明色;

		if (function_exists('imagecopyresampled'))
		{
			imagecopyresampled($tmpimg, $this->_img, 0, 0, 0, 0, $width, $height, $this->_width, $this->_height);
		}
		else
		{
			imagecopyresized($tmpimg, $this->_img, 0, 0, 0, 0, $width, $height, $this->_width, $this->_height);
		}
		$this->destroy();
		$this->_img = $tmpimg;
		$this->getxy();
	}

	//裁剪图片
	function cut($width, $height, $x = 0, $y = 0)
	{

		if (!is_resource($this->_img))
		{
			return false;
		}

		if ($width > $this->_width)
		{
			$width = $this->_width;
		}

		if ($height > $this->_height)
		{
			$height = $this->_height;
		}

		if ($x < 0)
		{
			$x = 0;
		}

		if ($y < 0)
		{
			$y = 0;
		}

		$tmpimg = imagecreatetruecolor($width, $height);
		imagealphablending($tmpimg, false);//这里很重要,意思是不合并颜色,直接用$img图像颜色替换,包括透明色;
		imagesavealpha($tmpimg, true);//这里很重要,意思是不要丢了$thumb图像的透明色;

		imagecopy($tmpimg, $this->_img, 0, 0, $x, $y, $width, $height);

		$tmpImgObj = new Image_Resize();


		$tmpImgObj->_img = $tmpimg;
		$tmpImgObj->getxy();

		return $tmpImgObj;
	}


	//增大背景
	function resize_bg($width, $height)
	{
		if (!is_resource($this->_img))
		{
			return false;
		}

		$tmpImgObj = new Image_Resize();
		$tmpimg    = imagecreatetruecolor($width, $height);

		$color = imagecolorallocatealpha($tmpimg, 0, 0, 0, 127);

		imagecolortransparent($tmpimg, $color);
		imagefill($tmpimg, 0, 0, $color);

		imagealphablending($tmpimg, false);//这里很重要,意思是不合并颜色,直接用$img图像颜色替换,包括透明色;
		imagesavealpha($tmpimg, true);//这里很重要,意思是不要丢了$thumb图像的透明色;


		imagecopy($tmpimg, $this->_img, ($width - $this->_width) / 2, $height - ($this->_height + $height * 0.1), 0, 0, $this->_width, $this->_height);
		$this->destroy();

		$tmpImgObj->_img = $tmpimg;
		$tmpImgObj->getxy();

		return $tmpImgObj;
	}


	//x轴翻转
	function turn_x()
	{
		if (!is_resource($this->_img))
		{
			return false;
		}

		$width  = imagesx($this->_img);
		$height = imagesy($this->_img);

		$tmpimg = imagecreatetruecolor($width, $height);
		imagealphablending($tmpimg, false);//这里很重要,意思是不合并颜色,直接用$img图像颜色替换,包括透明色;
		imagesavealpha($tmpimg, true);//这里很重要,意思是不要丢了$thumb图像的透明色;


		for ($x = 0; $x < $width; $x++)
		{
			//$width-$x-1 例如宽度500 那么每次复制1像素$height高度,那么复制到$new上,就要有1像素宽度,所以500-1=499 ，x=499,位置出粘贴复制过来的内容才正确(x轴翻转也一样)
			imagecopy($tmpimg, $this->_img, $width - $x - 1, 0, $x, 0, 1, $height);
		}


		$this->destroy();
		$this->_img = $tmpimg;
		$this->getxy();

	}

	//y轴翻转
	function turn_y()
	{
		if (!is_resource($this->_img))
		{
			return false;
		}

		$width  = imagesx($this->_img);
		$height = imagesy($this->_img);

		$tmpimg = imagecreatetruecolor($width, $height);
		imagealphablending($tmpimg, false);//这里很重要,意思是不合并颜色,直接用$img图像颜色替换,包括透明色;
		imagesavealpha($tmpimg, true);//这里很重要,意思是不要丢了$thumb图像的透明色;

		for ($y = 0; $y < $height; $y++)
		{
			//if (function_exists('imagecopyresampled')) imagecopyresampled($tmpimg,$this->_img,0,$height-$y-1,0,$y,$width,1);
			//else imagecopyresized($tmpimg,$this->_img,0,$height-$y-1,0,$y,$width,1);

			imagecopy($tmpimg, $this->_img, 0, $height - $y - 1, 0, $y, $width, 1);
		}

		$this->destroy();
		$this->_img = $tmpimg;
		$this->getxy();

	}

	//显示图片
	function display($destroy = true)
	{

		if (!is_resource($this->_img))
		{
			return false;
		}

		switch ($this->_imageType)
		{
			case 'jpg':
			case 'jpeg':
				header("Content-type: image/jpeg");
				imagejpeg($this->_img, null, 100);
				break;

			case 'gif':
				header("Content-type: image/gif");
				imagegif($this->_img);
				break;

			case 'png':
			default:
				header("Content-type: image/png");

				if (version_compare(phpversion(), '5.1.2') >= 0)
				{
					//针对php版本大于5.12参数变化后的处理情况
					$quality = 9;
				}
				else
				{
					$quality = 100;
				}

				imagepng($this->_img, null, $quality);
				break;
		}

		if ($destroy)
		{
			$this->destroy();
		}
	}

	//保存图片 $destroy=true 是保存后销毁图片变量，false这不销毁，可以继续处理这图片

	function save($fname, $destroy = false, $type = '')
	{

		if (!is_resource($this->_img))
		{
			return false;
		}

		if (empty($type))
		{
			$type = $this->_imageType;
		}

		switch ($type)
		{
			case 'jpg':
			case 'jpeg':
				$ret = imagejpeg($this->_img, $fname, 100);
				break;

			case 'gif':
				$ret = imagegif($this->_img, $fname);
				break;

			case 'png':
			default:
				if (version_compare(phpversion(), '5.1.2') >= 0)
				{
					//针对php版本大于5.12参数变化后的处理情况
					$quality = 9;
				}
				else
				{
					$quality = 100;
				}

				$ret = imagepng($this->_img, $fname, $quality);
				break;
		}

		if ($destroy)
		{
			$this->destroy();
		}

		return $ret;
	}

	//销毁图像

	function destroy()
	{

		if (is_resource($this->_img))
		{
			imagedestroy($this->_img);
		}
	}

	//取得图像长宽

	function getxy()
	{

		if (is_resource($this->_img))
		{
			$this->_width  = imagesx($this->_img);
			$this->_height = imagesy($this->_img);
		}
	}

	//获得图片的格式，包括jpg,png,gif

	function get_type($img_name) //获取图像文件类型

	{

		if (preg_match("/\.(jpg|jpeg|gif|png)$/i", $img_name, $matches))
		{
			$type = strtolower($matches[1]);
		}
		else
		{
			$type = "string";
		}

		return $type;
	}
}


?>