<?php

class Yf_Utils_DBManage
{
	var $db; // 数据库连接
	var $msg;

	static $loop = 0;

	/**
	 * 初始化
	 *
	 * @param string $db
	 */
	function __construct($db)
	{
		$this->db = $db;
		set_time_limit(0);//无时间限制
	}

	/**
	 * 将sql导入到数据库（普通导入）
	 *
	 * @param string $sqlfile
	 * @return boolean
	 */
	public function import($sqlfile, $db_prefix='yf_', $db_prefix_base='yf_', $echo_flag=true)
	{
		// sql文件包含的sql语句数组
		$sqls = array();
		$f    = fopen($sqlfile, "rb");

		// 创建表缓冲变量
		$create_table = '';
		if(self::$loop == 0){
			$scr = "
<script>
			function install_bottom()
{
var now = new Date();
var div = document.getElementById('installed'); 
div.scrollTop = div.scrollHeight;
}
</script>
";
			echo $scr."<ol id='installed' name='installed' style='height: 600px;overflow-y: auto;'>";
		}
		while (!feof($f))
		{
			// 读取每一行sql
			$line = fgets($f);

			if (substr($line, 0, 2) == '/*' || substr($line, 0, 2) == '--' || $line == '')
			{
				continue;
			}

			$create_table .= $line;
			if (substr(trim($line), -1, 1) == ';')
			{
				$create_table = str_replace($db_prefix_base, $db_prefix, $create_table); 
  
				//执行sql语句创建表
				$flag = $this->exec($create_table);

				
				 

				if ($echo_flag)
				{
					echo str_repeat(" ", 4096);  //以确保到达output_buffering值
					
					$pattern = '/CREATE TABLE.*`(.*)`/i';
				  preg_match($pattern, $create_table, $matches);  
				  $show_table_created = $matches[1];  

					if($show_table_created){
						echo "<li class='line'><span class='yes'><i class='iconfont'></i></span>".
								__("创建数据库")." ".$show_table_created." ".__("成功")."</li>";
					}else{
					//	echo "<li class='line'><span class='yes'><i class='iconfont'></i></span>".
					//			__("初始化数据")." ".$show_table_created." ".__("成功")."</li>";
					}
					echo "<script>install_bottom();</script>";
					ob_flush();
					flush();

				}

				if (!$flag)
				{
					return false;
				}

				// 清空当前，准备下一个表的创建
				unset($create_table);
				$create_table = '';
			}

			unset($line);
		}
	 
 	  self::$loop++;
		fclose($f);
		
		return true;
	}

	//插入单条sql语句
	private function exec($sql)
	{
		if (false === $this->db->exec(trim($sql)))
		{
			$msg = array();
			$error_info = array();
			
			$msg['code'] = $this->db->getErrorCode($error_info);
			$msg['msg']  = $error_info[2];
			$msg['sql']  = $sql;
			$this->msg  = $msg;
			die($msg['msg']." sql:". $msg['msg']);
			return false;
		}

		unset($sql);

		return true;
	}
}
