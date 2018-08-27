<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Api_ConfigCtl extends Api_Controller
{
	/**
	 * Constructor
	 * @param string $args 参数
	 * @return void
	 */
	public function __call($method, $args)
	{
		$config_type = $this->met;

		$config_type_row = request_row('config_type');

		if (!$config_type_row)
		{
			$config_type_row = array($config_type);
		}

		$Web_ConfigModel = new Web_ConfigModel();

		$data = array();
		foreach ($config_type_row as $config_type)
		{
			$data_tmp = $Web_ConfigModel->getByWhere(array('config_type' => $config_type));
			$data     = $data + $data_tmp;

			//系统环境上传变量
			if ('upload' == $config_type)
			{
				$sys_max_upload_file_size         = min(Yf_Utils_File::getByteSize(ini_get('upload_max_filesize')), Yf_Utils_File::getByteSize(ini_get('memory_limit')), Yf_Utils_File::getByteSize(ini_get('post_max_size'))) / 1024;
				$data['sys_max_upload_file_size'] = $sys_max_upload_file_size;
			}

			//站点设置
			if ('site' == $config_type)
			{
				//系统可选语言包
				$file_row = scandir(LAN_PATH);

				$language_row = array();

				foreach ($file_row as $file)
				{
					if ('.' != $file && '..' != $file && is_dir(LAN_PATH . '/' . $file))
					{
						$language_row[] = array(
							'id' => $file,
							'name' => $file
						);
					}
				}

				$data['language_row'] = $language_row;

				//系统可选风格
				$data['theme_row'] = array();
				$theme_dir         = APP_PATH . '/views/';
				$file_row          = scandir($theme_dir);

				$theme_row = array();

				foreach ($file_row as $file)
				{
					if ('.' != $file && '..' != $file && is_dir($theme_dir . '/' . $file))
					{
						$theme_row[] = array(
							'id' => $file,
							'name' => $file
						);
					}
				}

				$data['theme_row'] = $theme_row;
			}

			//插件设置
			if ('plugin' == $config_type)
			{
				$plugin_rows = array();
				//用户自定义
				$plugin_user_dir = APP_PATH . '/controllers/Plugin/';

				$file_row = scandir($plugin_user_dir);

				foreach ($file_row as $file)
				{
					if ('.' != $file && '..' != $file && is_file($plugin_user_dir . '/' . $file))
					{
						$ext_row     = pathinfo($file);
						$plugin_name = 'Plugin_' . $ext_row['filename'];

						if ('Plugin_Perm' == $plugin_name)
						{
							continue;
						}
						try
						{
							if (class_exists($plugin_name))
							{
								$plugin_desc   = $plugin_name::desc();
								$plugin_rows[] = array(
									'plugin_id' => $plugin_name,
									'plugin_name' => $plugin_name,
									'plugin_desc' => $plugin_desc
								);
							}
						}
						catch (Exception $e)
						{

						}
					}
				}

				$data['plugin_rows'] = $plugin_rows;
			}


			//插件设置
			if ('sphinx' == $config_type)
			{
				if (extension_loaded("sphinx"))
				{
					$data['sphinx_ext'] = 1;
				}
				else
				{
					$data['sphinx_ext'] = 0;
				}

				if (extension_loaded("scws"))
				{
					$data['scws_ext'] = 1;
				}
				else
				{
					$data['scws_ext'] = 0;
				}
			}
			//
		}


		$this->data->addBody(-140, $data);
	}

	/**
	 * 验证API是否正确
	 *
	 * @access public
	 */
	public function checkApi()
	{
		$this->data->addBody(-140, array());
	}

	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function edit()
	{
		$Web_ConfigModel = new Web_ConfigModel();

		$config_type_row = request_row('config_type');
		foreach ($config_type_row as $key => $config_type)
		{
			$config_value_row = request_row($config_type);

			$config_rows = $Web_ConfigModel->getByWhere(array('config_type' => $config_type));

			foreach ($config_rows as $config_key => $config_row)
			{
				$edit_row = array();

				if (isset($config_value_row[$config_key]))
				{
					if ('json' == $config_row['config_datatype'])
					{
						$edit_row['config_value'] = json_encode($config_value_row[$config_key]);
					}
					else
					{
						$edit_row['config_value'] = $config_value_row[$config_key];
					}
				}
				else
				{
					if ('number' == $config_row['config_datatype'])
					{
						if ('theme_id' != $config_key)
						{
							//$edit_row['config_value'] = 0;
						}
					}
                    
				}

				if ($edit_row)
				{
					$Web_ConfigModel->editConfig($config_key, $edit_row);
				}
			}


			if ('sms' == $config_type)
			{

				$str = '<?php

$sms_config = array();

$sms_config[\'sms_account\'] = \''.$config_value_row['sms_account'].'\';
$sms_config[\'sms_pass\'] = \''.$config_value_row['sms_pass'].'\';

Yf_Registry::set(\'sms_config\', $sms_config);

return $sms_config;
?>';

				$file = INI_PATH . '/sms_' . Yf_Registry::get('server_id') . '.ini.php';

				$flag = file_put_contents($file, $str);
			}
            if ('connect' == $config_type){
                $flag = $this->setConnect($key,$config_value_row);
            }
            
		}

		//其它全局变量
		$config_rows = array();
		//$file        = INI_PATH . '/global.ini.php';
		$file = INI_PATH . '/global_' . Yf_Registry::get('server_id') . '.ini.php';

		$temp_rows   = $Web_ConfigModel->getConfig(array(
													   'site_name',
													   'time_zone_id',
													   'language_id',
													   'theme_id',
													   'site_status',
													   'closed_reason'
												   ));

		foreach ($temp_rows as $config_row)
		{
			$config_rows[$config_row['config_key']] = $config_row['config_value'];
		}

		$rs = Yf_Utils_File::generatePhpFile($file, $config_rows);

		
		$this->data->addBody(-140, array());
	}

    //互联登录配置
    private function setConnect($key,$value){
        $file = INI_PATH . '/connect_' . Yf_Registry::get('server_id') . '.ini.php';
        $connect_rows = include $file;
        $connect_rows = is_array($connect_rows) ? $connect_rows : [];
        foreach ($value as $k => $v){
            $connect_rows[$key][$k] = $v;
        }
        $str = '<?php'."\n";
        $str .= '$connect_rows = ';
        $str .= var_export($connect_rows, true).";\n";
        $str .= 'return $connect_rows;';
        $flag = file_put_contents($file, $str);
        return $flag;
    }
    
    //互联登录配置
    public function connect(){
        $file = INI_PATH . '/connect_' . Yf_Registry::get('server_id') . '.ini.php';
        if(!is_file($file)){
            return $this->data->addBody(-140, [Yf_Registry::get('server_id')],'文件不存在',250);
        }
        $connect_rows = include $file;
        $data = is_array($connect_rows) ? $connect_rows : [];
        $this->data->addBody(-140, $data);
    }
    /**
	 * setStandard1
	 *
	 * @access public
	 */
	public function editUcenterApi()
	{
		//其它全局变量
		$config_rows = array();
		$file        = INI_PATH . '/ucenter_api.ini.php';


		$ucenter_api_row = request_row('ucenter_api');

		$ucenter_api_key = $ucenter_api_row['ucenter_api_key'];
		$ucenter_api_url = $ucenter_api_row['ucenter_api_url'];
		$ucenter_app_id  = 104;

		$data                    = array();
		$data['ucenter_api_key'] = $ucenter_api_key;
		$data['ucenter_api_url'] = $ucenter_api_url;
		$data['ucenter_app_id']  = $ucenter_app_id;

		//$file = INI_PATH . '/ucenter_api.ini.php';
		//$file = INI_PATH . '/ucenter_api_' . Yf_Registry::get('server_id') . '.ini.php';
		
		$file = INI_PATH . '/ucenter_api_' . Yf_Registry::get('server_id') . '.ini.php';
		/*
		if (is_file(INI_PATH . '/ucenter_api_' . Yf_Registry::get('server_id') . '.ini.php'))
		{
			$file = INI_PATH . '/ucenter_api_' . Yf_Registry::get('server_id') . '.ini.php';
		}
		else
		{
			$file = INI_PATH . '/ucenter_api.ini.php';
		}
		*/
		
		if (!Yf_Utils_File::generatePhpFile($file, $data))
		{
			$status = 250;
			$msg    = _('生成配置文件错误!');
		}
		else
		{
			$msg    = _('生成配置文件成功!');;
			$status = 200;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}

    /**
     *  注册设置
     */
	public function regConfig(){
		$config_type_row = request_row('config_type');
        $config_type = array_shift($config_type_row);
        $config_value_row = request_row($config_type);
        //初始化传值，如果不存在，给默认值
        if(!isset($config_value_row['reg_number']) || !$config_value_row['reg_number']){
            $config_value_row['reg_number'] = 0;
        }
        if(!isset($config_value_row['reg_lowercase']) || !$config_value_row['reg_lowercase']){
            $config_value_row['reg_lowercase'] = 0;
        }
        if(!isset($config_value_row['reg_uppercase']) || !$config_value_row['reg_uppercase']){
            $config_value_row['reg_uppercase'] = 0;
        }
        if(!isset($config_value_row['reg_symbols']) || !$config_value_row['reg_symbols']){
            $config_value_row['reg_symbols'] = 0;
        }
        if(!isset($config_value_row['reg_checkcode']) || !$config_value_row['reg_checkcode']){
            $config_value_row['reg_checkcode'] = 1;
        }
        //判断是否设置密码最小长度
        if(intval($config_value_row['reg_pwdlength']) < 1){
            $msg    = '密码最小长度设置有误';
            $status = 250;
            $this->data->addBody(-1, array(), $msg, $status);
            exit;
        }
        //先判断判断邮箱或者短信是否设置
        if(!in_array(intval($config_value_row['reg_checkcode']),array(1,2,3))){
            $msg    = '请选择注册验证方式';
            $status = 250;
            $this->data->addBody(-1, array(), $msg, $status);
            exit;
        }else{
            $check_msg_flag = $this->_checkMsgFlag($config_value_row['reg_checkcode']);
            if($check_msg_flag['status'] == 250){
                $msg    = !$check_msg_flag['msg'] ? 'failure' : $check_msg_flag['msg'];
                $status = 250;
                $this->data->addBody(-1, array(), $msg, $status);
                exit;
            }
        }
        
        $Web_ConfigModel = new Web_ConfigModel();
        $config_rows = $Web_ConfigModel->getByWhere(array('config_type' => $config_type));
        $config_rows_key = array_keys($config_rows);
        $flag = false;
        foreach ($config_value_row as $k => $value){
            if(in_array($k, $config_rows_key)){
                //编辑
                $config_data = array();
                $config_data['config_value'] = $config_rows[$k]['config_datatype'] === 'json' ? json_encode($value) : $value;
                $flag = $Web_ConfigModel->editConfig($k, $config_data);
                
            }else{
                //添加
                $config_data = array();
                $config_data['config_value'] = $value;
                $config_data['config_type'] = 'register';
                $config_data['config_enable'] = '1';
                $config_data['config_datatype'] = 'number';
                $config_data['config_key'] = $k;
                $flag = $Web_ConfigModel->addConfig($config_data);     
            }
        }
		if($flag === false){
            $msg    = 'failure';
			$status = 250;
		}else{
			$msg    = 'success';
			$status = 200;
		}
		$this->data->addBody(-1, $config_data, $msg, $status);
	}

	//注册页面广告位的设置
	public function logImgConfig()
	{
		$config_type_row = request_row('config_type');
		$config_type = array_shift($config_type_row);  //register_img
		$config_value_row = request_row($config_type);  //注册内容

		$Web_ConfigModel = new Web_ConfigModel();
		$config_rows = $Web_ConfigModel->getByWhere(array('config_type' => $config_type));


		foreach($config_value_row as $key => $val)
		{
			//修改
			if(isset($config_rows[$key]))
			{
				$edit_row = array();
				$edit_row['config_value'] = $val;
				$Web_ConfigModel->editConfig($key, $edit_row);
			}
			else
			{
				$add_row = array();
				$add_row['config_key'] = $key;
				$add_row['config_value'] = $val;
				$add_row['config_type'] = $config_type;
				$add_row['config_enable'] = 1;
				$add_row['config_datatype'] = 'string';

				$Web_ConfigModel->addConfig($add_row);
			}
		}

		$this->data->addBody(-140, array('a'=>'bbbb'));

	}
    
	/**
	 * setStandard1
	 *
	 * @access public
	 */
	public function editPaycenterApi()
	{
		//其它全局变量
		$config_rows = array();
		$file        = INI_PATH . '/paycenter_api.ini.php';


		$paycenter_api_row = request_row('paycenter_api');

		$paycenter_api_key = $paycenter_api_row['paycenter_api_key'];
		$paycenter_api_url = $paycenter_api_row['paycenter_api_url'];
		$paycenter_app_id  = 105;

		$data                    = array();
		$data['paycenter_api_key'] = $paycenter_api_key;
		$data['paycenter_api_url'] = $paycenter_api_url;
		$data['paycenter_app_id']  = $paycenter_app_id;

		//$file = INI_PATH . '/paycenter_api.ini.php';
		$file = INI_PATH . '/paycenter_api_' . Yf_Registry::get('server_id') . '.ini.php';

		if (!Yf_Utils_File::generatePhpFile($file, $data))
		{
			$status = 250;
			$msg    = _('生成配置文件错误!');
		}
		else
		{
			$msg    = _('生成配置文件成功!');;
			$status = 200;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}

	/**
	 * testEmail
	 *
	 * @access public
	 */
	public function testEmail()
	{
		//其它全局变量
		$email_row = request_row('email');

		$title    = '测试邮件';
		$name     = 'test';
		$email_to = $email_row['email_test'];
		$con      = '测试邮件';
		$reply    = $email_row['email_test'];  //收件人

		$email_host = $email_row['email_host'];
		$email_addr = $email_row['email_addr'];
		$email_pass = $email_row['email_pass'];
		$email_id   = $email_row['email_id'];
		$email_port   = $email_row['email_port'];


		include_once(LIB_PATH . "/phpmailer/class.phpmailer.php");


		try
		{
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			$mail->CharSet  = 'UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
			$mail->SMTPAuth = true; //开启认证
			$mail->Port     = $email_port;
			$mail->Host     = $email_host;
			$mail->Username = $email_addr;
			$mail->Password = $email_pass;
//$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could not execute: /var/qmail/bin/sendmail ”的错误提示
			$mail->AddReplyTo($email_addr, $email_id);//回复地址
			$mail->From     = $email_addr;
			$mail->FromName = $email_id;

			$mail->AddAddress($email_to);
			$mail->Subject = "邮件测试标题";
			$mail->Body    = "测试邮件内容";
			//$mail->AltBody  = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
			$mail->WordWrap = 80; // 设置每行字符串的长度
//$mail->AddAttachment("f:/test.png"); //可以添加附件
			$mail->IsHTML(true);
			$re = $mail->Send();
		}
		catch (phpmailerException $e)
		{
			$re  = false;
			$msg = "邮件发送失败：" . $e->errorMessage();
		}


		if ($re)
		{
			$status = 250;
			$msg    = _('测试邮件已经发送!');
		}
		else
		{
			$msg    = _('测试失败') . $msg;
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}

	/**
	 * testEmail
	 *
	 * @access public
	 */
	public function testSms()
	{
		//其它全局变量
		$email_row = request_row('sms');


		$sms_account = $email_row['sms_account'];
		$sms_pass    = $email_row['sms_pass'];

		if (true)
		{
			$status = 250;
			$msg    = _('测试邮件已经发送!');
		}
		else
		{
			$msg    = _('测试失败');
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);
	}


	public function version()
	{
		$app_id = request_int('app_id');
		$client_version = request_string('client_version', '1.0.0');

		//cpp version
		$package_version = request_string('package_version', '1.0.0');


		//修正读到旧的版本信息数据
		if (-1 == version_compare($client_version, $package_version))
		{
			$client_version = $package_version;
		}

		$Base_AppVersion = new Base_AppVersion();
		$base_app_rows_version = $Base_AppVersion->getAppVersion($app_id);

		if(!$base_app_rows_version)
		{
			$this->data->setError(_('游戏版本配置表错误'));
		}

		$app_version = $base_app_rows_version['app_version'];
		$version_row = array();

		if($app_version != $client_version)//客户端的版本不是最新版本
		{
			$Base_AppResources = new Base_AppResources();
			$base_app_rows_resources = $Base_AppResources->getAppResources($app_id, $client_version);

			if(!$base_app_rows_resources)//客户端版本号错误
			{
				$base_app_rows_resources = $Base_AppResources->getAppResources($app_id, $base_app_rows_version['app_version']);

				$version_row['client_version'] = $client_version;
				$version_row['current_version'] = $base_app_rows_version['app_version'];
				$version_row['latest_version'] = $base_app_rows_version['app_version'];
				$version_row['zip_url'] = $base_app_rows_resources['app_res_url'].$base_app_rows_resources['app_res_childpath'].$base_app_rows_resources['app_res_filename'];
				$version_row['state'] = $base_app_rows_resources['app_reinstall'] != 2 ? 1 : 2;
				$version_row['filesize'] = $base_app_rows_resources['app_res_filesize'];
			}
			else
			{
				$app_version_next = $base_app_rows_resources['app_version_next'];
				$base_app_rows_resources_next = $Base_AppResources->getAppResources($app_id, $app_version_next);
				$app_version_package_next = $base_app_rows_resources_next['app_version_package'];

				//如果下一个更新包，不是当前版本可更新的，则判断reinstall, 如果为2，则强制更新，否则不更新 ,根据app_version_package来判断。
				//新包发布完成后，将此字段改为2，目前仅限于官方版本，平台升级叫给平台处理
				//version_compare($package_version, $app_version_package_next) >= 0
				if ($package_version != $app_version_package_next)
				{
					//可以安装新包，不能直接next，可能当前包很旧，需要找到最新包！！！
					//目前从当前安装的包直接读取，每次有新包，则更新所有的字段app_package_url
					if (2==$base_app_rows_resources['app_reinstall'] && $base_app_rows_resources['app_package_url'])
					{
						$version_row['client_version'] = $client_version;
						$version_row['current_version'] = $base_app_rows_version['app_version'];
						$version_row['latest_version'] = $base_app_rows_version['app_version'];
						$version_row['zip_url'] = $base_app_rows_resources['app_package_url'];
						$version_row['state'] = intval($base_app_rows_resources['app_reinstall']);
						$version_row['filesize'] = $base_app_rows_resources['app_res_filesize'];
					}
					else
					{
						$version_row['client_version'] = $client_version;
						$version_row['current_version'] = $client_version;
						$version_row['latest_version'] = $client_version;
						$version_row['zip_url'] = '1';
						$version_row['state'] = 0;
						$version_row['filesize'] = 0.00;
					}
				}
				else
				{
					$version_row['client_version'] = $client_version;
					$version_row['current_version'] = $app_version_next;
					$version_row['latest_version'] = $base_app_rows_version['app_version'];
					$version_row['zip_url'] = $base_app_rows_resources_next['app_res_url'].$base_app_rows_resources_next['app_res_childpath'].$base_app_rows_resources_next['app_res_filename'];
					$version_row['state'] = version_compare($version_row['client_version'], $version_row['current_version'],'eq') == 1 ? 0 : 1;
					$version_row['filesize'] = $base_app_rows_resources_next['app_res_filesize'];
				}
			}
		}
		else
		{
			$version_row['client_version'] = $client_version;
			$version_row['current_version'] = $client_version;
			$version_row['latest_version'] = $client_version;
			$version_row['zip_url'] = '1';
			$version_row['state'] = 0;
			$version_row['filesize'] = 0.00;
		}

		$this->data->setBody($version_row);
	}

	/**
	 * 获取app 列表
	 *
	 * @access public
	 */
	public function listAppId()
	{
		$Base_App = new Base_App();
		$data = $Base_App->listByWhere();

		$rs = array();

		foreach ($data['items'] as $key=>$item)
		{
			$rs[$key]['id'] = $item['app_id'];
			$rs[$key]['name'] = $item['app_name'];
		}

		$this->data->addBody(-140, array('app_id'=>$rs));
	}
	
	
	/**
	 * 更新信息
	 *
	 * @access public
	 */
	public function update()
	{
		//从API获取。
		$client_version         = Web_ConfigModel::value('current_version', '1.0.1');
		$client_db_version      = Web_ConfigModel::value('current_db_version', '1');
		$required_php_version   = Web_ConfigModel::value('required_php_version', '5.3');
		$required_mysql_version = Web_ConfigModel::value('required_mysql_version', '5.0');
		
		$app_id   = '104';
		$db_id    = 'ucenter';
		$db_prefix     = 'ucenter_';
		$db_prefix_base     = 'ucenter_';
		
		
		$upgrader = new \Yf\Upgrader\Core($app_id, $client_version, LANG, $db_id, $db_prefix, $db_prefix_base);
		
		$version_rows = $upgrader->getCoreVersion();
		
		$version_row = $version_rows['latest'];
		
		//检测本地文件是否变动过
		$change_file_row = array();
		

		if ('json' == $this->typ)
		{
			$this->data->addBody(-140, array('change_file_row'=>$change_file_row, 'version_row'=>$version_row, 'client_version'=>$client_version, 'partial'=>$partial));
		}
		else
		{
			if ($partial && request_int('upgrade') || request_int('force-upgrade'))
			{
				$updates = $upgrader->getCoreUpdateList();
				
				$version = $version_row['version'];
				$locale  = $version_row['locale'];
				
				$update = $upgrader->findCoreUpdate($version, $locale, $updates);
				
				if ($update)
				{
					$this->view->setMet('upgrade');
				}
			}
			
			include $view = $this->view->getView();
		}
		
		
	}
    
    /**
     * Check the settings status of mailbox and message
     * @param type $status
     * @return array
     */
    private function _checkMsgFlag($status){
        $result = array();
        switch($status){
            case 1 : 
                //sms
                $data = $this->_getConfigData('sms');
                if(!$data){
                    $result['msg'] = '请先设置短信';
                    $result['status'] = 250;
                }else{
                    $result['msg'] = 'success';
                    $result['status'] = 200;
                }
                break;
            case 2 :
                //email
                $data = $this->_getConfigData('email');
                if(!$data){
                    $result['msg'] = '请先设置邮箱';
                    $result['status'] = 250;
                }else{
                    $result['msg'] = 'success';
                    $result['status'] = 200;
                }
                break;
			case 3:
				$data1 = $this->_getConfigData('sms');
				$data2 = $this->_getConfigData('email');
				if(!$data1 || !$data2){
					$result['msg'] = '请先设置短信和邮箱';
					$result['status'] = 250;
				}else{
					$result['msg'] = 'success';
					$result['status'] = 200;
				}
				break;

            default :
                $result['msg'] = '请先设置短信或者邮箱';
                $result['status'] = 250;
        }
        return $result;
    }
    
    /**
     * get config data by config_type
     * @param type $config_type
     * @return type
     */
    private function _getConfigData($config_type){
        $Web_ConfigModel = new Web_ConfigModel();
        $data = $Web_ConfigModel->getByWhere(array('config_type' => $config_type));
        return $data;
    }
	
}

?>