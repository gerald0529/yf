<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Report_BaseModel extends Report_Base
{

	const REPORT_DO = 1;
	const REPORT_DONE   = 2;
	const REPORT_USEFUL = 1;
	const REPORT_USELESS = 2;

	public static $order_type = array(
		'1' => 'do', //虚拟订单
		'2' => 'done', //实物订单
	);

	public        $settle_state;

	public function __construct()
	{
		parent::__construct();
		$this->settle_state = array(
			'1' => __("举报有效"), //已出账
			'2' => __("举报无效"), //商家已确认
		);
	}
	/**
	 * 读取分页列表
	 *
	 * @param  int $consult_cat_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCatList($cond_row = array(), $order_row = array(), $page=1, $rows=100)
	{
		$data =  $this->listByWhere($cond_row, $order_row, $page, $rows);
		$Shop_BaseModel = new Shop_BaseModel();
		foreach($data['items'] as $k=>$v){
			if($v['report_state']==self::REPORT_DO){
				$data['items'][$k]['state'] = __('处理中');
			}elseif($v['report_state']==self::REPORT_DONE){
				if($v['report_handle_state']==self::REPORT_USEFUL){
					$data['items'][$k]['state'] = __('有效投诉');
				}elseif($v['report_handle_state']==self::REPORT_USELESS){
					$data['items'][$k]['state'] = __('无效投诉');
				}
			}
			$data['items'][$k]['state_etext'] = self::$order_type[$v['report_state']];

			$shop_info = $Shop_BaseModel->getOne($v['shop_id']);
			$data['items'][$k]['shop_name'] = $shop_info['shop_name'];
		}
		return $data;
	}

	public function getReportBase($cond_row = array(), $order_row = array())
	{
		$data = $this->getOneByWhere($cond_row, $order_row);

		$data['state_etext'] = self::$order_type[$data['report_state']];
		$data['pic'] = explode(',', $data['report_pic']);
		if($data['report_handle_state']) {
			$data['handle_text'] = $this->settle_state[$data['report_handle_state']];
		}
		return $data;
	}

	public function getReportLook($id)
	{
		$data = $this->getOne($id);

		$data['state_etext'] = self::$order_type[$data['report_state']];
		$data['pic'] = explode(',', $data['report_pic']);
		if($data['report_handle_state']) {
			$data['handle_text'] = $this->settle_state[$data['report_handle_state']];
		}
		$Shop_BaseModel = new Shop_BaseModel();
		$shop_info = $Shop_BaseModel->getOne($data['shop_id']);
		$data['shop_name'] = $shop_info['shop_name'];
		return $data;
	}

	public function getSubQuantity($cond_row)
	{
		return $this->getNum($cond_row);
	}
}
?>