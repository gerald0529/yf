<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Upload_BaseModel extends Upload_Base
{

	const UPLOAD_IMAGE_UNGROUP = 0;

	/**
	 * 读取分页列表
	 *
	 * @param  int $upload_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getUploadList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getUploadNum($shop_id)
	{
		$row            = array();
		$row['shop_id'] = $shop_id;

		$num = $this->getNum($row);

		return $num;
	}

	/**
	 * 后台读取分页列表
	 *
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getUploadListByAdmin($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$shop_BaseModel   = new Shop_BaseModel();
		$uploadAlbumModel = new Upload_AlbumModel();
		//判断是否读取默认相册的图片
		$album_id = request_int('album_id');
		$shop_id  = request_int('shop_id');

		$shop_base = $shop_BaseModel->get($shop_id);
		$shop_base = pos($shop_base);
		$shop_name = $shop_base['shop_name'];

		if ($album_id == 0)
		{
			$cond_row['album_id'] = Upload_BaseModel::UPLOAD_IMAGE_UNGROUP;
			$album_name           = "未分组【" . $shop_name . "】";
		}
		else
		{
			$cond_row['album_id'] = $album_id;
			$album_base           = $uploadAlbumModel->get($album_id);
			$album_base           = pos($upload_base);
			$album_name           = $album_base['album_name'];

			$album_name = $album_name . "【" . $shop_name . "】";
		}

		$cond_row['shop_id'] = $shop_id;

		$upload_list = $this->listByWhere($cond_row, $order_row, $page, $rows);

		foreach ($upload_list['items'] as $key => $val)
		{
			$upload_list['items'][$key]['upload_size'] = round($val['upload_size'] / 1024);
			$upload_list['items'][$key]['upload_time'] = date('Y-m-d H:i:s', $val['upload_time']);
			$upload_list['items'][$key]['album_name']  = $album_name;
		}

		return $upload_list;
	}
}

?>