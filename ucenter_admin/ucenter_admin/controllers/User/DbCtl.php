<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}



use Camcima\MySqlDiff\Differ;
use Camcima\MySqlDiff\Parser;

/**
 * @author
 */
class User_DbCtl extends AdminController
{
	public $dataUserModel = null;


	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function lastest()
	{
		$ctl = 'Base_AppVersion';
		$met = 'lastest';

		$data = $this->getUrl($ctl, $met);
		include $this->view->getView();

	}

	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function check()
	{
		echo "<pre>";
		$app_id = request_int('app_id');
		$url    = request_string('url');
		
		if ($structure_from = file_get_contents($url))
		{
			
			$glue = ';' . PHP_EOL . '/* */;' . PHP_EOL;
			
			$db_rows = array(
				'ucenter_root'=>array(
					array(
						'host' => '139.196.25.35',
						'port' => '3306',
						'user' => 'admin',
						'password' => 'admin%^&ere!@#12',
						'database' => 'information_schema',
						'charset' => 'UTF8'
					
					)
				),
				
				'103'=>array(
					array(
						'host' => '139.196.25.35',
						'port' => '3306',
						'user' => 'admin',
						'password' => 'admin%^&ere!@#12',
						'database' => 'information_schema',
						'charset' => 'UTF8'
					
					)
				),
				'104'=>array(
					array(
						'host' => '139.196.25.35',
						'port' => '3306',
						'user' => 'admin',
						'password' => 'admin%^&ere!@#12',
						'database' => 'information_schema',
						'charset' => 'UTF8'
					
					)
				),
				'105'=>array(
					array(
						'host' => '139.196.25.35',
						'port' => '3306',
						'user' => 'admin',
						'password' => 'admin%^&ere!@#12',
						'database' => 'information_schema',
						'charset' => 'UTF8'
					
					)
				),
				'102'=>array(
					array (
						'host' => '139.196.6.92',
						'port' => '3306',
						'user' => 'root',
						'password' => 'www.b2b-builder.cn',
						'database' => 'information_schema',
						'charset' => 'UTF8'
					)
				)
			);
			
			$db_like = '';
			
			switch ($app_id)
			{
				case 101:
				case 201:
					$db_like = 'erp_1';
					$db_id = 101;
					break;
				case 102:
				case 202:
					$db_like = 'shop_1';
					$db_id = 102;
					break;
				case 103:
				case 203:
					$db_like = 'paycenter_1';
					$db_id = 103;
					break;
				case 104:
				case 204:
					$db_like = 'ucenter_1';
					$db_id = 104;
					break;
				case 105:
				case 205:
					$db_like = 'paycenter_1';
					$db_id = 105;
					break;
			}
			
			$db_index = 1;
			
			foreach ($db_rows[$db_id] as $cfg)
			{
				
				echo "#修改主机 - " . $cfg['host'];
				echo PHP_EOL;
				echo PHP_EOL;
				
				$db_index ++;
				$db_to = new Yf_Db_Pdo($db_index, $cfg);
				$db_to->exec(' USE `' . $cfg['database'] . '`');
				
				//获取所有数据库
				$sql = 'SELECT * FROM `SCHEMATA` where `SCHEMA_NAME` LIKE "' . $db_like . '%" limit 0,1000';
				$data_rows = $db_to->getAll($sql);
				
				//循环对比，输出差异。

				
				foreach ($data_rows as $item)
				{
					$db_to->exec(' USE `' . $item['SCHEMA_NAME'] . '`');
					
					$structure_rows = $db_to->getStructure();
					$structure_to   = implode($glue, $structure_rows) . $glue;
					
					$parser = new Parser();
					
					$from_db = $parser->parseDatabase($structure_from);
					$to_db   = $parser->parseDatabase($structure_to);
					
					$differ       = new Differ();
					$databaseDiff = $differ->diffDatabases($to_db, $from_db);
					
					
					if (!$databaseDiff->isEmptyDifferences() && $result=$differ->generateMigrationScript($databaseDiff))
					{
						
						//删除掉DROP操作，降低数据风险？
						echo 'USE `' . $item['SCHEMA_NAME'] . '`;';
						echo PHP_EOL;
						echo $result;
						
						echo PHP_EOL;
						echo PHP_EOL;
						//return $flag;
					}
				}
				
			}
			
		}
		else
		{
			echo _('无法获取标准库信息 - ') . $url;
		}
	}
}

?>