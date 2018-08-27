<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/15
 * Time: 17:57
 */
class Delivery_BaseModel extends Delivery_Base
{
	const DELIVERY_OPEN   = 1;
	const DELIVERY_CLOSE  = 2;
	const DELIVERY_PASS   = 2;
	const DELIVERY_PASSIN = 1;
	const DELIVERY_UNPASS = 3;

	public static $state       = array(
		'1' => 'open',
		'2' => 'close',
	);
	public static $check_state = array(
		'1' => 'passin',
		'2' => 'pass',
		'3' => 'unpass',
	);
	public        $delivery_state;

	public function __construct()
	{
		parent::__construct();
		$this->delivery_state = array(
			'1' => __("开启"),
			//已出账
			'2' => __("关闭"),
			//商家已确认
		);
	}

	/**
	 * @param array $cond_row
	 * @param array $order_row
	 * @param int $page
	 * @param int $rows
	 * @return array
	 */
	public function getDeliveryList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data_rows = $this->listByWhere($cond_row, $order_row, $page, $rows);

		foreach ($data_rows['items'] as $key => $value)
		{
			$data_rows['items'][$key]['delivery_state_text'] = $this->delivery_state[$value['delivery_state']];
		}

		return $data_rows;
	}

	public function getDeliveryInfo($delivery_id)
	{
		$data                               = $this->getOne($delivery_id);
		$data['delivery_state_etext']       = self::$state[$data['delivery_state']];
		$data['delivery_check_state_etext'] = self::$check_state[$data['delivery_check_state']];
		return $data;
	}

	public function editDelivery($delivery_id, $field_row)
	{
		if (!empty($field_row['delivery_password']))
		{
			$field_row['delivery_password'] = md5($field_row['delivery_password']);
		}
		else
		{
			unset($field_row['delivery_password']);
		}
		$update_flag = $this->editBase($delivery_id, $field_row);
		return $update_flag;
	}
}