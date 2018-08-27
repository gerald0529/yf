<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Base_AppVersionCtl extends Api_Controller
{
	public $baseAppResourcesModel = null;
	
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
		
		//include $this->view->getView();
		$this->baseAppResourcesModel = new Base_AppResourcesModel();
	}
	
	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		include $this->view->getView();
	}
	
	/**
	 * 管理界面
	 *
	 * @access public
	 */
	public function manage()
	{
		include $this->view->getView();
	}
	
	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function lists()
	{
		$user_id = Perm::$userId;
		
		$page = request_int('page');
		$rows = request_int('rows');
		$sort = request_int('sord');
		
		$cond_row  = array();
		$order_row = array();
		
		$data = array();
		
		if ($skey = request_int('app_id_ver'))
		{
			$cond_row['app_id'] = $skey;
			$data = $this->baseAppResourcesModel->getAppResourcesList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->baseAppResourcesModel->getAppResourcesList($cond_row, $order_row, $page, $rows);
		}
		
		
		$this->data->addBody(-140, $data);
	}
	
	/**
	 * 最终发布版本
	 *
	 * @access public
	 */
	public function lastest()
	{
		$user_id = Perm::$userId;
		
		$page = request_int('page');
		$rows = request_int('rows');
		$sort = request_int('sord');
		
		$cond_row  = array();
		$order_row = array();
		
		$data = array();
		
		$Base_App = new Base_App();
		$app_rows = $Base_App->getByWhere();
		
		$Base_AppModel = new Base_AppVersionModel();
		$rows = $Base_AppModel->getByWhere($cond_row, $order_row, $page, $rows);
		
		foreach ($rows as $app_id=>$row)
		{
			if ($app_id > 1000)
			{
				unset($rows[$app_id]);
				continue;
			}
			
			$url = sprintf('%s/%s/%s/db.sql', 'http://ucenter.yuanfeng021.com/app_release_version', $app_id, $row['app_version']);
			$row['url'] = $url;
			
			$row['app_name'] = $app_rows[$app_id]['app_name'];
			
			$rows[$app_id] = $row;
		}

		$this->data->addBody(-140, $rows);
	}
	
	/**
	 * 最终发布版本
	 *
	 * @access public
	 */
	public function check()
	{
	}


	/**
	 * 读取
	 *
	 * @access public
	 */
	public function get()
	{
		$user_id = Perm::$userId;
		
		$app_resource_id = request_int('app_resource_id');
		$rows = $this->baseAppResourcesModel->getAppResources($app_resource_id);
		
		$data = array();
		
		if ($rows)
		{
			$data = array_pop($rows);
		}
		
		$this->data->addBody(-140, $data);
	}
	
	/**
	 * 添加
	 *
	 * @access public
	 */
	public function add()
	{
		$data['app_id']                 = request_string('app_id_ver')        ; // AppId
		$data['app_version']            = request_string('app_version')   ; // 当前版本
		$data['app_version_next']       = request_string('app_version_next'); // 下个版本
		$data['app_res_filename']       = request_string('app_res_filename'); // 资源名称
		$data['app_res_filesize']       = request_string('app_res_filesize'); // 文件大小
		$data['app_package_url']        = request_string('app_package_url'); // 
		$data['app_res_time']           = request_string('app_res_time')  ; // 版本时间
		$data['app_reinstall']          = request_string('app_reinstall') ; // 
		$data['app_release']            = request_string('app_release')   ; // 
		$data['app_update_log']         = request_string('app_update_log')   ; //更新日志
		
		
		$app_resource_id = $this->baseAppResourcesModel->addAppResources($data, true);
		
		if ($app_resource_id)
		{
			$msg = _('success');
			$status = 200;
		}
		else
		{
			$msg = _('failure');
			$status = 250;
		}
		
		$data['app_resource_id'] = $app_resource_id;
		
		$this->data->addBody(-140, $data, $msg, $status);
	}
	
	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$app_resource_id = request_int('app_resource_id');
		
		$flag = $this->baseAppResourcesModel->removeAppResources($app_resource_id);
		
		if ($flag)
		{
			$msg = _('success');
			$status = 200;
		}
		else
		{
			$msg = _('failure');
			$status = 250;
		}
		
		$data['app_resource_id'] = array($app_resource_id);
		
		$this->data->addBody(-140, $data, $msg, $status);
	}
	
	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['app_resource_id']        = request_string('app_resource_id'); //
		$data['app_id']                 = request_string('app_id_ver')        ; // AppId
		$data['app_version']            = request_string('app_version')   ; // 当前版本
		$data['app_version_next']       = request_string('app_version_next'); // 下个版本
		$data['app_res_filename']       = request_string('app_res_filename'); // 资源名称
		$data['app_res_filesize']       = request_string('app_res_filesize'); // 文件大小
		$data['app_package_url']        = request_string('app_package_url'); // 
		$data['app_res_time']           = request_string('app_res_time')  ; // 版本时间
		$data['app_reinstall']          = request_string('app_reinstall') ; // 
		$data['app_release']            = request_string('app_release')   ; // 
		$data['app_update_log']         = request_string('app_update_log')   ; //更新日志
		
		
		$app_resource_id = request_int('app_resource_id');
		$data_rs = $data;
		
		unset($data['app_resource_id']);
		
		$flag = $this->baseAppResourcesModel->editAppResources($app_resource_id, $data);
		
		if ($data['app_release'] && $flag)
		{
			$Base_AppModel = new Base_AppVersionModel();
			$app_row = $Base_AppModel->getOne($data['app_id']);
			
			if (version_compare($data['app_version'], $app_row['app_version'], 'gt'))
			{
				$Base_AppModel->editAppVersion($data['app_id'], array('app_version'=>$data['app_version']));
			}
		}
		
		
		$this->data->addBody(-140, $data_rs);
	}
}
?>