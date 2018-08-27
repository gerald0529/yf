<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Article_GroupCtl extends Yf_AppController
{
	public $articleGroupModel = null;

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
		$this->articleGroupModel = new Article_GroupModel();
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

		if ($skey = request_string('skey'))
		{
			$data = $this->articleGroupModel->getGroupList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->articleGroupModel->getGroupList($cond_row, $order_row, $page, $rows);
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

		$article_group_id = request_int('article_group_id');
		$rows             = $this->articleGroupModel->getGroup($article_group_id);

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
		$data['article_group_id']        = request_string('article_group_id'); // ID
		$data['article_group_title']     = request_string('article_group_title'); // 标题
		$data['article_group_lang']      = request_string('article_group_lang'); // 语言
		$data['article_group_sort']      = request_string('article_group_sort'); // 排序
		$data['article_group_logo']      = request_string('article_group_logo'); // logo
		$data['article_group_parent_id'] = request_string('article_group_parent_id'); // 上级分类id


		$article_group_id = $this->articleGroupModel->addGroup($data, true);

		if ($article_group_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['article_group_id'] = $article_group_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$article_group_id = request_int('article_group_id');

		$flag = $this->articleGroupModel->removeGroup($article_group_id);

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

		$data['article_group_id'] = array($article_group_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['article_group_id']        = request_string('article_group_id'); // ID
		$data['article_group_title']     = request_string('article_group_title'); // 标题
		$data['article_group_lang']      = request_string('article_group_lang'); // 语言
		$data['article_group_sort']      = request_string('article_group_sort'); // 排序
		$data['article_group_logo']      = request_string('article_group_logo'); // logo
		$data['article_group_parent_id'] = request_string('article_group_parent_id'); // 上级分类id


		$article_group_id = request_int('article_group_id');
		$data_rs          = $data;

		unset($data['article_group_id']);

		$flag = $this->articleGroupModel->editGroup($article_group_id, $data);
		$this->data->addBody(-140, $data_rs);
	}


}

?>