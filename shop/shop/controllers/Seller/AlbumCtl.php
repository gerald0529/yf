<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_AlbumCtl extends Seller_Controller
{
	public $uploadModel      = null;
	public $config           = null;
	public $uploadAlbumModel = null;

	/**
	 * Constructor 用户上传目录 user_id/shop_id/
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类
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
			'imageCompressEnable' => true,
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
		$this->uploadModel      = new Upload_BaseModel();
		$this->uploadAlbumModel = new Upload_AlbumModel();
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
				"allowFiles" => $this->config['fileAllowFiles']
			);
			
			$field_name = $this->config['fileFieldName'];
		}
		
		/* 生成上传实例对象并完成上传 */
		$up = new Yf_Uploader($field_name, $config, $base64);
		
		$info = $up->getFileInfo();
		
		if ($info['state'] == "SUCCESS")
		{
			//判断文件类型
			if (in_array($info['type'], $this->config['imageAllowFiles']))
			{
				$file_type = 'image';
			}
			else
			{
				$file_type = 'other';
			}

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
			//$data['upload_original'] = str_replace($info['type'], '', $info['title']);;    // 原附件
			$data['upload_type']      = $file_type;                                             // 枚举
			$data['upload_mime_type'] = $info['type'];
			$data['album_id']         = request_int('album_id') ? request_int('album_id') : '0';  // 默认添加到未分组里

			$data['user_id'] = $user_id; // 用户id
			$data['shop_id'] = $shop_id; // 店铺id


			$upload_id = $this->uploadModel->addUpload($data, true);

			$info['upload_id'] = $upload_id;
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
	 * 列出图片
	 *
	 * @access public
	 */
	public function listImage()
	{
		$this->lists();
	}
	
	/**
	 * 列出文件
	 *
	 * @access public
	 */
	public function listFile()
	{
		$this->lists();
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
				$title_row = explode('.', $info['title']);
				$upload_mime_type = '.' . array_pop($title_row);
				$image_title      = str_replace($upload_mime_type, '', $info['title']);
				$image_original   = str_replace($upload_mime_type, '', $info['original']);

				$data = array();

				$data['upload_time']       = time();
				$data['upload_url_prefix'] = $info['url_prefix'];
				$data['upload_path']       = $info['url_path'];
				$data['upload_path']       = $info['url'];
				$data['upload_source']     = htmlspecialchars($img_url);
				$data['upload_size']       = $info['size'];

				$data['upload_name']      = $image_title;           // 附件标题
				$data['upload_original']  = $image_original;    // 原附件
				$data['upload_mime_type'] = $upload_mime_type;

				$data['upload_type'] = 'image';                                                // 枚举
				$data['album_id']    = request_int('album_id') ? request_int('album_id') : '0';  // 默认添加到未分组里

				$data['user_id'] = Perm::$userId; // 用户id
				$data['shop_id'] = Perm::$shopId; // 店铺id

				$this->uploadModel->addUpload($data);
			}
		}
		
		/* 返回抓取数据 */
		echo json_encode(array(
							 'state' => count($list) ? 'SUCCESS' : 'ERROR',
							 'list' => $list
						 ));
	}
	
	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		include $this->view->getView();
	}
	
	/**
	 * 管理界面
	 *
	 * @access public
	 */
	public function manage()
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
	
	/**
	 * 读取
	 *
	 * @access public
	 */
	public function get()
	{
		$user_id = Perm::$userId;
		
		$upload_id = $_REQUEST['upload_id'];
		$rows      = $this->uploadModel->getUpload($upload_id);
		
		$data = array();
		
		if ($rows)
		{
			$data = array_pop($rows);
		}
		
		$this->data->addBody(-140, $data);
	}
	
	/**
	 * 添加
	 *
	 * @access public
	 */
	public function add()
	{
		$data['upload_id']           = $_REQUEST['upload_id']; // 商品图片Id
		$data['album_id']            = $_REQUEST['album_id']; //
		$data['user_id']             = $_REQUEST['user_id']; // 用户id
		$data['shop_id']             = $_REQUEST['shop_id']; // 店铺id
		$data['upload_url']          = $_REQUEST['upload_url']; // 附件的url，链接地址-默认为原图, 如果为图片则命名按照宽x高像素
		$data['upload_thumbs']       = $_REQUEST['upload_thumbs']; // JSON存储其它尺寸
		$data['upload_original']     = $_REQUEST['upload_original']; // 原附件
		$data['upload_source']       = $_REQUEST['upload_source']; // 源头-网站抓取
		$data['upload_displayorder'] = $_REQUEST['upload_displayorder']; // 排序
		$data['upload_path']         = $_REQUEST['upload_path']; // 附件path-本地存储
		$data['upload_type']         = $_REQUEST['upload_type']; // image|video|
		$data['upload_image_spec']   = $_REQUEST['upload_image_spec']; // 规格
		$data['upload_size']         = $_REQUEST['upload_size']; // 原文件大小
		$data['upload_mime_type']    = $_REQUEST['upload_mime_type']; // 上传的附件类型
		$data['upload_metadata']     = $_REQUEST['upload_metadata']; //
		$data['upload_name']         = $_REQUEST['upload_name']; // 附件标题
		$data['upload_time']         = $_REQUEST['upload_time']; // 附件日期
		
		
		$upload_id = $this->uploadModel->addUpload($data, true);

		//album +1
//		$this->uploadAlbumModel->editAlbum($data['album_id'], array());


		if ($upload_id)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		
		$data['upload_id'] = $upload_id;
		
		$this->data->addBody(-140, $data, $msg, $status);
	}
	
	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$upload_id = $_REQUEST['upload_id'];

		if (is_array($upload_id))
		{
			foreach ($upload_id as $id)
			{
				$flag = $this->uploadModel->removeImage($id);
			}
		}
		else
		{
			$flag = $this->uploadModel->removeImage($upload_id);
		}

		
		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		
		$data['upload_id'] = $upload_id;
		
		$this->data->addBody(-140, $data, $msg, $status);
	}
	
	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['upload_id'] = $_REQUEST['upload_id']; // 商品图片Id

		if (!empty($_REQUEST['upload_name']))
		{
			$data['upload_name'] = $_REQUEST['upload_name']; // 附件标题
		}

		if (isset($_REQUEST['album_id']))
		{
			$data['album_id'] = $_REQUEST['album_id']; //
		}

		/*$data['user_id']           = $_REQUEST['user_id']; // 用户id
		$data['shop_id']             = $_REQUEST['shop_id']; // 店铺id
		$data['upload_url']          = $_REQUEST['upload_url']; // 附件的url，链接地址-默认为原图, 如果为图片则命名按照宽x高像素
		$data['upload_thumbs']       = $_REQUEST['upload_thumbs']; // JSON存储其它尺寸
		$data['upload_original']     = $_REQUEST['upload_original']; // 原附件
		$data['upload_source']       = $_REQUEST['upload_source']; // 源头-网站抓取
		$data['upload_displayorder'] = $_REQUEST['upload_displayorder']; // 排序
		$data['upload_path']         = $_REQUEST['upload_path']; // 附件path-本地存储
		$data['upload_type']         = $_REQUEST['upload_type']; // image|video|
		$data['upload_image_spec']   = $_REQUEST['upload_image_spec']; // 规格
		$data['upload_size']         = $_REQUEST['upload_size']; // 原文件大小
		$data['upload_mime_type']    = $_REQUEST['upload_mime_type']; // 上传的附件类型
		$data['upload_metadata']     = $_REQUEST['upload_metadata']; //
		$data['upload_time']         = $_REQUEST['upload_time']; // 附件日期*/
		
		
		$upload_id = $_REQUEST['upload_id'];
		$data_rs   = $data;

		unset($data['upload_id']);

		//修改分组，改变相册图片数量
		$upload_list = $this->uploadModel->getUpload($upload_id);

		//判断当前Album_id
		if (isset($data['album_id']))
		{
			$upload_fir = pos($upload_list);
			$album_id_s = $upload_fir['album_id'];

			$upload_num = count($upload_list);

			//移出相册
			if ($album_id_s != Upload_BaseModel::UPLOAD_IMAGE_UNGROUP)
			{
				$update_album['album_num'] = -$upload_num;
				$this->uploadAlbumModel->editAlbum($album_id_s, $update_album, true);
			}
			//移入相册
			if ($data['album_id'] != Upload_BaseModel::UPLOAD_IMAGE_UNGROUP)
			{
				$update_album_target['album_num'] = $upload_num;
				$this->uploadAlbumModel->editAlbum($data['album_id'], $update_album_target, true);
			}
		}

		$flag = $this->uploadModel->editUpload($upload_id, $data);
		$this->data->addBody(-140, $data_rs);
	}

	public function getAlbumList()
	{
		$condi['shop_id'] = Perm::$shopId;
		$data             = $this->uploadAlbumModel->getAlbumList($condi);

		if ($data)
		{
			$msg    = 'success';
			$status = 200;

			//默认分组 未分组
			$default_album['album_desc']       = '未分组';
			$default_album['album_id']         = 0;
			$default_album['album_is_default'] = 1;
			$default_album['album_type']       = 'image';

			array_unshift($data['items'], $default_album);

			//取出相册图片数量
			$album_ids            = array_column($data['items'], 'album_id');
			$condi['album_id:IN'] = $album_ids;
			$images               = $this->uploadModel->getByWhere($condi);

			if (empty($images))
			{
				foreach ($data['items'] as $key => $val)
				{
					$data['items'][$key]['image_num'] = 0;
				}
			}
			else
			{
				foreach ($data['items'] as $key => $val)
				{
					$image_num = 0;

					foreach ($images as $k => $v)
					{
						if ($val['album_id'] == $v['album_id'])
						{
							$image_num += 1;
							unset($images[$k]);
						}
					}
					if ($image_num == 0)
					{
						$data['items'][$key]['image_num'] = 0;
					}
					else
					{
						$data['items'][$key]['image_num'] = $image_num;
					}
				}
			}
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function addAlbum()
	{
		$data['album_desc'] = request_string('album_desc');
		$data['album_type'] = 'image';
		$data['shop_id']    = Perm::$shopId;

		$data['album_id'] = $this->uploadAlbumModel->addAlbum($data, true);

		if ($data['album_id'])
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function removeAlbum()
	{
		$album_id = request_int('album_id');
		
		if (!empty($album_id))
		{
			$flag = $this->uploadAlbumModel->removeAlbum($album_id);
		}

		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}

	public function renameAlbum()
	{
		$album_id                  = request_int('album_id');
		$update_data['album_desc'] = request_string('album_desc');

		$flag = $this->uploadAlbumModel->editAlbum($album_id, $update_data);

		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}
}

?>