<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让App等调用
 * 接口通信Api权限验证
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class WebPosApi_Controller extends Yf_AppController
{
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

		$data = new Yf_Data();

		//API PERM
		$key = Yf_Registry::get('shop_api_key');

		if (isset($_REQUEST['debug']))
		{
		}
		else
		{
			if ((isset($_POST['token']) && isset($_POST['app_id']) && $_POST['app_id'] == Yf_Registry::get('shop_app_id')))
			{
				if (!check_url_with_encrypt($key, $_POST))
				{
					$this->data->setError(__('API接口有误,请确保APP KEY及APP ID正确'), 30);
					$d = $this->data->getDataRows();

					$protocol_data = Yf_Data::encodeProtocolData($d);
					echo $protocol_data;

					exit();
				}
			}
			else
			{
				$this->data->setError(__('API接口有误,请确保APP KEY及APP ID正确'), 30);
				$d = $this->data->getDataRows();

				$protocol_data = Yf_Data::encodeProtocolData($d);
				echo $protocol_data;

				exit();
			}
		}
	}
}

?>