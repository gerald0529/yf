<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Supplier_AdpositionCtl extends Yf_AppController
{

	public $messageModel         = null;
	public $AdvPageLayoutModel   = null;
	public $AdvPageSettingsModel = null;
	public $AdvWidgetBaseModel   = null;
	public $AdvWidgetItemModel   = null;

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
		$this->AdvPageLayoutModel   = new Adv_PageLayoutModel();
		$this->AdvPageSettingsModel = new Adv_PageSettingsModel();
		$this->AdvWidgetBaseModel   = new Adv_WidgetBaseModel();
		$this->AdvWidgetItemModel   = new Adv_WidgetItemModel();
		$this->messageModel         = new MessageModel();
	}

	public function position()
	{
		$page_id = request_int("page_id");
		$data    = $this->AdvPageSettingsModel->getOne($page_id);

		$structure         = $this->AdvPageLayoutModel->getOne($data['layout_id']);
		$data['structure'] = $this->AdvPageSettingsModel->getAdpositionlist($page_id, $data['layout_id'], $structure);
		$AdvWidgetNavModel = new Adv_WidgetNavModel();
		$cond_row         = array(
				"page_id" =>$page_id,
		);
		$data["nav"] = $AdvWidgetNavModel->getByWhere($cond_row);
		$this->data->addBody(-140, $data);
	}

	public function category()
	{
		$data["page_id"]     = request_int("page_id");
		$data["layout_id"]   = request_int("layout_id");
		$data["widget_name"] = request_string("widget_name");
		$data["width"]       = request_string("width");
		$data["height"]      = request_string("height");
		$data["met"]         = request_string("met");
		//获取单个楼层位的样式

		$cond_row         = array(
			"page_id" => $data["page_id"],
			"layout_id" => $data["layout_id"],
			"widget_name" => $data["widget_name"]
		);
		$data["category"] = $this->AdvWidgetBaseModel->getByWhere($cond_row);

		//根据单个广告获取详情
		foreach ($data["category"] as $key => $val)
		{
			$array                         = array("widget_id" => $key);
			$data["category"][$key]["cat"] = $this->AdvWidgetItemModel->getByWhere($array);

		}
		$this->data->addBody(-140, $data);
	}

	public function nav()
	{
		$data["page_id"]     = request_int("page_id");
		//获取单个楼层位的样式

		$cond_row         = array(
				"page_id" => $data["page_id"],
		);
		$AdvWidgetNavModel = new Adv_WidgetNavModel();
		$data["nav"] = $AdvWidgetNavModel->getByWhere($cond_row);
		$this->data->addBody(-140, $data);
	}

	public function addNav(){
		$page_id     = request_int("page_id");
		$cond_row['widget_nav_name'] = request_row("widget_nav_name");
		$cond_row['widget_nav_url'] = request_row("widget_nav_url");
		$AdvWidgetNavModel = new Adv_WidgetNavModel();
		foreach ($cond_row['widget_nav_name'] as $key => $val) {
			$nav_row['widget_nav_name'] = $val;
			$nav_row['widget_nav_url'] = $cond_row['widget_nav_url'][$key];
			$nav_row['page_id'] = $page_id;
			$flag = $AdvWidgetNavModel->addWidgetNav($nav_row);
		}
		$re = $this->AdvPageSettingsModel->set_page_settings_html($page_id, "index");
		$data = array();
		$this->data->addBody(-140, $data);

	}

	public function editNav(){
		$page_id     = request_int("page_id");
		$cond_row['widget_nav_name'] = request_row("widget_nav_name");
		$cond_row['widget_nav_url'] = request_row("widget_nav_url");
		$cond_row['widget_nav_id'] = request_row("widget_nav_id");
		$AdvWidgetNavModel = new Adv_WidgetNavModel();
		foreach ($cond_row['widget_nav_id'] as $key => $val) {
			$widget_nav_id = $val;
			$nav_row['widget_nav_url'] = $cond_row['widget_nav_url'][$key];
			$nav_row['widget_nav_name'] = $cond_row['widget_nav_name'][$key];
			$nav_row['page_id'] = $page_id;
			$flag = $AdvWidgetNavModel->editWidgetNav($widget_nav_id,$nav_row);
		}
		$re = $this->AdvPageSettingsModel->set_page_settings_html($page_id, "index");
		$data = array();

		$this->data->addBody(-140, $data);

	}
	public function category_add()
	{
		$Base["page_id"]       = request_int('page_id');
		$de["widget_id "]      = request_int('widget_id');
		$Base["layout_id"]     = request_int('layout_id');
		$Base["widget_width"]  = substr(request_string('widget_width'), 0, strlen(request_string('widget_width')) - 2);
		$Base["widget_height"] = substr(request_string('widget_height'), 0, strlen(request_string('widget_height')) - 2);
		$Base["widget_name"]   = request_string('widget_name');
		$Base["widget_cat"]    = request_string('widget_cat');
		$Base["widget_type"]   = 3;
		$Base['widget_time']   = date('y-m-d   H:i:s', time());  // 时间
		$data['item_time']     = date('y-m-d   H:i:s', time());  // 时间
		$data['item_active']   = 1;
		$item_url              = request_row('item_url');
		$item_name             = request_row('item_name');


		if (!empty($de["widget_id "]))
		{
			//修改广告
			$flag              = $this->AdvWidgetBaseModel->editWidgetBase($de["widget_id "], $Base);
			$data["widget_id"] = $de["widget_id "];

			$array    = array("widget_id" => $de["widget_id "]);
			$selt_con = $this->AdvWidgetItemModel->getByWhere($array);
			foreach ($selt_con as $key => $val)
			{

				$data["item_url"]  = $item_url[$val['item_cat_id']];
				$data["item_name"] = $item_name[$val['item_cat_id']];

				$flag = $this->AdvWidgetItemModel->editWidgetItem($key, $data);
			}

			$re = $this->AdvPageSettingsModel->set_page_settings_html($Base["page_id"], "index");


		}
		else
		{
			//新增广告
			$cond_row = array(
				"page_id" => $Base["page_id"],
				"layout_id" => $Base["layout_id"],
				"widget_name" => $Base["widget_name"]
			);
			$flag     = $this->AdvWidgetBaseModel->getByWhere($cond_row);
			if (!$category)
			{
				$add               = $this->AdvWidgetBaseModel->addWidgetBase($Base, TRUE);
				$data["widget_id"] = $add;
				foreach ($item_url as $key => $val)
				{
					$data["item_url"]    = $val;
					$data["item_name"]   = $item_name[$key];
					$data["item_cat_id"] = $key;
					$add_con             = $this->AdvWidgetItemModel->addWidgetItem($data, TRUE);
				}
				$re = $this->AdvPageSettingsModel->set_page_settings_html($Base["page_id"], "index");
			}
		}

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


	public function ad()
	{
		//是不是修改广告
		$item_id             = request_int("item_id");
		$data["page_id"]     = request_int("page_id");
		$data["layout_id"]   = request_int("layout_id");
		$data["widget_name"] = request_string("widget_name");
		$data["width"]       = request_string("width");
		$data["height"]      = request_string("height");
		$data["met"]         = request_string("met");
		if (empty($item_id))
		{
			//获取单个广告位的样式
			$cond_row   = array(
				"page_id" => $data["page_id"],
				"layout_id" => $data["layout_id"],
				"widget_name" => $data["widget_name"]
			);
			$data['ad'] = $this->AdvWidgetBaseModel->getByWhere($cond_row);
			//根据单个广告获取详情
			foreach ($data['ad'] as $key => $val)
			{
				$item_row                = array("widget_id" => $key);
				$data['ad'][$key]["pic"] = $this->AdvWidgetItemModel->getByWhere($item_row);
			}


		}
		else
		{


			$data['adimgtext'] = $this->AdvWidgetItemModel->getOne($item_id);

		}
		$this->data->addBody(-140, $data);
	}


	//处理图片，和幻灯广告
	public function ag()
	{

		$data["page_id"]     = request_int("page_id");
		$data["layout_id"]   = request_int("layout_id");
		$data["widget_name"] = request_string("widget_name");
		$data["width"]       = request_string("width");
		$data["height"]      = request_string("height");
		$data["met"]         = request_string("met");
		//获取单个广告位的样式
		$cond_row   = array(
			"page_id" => $data["page_id"],
			"layout_id" => $data["layout_id"],
			"widget_name" => $data["widget_name"]
		);
		$data['ag'] = $this->AdvWidgetBaseModel->getByWhere($cond_row);
		//根据单个广告获取详情
		foreach ($data['ag'] as $key => $val)
		{
			$item_row                = array("widget_id" => $key);
			$data['ag'][$key]["pic"] = $this->AdvWidgetItemModel->getByWhere($item_row);
		}
		$this->data->addBody(-140, $data);

	}

	public function ad_add()
	{
		$Base["page_id"]       = request_int('page_id');
		$data["item_img_url"]  = request_string('item_img_url');
		$data["item_url"]      = request_string('item_url');
		$data["item_name"]     = request_string('item_name');
		$de["widget_id"]       = request_int('widget_id');
		$Base["layout_id"]     = request_int('layout_id');
		$Base["widget_width"]  = substr(request_string('widget_width'), 0, strlen(request_string('widget_width')) - 2);
		$Base["widget_height"] = substr(request_string('widget_height'), 0, strlen(request_string('widget_height')) - 2);
		$Base["widget_name"]   = request_string('widget_name');
		$Base["widget_cat"]    = request_string('widget_cat');
		$Base['widget_time']   = date('y-m-d   H:i:s', time());  // 时间
		$data['item_time']     = date('y-m-d   H:i:s', time());  // 时间
		$data['item_active']   = 1;


		//修改广告
		if (!empty($de["widget_id"]))
		{
			$edit              = $this->AdvWidgetBaseModel->editWidgetBase($de["widget_id"], $Base);
			$data["widget_id"] = $de["widget_id"];
			$item_id           = request_int("item_id");
			$array             = array("widget_id" => $de["widget_id"]);
			$selt_con          = $this->AdvWidgetItemModel->getByWhere($array);
			$flag              = $this->AdvWidgetItemModel->editWidgetItem($item_id, $data);


			//添加显示表里面的html
			$re = $this->AdvPageSettingsModel->set_page_settings_html($Base["page_id"], "index");

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

			//新增广告

			$cond_row = array(
				"page_id" => $Base["page_id"],
				"layout_id" => $Base["layout_id"],
				"widget_name" => $Base["widget_name"]
			);
			$ad       = $this->AdvWidgetBaseModel->getOneByWhere($cond_row);
			//判断是不是第一次添加，如果是的话就要加一个widgetbase
			if (!$ad)
			{
				$this->messageModel->sql->startTransactionDb();
				$add                 = $this->AdvWidgetBaseModel->addWidgetBase($Base, TRUE);
				$data["widget_id"]   = $add;
				$data["item_cat_id"] = 1;
				$add_con             = $this->AdvWidgetItemModel->addWidgetItem($data, TRUE);
				$re                  = $this->AdvPageSettingsModel->set_page_settings_html($Base["page_id"], "index");
				if ($add && $add_con && $this->messageModel->sql->commitDb())
				{
					$status = 200;
					$msg    = __('success');
				}
				else
				{
					$this->messageModel->sql->rollBackDb();
					$status = 250;
					$msg    = __('failure');
				}

			}
			else
			{

				$data["widget_id"] = $ad["widget_id"];
				$add_con           = $this->AdvWidgetItemModel->addWidgetItem($data, TRUE);
				$re                = $this->AdvPageSettingsModel->set_page_settings_html($Base["page_id"], "index");
				if ($add_con)
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


		}


		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);


	}

	public function ag_add()
	{

		$Base["page_id"]       = request_int('page_id');
		$data["item_img_url"]  = request_string('item_img_url');
		$data["item_url"]      = request_string('item_url');
		$data["item_name"]     = request_string('item_name');
		$de["widget_id"]       = request_int('widget_id');
		$Base["layout_id"]     = request_int('layout_id');
		$Base["widget_width"]  = substr(request_string('widget_width'), 0, strlen(request_string('widget_width')) - 2);
		$Base["widget_height"] = substr(request_string('widget_height'), 0, strlen(request_string('widget_height')) - 2);
		$Base["widget_name"]   = request_string('widget_name');
		$Base["widget_cat"]    = request_string('widget_cat');
		$Base['widget_time']   = date('y-m-d   H:i:s', time());  // 时间
		$data['item_time']     = date('y-m-d   H:i:s', time());  // 时间
		$data['item_active']   = 1;


		if (!empty($de["widget_id"]))
		{
			$edit              = $this->AdvWidgetBaseModel->editWidgetBase($de["widget_id"], $Base);
			$data["widget_id"] = $de["widget_id"];
			$array             = array("widget_id" => $de["widget_id"]);
			$selt_con          = $this->AdvWidgetItemModel->getByWhere($array);
			foreach ($selt_con as $key => $val)
			{
				$flag = $this->AdvWidgetItemModel->editWidgetItem($val['item_id'], $data);

			}

			//添加显示表里面的html

			$re = $this->AdvPageSettingsModel->set_page_settings_html($Base["page_id"], "index");
			if ($edit !== false)
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
			//新增广告
			$cond_row = array(
				"page_id" => $Base["page_id"],
				"layout_id" => $Base["layout_id"],
				"widget_name" => $Base["widget_name"]
			);
			$ad       = $this->AdvWidgetBaseModel->getByWhere($cond_row);
			if (!$ad)
			{
				$add               = $this->AdvWidgetBaseModel->addWidgetBase($Base, TRUE);
				$data["widget_id"] = $add;
				$add_con           = $this->AdvWidgetItemModel->addWidgetItem($data, TRUE);
				$re                = $this->AdvPageSettingsModel->set_page_settings_html($Base["page_id"], "index");

			}
			if ($ad)
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

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}
        
        public function delitem(){
            $item_id = request_int("item_id");
            if($item_id){
              $flag= $this->AdvWidgetItemModel->removeWidgetItem($item_id);  
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
            }else{
               $status = 250;
                $msg    = __('failure'); 
            }
          $data = array();
          $this->data->addBody(-140, $data, $msg, $status);
        }


}

?>