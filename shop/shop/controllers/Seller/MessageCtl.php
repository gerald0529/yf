<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Seller_MessageCtl extends Seller_Controller
{
	public $messageModel         = null;
	public $userMessageModel     = null;
	public $userInfoModel        = null;
	public $messageTemplateModel = null;
	public $messageSettingModel  = null;
	public $articleBaseModel     = null;
    public $construct_res = array();
	
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
		$this->construct_res = parent::__construct($ctl, $met, $typ);
       
		$this->messageModel         = new MessageModel();
		$this->userMessageModel     = new User_MessageModel();
		$this->userInfoModel        = new User_InfoModel();
		$this->messageTemplateModel = new Message_TemplateModel();
		$this->messageSettingModel  = new Message_SettingModel();
		$this->articleBaseModel     = new Article_BaseModel();
		$this->shopCustomServiceModel     = new Shop_CustomServiceModel();
		$this->shopBaseModel     = new Shop_BaseModel();
	}

	/**
	 * 客服消息页面
	 *
	 * @access public
	 */
	public function index()
	{
		$shop_id = Perm::$shopId;
		$cond_row['shop_id'] = $shop_id;
		
		$data = $this->shopCustomServiceModel->getServiceList($cond_row);
		if($data['items']){
			foreach($data['items'] as $key => $val)
			{
				$data[$key]["tool"] = $val['tool'];
				$data[$key]["number"] = $val['number'];
				$data[$key]["name"] = $val['name'];
				$data[$key]["id"] = $val['id'];

				if($val['type']==1)
				{
					$de['after'][] = $data[$key];	
				}
				else
				{
					$de['pre'][] = $data[$key];
				}
			}
			$data = array();
			$data = $de;
			
		}
		$re= $this->shopBaseModel->getOne($shop_id);

		$data['shop_workingtime'] = $re['shop_workingtime'];

		if ("json" == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	/**
	 * 客服消息保存
	 *
	 * @access public
	 */
	public function editService()
	{
		$shop_id = Perm::$shopId;
		$prename     = request_row("prename");
		$pretool      = request_row("pretool");
		$prenum      = request_row("prenum");
		
		$aftername     = request_row("aftername");
		$aftertool      = request_row("aftertool");
		$afternum      = request_row("afternum");
		$shop_workingtime      = request_string("workingtime");
		
		$customer_service_array = array();
		if($prename)
		{
			foreach($prename as $key => $val)
			{
				if($val && $pretool[$key] && $prenum[$key])
				{
					$customer_service = array();
					$customer_service['shop_id'] = $shop_id;
					$customer_service['name'] = $val;
					$customer_service['tool'] = intval($pretool[$key]);
					$customer_service['number'] = $prenum[$key];
					$customer_service['type'] = '0';
					$customer_service_array[] = $customer_service;
				}
			}
		}
		if($aftername)
		{
			foreach($aftername as $key => $val)
			{
				if($val && $aftertool[$key] && $afternum[$key])
				{
					$customer_service = array();
					$customer_service['shop_id'] = $shop_id;
					$customer_service['name'] = $val;
					$customer_service['tool'] = $aftertool[$key];
					$customer_service['number'] = $afternum[$key];
					$customer_service['type'] = '1';
					$customer_service_array[] = $customer_service;
				}
			}
		}
		
		$cond_row                         = array();
		$cond_row['shop_id']              = $shop_id;

		$data = $this->shopCustomServiceModel->getServiceList($cond_row);
		
		$serviceId  = array();
		if($data['items']){
			$serviceId = array_column($data['items'], 'id');
		}
		//开启事物
		$rs_row = array();
		$this->shopCustomServiceModel->sql->startTransactionDb();
		
		if ($serviceId)
		{
			$flag = $this->shopCustomServiceModel->removeService($serviceId);
			check_rs($flag, $rs_row);
		}
		foreach($customer_service_array as $k=>$v){
			$aflag = $this->shopCustomServiceModel->addService($v);
			check_rs($aflag, $rs_row);
		}
		$shop['shop_workingtime'] = $shop_workingtime;
		
		$up_flag = $this->shopBaseModel->editBase($shop_id,$shop);
		check_rs($up_flag, $rs_row);
		
		$flag = is_ok($rs_row);
		if ($flag !== false && $this->shopCustomServiceModel->sql->commitDb())
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$this->shopCustomServiceModel->sql->rollBackDb();
			$status = 250;
			$msg    = __('failure');
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}
	/**
	 * 系统消息页面
	 *
	 * @access public
	 */
	public function message()
	{
		//$remind_cat = array("1" => "订单信息","2" => "退货消息","3" => "店铺信息","4" => "其他");
		
		//$type = request_int('type');

		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);

		$cond_row                    = array();
		$cond_row['message_user_id'] = Perm::$userId;
		$cond_row['message_mold']    = 1;

		/* if ($type)
		{
			$cond_row['message_type'] = $type;
		} */

		$data = $this->messageModel->getMessageList($cond_row, array('message_create_time' => 'DESC'), $page, $rows);

		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();
		
		if ("json" == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
		
	}

	/**
	 * 删除选择系统消息
	 *
	 * @access public
	 */
	public function delAllMessage()
	{
		$message_id_list = request_row("id");

		//开启事物
		$rs_row = array();
		$this->messageModel->sql->startTransactionDb();

		//删除选中的
		$flag = $this->messageModel->removeMessageSelected($message_id_list);
		check_rs($flag, $rs_row);
		
		$flag = is_ok($rs_row);
		if ($flag !== false && $this->messageModel->sql->commitDb())
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

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 * 商家查看选中的消息
	 *
	 * @access public
	 */
	public function look()
	{
		$message_id = request_row("id");
		
		$cond_row['message_islook'] = 1;
		
		$flag = $this->messageModel->editMessage($message_id, $cond_row);

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

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 公告页面
	 *
	 * @access public
	 */
	public function messageAnnouncement()
	{

		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);

		$order_row                 = array();
		$order_row['article_type'] = 1;

		$data = $this->articleBaseModel->getBaseAllList($order_row, array('article_add_time' => 'DESC'), $page, $rows);

		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();

		if ("json" == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	/**
	 * 消息设置页面
	 *
	 * @access public
	 */
	public function messageManage()
	{

		$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);

		$cond_row         = array();
		$cond_row['type'] = 2;
		$name = ['退款自动处理提醒', '退货自动处理提醒', '退货未收货自动处理提醒'];
		$cond_row['name:NOT IN'] = $name;
		$order_row = array();

		$data = $this->messageTemplateModel->getTemplateList($cond_row, $order_row, $page, $rows);
		
		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();


		if ("json" == $this->typ)
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}
	
	/**
	 * 商家设置消息接收
	 *
	 * @access public
	 */
	public function set()
	{
//		
//		$data = $this->messageTemplateModel->getOne($id);
//		if ("json" == $this->typ)
//		{
//			$this->data->addBody(-140, $data);
//		}
//		else
//		{
//			include $this->view->getView();
//		}
        $t_id = request_int("id");
        $user_id = Perm::$userId;
        $cond_row = array('user_id'=>$user_id);
        $data  = $this->messageSettingModel->getSettingDetail($cond_row);
        if(!$data){
            $data['message_template_all'] = '';
            $data['is_receive'] = 0;
            $data['t_id'] = $t_id;
        }else{
            $data['t_id'] = $t_id;
            $data['message_template_all'] = explode(',', $data['message_template_all']);
            if(in_array($t_id, $data['message_template_all'])){
                $data['is_receive'] = 1;
            }else{
                $data['is_receive'] = 0;
            }
        }
        if ("json" == $this->typ){
			$this->data->addBody(-140, $data);
		}else{
			include $this->view->getView();
		}
        
	}

	/**
	 * 设置站内信
	 *
	 * @access public
	 */
	public function setMessage()
	{
		$shop_id = Perm::$shopId;
        $user_id = Perm::$userId;
		$t_id      = request_int("id");
        $is_receive = request_int("is_receive");
		$cond_row                         = array();
        $cond_row['user_id']              = $user_id;
        $re = $this->messageSettingModel->getSettingDetail($cond_row);
        $cond_row['shop_id']              = $shop_id;
        $cond_row['setting_time'] = get_date_time();
        $template_id  = array();
        $flag = true;
        if ($re){
            //编辑
            $setting_id = $re['setting_id'];
            if($re['message_template_all']){
                $template_id = explode(',', $re['message_template_all']);
            }
            if(in_array($t_id, $template_id)){
                if($is_receive == 0){
                    //删除id
                    $new_template_id = array_diff($template_id, array($t_id));
                    $cond_row['message_template_all'] = implode(',', $new_template_id);
                    $flag = $this->messageSettingModel->editSetting($setting_id, $cond_row);
                }
            }else{
                if($is_receive == 1){
                    //添加id
                    $template_id[] = $t_id;
                    $cond_row['message_template_all'] = implode(',', $template_id);
                    $flag = $this->messageSettingModel->editSetting($setting_id, $cond_row);
                }
            }
        }else{
            //添加
            $cond_row['message_template_all'] = $t_id;
            $flag = $this->messageSettingModel->addSetting($cond_row);
        }
       
        if ($flag){
            $status = 200;
            $msg    = __('success');
        }else{
            $status = 250;
            $msg    = __('failure');
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);

    }
    
    /**
     * 卖家获取信息（卖家版app）
     * @return type
     */
    public function getSellerMsg(){
        $construct_res = $this->construct_res;
        if(isset($construct_res['status']) && $construct_res['status'] == 250){
            return $this->data->addBody(-140, array(), $construct_res['msg'], $construct_res['status']); 
        }
        $data = array();
        $id = trim(request_string('id'));
        if(!$id){
           return $this->data->addBody(-140, $data, __('请选择信息类型'),250); 
        }
        $id_array = explode(',', $id);
        $type = request_string('type');
        
        //获取模板id
        $user_id = Perm::$userId;
        $shop_id = Perm::$shopId;
        $template_where = array(
            'user_id' => $user_id,
            'shop_id' => $shop_id
        );
        $set_info = $this->messageSettingModel->getOneByWhere($template_where);
        if(!$set_info || !$set_info['message_template_all']){
            return $this->data->addBody(-140, $data, __('您未设置任何消息通知'),250);
        }
        
        $template_id = explode(',', $set_info['message_template_all']);
        $select_template_id = array();
        foreach ($id_array as $value){
            if(in_array($value, $template_id)){
                $select_template_id[] = $value;
            }
        }
        
        //获取模板信息
        $template_info = $this->messageTemplateModel->getByWhere(array('id:IN'=>$select_template_id));
        $template_name = array();
        foreach ($template_info as $val){
            $template_name[] = trim($val['name']);
        }
		$cond_row                    = array();
		$cond_row['message_user_id'] = Perm::$userId;
		$cond_row['message_mold']    = 1;
        $cond_row['message_title:IN'] = $template_name;

        //$type=count 获取数量
        if($type == 'count'){
            $count = $this->messageModel->getCount($cond_row);
            $unread_cond_row = $cond_row;
            $unread_cond_row['message_islook'] = 0;
            $unread_count =  $this->messageModel->getCount($unread_cond_row);
            $last_result = $this->messageModel->getOneByWhere($cond_row,array('message_create_time' => 'DESC'));
            $data['count'] = $count ? $count : 0;
            $data['unread_count'] = $unread_count ? $unread_count : 0;
            $data['time'] = $last_result['message_create_time'] ? $last_result['message_create_time'] : '';
            return $this->data->addBody(-140, $data, __('数据获取成功'),200);
        } else if($type == 'update') {
            $unread_cond_row['message_islook'] = 0;
            $unread_count =  $this->messageModel->getCount($unread_cond_row);

			$data['unread_count'] = $unread_count;

			$unread_data = $this->messageModel->getByWhere($cond_row, array(), 1, $unread_count);

            $message_id = array();
            foreach ($unread_data as $v){
                $message_id[] = $v['message_id'];
            }
            $flag = $this->messageModel->editMessage($message_id, array('message_islook'=>1));
            if($flag === false){
                return $this->data->addBody(-140, $data, __('数据更新失败'),250);
            } else {
                return $this->data->addBody(-140, $data, __('数据更新成功'),200);
            }
            
        } else {
            $pagesize = request_int('pagesize')?request_int('pagesize'):10;
            $page = request_int('page', 1);

            $data = $this->messageModel->getMessageList($cond_row, array('message_create_time' => 'DESC'), $page, $pagesize);
			$data['template'] = $template_name;

			return $this->data->addBody(-140, $data, __('数据获取成功'),200);
        }
		
    }
	
}

?>