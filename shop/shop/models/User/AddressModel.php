<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class User_AddressModel extends User_Address
{
	const DEFAULT_ADDRESS = 1;

	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getAddressList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		return $this->getByWhere($cond_row, $order_row, $page, $rows);
	}

	public function getAddressInfo($user_address_id = null)
	{
		$data = $this->getOne($user_address_id);

		return $data;
	}

	/**
	 * 设置为默认
	 *
	 * @param  array $de 查询条件
	 * @return array $update_flag 返回的设置的状态
	 * @access public
	 */
	public function editAddressInfo($de)
	{
		$user_address_ids = array_column($de, 'user_address_id');
		
		$update_flag = $this->editAddress($user_address_ids, array('user_address_default' => '0'));

		return $update_flag;
	}

	/**
	 * 读数量
	 *
	 * @param  array $cond_row 查询条件
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getCount($cond_row = array())
	{
		return $this->getNum($cond_row);
	}
    
    /**
     * 获取用的默认地址
     * @param type $user_id
     * @return type
     */
    public function getDefaultAddress($user_id){
        $addr_info = $this->getByWhere(array('user_id'=>$user_id,'user_address_default'=>self::DEFAULT_ADDRESS));
        if(is_array($addr_info) && $addr_info){
            return array_shift($addr_info);
        }else{
            return array();
        }
    }
}

?>