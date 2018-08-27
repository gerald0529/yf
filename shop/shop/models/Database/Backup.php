<?php if (!defined('ROOT_PATH')) exit('No Permission');
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
class Database_Backup extends Yf_Model
{

    /**
     * @param string $user  User Object
     * @var   string $db_id 指定需要连接的数据库Id
     * @return void
     */
	private $db_name=null;
    public function __construct(&$db_id='shop', &$user=null)
    {
        parent::__construct($db_id, $user);
		
    }
	public function getTableList()
	{
		$res=$this->sql->getAll("SHOW TABLE STATUS FROM ".$this->getDbName());
		return $res;
	}
	public function getDbName()
	{
		if($this->db_name)
			return $this->db_name;
		else
		{
			$res=$this->sql->getRow("select database() as db_name;");
			$this->db_name=$res['db_name'];
			return $this->db_name;
		}
	}
	public function tableQuery($sql)
	{
		$ret=$this->sql->getAll($sql);
		fb($ret);
		return $ret;
	}
}
?>