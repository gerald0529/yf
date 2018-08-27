<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Banchangle
 */
class Seller_Shop_EntityshopCtl extends Seller_Controller
{

	public $shopBaseModel   = null;
	public $shopEntityModel = null;

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
		$this->shopBaseModel   = new Shop_BaseModel();
		$this->shopEntityModel = new Shop_EntityModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function entityShop()
	{
		// $return = file_get_contents("http://api.map.baidu.com/geocoder/v2/?address=上海南站&output=json&ak=CTXgAI1qpc1NlUq6MGn2eZdwLfc544ou");
		$act                = request_string('act');
		$Yf_Page            = new Yf_Page();
		$Yf_Page->listRows  = 10;
		$rows               = $Yf_Page->listRows;
		$offset             = request_int('firstRow', 0);
		$page               = ceil_r($offset / $rows);
		$shop_id            = Perm::$shopId;
		$data               = $this->shopEntityModel->listByWhere(array("shop_id" => $shop_id), array(), $page, $rows);
		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();

		if ($act == 'list')
		{
			$this->view->setMet('entitylist');
		}
		if ('json' == $this->typ)
		{

			$this->data->addBody(-140, $data);

		}
		else
		{
			include $this->view->getView();
		}
	}

	public function editEntity()
	{
		$entity_id  = request_int('entity_id');
		$entity_row = request_row('entity');
		$shop_id    = Perm::$shopId;
		if ($entity_id)
		{
			//判断是不是当前用户操作的
			$entity_info = $this->shopEntityModel->getOne($entity_id);
			if ($shop_id == $entity_info['shop_id'])
			{
				$flag = $this->shopEntityModel->editEntity($entity_id, $entity_row);
				if ($flag !== FALSE)
				{
					$status = 200;
					$msg    = __('success');
				}
				else
				{
					$status = 250;
					$msg    = __('failure');
				}
			}
			else
			{
				$status = 250;
				$msg    = __('failure');
			}

		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//删除一个实体店铺
	public function delEntity()
	{
		$entity_id = request_int('id');
		$shop_id   = Perm::$shopId;
		if ($entity_id)
		{
			//判断是不是当前用户操作的
			$entity_info = $this->shopEntityModel->getOne($entity_id);
			if ($shop_id == $entity_info['shop_id'])
			{
				$flag = $this->shopEntityModel->removeEntity($entity_id);
				if ($flag)
				{
					$status = 200;
					$msg    = __('success');
				}
				else
				{
					$status = 250;
					$msg    = __('failure');
				}
			}
			else
			{
				$status = 250;
				$msg    = __('failure');
			}

		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//仅仅加载一个页面
	public function addEntityInfo()
	{
		include $this->view->getView();
	}


	//仅仅加载一个页面
	public function editEntityInfo()
	{
		$entity_id   = request_int('entity_id');
		$entity_info = $this->shopEntityModel->getOne($entity_id);
		if ('json' == $this->typ)
		{
			$data['entity_info'] = $entity_info;
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	public function addEntityrow()
	{
		$entity_xxaddr           = request_string('entity_xxaddr');
		$entity                  = request_row('entity');
		$entity['shop_id']       = Perm::$shopId;
		$lat                     = request_string('lat');
		$lng                     = request_string('lng');
		$entity['entity_xxaddr'] = $entity_xxaddr;
		//判断实体店铺详细地址是否为空，如果不为空根据名字查询出他的经纬度。否则用默认的经纬度。
		if ($entity_xxaddr)
		{
			//原ak=CTXgAI1qpc1NlUq6MGn2eZdwLfc544ou , 已失效
			//A83cd06b54e826075981aa381d52b943
			$return        = file_get_contents("http://api.map.baidu.com/geocoder/v2/?address=$entity_xxaddr&output=json&ak=5At3anZe83x8oOpFap42Gt8eHYpy3wm9");
			$returns       = json_decode($return, true);
			$entity['lat'] = $returns['result']['location']['lat'];
			$entity['lng'] = $returns['result']['location']['lng'];
            //http://api.map.baidu.com/geocoder/v2/?location=31.1135396384551,121.38517950825646&output=json&pois=1&ak=A83cd06b54e826075981aa381d52b943
		}
		else
		{
			$entity['lat'] = $lat;
			$entity['lng'] = $lng;
		}
		$flag = $this->shopEntityModel->addEntity($entity);

		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>