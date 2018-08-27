<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Platform_CustomServiceModel extends Platform_CustomService
{

	const SERVICE_UNREPLY = 1;
	const SERVICE_REPLY   = 2;
	public        $service_state;
	public static $state = array(
		'1' => "unreply",
		'2' => "reply"
	);

	public function __construct()
	{
		parent::__construct();
		$this->service_state = array(
			'1' => __("未回复"),
			'2' => __("已回复"),
		);
	}

	/**
	 * 读取分页列表
	 *
	 * @param  int $custom_service_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCustomServiceList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		foreach ($data['items'] as $key => $val)
		{
			$data['items'][$key]['custom_service_status_etext'] = self::$state[$val['custom_service_status']];
			$data['items'][$key]['custom_service_status_text']  = $this->service_state[$val['custom_service_status']];
		}
		return $data;
	}

	public function getOneService($service_id)
	{
		$data                                = $this->getOne($service_id);
		$data['custom_service_status_etext'] = self::$state[$data['custom_service_status']];
		$data['custom_service_status_text']  = $this->service_state[$data['custom_service_status']];
		return $data;
	}

}

?>