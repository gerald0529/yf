<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Sns_CommentModel extends Sns_Comment
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $commect_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCommentList($commect_id = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$commect_id_row = array();
		$commect_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($commect_id_row)
		{
			$data_rows = $this->getComment($commect_id_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	public function getCommentBySid($sns_id = null, $sort='desc')
	{
		$this->sql->setwhere('sns_id',$sns_id)->setwhere('commect_state',0);
		$this->sql->setOrder('commect_id', $sort);
		$data = $this->getComment("*");
		return $data;
	}

	public function getCommentBySidTid($sns_id = null,$to_commect_id)
	{
		$this->sql->setwhere('sns_id',$sns_id)->setwhere('to_commect_id',$to_commect_id)->setwhere('commect_state',0);
		$data = $this->getComment("*");
		return $data;
	}
}
?>