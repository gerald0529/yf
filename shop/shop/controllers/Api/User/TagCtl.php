<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Api_User_TagCtl extends Api_Controller
{
	public $userTagModel          = null;
	public $logisticsExpressModel = null;

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

		$this->userTagModel          = new User_TagModel();
		$this->logisticsExpressModel = new ExpressModel();
	}

	/**
	 * 获取会员标签列表
	 *
	 * @access public
	 */

	public function getUserTagList()
	{
		$page = request_int('page', 1);
		$rows = request_int('rows', 10);
		$type = request_string('user_type');
		$name = request_string('search_name');
		
		$cond_row = array();
		$sort     = array('user_tag_sort' => 'asc');
		
		if ($name)
		{
			if ($type == 1)
			{
				$cond_row['user_tag_id'] = $name;
			}
			else
			{
				$type            = 'user_tag_name:LIKE';
				$cond_row[$type] = '%' . $name . '%';
			}
			
		}
		$data = $this->userTagModel->getTagList($cond_row, $sort, $page, $rows);

		$this->data->addBody(-140, $data);
	}

	/**
	 * 增加会员标签页面
	 *
	 * @access public
	 */
	public function addUserTag()
	{

		$data = array();
		
		$this->data->addBody(-140, $data);
	}

	/**
	 * 增加会员标签
	 *
	 * @access public
	 */
	public function addUserTagDetail()
	{
		$user_tag_name      = request_string('user_tag_name');
		$user_tag_sort      = request_int('user_tag_sort');
		$user_tag_image     = request_string('user_tag_image');
		$user_tag_content   = request_string('user_tag_content');
		$user_tag_recommend = request_int('user_tag_recommend');
		
		$add_tag_row['user_tag_name']      = $user_tag_name;
		$add_tag_row['user_tag_sort']      = $user_tag_sort;
		$add_tag_row['user_tag_image']     = $user_tag_image;
		$add_tag_row['user_tag_content']   = $user_tag_content;
		$add_tag_row['user_tag_recommend'] = $user_tag_recommend;

		$flag = $this->userTagModel->addTag($add_tag_row);
		
		if ($flag !== false)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			
			$msg    = __('failure');
			$status = 250;
		}
		
		$data = array();
		
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 编辑会员标签页面
	 *
	 * @access public
	 */
	public function editUserTag()
	{
		$user_tag_id             = request_int('id');
		$cond_row['user_tag_id'] = $user_tag_id;

		$data = $this->userTagModel->getTagDetail($cond_row);

		$this->data->addBody(-140, $data);
	}

	/**
	 * 修改会员标签
	 *
	 * @access public
	 */
	public function editUserTagDetail()
	{
		$user_tag_id        = request_int('user_tag_id');
		$user_tag_name      = request_string('user_tag_name');
		$user_tag_sort      = request_int('user_tag_sort');
		$user_tag_image     = request_string('user_tag_image');
		$user_tag_content   = request_string('user_tag_content');
		$user_tag_recommend = request_int('user_tag_recommend');
		
		$edit_tag_row['user_tag_name']      = $user_tag_name;
		$edit_tag_row['user_tag_sort']      = $user_tag_sort;
		$edit_tag_row['user_tag_image']     = $user_tag_image;
		$edit_tag_row['user_tag_content']   = $user_tag_content;
		$edit_tag_row['user_tag_recommend'] = $user_tag_recommend;


		$flag = $this->userTagModel->editTag($user_tag_id, $edit_tag_row);

		if ($flag !== false)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除会员标签
	 *
	 * @access public
	 */
	public function delUserTag()
	{
		$user_tag_id = request_int('id');

		$flag = $this->userTagModel->removeTag($user_tag_id);
		
		if ($flag !== false)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		
		$data = array();
		$this->data->addBody(-140, $data);
	}

}

?>