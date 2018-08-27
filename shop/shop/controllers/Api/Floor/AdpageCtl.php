<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Floor_AdpageCtl extends Api_Controller
{
	public $messageModel         = null;
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
		$this->AdvPageSettingsModel = new Adv_PageSettingsModel();
		$this->AdvWidgetBaseModel   = new Adv_WidgetBaseModel();
		$this->AdvWidgetItemModel   = new Adv_WidgetItemModel();
		$this->messageModel         = new MessageModel();
	}


	public function advIndex()
	{   
        $sub_site_id = request_int("sub_site_id");
        $cond_row = array();
        $cond_row['sub_site_id'] =  $sub_site_id;
        
		$data = $this->AdvPageSettingsModel->listPageSettingsWhere($cond_row);
		$this->data->addBody(-140, $data);

	}

	public function editPagelist()
	{

		$page_id       = request_int("page_id");
		$data          = $this->AdvPageSettingsModel->layoutColor();
		$data['items'] = $this->AdvPageSettingsModel->getOne($page_id);
		//导入编辑，添加模板
		$this->data->addBody(-140, $data);

	}

	public function addPagelist()
	{
		$data = $this->AdvPageSettingsModel->layoutColor();
		$this->data->addBody(-140, $data);
	}

	//添加楼层
	public function addPagerow()
	{
		$data['page_name']        = request_string('page_name'); // 页面名称
		$data['page_color']       = request_string('page_color'); //  页面风格颜色
		$data['layout_id']        = request_int('layout_id'); // 页面模板id
		$data['page_update_time'] = get_date_time(); // 时间
		$data['page_order']       = request_int('page_order'); // 页面排序
		$data['page_status']      = request_int('page_status'); // 页面状态
        $data['sub_site_id'] =  request_int("sub_site_id");
		$flag                     = $this->AdvPageSettingsModel->addPageSettings($data, TRUE);
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

		$this->data->addBody(-140, $data, $msg, $status);
	}

	//删除楼层
	public function delPagelist()
	{
		$page_id = request_int("page_id");
		//开启事物
		$this->messageModel->sql->startTransactionDb();
		$flag = $this->AdvPageSettingsModel->removePageSettings($page_id);

//          //同时删除他下面的模块和，广告

		$cond_row = array("page_id" => $page_id);
		$category = $this->AdvWidgetBaseModel->getByWhere($cond_row);
                if(!empty($category)){
		$widget_id = array_column($category, 'widget_id');
		$flag1     = $this->AdvWidgetBaseModel->removeWidgetBase($widget_id);

		$widget_id_array['widget_id:IN'] = $widget_id;
		$items_base                      = $this->AdvWidgetItemModel->getByWhere($widget_id_array);
		$items_id                        = array_column($items_base, 'item_id');
		$flag2                           = $this->AdvWidgetItemModel->removeWidgetItem($items_id);


                    if ($flag && $flag1 && $flag2 && $this->messageModel->sql->commitDb())
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
                }else{
                    
                        if ($flag && $this->messageModel->sql->commitDb())
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
		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}

	//添加楼层
	public function editPagerow()
	{
		$page_id                  = request_int("page_id");
		$data['page_name']        = request_string('page_name'); // 页面名称
		$data['page_color']       = request_string('page_color'); //  页面风格颜色
		$data['page_update_time'] = get_date_time(); // 时间
		$data['page_order']       = request_int('page_order'); // 页面排序
		$data['page_status']      = request_int('page_status'); // 页面状态
		$flag                     = $this->AdvPageSettingsModel->editPageSettings($page_id, $data);
		if ($flag !== false)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}
		//更新一下代码
		$re = $this->AdvPageSettingsModel->set_page_settings_html($page_id, "index");
		$this->data->addBody(-140, $data, $msg, $status);
	}


}

?>