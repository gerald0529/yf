<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Rec_PositionCtl extends Api_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
	}


	/*
	 * 查询显示数据
	 * @param int $page 页数
	 * @param int $rows 每页显示条数
	 *
	 * @return array $data 查询数据
	 */
	public function recPositionList()
	{
		$Rec_PositionModel = new Rec_PositionModel();
		$page              = request_int('page');
		$rows              = request_int('rows');
		$cond_rows         = array();
		$order_rows        = array();

		$data = $Rec_PositionModel->listByWhere($cond_rows, $order_rows, $page, $rows);

		$items = $data['items'];
		unset($data['items']);
		if (!empty($items))
		{
			foreach ($items as $key => $value)
			{
				if ($value['position_type'] == $Rec_PositionModel::POSITION_TYPE_PIC)
				{
					$items[$key]['position_type_name'] = __('图片');
					$items[$key]['position_detail']    = $value['position_pic'];
				}
				elseif ($value['position_type'] == $Rec_PositionModel::POSITION_TYPE_CON)
				{
					$items[$key]['position_type_name'] = __('文字');
					$items[$key]['position_detail']    = $value['position_content'];
				}

				if ($value['position_alert_type'] == $Rec_PositionModel::POSITION_ALERT_TYPE_SELF)
				{
					$items[$key]['position_alert_name'] = __('否');
				}
				elseif ($value['position_alert_type'] == $Rec_PositionModel::POSITION_ALERT_TYPE_NEW)
				{
					$items[$key]['position_alert_name'] = __('是');
				}
			}
		}

		$data['items'] = $items;
		if (!empty($items))
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

	/*
	 * 删除
	 * @param int $position_id 主键id
	 * @return int  $data 操作id
	 */
	public function removePosition()
	{
		$Rec_PositionModel = new Rec_PositionModel();

		$position_id = request_int('position_id');

		$flag = $Rec_PositionModel->removePosition($position_id);

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

		$data['id'] = $position_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 新增推荐位
	 * @param string $position_title    推荐位标题
	 * @param string $posirtion_type    推荐位类型
	 * @param string $position_con      推荐位内容
	 * @param string $position_pic      推荐位图片
	 * @param string $position_type     推荐位类型
	 * @param string $position_url      url
	 * @param string $position_alert_type 打开类型
	 */
	public function addRecPosition()
	{
		$Rec_PositionModel = new Rec_PositionModel();

		$data                   = array();
		$data['position_title'] = request_string('position_title');
		$position_type          = request_int('position_type');
		if ($position_type == 1)
		{
			$data['position_content'] = request_string('position_con');
		}
		elseif ($position_type == 0)
		{
			$data['position_pic'] = request_string('position_pic');
		}
		$data['position_type']       = $position_type;
		$data['position_url']        = request_string('position_url');
		$data['position_alert_type'] = request_int('position_alert_type');
		//$data['position_code'] = request_string('position_code');
		$position_id = $Rec_PositionModel->addPosition($data, true);

		if ($position_id)
		{
			$code_data['position_code'] = '<?php echo rec(' . $position_id . '); ?>';
			$flag                       = $Rec_PositionModel->editPosition($position_id, $code_data);
		}

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

		$data['id']          = $position_id;
		$data['position_id'] = $position_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 编辑推荐位
	 */
	public function editRecPosition()
	{
		$Rec_PositionModel = new Rec_PositionModel();

		$position_id = request_int('position_id');

		$data = array();

		$data['position_title'] = request_string('position_title');
		$position_type          = request_int('position_type');
		if ($position_type == 1)
		{
			$data['position_content'] = request_string('position_con');
		}
		elseif ($position_type == 0)
		{
			$data['position_pic'] = request_string('position_pic');
		}
		$data['position_type']       = $position_type;
		$data['position_url']        = request_string('position_url');
		$data['position_alert_type'] = request_int('position_alert_type');
		//$data['position_code'] = request_string('position_code');
		$flag = $Rec_PositionModel->editPosition($position_id, $data);

		if ($flag !== false)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['id']          = $position_id;
		$data['position_id'] = $position_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

}

?>