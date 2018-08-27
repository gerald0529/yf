<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class UploadCtl extends Yf_AppController
{
	public $uploadModel = null;
	public $config      = null;

	/**
	 * Constructor 用户上传目录 user_id/shop_id/
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		if (request_string('action'))
		{
			$met = request_string('action');
		}

		parent::__construct($ctl, $met, $typ);

		if (!request_string('plantform') && Perm::$login && Perm::$shopId)
		{
			if (Perm::$shopId && Perm::$userId)
			{
				$dir_path = sprintf('/media/%s/%d/%d', Yf_Registry::get('server_id'), Perm::$userId, Perm::$shopId);
			}
			else
			{
				$dir_path = sprintf('/media/%s/%d', Yf_Registry::get('server_id'), Perm::$userId);
			}
		}
		else
		{
			$dir_path = '/media/plantform/' . Yf_Registry::get('server_id');
		}

		$Web_ConfigModel    = new Web_ConfigModel();
		$image_allow_ext    = $Web_ConfigModel->getConfigValue('image_allow_ext');
		$image_max_filesize = $Web_ConfigModel->getConfigValue('image_max_filesize') * 1024;

		$url_prefix = Yf_Registry::get('base_url') . '/' . APP_DIR_NAME . '/data/upload';
		$url_prefix = '';

		/* 上传图片配置项 */
		$this->config = array(
			/* 执行上传图片的action名称 */
			'imageActionName' => 'uploadImage',
			/* 提交的图片表单名称 */
			'imageFieldName' => 'upfile',
			/* 上传大小限制，单位B */
			'imageMaxSize' => $image_max_filesize,
			/* 上传图片格式显示 */
			'imageAllowFiles' => array(
				'.png',
				'.jpg',
				'.jpeg',
				'.gif',
				'.bmp',
			),
			/* 是否压缩图片,默认是true */
			'imageCompressEnable' => false,
			/* 图片压缩最长边限制 */
			'imageCompressBorder' => 1600,
			/* 插入的图片浮动方式 */
			'imageInsertAlign' => 'none',
			/* 图片访问路径前缀 */
			'imageUrlPrefix' => $url_prefix,
			/* 上传保存路径,可以自定义保存路径和文件名格式 */
			/* {filename} 会替换成原文件名,配置这项需要注意中文乱码问题 */
			/* {rand:6} 会替换成随机数,后面的数字是随机数的位数 */
			/* {time} 会替换成时间戳 */
			/* {yyyy} 会替换成四位年份 */
			/* {yy} 会替换成两位年份 */
			/* {mm} 会替换成两位月份 */
			/* {dd} 会替换成两位日期 */
			/* {hh} 会替换成两位小时 */
			/* {ii} 会替换成两位分钟 */
			/* {ss} 会替换成两位秒 */
			/* 非法字符 \ : * ? " < > | */
			/* 具请体看线上文档: fex.baidu.com/ueditor/#use-format_upload_filename */

			'imagePathFormat' => $dir_path . '/image/{yyyy}{mm}{dd}/{time}{rand:6}',

			/* 涂鸦图片上传配置项 */
			'scrawlActionName' => 'uploadScrawl',
			'scrawlFieldName' => 'upfile',
			'scrawlPathFormat' => $dir_path . '/image/{yyyy}{mm}{dd}/{time}{rand:6}',
			'scrawlMaxSize' => 2048000,
			'scrawlUrlPrefix' => $url_prefix,
			'scrawlInsertAlign' => 'none',
			'snapscreenActionName' => 'uploadImage',
			'snapscreenPathFormat' => $dir_path . '/image/{yyyy}{mm}{dd}/{time}{rand:6}',
			'snapscreenUrlPrefix' => $url_prefix,
			'snapscreenInsertAlign' => 'none',
			'catcherLocalDomain' => array(
				'127.0.0.1',
				'localhost',
				'img.baidu.com',
			),
			'catcherActionName' => 'catchImage',
			'catcherFieldName' => 'source',
			'catcherPathFormat' => $dir_path . '/image/{yyyy}{mm}{dd}/{time}{rand:6}',
			'catcherUrlPrefix' => $url_prefix,
			'catcherMaxSize' => 2048000,
			'catcherAllowFiles' => array(
				'.png',
				'.jpg',
				'.jpeg',
				'.gif',
				'.bmp',
			),
			'videoActionName' => 'uploadVideo',
			'videoFieldName' => 'upfile',
			'videoPathFormat' => $dir_path . '/video/{yyyy}{mm}{dd}/{time}{rand:6}',
			'videoUrlPrefix' => $url_prefix,
			'videoMaxSize' => 102400000,
			'videoAllowFiles' => array(
				'.flv',
				'.swf',
				'.mkv',
				'.avi',
				'.rm',
				'.rmvb',
				'.mpeg',
				'.mpg',
				'.ogg',
				'.ogv',
				'.mov',
				'.wmv',
				'.mp4',
				'.webm',
				'.mp3',
				'.wav',
				'.mid',
			),
			'fileActionName' => 'uploadFile',
			'fileFieldName' => 'upfile',
			'filePathFormat' => $dir_path . '/file/{yyyy}{mm}{dd}/{time}{rand:6}',
			'fileUrlPrefix' => $url_prefix,
			'fileMaxSize' => 51200000,
			'fileAllowFiles' => array(
				'.png',
				'.jpg',
				'.jpeg',
				'.gif',
				'.bmp',
				'.flv',
				'.swf',
				'.mkv',
				'.avi',
				'.rm',
				'.rmvb',
				'.mpeg',
				'.mpg',
				'.ogg',
				'.ogv',
				'.mov',
				'.wmv',
				'.mp4',
				'.webm',
				'.mp3',
				'.wav',
				'.mid',
				'.rar',
				'.zip',
				'.tar',
				'.gz',
				'.7z',
				'.bz2',
				'.cab',
				'.iso',
				'.doc',
				'.docx',
				'.xls',
				'.xlsx',
				'.ppt',
				'.pptx',
				'.pdf',
				'.txt',
				'.md',
				'.xml',
				'.cvs',
			),
			'imageManagerActionName' => 'listImage',
			'imageManagerListPath' => $dir_path . '/image/',
			'imageManagerListSize' => 20,
			'imageManagerUrlPrefix' => $url_prefix,
			'imageManagerInsertAlign' => 'none',
			'imageManagerAllowFiles' => array(
				'.png',
				'.jpg',
				'.jpeg',
				'.gif',
				'.bmp',
			),
			'fileManagerActionName' => 'listFile',
			'fileManagerListPath' => $dir_path . '/file/',
			'fileManagerUrlPrefix' => $url_prefix,
			'fileManagerListSize' => 20,
			'fileManagerAllowFiles' => array(
				'.png',
				'.jpg',
				'.jpeg',
				'.gif',
				'.bmp',
				'.flv',
				'.swf',
				'.mkv',
				'.avi',
				'.rm',
				'.rmvb',
				'.mpeg',
				'.mpg',
				'.ogg',
				'.ogv',
				'.mov',
				'.wmv',
				'.mp4',
				'.webm',
				'.mp3',
				'.wav',
				'.mid',
				'.rar',
				'.zip',
				'.tar',
				'.gz',
				'.7z',
				'.bz2',
				'.cab',
				'.iso',
				'.doc',
				'.docx',
				'.xls',
				'.xlsx',
				'.ppt',
				'.pptx',
				'.pdf',
				'.txt',
				'.md',
				'.xml',
			),
		);


		//include $this->view->getView();
		$this->uploadModel = new Upload_BaseModel();
	}


	public function config()
	{
		if ($jsonp_callback = request_string('callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->config) . ')');
		}
		else
		{
			echo json_encode($this->config);
		}

		die();
	}

	/**
	 * 上传图片
	 *
	 * @access public
	 */
	public function uploadImage()
	{
		$config = array(
			"pathFormat" => $this->config['imagePathFormat'],
			"maxSize" => $this->config['imageMaxSize'],
			"allowFiles" => $this->config['imageAllowFiles']
		);

		$field_name = $this->config['imageFieldName'];

		$this->uploadFile($field_name, $config);
	}


	/**
	 * 上传涂鸦
	 *
	 * @access public
	 */
	public function uploadScrawl()
	{
		$config = array(
			"pathFormat" => $this->config['scrawlPathFormat'],
			"maxSize" => $this->config['scrawlMaxSize'],
			"allowFiles" => $this->config['scrawlAllowFiles'],
			"oriName" => "scrawl.png"
		);

		$field_name = $this->config['scrawlFieldName'];
		$base64     = "base64";

		$this->uploadFile($field_name, $config, $base64);
	}

	/**
	 * 上传视频
	 *
	 * @access public
	 */
	public function uploadVideo()
	{
		$config     = array(
			"pathFormat" => $this->config['videoPathFormat'],
			"maxSize" => $this->config['videoMaxSize'],
			"allowFiles" => $this->config['videoAllowFiles']
		);
		$field_name = $this->config['videoFieldName'];

		$this->uploadFile($field_name, $config);
	}

	/**
	 * 上传文件
	 *
	 * @access public
	 */
	public function uploadFile($field_name = null, $config = array(), $base64 = "upload")
	{
		if (!$field_name || !$config)
		{
			$config = array(
				"pathFormat" => $this->config['filePathFormat'],
				"maxSize" => $this->config['fileMaxSize'],
				"allowFiles" => $this->config['fileAllowFiles'],
			);

			$field_name = $this->config['fileFieldName'];
		}

		if(isset($_REQUEST['base64']) && $_REQUEST['base64']) { //IOS端base64传输
			$base64 = 'base64';
			$config['oriName'] = 'remote.png';
		}
		/* 生成上传实例对象并完成上传 */
		$up = new Yf_Uploader($field_name, $config, $base64);

		$info = $up->getFileInfo(); 
		
		if ($info['state'] == "SUCCESS")
		{ 
			//使用云存储
			$new_url = (new YunOss())->run($info); 
			if($new_url){
				$info['url'] = $new_url;
				$info['url_prefix'] = ""; 
			}

			 
			//判断文件类型
			if (in_array($info['type'], $this->config['imageAllowFiles']))
			{
				$file_type = 'image';

				//默认把图片添加到默认相册
				$uploadBaseModel = new Upload_BaseModel();

				$user = request_string('user');
				if ( !empty($user) && $user == 'admin' )
				{
					$shop_id = Shop_BaseModel::ADMIN_SHOP_ID;
					$user_id = Shop_BaseModel::ADMIN_USER_ID;
				}
				else
				{
					$shop_id =Perm::$shopId;
					$user_id = Perm::$userId;
				}

				$data                      = array();
				$data['upload_time']       = $_SERVER['REQUEST_TIME'];
				$data['upload_url_prefix'] = $info['url_prefix'];
				$data['upload_path']       = $info['url_path'];
				$data['upload_path']       = $info['url'];
				$data['upload_size']       = $info['size'];
				$data['upload_name']       = str_replace($info['type'], '', $info['original']);      // 附件标题
				$data['upload_type']       = $file_type;                                             // 枚举
				$data['upload_mime_type']  = $info['type'];
				$data['album_id']          = Upload_BaseModel::UPLOAD_IMAGE_UNGROUP;                  // 默认添加到未分组里
				$data['user_id']           = $user_id; // 用户id
				$data['shop_id']           = $shop_id; // 店铺id

				$uploadBaseModel->addUpload($data);
			}
			else
			{
				$file_type = 'other';
			}
		}

		/**
		 * 得到上传文件所对应的各个参数,数组结构
		 * array(
		 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
		 *     "url" => "",            //返回的地址
		 *     "title" => "",          //新文件名
		 *     "original" => "",       //原始文件名
		 *     "type" => ""            //文件类型
		 *     "size" => "",           //文件大小
		 * )
		 */

		/* 返回数据 */
		echo json_encode($info);
		die();

	}


	/**
	 * 列出图片-目录读取
	 *
	 * @access public
	 */
	public function listImage()
	{
		$this->lists();
	}

	/**
	 * 列出文件列出图片-目录读取
	 *
	 * @access public
	 */
	public function listFile()
	{
	}

	/**
	 * 抓取远程文件
	 *
	 * @access public
	 */
	public function catchImage()
	{
		set_time_limit(0);

		/* 上传配置 */
		$config = array(
			"pathFormat" => $this->config['catcherPathFormat'],
			"maxSize" => $this->config['catcherMaxSize'],
			"allowFiles" => $this->config['catcherAllowFiles'],
			"oriName" => "remote.png"
		);

		$field_name = $this->config['catcherFieldName'];

		/* 抓取远程图片 */
		$list = array();
		if (isset($_POST[$field_name]))
		{
			$source = $_POST[$field_name];
		}
		else
		{
			$source = $_GET[$field_name];
		}
		foreach ($source as $img_url)
		{
			$item = new Yf_Uploader($img_url, $config, "remote");
			$info = $item->getFileInfo();
			array_push($list, array(
				"state" => $info["state"],
				'upload_url_prefix' => $info['url_prefix'],
				'upload_path' => $info['url_path'],
				"url" => $info["url"],
				"size" => $info["size"],
				"title" => htmlspecialchars($info["title"]),
				//"original" => htmlspecialchars($info["original"]),
				"source" => htmlspecialchars($img_url)
			));

			if ($info['state'] == "SUCCESS")
			{
			}
		}

		/* 返回抓取数据 */
		echo json_encode(array(
							 'state' => count($list) ? 'SUCCESS' : 'ERROR',
							 'list' => $list
						 ));
	}

	public function image()
	{
		include $this->view->getView();
	}

	public function cropperImage()
	{
		include $this->view->getView();
	}

	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function lists()
	{
		$user_id = Perm::$userId;

		$page = request_int('page');
		$rows = request_int('rows');
		$user = request_string('user');

		$cond_row  = array();
		$order_row = array();

		$data = array();

		if ( !empty($user) && $user == 'admin' )
		{
			$cond_row['shop_id'] = Shop_BaseModel::ADMIN_SHOP_ID;
		}
		else
		{
			$cond_row['shop_id'] = Perm::$shopId;
		}


		$param = request_row('param');
		if (!empty($param['album_id']))
		{
			$cond_row['album_id'] = $param['album_id'];
		}
		else
		{
			$cond_row['album_id'] = 0;
		}

		$data = $this->uploadModel->getUploadList($cond_row, $order_row, $page, $rows);

		//分页
		$Yf_Page = new Yf_Page();
		$rows    = $Yf_Page->listRows = 15;
		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();
		$data['page_nav']   = $page_nav;

		$this->data->addBody(-140, $data, 'success', 200);
	}

	public function uploadGoodsExcel()
	{
		$config = array(
			"pathFormat" => $this->config['filePathFormat'],
			"maxSize" => $this->config['fileMaxSize'],
			"allowFiles" => array(".xls", ".xlsx", ".csv")
		);

		$field_name = $this->config['imageFieldName'];

		$up = new Yf_Uploader($field_name, $config, "upload");

		$info = $up->getFileInfo();

		$this->data->addBody(-140, $info, 'success', 200);
	}

	/**
	 * catch image by excel
	 * @param $image_url
	 * @return array
	 */
	public static function catchImageByExcel( $image_url )
	{
		set_time_limit(0);

		/* 上传配置 */
		$dir_path = sprintf('/media/%s/%d/%d', Yf_Registry::get('server_id'), Perm::$userId, Perm::$shopId);

		$config = array(
			"pathFormat" => $dir_path . '/image/{yyyy}{mm}{dd}/{time}{rand:6}',
			"maxSize" => 2048000,
			"allowFiles" => array(
				'.png',
				'.jpg',
				'.jpeg',
				'.gif',
				'.bmp',
			),
			"oriName" => "remote.png"
		);

		$item = new Yf_Uploader($image_url, $config, "remote");
		$info = $item->getFileInfo();

		return $info;
	}

	public function uploadTaoBaoImage ()
	{

		$config = array(
			"pathFormat" => $this->config['filePathFormat'],
			"maxSize" => $this->config['fileMaxSize'],
			"allowFiles" => array(".tbi", ".jpg")
		);

		$field_name = $this->config['fileFieldName'];


		/* 生成上传实例对象并完成上传 */
		$up = new Yf_Uploader($field_name, $config, "upload");

		$result_info = $up->getFileInfo();

		if ( $result_info['state'] == "SUCCESS" )
		{
			$original = explode(".", $result_info["original"]);
			array_pop($original);
			$original = implode("", $original);

			$image_url = $result_info['url'];

			$goodsCommonModel = new Goods_CommonModel();

			$goods_common_data = $goodsCommonModel->getByWhere( array("common_image" => $original) );

			if ($goods_common_data)
			{
				$goods_common_data = current($goods_common_data);

				$common_id = $goods_common_data['common_id'];
				$goodsCommonModel->editCommon($common_id, array("common_image" => $image_url));
			}

			$goodsImagesModel = new Goods_ImagesModel();

			$goods_image_data = $goodsImagesModel->getByWhere( array("images_image" => $original) );

			if ($goods_image_data)
			{
				$goods_image_data = current($goods_image_data);
				$images_id = $goods_image_data['images_id'];

				$goodsImagesModel->editImages( $images_id, array("images_image" => $image_url) );
			}

			$goodsBaseModel = new Goods_BaseModel();
			$goods_data = $goodsBaseModel->getByWhere( array("goods_image" => $original) );

			if ($goods_data)
			{
				$goods_data = current($goods_data);
				$goods_id = $goods_data['goods_id'];

				$update_goods_data['goods_image'] = $image_url;
				$goodsBaseModel->editBase($goods_id, $update_goods_data, false);
			}
		}

		$this->data->addBody(-140, $result_info, "success", 200);
	}
}

?>