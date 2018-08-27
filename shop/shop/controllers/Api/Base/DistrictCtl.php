<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Base_DistrictCtl extends Api_Controller
{
	public $baseDistrictModel = null;

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
		$this->baseDistrictModel = new Base_DistrictModel();
	}

	/**
	 *
	 *
	 * @access public
	 */
	public function district()
	{
		$district_parent_id = request_int('nodeid', 0);
		//$district_parent_id = request_int('parentid', 0);
		$district_level = request_int('n_level', 0);

		$data = $this->baseDistrictModel->getDistrictTree($district_parent_id, false, $district_level);

		$this->data->addBody(-140, $data);
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

		if ($skey = request_string('skey'))
		{
			$data = $this->baseDistrictModel->getDistrictList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->baseDistrictModel->getDistrictList($cond_row, $order_row, $page, $rows);
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

		$district_id = request_int('district_id');
		$rows        = $this->baseDistrictModel->getDistrict($district_id);

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
		$data['district_id']           = request_string('district_id'); // 地区id
		$data['district_name']         = request_string('district_name'); // 地区名称
		$data['district_parent_id']    = request_string('district_parent_id'); // 父id
		$data['district_displayorder'] = request_string('district_displayorder'); // 排序
		$data['district_region']       = request_string('district_region'); // 区域名称 - 华北、东北、华东、华南、华中、西南、西北、港澳台、海外


		$district_id = $this->baseDistrictModel->addDistrict($data, true);

		if ($district_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['district_id'] = $district_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$district_id = request_int('district_id');

		$flag = $this->baseDistrictModel->removeDistrict($district_id);

		if ($flag)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$m      = $this->baseDistrictModel->msg->getMessages();
			$msg    = $m ? $m[0] : __('failure');
			$status = 250;
		}

		$data['id'] = array($district_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['district_id']           = request_string('district_id'); // 地区id
		$data['district_name']         = request_string('district_name'); // 地区名称
		$data['district_parent_id']    = request_string('district_parent_id'); // 父id
		$data['district_displayorder'] = request_string('district_displayorder'); // 排序
		$data['district_region']       = request_string('district_region'); // 区域名称 - 华北、东北、华东、华南、华中、西南、西北、港澳台、海外


		$district_id = request_int('district_id');
		$data_rs     = $data;

		unset($data['district_id']);

		$flag = $this->baseDistrictModel->editDistrict($district_id, $data);
		$this->data->addBody(-140, $data_rs);
	}

	public function getDistrictName()
	{
		$data_re = array();
		$id      = request_int('id');
		$data    = $this->baseDistrictModel->getOne($id);
		if ($data)
		{
			$data_re['id']            = $id;
			$data_re['district_name'] = $data['district_name'];
		}

		if ($data_re)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$this->data->addBody(-140, $data_re, $msg, $status);
	}

	public function editDistrict()
	{
		$id = request_int('district_id');

		$edit_data['district_name']   = request_string('district_name');
		$edit_data['district_region'] = request_string('district_region');

		$flag = $this->baseDistrictModel->editDistrict($id, $edit_data);

		if ($flag !== false)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data                = $edit_data;
		$data['id']          = $id;
		$data['district_id'] = $id;
		$data_re['items']    = $data;
		$this->data->addBody(-140, $data_re, $msg, $status);
	}

	public function addDistrict()
	{
		$data['district_name']      = request_string('district_name');
		$data['district_parent_id'] = request_int('parent_district');
		$data['district_region']    = request_string('district_region');
		$district_id                = $this->baseDistrictModel->addDistrict($data, true);
		if ($district_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data['id']       = $district_id;
		$data_re['items'] = $data;
		$this->data->addBody(-140, $data_re, $msg, $status);
	}

	public function refresh()
	{
		$Db    = Yf_Db::get('shop');

		$db = new Yf_Utils_DbManage ($Db);

		$file = APP_PATH . '/docs/district.sql';
		$init_db_row = array();
		if (is_file($file))
		{
			$flag = $db->import($file, TABEL_PREFIX, 'yf_',false);
			check_rs($flag, $init_db_row);
		}


		if (is_ok($init_db_row))
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>