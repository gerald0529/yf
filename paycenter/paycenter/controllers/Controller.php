<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Controller extends Yf_AppController
{
	//是否验证用户登录
	public $_check_perm = true;
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		if($this->_check_perm === true){
			$this->initBuyerInfo();
		}
		
	}
	public function initBuyerInfo()
	{
		$user_id = Perm::$userId;
		fb($user_id);
		//$user_id = 1;
		$User_InfoModel = new User_InfoModel();
		$this->user_info      = $User_InfoModel->getOne($user_id);
	}
}

?>