<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_User_MessageCtl extends Yf_AppController
{
	public $userMessageModel = null;
	
	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		
		$this->userMessageModel = new User_MessageModel();
	}


	/**
	 * 消息页面
	 *
	 * @access public
	 */
	public function getMessageList()
	{

		$page = request_int('page');
		$rows = request_int('rows');
		$type = request_string('user_type');
		$name = request_string('search_name');
		
		$cond_row = array();
		$sort     = array();
		
		if ($name)
		{
			if ($type == 1)
			{
				$type = 'user_message_send:LIKE';
			}
			else
			{
				$type = 'user_message_receive:LIKE';
				
			}
			$cond_row[$type] = '%' . $name . '%';
		}
		
		$data = $this->userMessageModel->getMessageList($cond_row, $sort, $page, $rows);
		
		$this->data->addBody(-140, $data);
	}
	
}


?>