<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_LicenceCtl extends Api_Controller
{
	public $baseAppLicenceModel = null;

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
		$this->baseAppLicenceModel = new Base_AppLicenceModel();
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

		request_string('begin_date') ? $cond_row['licence_effective_startdate:>='] = request_string('begin_date') : ''; // 有效期开始与结束
		request_string('end_date') ? $cond_row['licence_effective_enddate:<='] = request_string('end_date') : ''; // 有效期开始与结束1

		{
			$data = $this->baseAppLicenceModel->getAppLicenceList($cond_row, $order_row, $page, $rows);
		}


		$this->data->addBody(-140, $data);
	}


	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function logLists()
	{
		$page = request_int('page');
		$rows = request_int('rows');
		$sort = request_int('sord');

		$cond_row  = array();
		$order_row = array();

		request_string('begin_date') ? $cond_row['licence_log_date:>='] = request_string('begin_date') : ''; // 有效期开始与结束
		request_string('end_date') ? $cond_row['licence_log_date:<='] = request_string('end_date') : ''; // 有效期开始与结束1

		{
			$Base_AppLicenceLogModel = new Base_AppLicenceLogModel();
			$data = $Base_AppLicenceLogModel->listByWhere($cond_row, $order_row, $page, $rows);
		}


		$this->data->addBody(-140, $data);
	}


	/**
	 * 读取
	 *
	 * @access public
	 */
	public function get()
	{
		$user_id = Perm::$userId;

		$licence_id = request_int('licence_id');
		$rows = $this->baseAppLicenceModel->getAppLicence($licence_id);

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
		$data['licence_domain']         = request_string('licence_domain'); // 允许的域名','分割
		$data['licence_price']          = request_string('licence_price') ; // 费用
		$data['licence_effective_startdate'] = request_string('licence_effective_startdate'); // 有效期开始与结束
		$data['licence_effective_enddate'] = request_string('licence_effective_enddate'); // 有效期开始与结束1
		$data['app_id']                 = request_string('licence_app_id')        ; // 所属游戏id
		$data['company_name']           = request_string('company_name')  ; // 公司名称
		$data['company_phone']          = request_string('company_phone') ; // 电话
		$data['contacter']              = request_string('contacter')     ; // 联系人
		$data['sign_time']              = request_string('sign_time')     ; // 签约时间
		$data['user_name']              = request_string('user_name')     ; // 管理员账号
		$data['business_agent']         = request_string('business_agent'); // 业务代表



		$licence_row = array('expires' => strtotime($data['licence_effective_enddate']), 'licensee' => $data['company_name'], 'domain' => $data['licence_domain'], 'key' => sha1(uniqid(true)));
 
		$lic = new Yf_Licence_MakerNew();
	 
		$licence_key = $lic->createLicence($licence_row, APP_PATH . '/data/licence/private.pem', APP_PATH . '/data/licence/licence.lic');
		$data['licence_key']         = $licence_key;

		$licence_id = $this->baseAppLicenceModel->addAppLicence($data, true);

		if ($licence_id)
		{
			$msg = _('success');
			$status = 200;

			//生成证书
		}
		else
		{
			$msg = _('failure');
			$status = 250;
		}

		$data['licence_id'] = $licence_id;
		$data['id'] = $licence_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['licence_domain']         = request_string('licence_domain'); // 允许的域名','分割
		$data['licence_price']          = request_string('licence_price') ; // 费用
		$data['licence_effective_startdate'] = request_string('licence_effective_startdate'); // 有效期开始与结束
		$data['licence_effective_enddate'] = request_string('licence_effective_enddate'); // 有效期开始与结束1
		$data['app_id']                 = request_string('licence_app_id')        ; // 所属游戏id
		$data['company_name']           = request_string('company_name')  ; // 公司名称
		$data['company_phone']          = request_string('company_phone') ; // 电话
		$data['contacter']              = request_string('contacter')     ; // 联系人
		$data['sign_time']              = request_string('sign_time')     ; // 签约时间
		$data['user_name']              = request_string('user_name')     ; // 管理员账号
		$data['business_agent']         = request_string('business_agent'); // 业务代表


		$licence_row = array('expires' => strtotime($data['licence_effective_enddate']), 'licensee' => $data['company_name'], 'domain' => $data['licence_domain'], 'key' => sha1(uniqid(true)));

		$lic = new Yf_Licence_MakerNew();
		$licence_key = $lic->createLicence($licence_row, APP_PATH . '/data/licence/private.pem', APP_PATH . '/data/licence/licence.lic');
		$data['licence_key']         = $licence_key;



		$licence_id = request_int('licence_id');
		$data_rs = $data;

		$data_rs['id']         = $licence_id;
		unset($data['licence_id']);

		$flag = $this->baseAppLicenceModel->editAppLicence($licence_id, $data);

		//生成证书



		$this->data->addBody(-140, $data_rs);
	}


	/**
	 * 获取证书
	 *
	 * @access public
	 */
	public function download()
	{
		$licence_id = request_int('licence_id');
		$row = $this->baseAppLicenceModel->getOne($licence_id);

		$data = array();

		$this->data->addBody(-140, $row);
	}


}
?>