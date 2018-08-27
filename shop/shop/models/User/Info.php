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
class User_Info extends Yf_Model
{
	public $_cacheKeyPrefix  = 'c|user_info|';
	public $_cacheName       = 'user';
	public $_tableName       = 'user_info';
	public $_tablePrimaryKey = 'user_id';

	/**
	 * @param string $user User Object
	 * @var   string $db_id 指定需要连接的数据库Id
	 * @return void
	 */
	public function __construct(&$db_id = 'shop', &$user = null)
	{
		$this->_tableName = TABEL_PREFIX . $this->_tableName;
		$this->_cacheFlag = CHE;
		parent::__construct($db_id, $user);
	}

	/**
	 * 根据主键值，从数据库读取数据
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getInfo($user_id = null, $sort_key_row = null)
	{
		$rows = array();
		$rows = $this->get($user_id, $sort_key_row);

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
	public function addInfo($field_row, $return_insert_id = false)
	{
		$add_flag = $this->add($field_row, $return_insert_id);

		//$this->removeKey($user_id);
		return $add_flag;
	}

	/**
	 * 根据主键更新表内容
	 * @param mix $user_id 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editInfo($user_id = null, $field_row)
	{
		$update_flag = $this->edit($user_id, $field_row);

		return $update_flag;
	}

	/**
	 * 更新单个字段
	 * @param mix $user_id
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editInfoSingleField($user_id, $field_name, $field_value_new, $field_value_old)
	{
		$update_flag = $this->editSingleField($user_id, $field_name, $field_value_new, $field_value_old);

		return $update_flag;
	}

	/**
	 * 删除操作
	 * @param int $user_id
	 * @return bool $del_flag 是否成功
	 * @access public
	 */
	public function removeInfo($user_id)
	{
		$del_flag = $this->remove($user_id);

		//$this->removeKey($user_id);
		return $del_flag;
	}
    
    
    /**
     *  获取白条状态，从paycenter获取
     */
    public function getBtInfo(){
        
        $key = Yf_Registry::get('paycenter_api_key');
        $url = Yf_Registry::get('paycenter_api_url');
        $app_id = Yf_Registry::get('paycenter_app_id');

        $formvars = array();
        $formvars['app_id'] = $app_id;
        $formvars['user_id'] = Perm::$userId;
        $formvars['resource'] = 1; //获取user_resource表信息，白条额度
        $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User_Info&met=getUserInfo&typ=json', $url), $formvars);
        if($rs['status'] == 200 && isset($rs['data']['user_bt_status'])){
            if(isset($rs['data']['baitiao_is_open']) && $rs['data']['baitiao_is_open'] == 1){
                if($rs['data']['user_bt_status'] == 2 && $rs['data']['user_credit_limit'] <= 0){
                    $rs['data']['user_bt_status'] = 1;  //如果没有设置额度，就改为未激活状态
                }
                if($this->user['info']['user_bt_status'] != $rs['data']['user_bt_status']){
                    //更新user_info表的白条状态
                    $user_info_model = new User_InfoModel();
                    $user_id = Perm::$userId;
                    $user_info_model->editInfo($user_id,array('user_bt_status'=>$rs['data']['user_bt_status']));
                }
            }
            return $rs['data'];
        }else{
            return array();
        }
    }
}

?>