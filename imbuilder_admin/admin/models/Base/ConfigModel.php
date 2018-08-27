<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Base_ConfigModel extends Base_Config
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $config_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getConfigList($config_id = null, $page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);

		$this->sql->setLimit($offset, $rows);

		$config_id_row = array();
		$config_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();

		$data_rows = array();

		if ($config_id_row)
		{
			$data_rows = $this->getConfig($config_id_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		return $data;
	}
	public function getSmsConfig()
	{
		$this->sql->setWhere('config_key','smsAccount');
		$smsAccount=$this->_select();
		$temp=array_values($smsAccount);
		$data['smsAccount']=$temp[0]['config_data'];
		$this->sql->setWhere('config_key','smsPassword');
		$smsPassword=$this->_select();
		$temp=array_values($smsPassword);
		$data['smsPassword']=$temp[0]['config_data'];
		return $data;
	}
	public function saveSmsConfig($post)
	{
		$this->sql->setWhere('config_key','smsAccount');
		$this->_delete();
		$this->sql->setWhere('config_key','smsPassword');
		$this->_delete();
		if(is_array($post))
		{
			foreach($post as $ke=>$va)
			{
				$po['config_name']='短信设置';
				if($ke=='smsAccount')	
					$po['config_key']='smsAccount';
				elseif($ke=='smsPassword')	
					$po['config_key']='smsPassword';
				$po['config_data']=$va;
				$po['config_comment']='';
				$po['config_datatype']='string';
				$id=$this->addConfig($po,true);
			}
		}
		
		return $id;
	}
}
?>