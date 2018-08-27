<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_AddressModel extends User_Address
{
	const DEFAULT_ADDRESS  = 1;

	/**
	 * 读取分页列表
	 *
	 * @param  int $user_address_id主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getAddressList($cond_row=array(), $order_row=array(), $page=1, $rows=100)
	{
		return $this->getByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getAddressInfo($user_address_id = null)
	{
		$data = $this->getOne($user_address_id);

		return $data;
	}
	
	//设置为默认
	public function editAddressInfo($de)
	{
		$user_address_ids = array_column($de,'user_address_id');
		
		$update_flag = $this->editAddress($user_address_ids,array('user_address_default'=>'0'));

		return $update_flag;
	}
	/**
	 * 读数量
	 *
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCount($cond_row = array())
	{
		return $this->getNum($cond_row);
	}
}
?>