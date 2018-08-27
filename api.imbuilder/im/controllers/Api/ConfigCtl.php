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
//	public function site ()
//	{
//		include $this->view->getView();
//	}
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
fb($data);
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
                fb($data);
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
	public function edit1()
	{
		$Web_ConfigModel = new Web_ConfigModel();

		$config_type_row = request_row('config_type');
		foreach ($config_type_row as $config_type)
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
					else
					{
					}
				}

				if ($edit_row)
				{
					$Web_ConfigModel->editConfig($config_key, $edit_row);
				}
			}
		}

		//其它全局变量
		$config_rows = array();
		$file        = INI_PATH . '/global.ini.php';
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

	public function edit()
	{
		$Web_ConfigModel = new Web_ConfigModel();

		$config_type_row = request_row('config_type');

		foreach ($config_type_row as $config_type)
		{
			$config_value_row = request_row($config_type);

			$config_rows = $Web_ConfigModel->getByWhere(array('config_type' => $config_type));

			if($config_rows){
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
						else
						{
						}
					}

					if ($edit_row)
					{
						$Web_ConfigModel->editConfig($config_key, $edit_row);
					}

				}
			}
			else{
				foreach ($config_value_row as $config_key => $config_row)
				{
					$add_row = array();
					$add_row['config_key'] = $config_key;
					$add_row['config_value'] = $config_row;
					$add_row['config_type'] = $config_type;
					$Web_ConfigModel->addConfig($add_row,true);

				}
			}

			if ('email' == $config_type_row[0])
			{

				$key       =  Yf_Registry::get('shop_api_key');
				$url            = Yf_Registry::get('paycenter_api_url');
				$app_id         = Yf_Registry::get('paycenter_app_id');

				//开通ucenter
				//本地读取远程信息
				$formvars              = array();
				$formvars['app_id']    = $app_id;

				$formvars['config_type'][0]    = 'email';
				$formvars['email']['email_addr']    	= $config_value_row['email_addr'];
				$formvars['email']['email_host']    	= $config_value_row['email_host'];
				$formvars['email']['email_id']    	= $config_value_row['email_id'];
				$formvars['email']['email_pass']    	= $config_value_row['email_pass'];
				$formvars['email']['email_port']    	= $config_value_row['email_port'];

				$init_rs1 = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Config&met=edit&typ=json', $url), $formvars);

				$url            = Yf_Registry::get('ucenter_api_url');
				$app_id         = Yf_Registry::get('ucenter_app_id');
				$formvars['app_id']    = $app_id;
				$init_rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Config&met=edit&typ=json', $url), $formvars);

			}


		}


		//其它全局变量
		$config_rows = array();

		if (true || is_file(INI_PATH . '/global_' . Yf_Registry::get('server_id') . '.ini.php'))
		{
			$file = INI_PATH . '/global_' . Yf_Registry::get('server_id') . '.ini.php';
		}
		else
		{
			$file = INI_PATH . '/global.ini.php';
		}

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

	public function editUcenterApi()
	{
		//其它全局变量
		$config_rows = array();
		$file        = INI_PATH . '/ucenter_api.ini.php';


		$ucenter_api_row = request_row('ucenter_api');
		fb($ucenter_api_row);

		$ucenter_api_key = $ucenter_api_row['ucenter_api_key'];
		$ucenter_api_url = $ucenter_api_row['ucenter_api_url'];
		$ucenter_app_id  = 104;

		$data                    = array();
		$data['ucenter_api_key'] = $ucenter_api_key;
		$data['ucenter_api_url'] = $ucenter_api_url;
		$data['ucenter_app_id']  = $ucenter_app_id;

		$file = INI_PATH . '/ucenter_api.ini.php';

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

		$this->data->addBody(-140, $ucenter_api_row, $file, $status);
	}

    /**
     * setStandard1
     *
     * @access public
     */
    public function setStandard()
    {
        $data = Yf_Utils_File::getPhpFile(ROOT_PATH);

        foreach ($data as $key => $file)
        {
            $k            = md5($file . md5_file($file));
            $data_row[$k] = $file;
        }

        $validator_standard_file = APP_PATH . '/data/php_standard_file';

        $flag = file_put_contents($validator_standard_file, json_encode($data_row));

        if ($flag)
        {
            $msg    = _('sucess');
            $status = 200;
        }
        else
        {
            $msg    = _('失败');
            $status = 250;
        }

        $this->data->addBody(-140, $data_row, $msg, $status);
    }

	/**
	 * setStandard1
	 *
	 * @access public
	 */
	public function editImbuilderApi()
	{
		//其它全局变量
		$config_rows = array();
		$file        = INI_PATH . '/im_api.ini.php';
		$im_api_row = request_row('im_api');
		$im_api_key = $im_api_row['im_api_key'];
		$im_api_url = $im_api_row['im_api_url'];
		$im_app_id  = 103;
		$data                    = array();
		$data['im_api_key'] = $im_api_key;
		$data['im_api_url'] = $im_api_url;
		$data['im_app_id']  = $im_app_id;

		$file = INI_PATH . '/im_api.ini.php';

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


	public function editShopApi()
	{
		//其它全局变量
		$config_rows = array();
		$file        = INI_PATH . '/shop_api.ini.php';


		$shop_api_row = request_row('shop_api');

		$shop_api_key = $shop_api_row['shop_api_key'];
		$shop_api_url = $shop_api_row['shop_api_url'];
		$shop_app_id  = 105;

		$data                    = array();
		$data['shop_api_key'] = $shop_api_key;
		$data['shop_api_url'] = $shop_api_url;
		$data['shop_app_id']  = $shop_app_id;

		$file = INI_PATH . '/shop_api.ini.php';

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


		include_once(LIB_PATH . "/phpmailer/class.phpmailer.php");


		try
		{
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			$mail->CharSet  = 'UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
			$mail->SMTPAuth = true; //开启认证
			$mail->Port     = 25;
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

		if ($re)
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
     * 清除缓存
     *
     * @access public
     */
    public function cache()
    {
        $error_row = array();
        $data_row  = array();

        $config_cache = Yf_Registry::get('config_cache');

        foreach ($config_cache as $name => $item)
        {
            if (isset($item['cacheDir']))
            {
                if (clean_cache($item['cacheDir']))
                {
                    $data_row[] = $item['cacheDir'];
                }
                else
                {
                    $error_row[] = $item['cacheDir'];
                }

                $Cache = Yf_Cache::create($name);

                $data_row[] = json_encode($config_cache['memcache'][$name]);

                if (method_exists($Cache, 'flush') && !$Cache->flush())
                {
                    $error_row[] = 'memcache-' . $name;
                }
            }
            else
            {

            }
        }

        if (true)
        {
            $msg    = _('sucess');
            $status = 200;
        }
        else
        {
            $msg    = _('清除cache失败');
            $status = 250;
        }

        $this->data->addBody(-140, $data_row);
    }


}

?>