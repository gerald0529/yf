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
class Database_Maintain extends Yf_Model
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
		$dir=substr(dirname(__FILE__), 0, -9);
		$handle = opendir($dir);
		$tables=array();
		while($filename= readdir($handle)){
			if($filename!='.'&&$filename!='..'&&is_dir($dir.'/'.$filename))
			{
				$shandle = opendir($dir.'/'.$filename);
				while($sfilename= readdir($shandle))
				{
					if($sfilename!='.'&&$sfilename!='..'&&is_file($dir.'/'.$filename.'/'.$sfilename)&&substr($sfilename,0,-9)!='Model.php')
					{
						if(file_exists($dir.'/'.$filename.'/'.substr($sfilename,0,-4).'Model.php'))
						{
							$tmp=preg_split("/(?=[A-Z])/",$sfilename);
							$tmpname='';
							foreach($tmp as $ke=>$va)
							{
								if($va)
								{
									$tmpname.='_'.$va;
								}
							}
							$tables[]=substr('yf_'.strtolower($filename).strtolower($tmpname),0,-4);
						}
					}
				}
			}
		}
		$data['res']=$res;
		$data['tables']=$tables;
		return $data;
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