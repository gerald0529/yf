<?php

//用户登录认证
class Plugin_Perm implements Yf_Plugin_Interface
{
	//解析函数的参数是pluginManager的引用
	function __construct()
	{
		//注册这个插件
		//第一个参数是钩子的名称
		//第二个参数是pluginManager的引用
		//第三个是插件所执行的方法
		Yf_Plugin_Manager::getInstance()->register('perm', $this, 'checkPerm');
		Yf_Plugin_Manager::getInstance()->register('server_state', $this, 'checkServerState');
	}

	public static function desc()
	{
		return '<b>这个是用户权限认证插件!</b>';
	}

	public function checkPerm()
	{
		$data = new Yf_Data();

		//无需权限判断的文件

		$not_perm = array(
			'Upload',
			'Login',
			'Api',
			'ImApi',
			'Index',
			'Base_District',
			'Connect_Qq',
			'Connect_Weixin',
			'Connect_Weibo',
			'Transport',
			'Shop',
			'GroupBuy',
			'Points',
			'Voucher',
			'Article_Base',
			'Shop_Index',
			'RedPacket',
			'Supplier_Index',
			'Supplier_Goods',
            'PinTuan',
			'Qr'
		);

	 	
		//不需要登录
		if (!isset($_REQUEST['ctl']) || (isset($_REQUEST['ctl']) && in_array($_REQUEST['ctl'], $not_perm)) || (isset($_REQUEST['ctl']) && 'Api_' == substr($_REQUEST['ctl'], 0, 4)) || (isset($_REQUEST['ctl']) && 'WebPosApi_' == substr($_REQUEST['ctl'], 0, 10)) || (isset($_REQUEST['ctl']) && 'Goods_' == substr($_REQUEST['ctl'], 0, 6)))
		{
			//检查是否为店铺子域名
			$is_sub_Domain = Perm::checkSubDomain();
			if ($is_sub_Domain && $store_id = $is_sub_Domain)
			{
				$store_url = sprintf('%s?ctl=Shop&met=index&typ=e&id=%s', Yf_Registry::get('shop_api_url'), $store_id);
				location_to($store_url);
			}

			if (Perm::checkUserPerm())
			{

			}
		}
		elseif (Perm::checkUserPerm())
		{
			/*
			if (!Perm::checkUserRights())
			{
				//无权限
				fb($_REQUEST, '-2:无访问权限', FirePHP::ERROR);
				$data->setError(__('无访问权限'));
				return $this->outputError($data);
			}
			*/
//			$asd = Perm::checkUserPerm();
//			$data->setError(__('需要登录'), $asd);
//			return $this->outputError($data);
		}
		else
		{
			Perm::removeUserInfo();

			if ('e' == $_REQUEST['typ'])
			{
				$url = Yf_Registry::get('url') . '?ctl=Login&met=login&typ=e';

				if (request_string('forward_self'))
				{
					//$forward = '&forward=//:' . $_SERVER['HTTP_HOST'] . urlencode($_SERVER['REQUEST_URI']);
					$forward = '&forward=' . urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                    $forward = str_replace('forward_self', 'x_self', $forward);
					$url     = $url . $forward;
				}
				else
				{
					if (isset($_SERVER['HTTP_REFERER']))
					{
						$forward = '&forward=' . urlencode($_SERVER['HTTP_REFERER']);
						$url     = $url . $forward;
					}
				}
				location_to($url);
			}
			else
			{
				if('json' == $_REQUEST['typ'] && Perm::checkUserPerm())
				{ 
				}
				else
				{
					$data->setError(__('需要登录'), 30);
					return $this->outputError($data);
				}
			}
		}
	}

	public function outputError($data)
	{
		$d = $data->getDataRows();

		$protocol_data = Yf_Data::encodeProtocolData($d);
		echo $protocol_data;

		exit();
	}
}