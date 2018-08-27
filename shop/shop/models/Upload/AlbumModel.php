<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Upload_AlbumModel extends Upload_Album
{
	/**
	 * 读取分页列表
	 *
	 * @param  int $album_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getAlbumList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	/*
	 * 读取后台相册列表
	 * @return array $rows 返回的查询内容
	 * @access public
	 * */

	public function getAlbumListByAdmin($cond_row = array(), $order_row = array(), $page = 1, $rows = 100, $group)
	{
		$uploadModel   = new Upload_BaseModel();
		$shopBaseModel = new Shop_BaseModel();

		//根据相册ID和店铺ID进行分组
        $album_list = $uploadModel->listByWhere($cond_row, $order_row, $page, $rows, true, $group);
        foreach ($album_list['items'] as $key=>$val){
            $album_id   = $val['album_id'];
            $shop_id    = $val['shop_id'];
            $album      = $this->getOne($album_id);
            $shop       = $shopBaseModel->getOne($shop_id);
            //存在相册
            if ($album) {
                $album_list['items'][$key]['album_desc'] = $album['album_desc'];
                $album_list['items'][$key]['album_num']  = $album['album_num'];
            } else {
                $album_list['items'][$key]['album_desc'] = '未分组【系统】';
                $upload_cond_row['album_id'] = 0;
                $upload_cond_row['shop_id'] = $shop_id;
                //未分组的相册重新计算图片数量
                $upload_images = $uploadModel->getByWhere($upload_cond_row);
                $album_list['items'][$key]['album_num'] = count($upload_images);
            }
            //店铺存在
            if ($shop) {
                $album_list['items'][$key]['shop_name'] = $shop['shop_name'];
            } else {
                $album_list['items'][$key]['shop_name'] = '【店铺不存在】';
            }
        }

//		$cond_row['album_type'] = 'image';
//
//		$album_list = $this->listByWhere($cond_row, $order_row, $page, $rows);
//
//		$shop_ids = array_column($album_list['items'], 'shop_id');
//		$shop_ids = array_values(array_unique($shop_ids));
//
//		$shop_list = $shopBaseModel->getByWhere();        //取出所有店铺的信息
//
//		$condi_upload['album_id']   = Upload_BaseModel::UPLOAD_IMAGE_UNGROUP;
//
//		$upload_image_list = $uploadModel->getByWhere($condi_upload);
//
//		foreach ($album_list['items'] as $key => $val)
//		{
//			//处理店铺名称
//			foreach ($shop_list as $k => $v)
//			{
//				if ($val['shop_id'] == $v['shop_id'])
//				{
//					$album_list['items'][$key]['shop_name'] = $v['shop_name'];
//
//					$shop_base[$k]['shop_id']   = $v['shop_id'];
//					$shop_base[$k]['shop_name'] = $v['shop_name'];
//				}
//			}
//
//			$album_list['items'][$key]['is_default'] = false;
//		}
//
//		//处理默认相册
//
//		foreach ($shop_list as $key => $val)
//		{
//			$default_album               = array();
//			$default_album['album_num']  = 0;
//			$default_album['album_id']   = 0;
//			$default_album['is_default'] = true;
//			$default_album['shop_id']    = $val['shop_id'];
//			$default_album['shop_name']  = $val['shop_name'];
//			$default_album['album_desc'] = '未分组【系统】';
//
//			foreach ($upload_image_list as $k_img => $v_img)
//			{
//				if ($val['shop_id'] == $v_img['shop_id'])
//				{
//					$default_album['album_num'] += 1;
//					unset($upload_image_list[$k_img]);
//				}
//			}
//
//			//加入相册分组
//			array_push($album_list['items'], $default_album);
//		}

		//对数组进行排序 -- 根据shop_id排序 DESC -- 默认分组

//		usort($album_list['items'], function ($v_f, $v_s)
//		{
//
//			if ($v_f['shop_id'] == $v_s['shop_id'])
//			{
//				if ($v_f['is_default'] || $v_f['is_default'])
//				{
//					return $v_f['is_default'] ? -1 : 1;
//				}
//			}
//
//			if ($v_f['shop_id'] == $v_s['shop_id'])
//			{
//				return ($v_f['album_id'] < $v_s['album_id']) ? -1 : 1;
//			}
//
//			return ($v_f['shop_id'] < $v_s['shop_id']) ? -1 : 1;
//		});

		return $album_list;
	}

	/*
	 * 删除相册
	 * 仅删除分组，不删除图片，组内图片将自动归入未分组
	 * @param  int $album_id 主键值
	 * @return bool $del_flag 是否成功
	 * */
	public function removeAlbum($album_id = 0)
	{
		if ($album_id != 0)
		{
			$uploadModel = new Upload_BaseModel();

			$condi_upload['album_id'] = $album_id;

			$upload_list = $uploadModel->getByWhere($condi_upload);

			if (!empty($upload_list))
			{
				$update_data['album_id'] = Upload_BaseModel::UPLOAD_IMAGE_UNGROUP;

				$upload_ids = array_values(array_column($upload_list, 'upload_id'));
				$uploadModel->editUpload($upload_ids, $update_data);
			}

			return $this->remove($album_id);
		}

	}
}

?>