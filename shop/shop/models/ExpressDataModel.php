<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class ExpressDataModel extends Yf_Model
{

	public $_cacheKeyPrefix  = 'c|ExpressData|';
	public $_cacheName       = 'express_data';
	public $_tableName       = 'express_data';
	public $_tablePrimaryKey = 'id';
    public $jsonKey    = ['data'];

	/**
	 * @param string $user User Object
	 * @var   string $db_id 指定需要连接的数据库Id
	 * @return void
	 */
	public function __construct(&$db_id = 'shop', &$user = null)
	{
		$this->_tableName = TABEL_PREFIX . $this->_tableName;
		parent::__construct($db_id, $user);
	}

	/**
	 * @param  int $config_key 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getExpress($express_id = null, $sort_key_row = null)
	{
		$rows = array();
		$rows = $this->get($express_id, $sort_key_row);
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
	public function addExpress($field_row)
	{
		$add_flag = $this->add($field_row);

		//$this->removeKey($config_key);
		return $add_flag;
	}

	/**
	 * 根据主键更新表内容
	 * @param mix $config_key 主键
	 * @param array $field_row key=>value数组
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editExpress($express_id = null, $field_row = array())
	{
		$update_flag = $this->edit($express_id, $field_row);

		return $update_flag;
	}

	/**
	 * 更新单个字段
	 * @param mix $config_key
	 * @param array $field_name
	 * @param array $field_value_new
	 * @param array $field_value_old
	 * @return bool $update_flag 是否成功
	 * @access public
	 */
	public function editExpressSingleField($express_id, $company)
	{
		$update_flag = $this->editSingleField($express_id, $company);

		return $update_flag;
	}


    /**
     * 存在则修改
     * @param type $rows
     * @return boolean
     * 请不掉的缓存。。。。
     */
    public function addOrUpdate($rows = array()){
        if(!is_array($rows) || !$rows){
            return false;
        }
        $key_str = '';
        $value_str = '';
        $data_row = [];
        foreach ($rows as $key => $value){
            $value = in_array($key, $this->jsonKey) ? encode_json($value) : htmlspecialchars($value);
            $key_str .= $key.',';
            $value_str .=  mres($value).',';
            $data_row[] = $key . "=" . $key . ' + ' . $value;
        }
        
        $fields = trim($key_str,',');
        $values= trim($value_str,',');
        $key_value = implode(',', $data_row);
        $sql = "INSERT INTO ".$this->_tableName."(".$fields.") VALUES(".$values.") ON DUPLICATE KEY UPDATE ".$key_value;
        $flag = $this->sql->exec($sql);
        return $flag;
    }

    /**
     * 存在则修改
     * @param type $rows
     * @param type $data
     * @return boolean
     */
    public function selectToaddOredit($rows = array(),$data=array()){
        if(!$rows || !$data){
            return false;
        }
        $info = $this->getByWhere($rows);

        if(!$info){
            //add
            $data['create_time'] = date('Y-m-d H:i:s');
            $result = $this->add($data);
        } else {
            //update
            $info = array_shift($info);
            $result = $this->edit($info['id'],$data);
        }
        
        return $result;
    }
    

    /**
     * 根据运单号和物流编号获取轨迹信息
     * @param type $logistic_code
     * @param type $shipper_code
     */
    public function getTracesInfo($logistic_code,$shipper_code){
        $where = array('logistic_code'=>$logistic_code,'shipper_code'=>$shipper_code);
        $result = $this->getOneByWhere($where);
        return $result;
    }
    
    
    /**
     * 根据运单号和物流编号获取轨迹信息
     * @param type $logistic_code
     * @param type $shipper_code
     */
    public function getTracesHtml($logistic_code,$shipper_code){
        $result = $this->getTracesInfo($logistic_code,$shipper_code);
        $content_div = '';
        if($result && isset($result['Data']['Success']) && $result['Data']['Success'] && $result['Data']['Traces']){
            $content_div = sprintf('<div class="pc"><div class="p-tit">运单号：%s (%s)</div><div class="logistics-cont"><ul>',$result['Data']['LogisticCode'],$result['Data']['ShipperCode']);
            foreach ($result['Data']['Traces'] as $key => $val) {
                $time    = $val['AcceptTime'];
                $context = $val['AcceptStation'];
                $deliver_info[] = [ 'time'=>$time, 'context'=>$context ];
                $class_name = '';

                if ($key == 0){
                    $class_name = 'first';
                    $content_div = sprintf('%s<li class=%s><i class="node-icon bbc_bg"></i><a> %s </a><div class="ftx-13"> %s </div></li>',$content_div, $class_name,$context,$time);
                } else {
                    $content_div = sprintf('%s<li class=%s><i class="node-icon bbc_bg"></i><a> %s </a><div class="ftx-13"> %s </div></li>',$content_div, $class_name,$context,$time);
                }
            }

            $content_div = sprintf('%s</ul></div></div><div class="p-arrow p-arrow-left" style="top: 242px;"></div>',$content_div);
        }
        return $content_div;
    }
}

?>