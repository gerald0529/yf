<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Article_BaseCtl extends Api_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

	}

	/*
	 * 获取文章列表
	 *
	 * @param int $page 页数
	 * @param int $rows 每页显示行数
	 *
	 * @return data 文章显示数据
	 */
	public function articleBaseList()
	{
		$Article_BaseModel  = new Article_BaseModel();
		$Article_GroupModel = new Article_GroupModel();

		$page      = request_int('page');
		$rows      = request_int('rows');
		$cond_row  = array();
		$order_row = array();
           
		$article_group = request_int('article_group');
		if($article_group)
		{
			$cond_row['article_group_id'] = $article_group;
		}
                
		$data = $Article_BaseModel->listByWhere($cond_row, $order_row, $page, $rows);

		$items = $data['items'];
		unset($data['items']);
             
		if (!empty($items))
		{
			foreach ($items as $key => $value)
			{
				if ($value['article_status'] == $Article_BaseModel::ARTICLE_STATUS_TRUE)
				{
					$items[$key]['article_status_name'] = '开启';
				}
				elseif ($value['article_status'] == $Article_BaseModel::ARTICLE_STATUS_FALSE)
				{
					$items[$key]['article_status_name'] = '关闭';
				}
				
				$items[$key]['article_group_name'] = $Article_GroupModel->getGroupName($value['article_group_id']);
			}
		}
		if ($items)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$data['items'] = $items;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 删除文章
	 *
	 * @param int article_id 文章id
	 *
	 * @return data 操作记录id
	 */
	public function removeBase()
	{
		$Article_BaseModel = new Article_BaseModel();

		$id = request_int('article_id');
		if ($id)
		{
			$flag = $Article_BaseModel->removeBase($id);
		}
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
		$data['id'] = $id;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 新增文章
	 *
	 * @param   article_title   文章标题
	 * @param   article_url     文章链接地址
	 * @param   article_status  文章状态
	 * @param   article_type    文章类型
	 * @param   article_sort    文章排序
	 * @param   article_desc    文章描述
	 * @param   article_logo    文章图片
	 * @param   article_group_id文章分类
	 *
	 * @return  data            新增的文章内容
	 */
	public function addArticleBase()
	{
		$Article_BaseModel = new Article_BaseModel();

		$data = array();

		$data['article_title']  = request_string('article_title');
		$data['article_url']    = request_string('article_url');
		$data['article_status'] = request_int('article_status');
		$data['article_type']   = request_int('article_type');
		$data['article_sort']   = request_int('article_sort');
		$data['article_desc']   = request_string('content');
		//$data['article_pic']    = request_string('article_pic');
		$article_pic_row          = request_row('setting');
		$data['article_pic']      = $article_pic_row['article_logo'];
		$data['article_group_id'] = request_int('article_group_id');
		$data['article_add_time'] = date('Y-m-d H:i:s', time());

		$article_id = $Article_BaseModel->addBase($data, true);

		if ($article_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['id']         = $article_id;
		$data['article_id'] = $article_id;
		

		$Article_GroupModel = new Article_GroupModel();
		$data['article_group_name'] = $Article_GroupModel->getGroupName($data['article_group_id']);
		
		if ($data['article_status'] == $Article_BaseModel::ARTICLE_STATUS_TRUE)
		{
			$data['article_status_name'] = __('开启');
		}
		elseif ($data['article_status'] == $Article_BaseModel::ARTICLE_STATUS_FALSE)
		{
			$data['article_status_name'] = __('关闭');
		}
		
		$this->data->addBody(-140, $data, $msg, $status);
	}

    /*
     * 编辑文章
     *
     * @param   article_id      文章id
     * @param   article_title   文章标题
     * @param   article_url     文章链接地址
     * @param   article_status  文章状态
     * @param   article_type    文章类型
     * @param   article_sort    文章排序
     * @param   article_desc    文章描述
     * @param   article_pic     文章图片
     * @param   article_group_id文章分类
     *
     * @return   data           编辑的内容
     */
	public function editArticleBase()
	{

		$Article_BaseModel = new Article_BaseModel();

		$article_id = request_int('article_id');

		$data['article_title']  = request_string('article_title');
		$data['article_url']    = request_string('article_url');
		$data['article_status'] = request_int('article_status');
		$data['article_type']   = request_int('article_type');
		$data['article_sort']   = request_int('article_sort');
		$data['article_desc']   = request_string('content');
		//$data['article_pic']    = request_string('article_pic');
		$article_pic_row          = request_row('setting');
		$data['article_pic']      = $article_pic_row['article_logo'];
		$data['article_group_id'] = request_int('article_group_id');
		$flag                     = $Article_BaseModel->editBase($article_id, $data);

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

		$data['id']         = $article_id;
		$data['article_id'] = $article_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function articleGroup()
	{
		$Article_GroupModel = new Article_GroupModel();
		$order = array('article_group_sort' => 'asc');
		$data  = $Article_GroupModel->getByWhere(array(),$order);
		$data = array_values($data);
		$result = array();
		$result[0]['id'] = 0;
		$result[0]['name'] = "文章分类";
		foreach($data as $key=>$value)
		{
			$result[$key+1]['id'] = $value['article_group_id'];
			$result[$key+1]['name'] = $value['article_group_title'];
		}

		$this->data->addBody(-140, $result);
	}
}

?>