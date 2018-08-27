<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Sns_TimelineModel extends Sns_Timeline
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $timeline_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getTimelineList($user_id = null, $page=1, $rows=100, $sort='desc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$user_id_row = array();
		if($user_id)
		{
			$a=$this->sql->setwhere('user_id',$user_id);
		}

		$this->sql->setOrder('timeline_id',$sort);
		$user_id_row = $this->selectKeyLimit();
		//echo '<pre>';print_r($user_id_row);exit;
		//读取主键信息
		$total = $this->getFoundRows();
		$data_rows = array();

		if ($user_id_row)
		{
			$data_rows = $this->getTimeline($user_id_row);
		}
		rsort($data_rows);
		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	public function updateTimelineList($user_id = null,$status= null,$timeline_id= null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$user_id_row = array();
		$this->sql->setwhere('user_id',$user_id);
		if($status == 1) //更新
		{
			$this->sql->setwhere('timeline_id',$timeline_id,'>');
		}
		if($status == 2) //更多
		{
			$this->sql->setwhere('timeline_id',$timeline_id,'<');
		}
		$user_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($user_id_row)
		{
			$data_rows = $this->getTimeline($user_id_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}

	public function removeTimeByUidSid($user_id=null,$sns_id=null)
	{
		$this->sql->setwhere('user_id',$user_id);

		$this->sql->setwhere('sns_id',$sns_id,'IN');

		$id_row = $this->selectKeyLimit();
		
		$this->removeTimeline($id_row);
	}
	
	//通过sns_id删除信息
	public function removeTimeBySid($sns_id=null)
	{
		$this->sql->setwhere('sns_id',$sns_id);

		$id_row = $this->selectKeyLimit();
		
		$this->removeTimeline($id_row);
	}
	
	//通过user_id获得信息
	public function getTimeByUid($user_id=null)
	{
		$this->sql->setwhere('user_id',$user_id);
		$row = $this->selectKeyLimit();

		return $row;
	}

	//通过user_id和sns_id_row获得信息
	public function getTimeByUidSid($user_id=null,$sns_id_row=array(),$page,$rows,$sort='desc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);
		//echo '<pre>';print_r($sns_id_row);exit;
		$this->sql->setLimit($offset, $rows);

		$user_id_row = array();
		if($user_id)
		{
			$this->sql->setwhere('user_id',$user_id);
		}
		if($sns_id_row)
		{
			$this->sql->setwhere('sns_id',$sns_id_row,'IN');
		}

		$this->sql->setOrder('timeline_id',$sort);
		$timeline_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();
		$data_rows = array();

		if ($timeline_id_row )
		{
			$data_rows = $this->getTimeline($timeline_id_row);
		}
		rsort($data_rows);
		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		fb($data);
		return $data;
	}

	//通过user_id获得一条最新的信息
	public function getTimeByUidDesc($user_id=null)
	{
		$this->sql->setwhere('user_id', $user_id);
		$this->sql->setLimit(0, 1);
		$this->sql->setOrder('action_time', 'desc');
		$row = $this->selectKeyLimit();

		$data_rows = array();
		if($row)
		{
			$data_rows = $this->getTimeline($row);
		}

		return $data_rows;
	}

	//通过user_id和sns_id_row获得信息
	public function getTimeByUidSidSearch($user_id=null,$sns_id_row=array(),$page,$rows,$sort='desc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$user_id_row = array();
		if($user_id)
		{
			$this->sql->setwhere('user_id',$user_id);
		}
		$this->sql->setwhere('sns_id',$sns_id_row,'IN');
		
		$this->sql->setOrder('timeline_id',$sort);
		$timeline_id_row = $this->selectKeyLimit();
		//echo '<pre>';print_r($timeline_id_row);exit;
		//读取主键信息
		$total = $this->getFoundRows();
		$data_rows = array();
		if ($timeline_id_row )
		{
			$data_rows = $this->getTimeline($timeline_id_row);
		}

		rsort($data_rows);
		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		fb($data);
		return $data;
	}
	
}
?>
