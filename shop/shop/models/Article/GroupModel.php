<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Article_GroupModel extends Article_Group
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $article_group_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getGroupList($article_group_id = null, $page = 1, $rows = 100, $sort = 'asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$article_group_id_row = array();
		$article_group_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($article_group_id_row)
		{
			$data_rows = $this->getGroup($article_group_id_row);
		}

		$data              = array();
		$data['page']      = $page;
		$data['total']     = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records']   = count($data_rows);
		$data['items']     = array_values($data_rows);

		return $data;
	}

    /*
     * 获取指定文章分类下面的文章
     * @param int $group_id 文章分类id
     * @return array  $re 查询数据
     */
	public function getGroupArticleList($group_id = null)
	{
		$Article_BaseModel  = new Article_BaseModel();
		$Artilce_GroupModel = new Article_GroupModel();

		$child_id_rows = $Artilce_GroupModel->getChildIds($group_id);
		array_push($child_id_rows, $group_id);

		$order_row = array();
		$order_row['article_sort'] = 'ASC';
		$order_row['article_add_time'] = 'DESC';


			$re = $Article_BaseModel->getByWhere(array(
				'article_group_id:in' => $child_id_rows,
				// 'article_type' => $Article_BaseModel::ARTICLE_TYPE_ARTICLE,
			),$order_row);


		return $re;
	}

    /*
     * 根据父类id,获取子类id
     * @param int $group_parent_id 文章分类父类id
     * @return array group_id_row
     */
	public function getChildIds($group_parent_id = null, $recursive = true)
	{
		$Artilce_GroupModel = new Article_GroupModel();

		$group_data = array();

		if (is_array($group_parent_id))
		{
			$cond_row = array('article_group_parent_id:in' => $group_parent_id);
		}
		else
		{
			$cond_row = array('article_group_parent_id' => $group_parent_id);
		}

		$group_id_row = $this->getKeyByMultiCond($cond_row);

		if ($recursive && $group_id_row)
		{
			$rs = call_user_func_array(array(
										   $this,
										   'getChildIds'
									   ), array(
										   $group_id_row,
										   $recursive
									   ));

			$group_id_row = array_merge($group_id_row, $rs);
		}

		return $group_id_row;
	}
}

?>