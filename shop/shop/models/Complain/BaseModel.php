<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Complain_BaseModel extends Complain_Base
{
	const COMPLAIN_FRESH  = 1;
	const COMPLAIN_APPEAL = 2;
	const COMPLAIN_TALK   = 3;
	const COMPLAIN_HANDLE = 4;
	const COMPLAIN_FINISH = 5;

	const VERIFY_PASS = 1;  //审核通过

	public static $state = array(
		'1' => 'new',
		'2' => 'appeal',
		//投诉通过转给被投诉人
		'3' => 'talk',
		//被投诉人已申诉
		'4' => 'handle',
		//提交仲裁
		'5' => 'finish',
	);

	public $stateMap;


	public function __construct()
	{
		parent::__construct();
		$this->stateMap = array(
			'1' => __('新投诉'),
			'2' => __('待申诉'),
			//投诉通过转给被投诉人
			'3' => __('对话中'),
			//被投诉人已申诉
			'4' => __('待仲裁'),
			//提交仲裁
			'5' => __('已关闭'),
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
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		foreach ($data['items'] as $key => $value)
		{
			if ($value['complain_pic'])
			{
				$complain_pic_content                  = explode(',', $value['complain_pic']);
				$data['items'][$key]['complain_image'] = $complain_pic_content[0];
			}
			else
			{
				$data['items'][$key]['complain_image'] = '';
			}

			$data['items'][$key]['complain_state_text']  = $this->stateMap[$value['complain_state']];
			$data['items'][$key]['complain_state_etext'] = self::$state[$value['complain_state']];
		}
		return $data;
	}


	public function getComplainBase($cond_row = array(), $order_row = array())
	{
		$data = $this->getOneByWhere($cond_row, $order_row);

		$data['complain_state_etext'] = self::$state[$data['complain_state']];
		if ($data['complain_pic'])
		{
			$data['complain_pic_content'] = explode(',', $data['complain_pic']);
		}
		else
		{
			$data['complain_pic_content'] = NULL;
		}
		if ($data['appeal_pic'])
		{
			$data['appeal_pic_content'] = explode(',', $data['appeal_pic']);
		}
		else
		{
			$data['appeal_pic_content'] = NULL;
		}

		return $data;
	}

	public function getSubQuantity($cond_row)
	{
		return $this->getNum($cond_row);
	}


}

?>