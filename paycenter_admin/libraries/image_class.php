<?php
class cls_image
{
    var $bgcolor     = '#FFFFFF';
    var $type_maping = array(1 => 'image/gif', 2 => 'image/jpeg', 3 => 'image/png');
	var $watermark	 ='';//是否加水印

    function cls_image($bgcolor='')
    {
        ;
    }
    /**
     * 创建图片的缩略图
     *
     * @access  public
     * @param   string      $img    原始图片的路径
     * @param   int         $thumb_width  缩略图宽度
     * @param   int         $thumb_height 缩略图高度
     * @param   strint      $path         指定生成图片的目录名
     * @return  mix         如果成功返回缩略图的路径，失败则返回false
     */
    function make_thumb($img, $path = '',$thumb_width = 0, $thumb_height = 0,  $bgcolor='')
    {
		//=======================================================
		if($this->watermark)
		{	

		}
		//========================================================
		
        /* 检查缩略图宽度和高度是否合法 */
        if ($thumb_width == 0 && $thumb_height == 0)
        {
            return str_replace(ROOT_PATH, '', str_replace('\\', '/', realpath($img)));
        }

        /* 检查原始文件是否存在及获得原始文件的信息 */
        $org_info = @getimagesize($img);
        if (!$org_info)
            return false;
        if (!$this->check_img_function($org_info[2]))
             return false;
        $img_org = $this->img_resource($img, $org_info[2]);

        /* 原始图片以及缩略图的尺寸比例 */
        $scale_org      = $org_info[0] / $org_info[1];
        /* 处理只有缩略图宽和高有一个为0的情况，这时背景和缩略图一样大 */
        if ($thumb_width == 0)
            $thumb_width = $thumb_height * $scale_org;
        if ($thumb_height == 0)
            $thumb_height = $thumb_width / $scale_org;

        /* 创建缩略图的标志符 */
        $img_thumb  = imagecreatetruecolor($thumb_width, $thumb_height);
        /* 背景颜色 */
        $bgcolor = $this->bgcolor;
        $bgcolor = trim($bgcolor,"#");
        sscanf($bgcolor, "%2x%2x%2x", $red, $green, $blue);
        $clr = imagecolorallocate($img_thumb, $red, $green, $blue);
        imagefilledrectangle($img_thumb, 0, 0, $thumb_width, $thumb_height, $clr);

        if ($org_info[0] / $thumb_width > $org_info[1] / $thumb_height)
        {
            $lessen_width  = $thumb_width;
            $lessen_height  = $thumb_width / $scale_org;
        }
        else
        {
            /* 原始图片比较高，则以高度为准 */
            $lessen_width  = $thumb_height * $scale_org;
            $lessen_height = $thumb_height;
        }

        $dst_x = ($thumb_width  - $lessen_width)  / 2;
        $dst_y = ($thumb_height - $lessen_height) / 2;

        /* 将原始图片进行缩放处理 */
         imagecopyresampled($img_thumb, $img_org, $dst_x, $dst_y, 0, 0, $lessen_width, $lessen_height, $org_info[0], $org_info[1]);
        /* 生成文件 */
        if (function_exists('imagejpeg'))
        {
            imagejpeg($img_thumb, $path,100);
        }
		imagedestroy($img_thumb);
        imagedestroy($img_org);
    }

    /*------------------------------------------------------ */
    //-- 工具函数
    /*------------------------------------------------------ */
    /**
     * 检查图片类型
     * @param   string  $img_type   图片类型
     * @return  bool
     */
    function check_img_type($img_type)
    {
        return $img_type == 'image/pjpeg' ||
               $img_type == 'image/x-png' ||
               $img_type == 'image/png'   ||
               $img_type == 'image/gif'   ||
               $img_type == 'image/jpeg';
    }
    /**
     * 检查图片处理能力
     *
     * @access  public
     * @param   string  $img_type   图片类型
     * @return  void
     */
    function check_img_function($img_type)
    {
        switch ($img_type)
        {
            case 'image/gif':
            case 1:

                if (PHP_VERSION >= '4.3')
                {
                    return function_exists('imagecreatefromgif');
                }
                else
                {
                    return (imagetypes() & IMG_GIF) > 0;
                }
            break;

            case 'image/pjpeg':
            case 'image/jpeg':
            case 2:
                if (PHP_VERSION >= '4.3')
                {
                    return function_exists('imagecreatefromjpeg');
                }
                else
                {
                    return (imagetypes() & IMG_JPG) > 0;
                }
            break;

            case 'image/x-png':
            case 'image/png':
            case 3:
                if (PHP_VERSION >= '4.3')
                {
                     return function_exists('imagecreatefrompng');
                }
                else
                {
                    return (imagetypes() & IMG_PNG) > 0;
                }
            break;

            default:
                return false;
        }
    }

    /**
     *  返回文件后缀名，如‘.php’
     *
     * @access  public
     * @param
     *
     * @return  string      文件后缀名
     */
    function get_filetype($path)
    {
        $pos = strrpos($path, '.');
        if ($pos !== false)
        {
            return substr($path, $pos);
        }
        else
        {
            return '';
        }
    }

     /**
     * 根据来源文件的文件类型创建一个图像操作的标识符
     *
     * @access  public
     * @param   string      $img_file   图片文件的路径
     * @param   string      $mime_type  图片文件的文件类型
     * @return  resource    如果成功则返回图像操作标志符，反之则返回错误代码
     */
    function img_resource($img_file, $mime_type)
    {
        switch ($mime_type)
        {
            case 1:
            case 'image/gif':
                $res = imagecreatefromgif($img_file);
                break;

            case 2:
            case 'image/pjpeg':
            case 'image/jpeg':
                $res = imagecreatefromjpeg($img_file);
                break;

            case 3:
            case 'image/x-png':
            case 'image/png':
                $res = imagecreatefrompng($img_file);
                break;

            default:
                return false;
        }

        return $res;
    }
////////////////////////////////////////////////////////////////////////////////////////////////
function  ImageColor($image,$color)
{
    preg_match_all("/([0-f]){2,2}/i",$color,$out);
    if(count($out[0])!=3)$out[0]=array_pad ($out[0],3,0);
    return ImageColorAllocate($image, hexdec($out[0][0]),hexdec($out[0][1]),hexdec($out[0][2])); 
}
function imageWaterMark($sourceImage,$waterPos=9,$waterImage="",$waterText="",$textFont=14,$textColor="#339900") 
{	
    $isWaterImage = FALSE;
    if(!empty($waterImage) && file_exists($waterImage))
	{
        $isWaterImage = TRUE; 
		$watermark_info     = @getimagesize($waterImage);
        $water_handle   = $this->img_resource($waterImage, $watermark_info[2]);
        $water_w    = $watermark_info[0];
        $water_h    = $watermark_info[1];
    }
	$source_info    = @getimagesize($sourceImage);
    $source_handle  = $this->img_resource($sourceImage, $source_info[2]);
    $ground_w  = $source_info[0];
    $ground_h = $source_info[1];
	//---------------
    if($isWaterImage)
	{
        $w = $water_w;
        $h = $water_h;
	}
	else
	{
        $w = strlen($waterText)*9;
        $h = 15; 
    }
	$offset = 5;
    switch($waterPos){
        case 0:
            $posX = rand(0,($ground_w - $w));
            $posY = rand(0,($ground_h - $h));
            break;
        case 1:
            $posX = $offset;
            $posY = $offset;
            break;
        case 2:
            $posX = ($ground_w - $w) / 2;
            $posY = $offset;
            break;
        case 3:
            $posX = $ground_w - $w;
            $posY = $offset;
			if($posX > $offset)
				$posX = $posX - $offset;
            break;
        case 4:
            $posX = $offset;
            $posY = ($ground_h - $h) / 2;
            break;
        case 5:
            $posX = ($ground_w - $w) / 2;
            $posY = ($ground_h - $h) / 2;
            break;
        case 6:
            $posX = $ground_w - $w;
			if($posX > $offset)
				$posX = $posX  - $offset ;
            $posY = ($ground_h - $h) / 2;
            break;
        case 7:
            $posX = $offset;
            $posY = $ground_h - $h;
			if($posY > $offset )
				$posY = $posY - $offset;
            break;
        case 8:
            $posX = ($ground_w - $w) / 2;
            $posY = $ground_h - $h;
			if($posY > $offset )
				$posY = $posY - $offset;
            break;
        case 9:
            $posX = $ground_w - $w;
            $posY = $ground_h - $h;
			if($posX > $offset && $posY > $offset){
				$posY = $posY - $offset;
				$posX = $posX - $offset;
			}
            break;
        default:
            $posX = rand(0,($ground_w - $w));
            $posY = rand(0,($ground_h - $h));
            break;     
    }
    imagealphablending($source_handle, true);
    if($isWaterImage)
        imagecopy($source_handle, $water_handle, $posX, $posY, 0, 0, $water_w,$water_h);
    else
        imagestring($source_handle,$textFont,$posX,$posY,$waterText,$this->ImageColor($source_handle,$textColor));
		
	imagejpeg($source_handle, $sourceImage,100);
    if(isset($water_info)) unset($water_info);
    if(isset($water_handle)) imagedestroy($water_handle);
	if(isset($source_handle)) imagedestroy($source_handle);
}
}

?>