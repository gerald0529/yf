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
class Seller_Trade_WaybillCtl extends Seller_Controller
{
	public $logisticsWaybillModel = null;
	public $logisticsExpressModel = null;
	public $shopExpressModel      = null;

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

		$this->logisticsWaybillModel = new Waybill_TplModel();
		$this->logisticsExpressModel = new ExpressModel();
		$this->shopExpressModel      = new Shop_ExpressModel();
	}

	/**
	 * 模板绑定页面
	 *
	 */

	public function waybillManage()
	{
        $page_size = 10;
        $offset            = request_int('firstRow', 0);
		$default_shop_express_list = $this->shopExpressModel->getDefaultShopExpress();
        $total = count($default_shop_express_list);
        
        $default_shop_express = array();
        //假分页
        if($total > $page_size){
            if($total >= $offset ){
                
                $Yf_Page           = new Yf_Page();
                $Yf_Page->listRows = $page_size;
                $Yf_Page->totalRows = $total;
                $page_nav           = $Yf_Page->prompt();
                $i = 1;
                $j = 1;
                foreach ($default_shop_express_list as $value){
                    if($i >= $offset){
                        $default_shop_express[] = $value;
                        if($j >= $page_size){
                            break; 
                        }
                        $j ++ ;
                    }
                    $i ++ ;
                }
            }else{
               $default_shop_express = array(); 
            }
            
        }else{
            $default_shop_express = $default_shop_express_list;
        }
		include $this->view->getView();
	}

	/**
	 * 模板页面
	 *
	 */

	public function waybillIndex()
	{
        $Yf_Page           = new Yf_Page();
        $Yf_Page->listRows = 10;
        $rows              = $Yf_Page->listRows;
        $offset            = request_int('firstRow', 0);
        $page              = ceil_r($offset / $rows);
		$shop_express = $this->logisticsWaybillModel->getTplList(array(),array(),$page,$rows);
		$shop_express_list = $shop_express['items'];

        $Yf_Page->totalRows = $shop_express['totalsize'];
        $page_nav           = $Yf_Page->prompt();
		include $this->view->getView();
	}

	/*
	 * 模板绑定页面操作
	 * */
	public function operateByManage()
	{
		$action          = request_string('action');
		$user_express_id = request_int('user_express_id');

		if ($action == 'set_default')
		{
			//只能有一条数据为default
			$shop_express_search['user_is_default'] = Shop_ExpressModel::DEFAULT_TRUE;
			$shop_express_base                      = $this->shopExpressModel->getOneByWhere($shop_express_search);

			if (!empty($shop_express_base))
			{
				$u_express_id                           = $shop_express_base['user_express_id'];
				$shop_express_search['user_is_default'] = Shop_ExpressModel::DEFAULT_FALSE;
				$this->shopExpressModel->editExpress($u_express_id, $shop_express_search);
			}

			$shop_express_search['user_is_default'] = Shop_ExpressModel::DEFAULT_TRUE;
			$flag                                   = $this->shopExpressModel->editExpress($user_express_id, $shop_express_search);
		}
		else if ($action == 'unbind_tpl')
		{
			$flag = $this->shopExpressModel->editExpress($user_express_id, array('waybill_tpl_id' => 0));
		}

		if (!empty($flag))
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$this->data->addBody(-140, array(), $msg, $status);

	}

	/**
	 * 模板绑定
	 * @access public
	 */
	public function waybillBind()
	{
		$typ             = request_string('typ');
		$express_id      = request_int('express_id');
		$user_express_id = request_int('user_express_id');

		if ($typ == 'e')
		{
			$waybill_search['express_id'] 		  = $express_id;
			$waybill_search['waybill_tpl_enable'] = Waybill_TplModel::ENABLE_TRUE;
			$waybill_search['shop_id:IN'] = array( Perm::$shopId, 0 );	//读取后台配置的模板
			$waybill_data                 = $this->logisticsWaybillModel->getTplList($waybill_search);
			$waybill_data                 = $waybill_data['items'];
			include $this->view->getView();
		}
		else
		{
			$waybill_tpl_id                      = request_int('waybill_tpl_id');
			$shop_express_data['waybill_tpl_id'] = $waybill_tpl_id;

			$flag = $this->shopExpressModel->editExpress($user_express_id, $shop_express_data);
			if ($flag)
			{
				$msg    = __('success');
				$status = 200;
			}
			else
			{
				$msg    = __('failure');
				$status = 250;
			}
			$this->data->addBody(-140, array(), $msg, $status);
		}

	}

	/**
	 * 模板设置
	 * @access public
	 */
	public function waybillSetting()
	{
		$typ             = request_string('typ');
		$user_express_id = request_int('user_express_id');

		if ($typ == 'e')
		{
			$waybill_tpl_id = request_int('waybill_tpl_id');

			//初始化
			/*$waybill_data = $this->logisticsWaybillModel->getTpl($waybill_tpl_id);
			$waybill_data = pos($waybill_data);*/
			$shop_express_data = $this->shopExpressModel->getExpress($user_express_id);
			$shop_express_data = pos($shop_express_data);

			include $this->view->getView();
		}
		else
		{
			$data = request_row('data');

			$express_data['user_tpl_item'] = array_keys($data);
			$express_data['user_tpl_top']  = request_int('store_waybill_top');
			$express_data['user_tpl_left'] = request_int('store_waybill_left');

			$flag = $this->shopExpressModel->editExpress($user_express_id, $express_data);

			if ($flag)
			{
				$msg    = __('success');
				$status = 200;
			}
			else
			{
				$msg    = __('failure');
				$status = 250;
			}
			$this->data->addBody(-140, array(), $msg, $status);
		}
	}

	/**
	 * 删除模板
	 * @access public
	 */
	public function removeTpl()
	{
		$waybill_tpl_id = request_int('waybill_tpl_id');

		$flag = $this->logisticsWaybillModel->removeTpl($waybill_tpl_id);

		if ($flag)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}
		$this->data->addBody(-140, array(), $msg, $status);
	}

	/**
	 * 添加模板
	 * @access public
	 */
	public function addTpl()
	{
		$typ = request_string('typ');

		if ($typ == 'e')
		{
			//取出所有的物流公司
			$express_data = $this->logisticsExpressModel->getExpressList();
			$express_data = $express_data['items'];

			$this->view->setMet('waybillAdd');
			include $this->view->getView();
		}
		else
		{
			$waybill_data['shop_id']            = Perm::$shopId;
			$waybill_data['waybill_tpl_name']   = request_string('waybill_name');
			$waybill_data['express_id']         = request_int('waybill_express');
			$waybill_data['waybill_tpl_width']  = request_int('waybill_width');
			$waybill_data['waybill_tpl_height'] = request_int('waybill_height');
			$waybill_data['waybill_tpl_top']    = request_int('waybill_top');
			$waybill_data['waybill_tpl_left']   = request_int('waybill_left');
			$waybill_data['waybill_tpl_image']  = request_string('waybill_image');
			$waybill_data['waybill_tpl_enable'] = request_int('waybill_usable');

			$flag = $this->logisticsWaybillModel->addTpl($waybill_data, true);

			if ($flag)
			{
				$msg    = __('success');
				$status = 200;
			}
			else
			{
				$msg    = __('failure');
				$status = 250;
			}
			$this->data->addBody(-140, array(), $msg, $status);
		}
	}

	/**
	 * 修改模板
	 * @access public
	 */
	public function editTpl()
	{

		$typ            = request_string('typ');
		$waybill_tpl_id = request_int('waybill_tpl_id');

		if ($typ == 'e')
		{
			if (!empty($waybill_tpl_id))
			{
				$waybill_data = $this->logisticsWaybillModel->getTpl($waybill_tpl_id);
				$waybill_data = pos($waybill_data);
			}

			//取出所有的物流公司
			$express_data = $this->logisticsExpressModel->getExpressList();
			$express_data = $express_data['items'];

			$this->view->setMet('waybillAdd');
			include $this->view->getView();
		}
		else
		{
			$waybill_data['shop_id']            = Perm::$shopId;
			$waybill_data['waybill_tpl_name']   = request_string('waybill_name');
			$waybill_data['express_id']         = request_int('waybill_express');
			$waybill_data['waybill_tpl_width']  = request_int('waybill_width');
			$waybill_data['waybill_tpl_height'] = request_int('waybill_height');
			$waybill_data['waybill_tpl_top']    = request_int('waybill_top');
			$waybill_data['waybill_tpl_left']   = request_int('waybill_left');
			$waybill_data['waybill_tpl_image']  = request_string('waybill_image');
			$waybill_data['waybill_tpl_enable'] = request_int('waybill_usable');

			$flag = $this->logisticsWaybillModel->editTpl($waybill_tpl_id, $waybill_data);

			if ($flag)
			{
				$msg    = __('success');
				$status = 200;
			}
			else
			{
				$msg    = __('failure');
				$status = 250;
			}
			$this->data->addBody(-140, array(), $msg, $status);
		}

	}

	/**
	 * 测试模板
	 * @access public
	 */
	public function testTpl()
	{
		$waybill_tpl_id = request_int('waybill_tpl_id');
		$waybill_data   = $this->logisticsWaybillModel->getTpl($waybill_tpl_id);
		$waybill_data   = pos($waybill_data);

		$this->view->setMet('waybillTest');
		include $this->view->getView();
	}

	/**
	 * 打印运单
	 * @access public
	 */
	public function printTpl()
	{
		$order_id       = request_string('order_id');
		$waybill_tpl_id = request_int('waybill_tpl_id');

		$result_data = $this->logisticsWaybillModel->createPrintData($order_id, $waybill_tpl_id);
	 	$waybill_tpl_id =  $result_data['waybill_data']['waybill_tpl_id'];

		$data         = $result_data['data'];
		$waybill_data = $result_data['waybill_data'];

		$this->view->setMet('waybillTest');
		include $this->view->getView();
	}

	/**
	 * 设计运单
	 * @access public
	 */
	public function designTpl()
	{
		$typ            = request_string('typ');
		$waybill_tpl_id = request_int('waybill_tpl_id');

		if ($typ == 'e')
		{
			$waybill_tpl_id = request_int('waybill_tpl_id');
			$waybill_data   = $this->logisticsWaybillModel->getTpl($waybill_tpl_id);
			$waybill_data   = pos($waybill_data);

			$this->view->setMet('waybillDesign');
			include $this->view->getView();
		}
		else
		{
			$waybill_tpl_item = array();

			$waybill_data = request_row('waybill_data');

			foreach ($waybill_data as $key => $val)
			{
				if (!empty($val['check']) && $val['check'] == 'on')
				{
					unset($val['check']);
					$waybill_tpl_item[$key] = $val;
				}
			}

			$waybill_edit_data['waybill_tpl_item'] = $waybill_tpl_item;
			$flag                                  = $this->logisticsWaybillModel->editTpl($waybill_tpl_id, $waybill_edit_data);

			if ($flag)
			{
				$msg    = __('success');
				$status = 200;
			}
			else
			{
				$msg    = __('failure');
				$status = 250;
			}

			$this->data->addBody(-140, array(), $msg, $status);
		}

	}
}

?>