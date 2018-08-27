<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Complain_TalkModel extends Complain_Talk
{
	const COMPLAIN_ACCUSER = 1;     //投诉人
	const COMPLAIN_ACCUSED = 2;    //被投诉人
	const COMPLAIN_ADMIN   = 3;                //平台管理员

	const TALK_MASK = 0;    //屏蔽对话


	public $stateMap;
	public $accTypeMap;

	public function __construct()
	{
		parent::__construct();
		$this->stateMap = array(
			'1' => __('投诉人'),
			'2' => __('被投诉店铺'),
			'3' => __('管理员'),
		);
		$this->accTypeMap = array(
			'1' => 'accuser',
			'2' => 'accused',
			'3' => 'admin',
		);
	}


	/**
	 * 读取分页列表
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getTalkList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->listByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getComplainTalk($cond_row = array(), $order_row = array())
	{
		$complain_talk = array_values($this->getByWhere($cond_row, $order_row));

		foreach ($complain_talk as $key => $value)
		{
			$complain_talk[$key]['member_type'] = $this->stateMap[$value['talk_member_type']];
			$complain_talk[$key]['acc_type']    = $this->accTypeMap[$value['talk_member_type']];
			if (!$value['talk_state'])
			{
				$complain_talk[$key]['talk_content'] = __('<该对话被管理员屏蔽>');
			}
		}

		return $complain_talk;
	}


}

?>