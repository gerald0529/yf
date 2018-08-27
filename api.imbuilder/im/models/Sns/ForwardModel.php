<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Sns_ForwardModel extends Sns_Forward
{

	public $_multiCollect = array();
	/**
	 * 读取分页列表
	 *
	 * @param  int $collect_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getForwardList($forward_id = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$forward_id_row = array();
		$this->sql->setwhere('forward_id',$forward_id);
		$forward_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($forward_id_row)
		{
			$data_rows = $this->getForward($forward_id_row);
		}
		rsort($data_rows);
		fb($data_rows);

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	public function getForwardBySidUid($sns_id,$forward_id)
	{
		$this->_multiCollect['sns_id'] = $sns_id;
		$this->_multiCollect['forward_id'] = $forward_id;
		$id_row = $this->getKeyByMultiCond($this->_multiCollect);
		return $id_row;
	}

	//根据被转发帖的ID得到转发数据
	public function getForwardrow($sns_id)
	{
		$rows = array();
        $this->sql->setWhere('sns_id', $sns_id);
        $rows = $this->getForward('*');

        return $rows;
	}
	
	//根据转发后的帖子ID得到转发数据
	public function getForwardByFid($forward_sns_id)
	{
		$row = array();
		$this->sql->setWhere('forward_sns_id', $forward_sns_id);
		$rows = $this->getForward('*');
		return $rows;
	}

	//根据源帖ID得到转发数据
	public function getForwardBySid($source_sns_id)
	{
		$row = array();
		$this->sql->setWhere('source_sns_id', $source_sns_id);
		$rows = $this->getForward('*');
		return $rows;
	}
}
?>