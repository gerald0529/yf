<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_User_SharedCtl extends Yf_AppController
{
	public $sharedBindingsModel = null;


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
		
		$this->sharedBindingsModel = new Shared_BindingsModel();

	}
	
	/**
	 *获取分享绑定
	 *
	 * @access public
	 */
	public function getSharedList()
	{

		$data = $this->sharedBindingsModel->getSharedBindingsList();
		
		$this->data->addBody(-140, $data);

	}
	
	/**
	 *编辑分享绑定页面
	 *
	 * @access public
	 */
	public function manageShared()
	{
		$shared_bindings_id             = request_int("shared_bindings_id");
		$cond_row['shared_bindings_id'] = $shared_bindings_id;

		$data = $this->sharedBindingsModel->getSharedBindings($cond_row);

		$this->data->addBody(-140, $data);
	}
	
	/**
	 *编辑分享绑定
	 *
	 * @access public
	 */
	public function editShared()
	{
		$shared_bindings_id      = request_int("shared_bindings_id");
		$shared_bindings_statu   = request_int("shared_bindings_statu");
		$shared_bindings_appcode = request_string("shared_bindings_appcode");
		$shared_bindings_appid   = request_string("shared_bindings_appid");
		$shared_bindings_key     = request_string("shared_bindings_key");
		
		$cond_row['shared_bindings_statu']   = $shared_bindings_statu;
		$cond_row['shared_bindings_appcode'] = $shared_bindings_appcode;
		$cond_row['shared_bindings_appid']   = $shared_bindings_appid;
		$cond_row['shared_bindings_key']     = $shared_bindings_key;
		
		$flag = $this->sharedBindingsModel->editBindings($shared_bindings_id, $cond_row);

		if ($flag === false)
		{
			$status = 250;
			$msg    = __('failure');
		}
		else
		{

			$status = 200;
			$msg    = __('success');
		}
		$data = array();
		
		$this->data->addBody(-140, $data, $msg, $status);
	}
	
}

?>