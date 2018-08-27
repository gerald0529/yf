<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Article_GroupCtl extends Api_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

	}

	/*
	 * 文章分类列表
	 *
	 * @param   page    页码
	 * @param   rows    每天显示条数
	 *
	 * @return  data    文章分类数据
	 *
	 */
	public function articleGroupList()
	{
		$Article_GroupModel = new Article_GroupModel();
		$page               = request_int('page');
		$rows               = request_int('rows');
		$cond_rows          = array();
		$order_rows         = array();
		$data               = $Article_GroupModel->listByWhere($cond_rows, $order_rows, $page, $rows);
		$this->data->addBody(-140, $data);
	}

	/*
	 * 获取父类id，name
	 *
	 * @param   id  文章id
	 *
	 * @return  re  文章分id,名字
	 */
	public function getGroupName()
	{
		$Article_GroupModel = new Article_GroupModel();
		$re                 = array();
		$id                 = request_int('id');
		if ($id)
		{
			$data              = $Article_GroupModel->getOne($id);
			$re['parent_name'] = $data['article_group_title'];
			$re['parent_id']   = $id;
		}
		if ($re)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$this->data->addBody(-140, $re, $msg, $status);
	}

	/*
	 * 编辑文章分类
	 *
	 * @param   article_group_id    文章分类id
	 * @param   article_group_sort  文章排序
	 * @param   article_group_title 文章标题
	 * @param   article_group_parent_id 文章父类id
	 *
	 * @return  data    编辑的文章内容
	 */
	public function editGroup()
	{
		$Article_GroupModel = new Article_GroupModel();
		$data               = array();

		$article_group_id = request_int('article_group_id');

		$data['article_group_sort']      = request_int('article_group_sort');
		$data['article_group_title']     = request_string('article_group_title');
		$data['article_group_parent_id'] = request_int('article_group_parent_id');
		$flag                            = $Article_GroupModel->editGroup($article_group_id, $data);

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
		$data['id']               = $article_group_id;
		$data['article_group_id'] = $article_group_id;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 新增文章分类
	 *
	 * @param   article_group_sort  文章分类排序
	 * @param   article_group_title 文章分类标题
	 * @param   article_group_parent_id 文章分类父类id
	 *
	 * @return  data    新增成功的文章内容
	 */
	public function addGroup()
	{
		$Article_GroupModel = new Article_GroupModel();

		$data['article_group_sort']      = request_int('article_group_sort');
		$data['article_group_title']     = request_string('article_group_title');
		$data['article_group_parent_id'] = request_int('article_group_parent_id');

		$article_group_id = $Article_GroupModel->addGroup($data, true);

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
		$data['id']               = $article_group_id;
		$data['article_group_id'] = $article_group_id;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 删除数据
	 *
	 * @param   article_group_id    文章id
	 *
	 * @return  data    删除操作的id
	 *
	 */
	public function removeGroup()
	{
		$Article_GroupModel = new Article_GroupModel();

		$id = request_int('article_group_id');

		$flag = $Article_GroupModel->removeGroup($id);

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

		$data['id']               = $id;
		$data['article_group_id'] = $id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 获取所有分类
	 *
	 * @return  re  查询出来的分类数据
	 */

	public function queryAllGroup()
	{
		$Article_GroupModel = new Article_GroupModel();

		$data       = $Article_GroupModel->getByWhere();
		$data       = array_values($data);
		$re['rows'] = $data;
		$this->data->addBody(-140, $re);
	}

}

?>