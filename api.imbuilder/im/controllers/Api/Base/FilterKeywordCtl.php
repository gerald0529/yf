<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Base_FilterKeywordCtl extends Yf_AppController
{
	public $baseFilterKeywordModel = null;

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
		$this->baseFilterKeywordModel = new Base_FilterKeywordModel();
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

		$page = request_string('page');
		$rows = request_string('rows');
		$sort = request_string('sord');

		$cond_row  = array();
		$order_row = array();

		$data = array();

		if ($skey = request_string('skey'))
		{
			$data = $this->baseFilterKeywordModel->getFilterKeywordList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->baseFilterKeywordModel->getFilterKeywordList($cond_row, $order_row, $page, $rows);
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

		$keyword_find = request_string('keyword_find');
		$rows         = $this->baseFilterKeywordModel->getFilterKeyword($keyword_find);

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
		$data['keyword_find']    = request_string('keyword_find'); //
		$data['keyword_replace'] = request_string('keyword_replace'); //
		$data['keyword_time']    = get_date_time(); //

		$data['keyword_statu'] = $data['keyword_replace'] ? 2 : 1; // 1:禁止 2：替换

		$flag = true;

		if ($data['keyword_replace'])
		{
			if (preg_match("/[\x{4e00}-\x{9fa5}\w]+$/u", $data['keyword_replace']))
			{

			}
			else
			{
				$status = 250;
				$msg    = _('不能包含特殊字符串');
				$flag   = false;
			}
		}


		if ($flag && preg_match("/[\x{4e00}-\x{9fa5}\w]+$/u", $data['keyword_find']))
		{
			$keyword_find = $this->baseFilterKeywordModel->addFilterKeyword($data);

			if ($keyword_find)
			{
				$msg    = _('success');
				$status = 200;

				//初始化
				$filter_rule_rows = $this->baseFilterKeywordModel->getFilterRule();
			}
			else
			{
				$msg    = _('failure');
				$status = 250;
			}
		}
		else
		{
			$status = 250;
			$msg    = $v->errors();
		}

		$data['id'] = $data['keyword_find'];

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$keyword_find = request_string('keyword_find');

		$flag = $this->baseFilterKeywordModel->removeFilterKeyword($keyword_find);

		if ($flag)
		{
			$msg    = _('success');
			$status = 200;

			//初始化
			$filter_rule_rows = $this->baseFilterKeywordModel->getFilterRule();
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}

		$data['keyword_find'] = array($keyword_find);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['keyword_find']    = request_string('keyword_find'); //
		$data['keyword_replace'] = request_string('keyword_replace'); //
		$data['keyword_time']    = get_date_time(); //

		$data['keyword_statu'] = $data['keyword_replace'] ? 2 : 1; // 1:禁止 2：替换

		$keyword_find = request_string('keyword_find');
		$data_rs      = $data;

		if ($data['keyword_replace'] && preg_match("/[\x{4e00}-\x{9fa5}\w]+$/u", $data['keyword_replace']))
		{
			unset($data['keyword_find']);

			$flag = $this->baseFilterKeywordModel->editFilterKeyword($keyword_find, $data);

			//初始化
			$filter_rule_rows = $this->baseFilterKeywordModel->getFilterRule();

			$status = 200;
			$msg    = _('success');
		}
		else
		{
			if (!$data['keyword_replace'])
			{
				unset($data['keyword_find']);

				$flag = $this->baseFilterKeywordModel->editFilterKeyword($keyword_find, $data);

				//初始化
				$filter_rule_rows = $this->baseFilterKeywordModel->getFilterRule();

				$status = 200;
				$msg    = _('success');
			}
			else
			{
				$status = 250;
				$msg    = _('不能包含特殊字符串');
			}

		}

		$this->data->addBody(-140, $data_rs, $msg, $status);
	}
}

?>