<?php if (!defined('ROOT_PATH')) exit('No Permission');
class Api_Database_BackupCtl extends Api_Controller
{
	public $databaseBackupModel=null;
	public $backupDir=null;
	public $subDir=null;
	private $db=null;
	
	private $currentSize=0;
	private $currentData='';
	private $pages=1;
	
	private $tmp_table=null;
	private $foreign=array();
    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
        $this->databaseBackupModel = new Database_Backup();
        $this->backupDir=substr(dirname(__FILE__), 0, -24).'data/backup';
        $this->db=Yf_Db::get('shop');

    }

	public function manage()
    {
		$this->data->addBody(-140, $data, $msg, $status);
    }
	public function getBackupList()
	{
		@$open=opendir($this->backupDir);
		$num=0;
		
		while($exist=readdir($open))
		{
			
			if(is_dir($this->backupDir.'/'.$exist)&&substr($exist,0,3)=='db_')
			{
				$totalsize=0;
				$file=opendir($this->backupDir.'/'.$exist);
				while($filename=readdir($file))
				{
					if(is_file($this->backupDir.'/'.$exist.'/'.$filename)&&substr($filename,0,3)=='db_')
					{
						$totalsize+=(int)@filesize($this->backupDir.'/'.$exist.'/'.$filename);
						$uptime=filectime($this->backupDir.'/'.$exist.'/'.$filename);
					}
				}
				@closedir($this->backupDir.'/'.$exist);
				$items[$num]['file_name']=$exist;
				$items[$num]['size']=$this->setupSize($totalsize);
				$items[$num]['time']=date('Y-m-d H:i:s',$uptime);
				$items[$num]['timestamp']=$uptime;
				if($totalsize==0)
					unset($items[$num]);
				$num++;
			}
		}
		@closedir($this->backupDir);
		if(!empty($items))
		{
			foreach($items as $ke=>$va)
			{
				$sort[$ke]=$items[$ke]['timestamp'];
			}
			array_multisort($sort,SORT_DESC,$items);
			$data['items']=array_values($items);
			$data['records']=count($items);
		}
		else
		{
			$data['items']=array();
			$data['records']=0;
		}
		$this->data->addBody(-140, $data);
	}
	public function restore()
	{
		if($_REQUEST['action']!='restore'||substr($_REQUEST['file'],0,3)!='db_')
		{
			$msg='非法命令!';
			$status=250;
		}
		$restoreDir=$_REQUEST['file'];
		if(!is_dir($this->backupDir.'/'.$restoreDir)||!is_file($this->backupDir.'/'.$restoreDir.'/'.$restoreDir.'_1.php'))
		{
			$msg='备份文件不存在！';
			$status=250;
		}
		$num=1;
		@set_time_limit(0);
		$this->db->exec("SET FOREIGN_KEY_CHECKS = 0;");
		while(file_exists($restoreFile=$this->backupDir.'/'.$restoreDir.'/'.$restoreDir.'_'.$num.'.php'))
		{
			include($restoreFile);
			$num++;
		}
		$data=array();
		$msg=empty($msg)?'':$msg;
		$status=empty($status)?200:250;
		$this->db->exec("SET FOREIGN_KEY_CHECKS = 1;");
		$this->data->addBody(-140, $data, $msg, $status);
	}
	public function delete()
	{
		if($_REQUEST['action']!='delete'||substr($_REQUEST['file'],0,3)!='db_')
		{
			$msg='非法命令!';
			$status=250;
		}
		$deleteDir=$_REQUEST['file'];
		if(!is_dir($this->backupDir.'/'.$deleteDir)||!is_file($this->backupDir.'/'.$deleteDir.'/'.$deleteDir.'_1.php'))
		{
			$msg='备份文件不存在！';
			$status=250;
		}
		$open=opendir($this->backupDir.'/'.$deleteDir);
		while($exist = readdir($open)) 
		{
			if (is_file($this->backupDir.'/'.$deleteDir."/".$exist)) 
			{
				unlink($this->backupDir.'/'.$deleteDir."/".$exist);
			}
		}	
		@closedir($open);
		@rmdir($this->backupDir.'/'.$deleteDir);
		$data=array();
		$msg=empty($msg)?'':$msg;
		$status=empty($status)?200:250;
		$this->data->addBody(-140, $data, $msg, $status);
	}
	public function backup()
	{
		if($_REQUEST['action']!='backup')
		{
			$msg='非法命令!';
			$status=250;
		}
		
		if(!is_dir($this->backupDir))
		{
			$msg='备份目录不存在！';
			$status=250;
		}
		else if(!is_writable($this->backupDir))
		{
			$msg='备份目录不可写！';
			$status=250;
		}
		else
		{
			@set_time_limit(0);
			$times=time();
			$this->subDir = '/db_'.substr(md5($_SERVER['SERVER_ADDR'].$_SERVER['HTTP_USER_AGENT'].substr($times, strlen($times)-4, 4)), 8, 6);
			@mkdir($this->backupDir.$this->subDir, 0777);
            if($_REQUEST['data']){
                $data=json_decode($_REQUEST['data']);
                foreach($data->tables as $k =>$name){
                    $rows[$k]['Name'] = $name->name;
                }
            }else{
                $rows=$this->databaseBackupModel->getTableList();
            }
			foreach($rows as $ke=>$va)
			{
				$this->dumpTable($va['Name'],$ke);
			}
			$data_end=" ?".">";
			if(!empty($this->foreign))
			{
				foreach($this->foreign as $ke=>$va)
				{
					$this->currentdata .= '$this->db->exec("'.$this->foreign[$ke].'");'."\n";
				}
			}
			$this->currentdata .= $data_end;
			$this->dumpFileCreate($this->currentdata,"w");
		}
		$data=array();
		$msg=empty($msg)?'':$msg;
		$status=empty($status)?200:250;
		$this->data->addBody(-140, $data, $msg, $status);
	}
	public function setupSize($fileSize)  
	{          
		$size = sprintf("%u",$fileSize);          
		if($size==0)  
		{
			return( "0 Bytes");          
		}          
		$sizename=array("Bytes","KB","MB","GB","TB");        
		return  round($size/pow(1024,($i= floor(log($size,1024)))),2).$sizename[$i];  
	}  

	public function create_table($table,$sql)
	{
		$this->tmp_table="yf_tmp_".mt_rand();
		$this->db->exec("CREATE TABLE `$this->tmp_table` $sql");
		$this->db->exec("drop table if exists `$table`");
	}
	public function insert_table($data)
	{
		$this->db->getRow("insert ignore into `$this->tmp_table` values $data");
	}
	public function clear_table($table)
	{
		$this->db->exec("alter table `$this->tmp_table` rename `$table`");
	}
	public function dumpTable($table,$table_id)
	{
		$this->db->exec("set sql_quote_show_create = 1");
		$row=$this->db->getRow("show create table `$table`");
		//去外键
		if (preg_match('@CONSTRAINT|FOREIGN[\s]+KEY@', $row['Create Table'])) 
		{
			$sql_lines = explode("\n", $row['Create Table']);
            $sql_count = count($sql_lines);

            // lets find first line with constraints
            for ($i = 0; $i < $sql_count; $i++) {
                if (preg_match('@^[\s]*(CONSTRAINT|FOREIGN[\s]+KEY)@', $sql_lines[$i])) {
                    break;
                }
            }

            // If we really found a constraint
            if ($i != $sql_count) {

                // remove , from the end of create statement
                $sql_lines[$i - 1] = preg_replace('@,$@', '', $sql_lines[$i - 1]);

                $this->foreign[$table] = 'ALTER TABLE ' . $table . "\n";

                $first = TRUE;
                for ($j = $i; $j < $sql_count; $j++) {
                    if (preg_match('@CONSTRAINT|FOREIGN[\s]+KEY@', $sql_lines[$j])) {
                        if (!$first) {
                            $this->foreign[$table] .= "\n";
                        }
                        if (strpos($sql_lines[$j], 'CONSTRAINT') === FALSE) {
                            $tmp_str = preg_replace('/(FOREIGN[\s]+KEY)/', 'ADD \1', $sql_lines[$j]);
                            $this->foreign[$table] .= $tmp_str;
                        } else {
                            $tmp_str = preg_replace('/(CONSTRAINT)/', 'ADD \1', $sql_lines[$j]);
                            $this->foreign[$table] .= $tmp_str;
                            // preg_match('/(CONSTRAINT)([\s])([\S]*)([\s])/', $sql_lines[$j], $matches);
                            // if (! $first) {
                                // $sql_drop_foreign_keys .= ', ';
                            // }
                            // $sql_drop_foreign_keys .= 'DROP FOREIGN KEY ' . $matches[3];
                        }
                        $first = FALSE;
                    } else {
                        break;
                    }
                }
                $this->foreign[$table] .= ';';

                $row['Create Table'] = implode("\n", array_slice($sql_lines, 0, $i)) . "\n" . implode("\n", array_slice($sql_lines, $j, $sql_count - 1));
                unset($sql_lines);
            }
        }
		$sql = str_replace("\n","\\n",str_replace("\"","\\\"",$row['Create Table']));
		$sql = preg_replace("/^(CREATE\s+TABLE\s+`$table`)/mis","",$sql);
		$sqlcreate = "\$this->create_table(\"$table\",\"$sql\");\r\n\r\n";
		$this->stackData($sqlcreate);
		$limitstrart = 0;
		$dumpedrows=$limitoffset=400;
		$sqldumped = "";
		while ($dumpedrows == $limitoffset) {
			$allRows=array_values($this->db->getAll("SELECT * FROM `$table` limit ".$limitstrart.",$limitoffset "));
			$fields = $this->db->getAll("show columns from `$table`;");
			$numoffields=count($fields);
			$dumpedrows = count($allRows);
			foreach($allRows as $sqlrow){
				$sqlrow=array_values($sqlrow);
				$sqldumped .= ($sqldumped ? ",\r\n" : "")."(";
				for ($i=0;$i<$numoffields;$i++) {
					if (!isset($sqlrow[$i]) or is_null($sqlrow[$i])) {
						$sqlrow[$i] = "NULL";
					} else {
						$sqlrow[$i] = '\''.$this->escape_string($sqlrow[$i]).'\'';
					}
				}
				$limitstrart++;
				$sqldumped .= implode(",",$sqlrow).")";
				$dumpedlength = strlen($sqldumped);

				if ($dumpedlength > 100000 || ($this->currentsize+$dumpedlength >= 2000*1000)) {
					$dumpstring = "\$this->insert_table(\"$sqldumped\");\r\n\r\n";
					$this->stackData($dumpstring);
					$sqldumped = "";
				}
			}
		}
		if ($sqldumped) {
			$dumpstring = "\$this->insert_table(\"\r\n$sqldumped\");\r\n\r\n";
			$this->stackData($dumpstring);
		}
		$dumpstring = "\$this->clear_table(\"$table\");\r\n\r\n";
		$this->stackData($dumpstring);
	}

	public function stackData($data) {
		$this->currentsize += strlen($data);
		$this->currentdata .= $data;
		if($this->currentsize >= 2000*1000){
			$this->currentsize = 0;
			$this->currentdata .= "\r\n?".">";
			$this->dumpFileCreate($this->currentdata, "w");
			$this->pages++;
		}
	}

	public function dumpFileCreate($data,$method='w') {
		$dumpfilename = "{$this->subDir}_{$this->pages}.php";
		$data = "<?\r\nif (!defined('ROOT_PATH')) exit('No Permission');\r\n".$data;
		$fp=fopen($this->backupDir.$this->subDir."/{$dumpfilename}","$method");
		flock($fp,2);
		fwrite($fp,$data);
		$this->currentdata = "";
	}

	public function escape_string($string) {
		$string = @mysql_escape_string($string);
		$string = str_replace('\\\'','\'\'',$string);
		$string = str_replace("\\\\","\\\\\\\\",$string);
		$string = str_replace('$','\$',$string);
		return $string;
	}
}
?>