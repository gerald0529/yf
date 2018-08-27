<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 *
 *
 * @category   Framework
 * @package    __init__
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo
 */
class Article_Group extends Yf_Model
{
	public $_cacheKeyPrefix     = 'c|article_group|';
	public $_cacheName          = 'article';
	public $_tableName          = 'article_group';
	public $_tablePrimaryKey    = 'article_group_id';
	public $articleGroupListKey = null;

	/**
	 * @param string $user User Object
	 * @var   string $db_id 指定需要连接的数据库Id
	 * @return void
	 */
	public function __construct(&$db_id = 'shop', &$user = null)
	{
		$this->_tableName = TABEL_PREFIX . $this->_tableName;

		parent::__construct($db_id, $user);
		$this->articleGroupListKey = $this->_cacheKeyPrefix . 'article_list|all_data';

	}

	/**
	 * 根据主键值，从数据库读取数据
	 *
	 * @param  int $article_group_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGroup($article_group_id = null, $sort_key_row = null)
	{
		$rows = array();
		$rows = $this->get($article_group_id, $sort_key_row);

		return $rows;
	}

	/**
	 * 插入
	 * @param array $field_row 插入数据信息
	 * @param bool $return_insert_id 是否返回inset id
	 * @param array $field_row 信息
	 * @return bool  是否成功
	 * @access public
	 */
	public function addGroup($field_row, $return_insert_id = false)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		//$this->removeKey($article_group_id);
		return $add_flag;
	}

	/**
	 * 根据主键更新表内容
	 * @param mix $article_group_id 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editGroup($article_group_id = null, $field_row)
	{
		$update_flag = $this->edit($article_group_id, $field_row);

		return $update_flag;
	}

	/**
	 * 更新单个字段
	 * @param mix $article_group_id
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editGroupSingleField($article_group_id, $field_name, $field_value_new, $field_value_old)
	{
		$update_flag = $this->editSingleField($article_group_id, $field_name, $field_value_new, $field_value_old);

		return $update_flag;
	}

	/**
	 * 删除操作
	 * @param int $article_group_id
	 * @return bool $del_flag 是否成功
	 * @access public
	 */
	public function removeGroup($article_group_id)
	{
		$del_flag = $this->remove($article_group_id);

		//$this->removeKey($article_group_id);
		return $del_flag;
	}

    /*
     * 获取文章分类名称
     *
     * @param   int $id 主键id
     *
     * @return  string 分类名称
     */
	public function getGroupName($id = 0)
	{
		$re = array();
		if ($id)
		{
			$data = $this->getOne($id);
			if ($data)
			{
				$re = $data['article_group_title'];
			}
			else
			{
				$re = '分类不存在';
			}
		}
		else
		{
			$re = '未分类';
		}
		return $re;
	}

	/*
	 * 获取底部文章显示数据
	 * @param   int $parent_id 文章父类id，默认为0，即查询出所有数据
	 * @return  array  $re 查询出来的数据
	 */
	public function getArticleGroupList($parent_id = 0)
	{
		/*//设置cache
		$Cache = Yf_Cache::create('base');

		if ($re = $Cache->get($this->articleGroupListKey))
		{

		}
		else
		{*/
		$re                 = array();
		$Article_GroupModel = new Article_GroupModel();
		$datas              = $Article_GroupModel->getByWhere(array('article_group_parent_id' => $parent_id), array('article_group_sort'=>'ASC'));



		if (!empty($datas) && $parent_id == 0)
		{
			$re = array();
			foreach ($datas as $key => $value)
			{
				$group_id = $key;
				$data     = array();
				$data     = $Article_GroupModel->getGroupArticleList($group_id);
				if (!empty($data))
				{
					$re[$key]['article']    = $data;
				}
                $re[$key]['group_name'] = $value['article_group_title'];
			}
		}
		/*$Cache->save($re, $this->articleGroupListKey);
	}*/

		return $re;
	}



    /*
     * 获取所有文章分类
     * @param int $parent_id
     * @return array $re
     */
	public function getArticleGroupLists($parent_id)
	{
		$Article_GroupModel = new Article_GroupModel();
		$re                 = array();
		if ($parent_id != 0)
		{
			$datas            = array();
			$datas            = $Article_GroupModel->getOne($parent_id);
			$re['group_name'] = $datas['article_group_title'];

			$data = $Article_GroupModel->getGroupArticleList($parent_id);
			if (!empty($data))
			{
				$re['article'] = $data;
			}
		}
		return $re;
	}
}

?>