<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Article_ReplyCtl extends Yf_AppController
{
	public $articleReplyModel = null;

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
		$this->articleReplyModel = new Article_ReplyModel();
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
			$data = $this->articleReplyModel->getReplyList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->articleReplyModel->getReplyList($cond_row, $order_row, $page, $rows);
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

		$article_reply_id = request_int('article_reply_id');
		$rows             = $this->articleReplyModel->getReply($article_reply_id);

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
		$data['article_reply_id']        = request_string('article_reply_id'); // 评论回复id
		$data['article_reply_parent_id'] = request_string('article_reply_parent_id'); // 回复父id
		$data['article_id']              = request_string('article_id'); // 所属文章id
		$data['user_id']                 = request_string('user_id'); // 评论回复id
		$data['user_name']               = request_string('user_name'); // 评论回复姓名
		$data['user_id_to']              = request_string('user_id_to'); // 评论回复用户id
		$data['user_name_to']            = request_string('user_name_to'); // 评论回复用户名称
		$data['article_reply_content']   = request_string('article_reply_content'); // 评论回复内容
		$data['article_reply_time']      = request_string('article_reply_time'); // 评论回复时间
		$data['article_reply_show_flag'] = request_string('article_reply_show_flag'); // 问答是否显示


		$article_reply_id = $this->articleReplyModel->addReply($data, true);

		if ($article_reply_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['article_reply_id'] = $article_reply_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$article_reply_id = request_int('article_reply_id');

		$flag = $this->articleReplyModel->removeReply($article_reply_id);

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

		$data['article_reply_id'] = array($article_reply_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['article_reply_id']        = request_string('article_reply_id'); // 评论回复id
		$data['article_reply_parent_id'] = request_string('article_reply_parent_id'); // 回复父id
		$data['article_id']              = request_string('article_id'); // 所属文章id
		$data['user_id']                 = request_string('user_id'); // 评论回复id
		$data['user_name']               = request_string('user_name'); // 评论回复姓名
		$data['user_id_to']              = request_string('user_id_to'); // 评论回复用户id
		$data['user_name_to']            = request_string('user_name_to'); // 评论回复用户名称
		$data['article_reply_content']   = request_string('article_reply_content'); // 评论回复内容
		$data['article_reply_time']      = request_string('article_reply_time'); // 评论回复时间
		$data['article_reply_show_flag'] = request_string('article_reply_show_flag'); // 问答是否显示


		$article_reply_id = request_int('article_reply_id');
		$data_rs          = $data;

		unset($data['article_reply_id']);

		$flag = $this->articleReplyModel->editReply($article_reply_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>