<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class ConnectCtl extends Yf_AppController
{
	private $rest = null;

	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		//include $this->view->getView();
	}


	//将配置信息写入临时文件中
	public function setConnectConfig()
	{
		$key = Yf_Registry::get('ucenter_api_key');


		if (!check_url_with_encrypt($key, $_POST))
		{
			$this->data->setError('协议数据有误');

			$d = $this->data->getDataRows();

			$protocol_data = Yf_Data::encodeProtocolData($d);
			echo $protocol_data;

			exit();
		}


		unset($_REQUEST['ctl']);
		unset($_REQUEST['app_id']);
		unset($_REQUEST['met']);
		unset($_REQUEST['typ']);
		unset($_REQUEST['token']);

		foreach ($_REQUEST as $key => $value)
		{
			$k             = strstr($key, '_', true);
			$v             = substr($key, strpos($key, "_") + 1);
			$array[$k][$v] = $value;
		}

		$str = '<?php
/**
 * Created by PhpStorm.
 * User: xinze
 * Date: 16/3/22
 * Time: 下午5:39
*/
$connect_rows=';

		$str .= var_export($array, true);
		$str .= ';

return $connect_rows;

?>';
		
		$flag = file_put_contents('./ucenter/configs/connect.ini.php', $str);
		
		$data = array();

		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, $array, $msg, $status);
		
	}


	//将配置信息写入临时文件中
	public function setSmsConfig()
	{
		$key = Yf_Registry::get('ucenter_api_key');


		if (!check_url_with_encrypt($key, $_POST))
		{
			$this->data->setError('协议数据有误');

			$d = $this->data->getDataRows();

			$protocol_data = Yf_Data::encodeProtocolData($d);
			echo $protocol_data;

			exit();
		}

		foreach ($_REQUEST as $key => $value)
		{
			$array[$value['config_key']] = $value['config_data'];
		}

		$str = '<?php

$sms_config = array();

$sms_config[\'sms_account\'] = \''.$array['sms_account'].'\';
$sms_config[\'sms_pass\'] = \''.$array['sms_pass'].'\';

Yf_Registry::set(\'sms_config\', $sms_config);

return $sms_config;
?>';

		$flag = file_put_contents('./ucenter/configs/sms.ini.php', $str);

		$data = array();

		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, $array, $msg, $status);

	}
	public function urationConfig()
	{
		$key = Yf_Registry::get('ucenter_api_key');


		if (!check_url_with_encrypt($key, $_POST))
		{
			$this->data->setError('协议数据有误');

			$d = $this->data->getDataRows();

			$protocol_data = Yf_Data::encodeProtocolData($d);
			echo $protocol_data;

			exit();
		}
		foreach ($_REQUEST as $key => $value)
		{
			$array = $value['ucenter_api_url'];
		}
		$str = '<?php

$uration_Config = \''.$array['ucenter_api_url'].'\';


Yf_Registry::set(\'uration_Config\', $uration_Config);

return $uration_Config;
?>';
		
		$flag = file_put_contents('./ucenter/configs/uration.ini.php', $str);

		$data = array();

		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, $array, $msg, $status);
	}
	/**
	*获取connect配置信息
	**/
	public function getConnectConfig(){
		$connect_config = Yf_Registry::get('connect_rows');
		if ($connect_config)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, $connect_config, $msg, $status);
	}
}

?>
