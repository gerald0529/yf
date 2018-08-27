<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Base_SearchCtl extends Yf_AppController
{
	public $searchWordModel = null;

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
		$this->searchWordModel = new Search_WordModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function search()
	{

		include $this->view->getView();
	}

	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function getSearchList()
	{

		$page = request_string('page');
		$rows = request_string('rows');

		$cond_row  = array();
		$order_row = array('search_nums' => 'DESC');

		$data = array();
		
		$data = $this->searchWordModel->getSearchWordList($cond_row, $order_row, $page, $rows);
		

		$this->data->addBody(-140, $data);
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
	 * 读取
	 *
	 * @access public
	 */
	public function get()
	{
		$search_id = request_int('search_id');

		$cond_row['search_id'] = $search_id;

		$rows = $this->searchWordModel->getSearchWordInfo($cond_row);
		
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

		$data['search_keyword']    = request_string('search_keyword');
		$data['search_char_index'] = request_string('search_char_index');

		$flag = $this->searchWordModel->addSearchWord($data);

		if ($flag)
		{
			$msg    = __('success');
			$status = 200;

		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['id'] = $data['search_id'];

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$search_id = request_int('search_id');

		$flag = $this->searchWordModel->removeSearchWord($search_id);

		if ($flag)
		{
			$msg    = __('success');
			$status = 200;

		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['search_id'] = array($search_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['search_id']         = request_int('search_id');
		$data['search_keyword']    = request_string('search_keyword');
		$data['search_char_index'] = request_string('search_char_index');

		$search_id = request_int('search_id');
		$data_rs   = $data;

		unset($data['search_id']);

		$flag = $this->searchWordModel->editSearchWord($search_id, $data);


		$this->data->addBody(-140, $data_rs);
	}
}

?>