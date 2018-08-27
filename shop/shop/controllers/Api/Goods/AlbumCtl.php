<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Goods_AlbumCtl extends Api_Controller
{
	public $uploadAlbumModel;
	public $Upload_BaseModel;

	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		$this->uploadAlbumModel = new Upload_AlbumModel();
		$this->Upload_BaseModel = new Upload_BaseModel();
	}

	/**
	 * 设置商城API网址及key - 后台独立使用
	 *
	 * @access public
	 */
	public function album()
	{
		include $view = $this->view->getView();;

	}

	public function getAlbumList()
	{
		$condi = array();
		$page  = request_int('page', 1);
		$rows  = request_int('rows', 100);
		$sord['shop_id']  = 'asc';
        $group  = 'album_id,shop_id';

		$data = $this->uploadAlbumModel->getAlbumListByAdmin($condi, $sord, $page, $rows, $group);

		if ($data)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, $data, $condi, $status);
	}

	public function remove()
	{
		$album_id = request_int('id');

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

		$this->data->addBody(-140, array(), $msg, $status);
	}

	public function getImageList()
	{
		$condi = array();
		$page  = request_int('page', 1);
		$rows  = request_int('rows', 100);
		$sord  = request_string('sord', 'asc');

		$data = $this->Upload_BaseModel->getUploadListByAdmin($condi, $sord, $page, $rows);

		if ($data)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function removeImage()
	{
		$upload_id = request_int('id');

		$flag = $this->Upload_BaseModel->removeImage($upload_id);

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

		$this->data->addBody(-140, array(), $msg, $status);
	}
}

?>