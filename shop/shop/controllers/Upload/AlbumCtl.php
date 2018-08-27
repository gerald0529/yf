<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Upload_AlbumCtl extends Yf_AppController
{
	public $uploadAlbumModel = null;

	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		//include $this->view->getView();
		$this->uploadAlbumModel = new Upload_AlbumModel();
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
		$sort = request_int('sord');

		$cond_row  = array();
		$order_row = array();

		$data = array();

		if ($skey = request_string('skey'))
		{
			$data = $this->uploadAlbumModel->getAlbumList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->uploadAlbumModel->getAlbumList($cond_row, $order_row, $page, $rows);
		}


		$this->data->addBody(-140, $data);
	}

	/**
	 * 读取
	 *
	 * @access public
	 */
	public function get()
	{
		$user_id = Perm::$userId;

		$album_id = request_int('album_id');
		$rows     = $this->uploadAlbumModel->getAlbum($album_id);

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
		$data['album_id']           = request_string('album_id'); // 商品图片Id
		$data['album_name']         = request_string('album_name'); // 商品图片地址
		$data['album_cover']        = request_string('album_cover'); // 封面
		$data['album_desc']         = request_string('album_desc'); // 描述
		$data['album_num']          = request_string('album_num'); // 内容数量
		$data['album_is_default']   = request_string('album_is_default'); // 默认相册，1是，0否
		$data['album_displayorder'] = request_string('album_displayorder'); // 排序
		$data['album_type']         = request_string('album_type'); // 附件册
		$data['user_id']            = request_string('user_id'); // 所属用户id
		$data['shop_id']            = request_string('shop_id'); //


		$album_id = $this->uploadAlbumModel->addAlbum($data, true);

		if ($album_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['album_id'] = $album_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$album_id = request_int('album_id');

		$flag = $this->uploadAlbumModel->removeAlbum($album_id);

		if ($flag)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['album_id'] = array($album_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['album_id']           = request_string('album_id'); // 商品图片Id
		$data['album_name']         = request_string('album_name'); // 商品图片地址
		$data['album_cover']        = request_string('album_cover'); // 封面
		$data['album_desc']         = request_string('album_desc'); // 描述
		$data['album_num']          = request_string('album_num'); // 内容数量
		$data['album_is_default']   = request_string('album_is_default'); // 默认相册，1是，0否
		$data['album_displayorder'] = request_string('album_displayorder'); // 排序
		$data['album_type']         = request_string('album_type'); // 附件册
		$data['user_id']            = request_string('user_id'); // 所属用户id
		$data['shop_id']            = request_string('shop_id'); //


		$album_id = request_int('album_id');
		$data_rs  = $data;

		unset($data['album_id']);

		$flag = $this->uploadAlbumModel->editAlbum($album_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>