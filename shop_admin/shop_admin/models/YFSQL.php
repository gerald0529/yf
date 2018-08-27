<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}


/** 
 *		
 *		  if($voucher_end_date)
		 {
			 $sql .= " AND voucher_end_date >= :voucher_end_date  ";
			 $bind_value[':voucher_end_date'] = $voucher_end_date;
		 }
		 $sql .= "  ORDER BY ".$order_by." ".$limit;
 
		 $db = new YFSQL();
		 $list = $db->find($sql,$bind_value);
		 

 */
class YFSQL
{
	 
	protected $sql;
	protected $pdo;
	protected $query;
	public function __construct()
	{
		$this->_tableName = TABEL_PREFIX . $this->_tableName;
		$config = Yf_Registry::get('db_cfg');
		$arr = $config['db_cfg_rows'][$config['db_write_read']];
		foreach ($arr as $key => $value) {
			if($key != 'root_rights'){
				$db_id = $key;
				goto NE;
			} 
		}
		NE: 
		$this->sql  = new Yf_Sql($db_id);  
		$this->pdo = $this->sql->getDb()->getDbHandle();
		$this->sql->noLimit = true; 
	}

	/**
	 * 查寻多条记录
	 * @param   [type]       $sql
	 * @param   array        $value
	 *  
	 */
	public function find($sql,$value = [], $fetch_all = true){ 
		$db = $this->pdo;
		$query = $db->prepare($sql ,[PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]); 
 		try 
		{     
 	        if($value){
 	        	foreach ($value as $key => $value) {
 	        		$query->bindValue($key,$value);
 	        	}
 	        } 
 	        $r = $query->execute(); 
  			if($fetch_all === true){
				$rs = $query->fetchAll(PDO::FETCH_ASSOC);  
			}else{
				$rs = $query->fetch(PDO::FETCH_ASSOC);
			}
			$this->query = $query;  
			return $rs;  
		} 
		catch(PDOExecption  $e) 
		{  
			file_put_contents(__DIR__.'/../data/logs/errorSQL.txt',$e->getMessage(),FILE_APPEND);
			return;
		}
		
	}
	 
	/**
	 * 查寻一条数据
	 */
	public function find_one($sql,$value = []){
		if(strrpos(strtolower($sql),'limit')===false){
			$sql = $sql." LIMIT 1";
		}
		return $this->find($sql,$value,false); 
	}
	/**
	 * 更新数组
	 */
	public function save($sql,$value = []){  
		$query = $this->pdo->prepare($sql);  
        $r = $query->execute($value); 
        $error = $query->errorInfo()[2]; 
        if($error){
        	print_r($error);
        }
		$this->query = $query;  
		return $query->lastInsertId();  
		 
	}

	/**
	 * 分页
	 Array
	(
	    [data] => Array
	        (
	            [0] => Array
	                (
	                    
	                ) 

	        )

	    [nums] => 265 总数
	    [page] => 1 当前页
	    [pager] => 分页条
	)

	 */
	public function pager($arr = []){
		$sql_count = $arr['sql_count'];
		$sql = $arr['sql'];
		$value = $arr['value'];
		$size = (int)$arr['size']?:10; 
 		$Yf_Page           = new Yf_Page();
   		$Yf_Page->listRows = $size;
  		$rows              = $Yf_Page->listRows;
   		$offset            = request_int('firstRow', 0);
   		$page              = ceil_r($offset / $rows);  
   		if(!$sql_count){
   			$sql_count = str_replace("*","count(*) num",$sql); 
   		} 
   		$num_row = $this->find_one($sql_count,$value);
   		$num = $num_row['num']?:0;//总数 
   		$sql = $sql."  LIMIT $offset ,$size";  
   		$data = $this->find($sql,$value);  
  		if ($num > 0)
   		{
	  		$Yf_Page->totalRows = $num;
	  		$list['data'] = $data;
	  		$list['nums'] = $num;
	  		$list['page'] = $page;
	  		$list['pager'] = $Yf_Page->prompt();
	  		$list['pages'] = $Yf_Page->totalPages; 
	  		//重置数据显示。
	  		if($arr['reset']){
	  			$new['items'] = $list['data'];
			 	$new['page'] = $list['page'];
			 	$new['records'] = $list['nums'];
			 	$new['total'] = $list['pages'];
			 	$new['totalsize'] = $list['pages'];
			 	return $new;
	  		}
	   		
	   		return $list;
  		} 
  		return;
	}





	 
}

 