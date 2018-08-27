<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Base_DistrictCtl extends Yf_AppController
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
		$district_parent_id = request_int('pid', request_int('area_id'));
		$data               = $this->baseDistrictModel->getDistrictTree($district_parent_id);
		fb($data);
		$this->data->addBody(-140, $data);
	}

	public function getAllDistrict()
	{
		$data = $this->baseDistrictModel->getDistrictAll();

		fb($data);
		$this->data->addBody(-140, $data);
	}

	public function getDistrictNameList()
	{
		$district_id = request_int('id');

		$data = $this->baseDistrictModel->getCookieDistrict($district_id);

		$this->data->addBody(-140, $data);
	}

	public function getDistrictName()
	{
		$district_id = request_int('id');

		$data = $this->baseDistrictModel->getOne($district_id);

		$this->data->addBody(-140, $data);
	}

}

?>