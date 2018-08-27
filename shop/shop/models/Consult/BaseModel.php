<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Consult_BaseModel extends Consult_Base
{
	const CONSULT_UNREPLY = 1;
	const CONSULT_REPLY   = 2;

	const NO_SHOW_USER = 1;
	const SHOW_USER    = 0;

	public static $state = array(
		'1' => 'unreply',
		'2' => 'reply',
	);

	public $stateMap;


	public function __construct()
	{
		parent::__construct();
		$this->stateMap = array(
			'1' => __('未回复'),
			'2' => __('已回复'),
		);
	}

	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		$User_BaseModel = new User_BaseModel();
		$Goods_BaseModel = new Goods_BaseModel();
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		foreach ($data['items'] as $key => $value)
		{
			$user_id = $value['user_id'];
			if ($value['no_show_user'] == $this::NO_SHOW_USER)
			{
				$data['items'][$key]['user_name'] = __('***');
			}
			elseif ($value['no_show_user'] == $this::SHOW_USER)
			{
				$user_data = $User_BaseModel->getOne($user_id);
				$data['items'][$key]['user_name'] = $user_data['user_account'];
			}
			$common_info = $Goods_BaseModel->getCommonInfo($value['goods_id']);
			$data['items'][$key]['common_id'] = $common_info['common_id'];
			$data['items'][$key]['consult_state_text'] = $this->stateMap[$value['consult_state']];
		}
		return $data;
	}

	public function removeSelectedConsult($consult_id)
	{

		$del_flag = $this->remove($consult_id);

		//$this->removeKey($config_key);
		return $del_flag;
	}

}

?>