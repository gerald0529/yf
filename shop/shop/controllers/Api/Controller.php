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
class Api_Controller extends Yf_AppController
{

	protected function _array_merge($a,$b){
      $r = $a+$b;
      foreach($r as $k=>$v){
          if(is_null($v)){
              $r[$k] = $b[$k];
          }
      }
      return $r;
  }

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
			if ((isset($_REQUEST['token']) && isset($_REQUEST['app_id'])))
			{
				//$data = $_GET + $_POST;
				$data = $this->_array_merge($_GET , $_POST);
				if (!isset($_POST['ctl']))
				{
					unset($data['ctl']);
				}
				
				if (!isset($_POST['met']))
				{
					unset($data['met']);
				}
				
				if (!isset($_POST['typ']))
				{
					unset($data['typ']);
				}


				if (!check_url_with_encrypt($key, $data))
				{
					$this->data->setError(__('API接口有误,请确保APP KEY及APP ID正确'), 301);
					$d = $this->data->getDataRows();

					$protocol_data = Yf_Data::encodeProtocolData($d);
					echo $protocol_data;

					exit();
				}
			}
			else
			{
				$this->data->setError(__('API接口有误,请确保APP KEY及APP ID正确'), 302);
				$d = $this->data->getDataRows();

				$protocol_data = Yf_Data::encodeProtocolData($d);
				echo $protocol_data;

				exit();
			}
		}
	}
}

?>