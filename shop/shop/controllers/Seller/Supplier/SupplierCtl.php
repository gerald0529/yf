<?php if (!defined('ROOT_PATH')) exit('No Permission');

/**
 * @author     yjj
 */
class Seller_Supplier_SupplierCtl extends Seller_Controller
{
    public $shopDistributorModel = null;

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
        $this->shopDistributorModel = new Distribution_ShopDistributorModel();
        //include $this->view->getView();
    }
    
	//供应商
    public function index()
	{
    	$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = 10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);
    	
		$shopBaseModel = new Shop_BaseModel();
    	
    	$cond_row  = array();
    	$order_row = array();
 
		//审核状态
		if(request_int('state')==1)
		{
			$cond_row['distributor_enable'] =1; //审核通过
		}elseif(request_int('state')==2)
		{
			$cond_row['distributor_enable'] = 0;  //未审核
		}elseif(request_int('state')==3)
		{
			$cond_row['distributor_enable'] =-1; //审核未通过
		}
 
		//开始时间
    	if(request_string('start_date'))
		{
    		$cond_row['shop_distributor_time:>'] = request_string('start_date');
    	}
		
		//结束时间
    	if(request_string('end_date'))
		{
    		$cond_row['shop_distributor_time:<'] = request_string('end_date');
    	}
 
    	$cond_row['distributor_id'] = Perm::$shopId;
    	$data = $this->shopDistributorModel->listByWhere($cond_row, $order_row, $page, $rows);
    	
    	foreach ($data['items'] as $key => $value)
		{
    		$shop_info = $shopBaseModel->getOneByWhere(array('shop_id'=>$value['shop_id']));
    		$data['items'][$key]['user_name'] =  $shop_info['user_name'];
    		$data['items'][$key]['mobile']     = $shop_info['shop_tel'];
    		$data['items'][$key]['shop_name'] = $shop_info['shop_name'];
    	}
    	
    	$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();
    	
    	include $this->view->getView();
    }
    
	
	//更改状态
    public function edit_statu()
	{
    	$shop_distributor_id = request_int('shop_distributor_id');
    	
		if(request_string('act') && request_string('act') =="again")
		{	
			//再次申请
			$edit_row['distributor_enable'] = 0;
			$flag=$this->shopDistributorModel->editShopDistributor($shop_distributor_id,$edit_row);
			
    	}elseif(request_string('act') && request_string('act') =="del")
		{
			//删除
    		$supplier_base  = $this->shopDistributorModel->getOne($shop_distributor_id);
    		$flag=$this->shopDistributorModel->removeShopDistributor($shop_distributor_id);
    		
    		//删除分销商品
    		$goodsCommonModel   = new Goods_CommonModel();
    		$commons  =$goodsCommonModel -> getByWhere(array('shop_id'=>Perm::$shopId,'supply_shop_id'=>$supplier_base['shop_id']));
    		
			if(!empty($commons))
			{
	    		foreach ($commons as $key => $value) 
				{
	    			$goodsCommonModel->removeCommon($value['common_id']);
	    		}
    		}
    	}	
  		
		if($flag !== false)
		{
  			$status = 200;
  			$msg = __('success');
  		}else
		{
  			$status = 250;
  			$msg = __('failure');
  		}
  		$data = array();
  		$this->data->addBody(-140, $data,$msg,$status);
    }
    
	//申请
    public function apply()
	{
    	$shop_id = request_string('shop_id');
    	$shopGoodCatModel = new Shop_GoodCatModel();
    	$shopBaseModel = new Shop_BaseModel();
    	
    	if(request_string('shop_distributor_id'))
		{
    		$distributor_info = $this->shopDistributorModel->getOne(request_string('shop_distributor_id'));
    		$distributor_cat = explode(',',$distributor_info['distributor_cat_ids']);
			$distributor_new_cat = explode(',',$distributor_info['distributor_new_cat_ids']);
			$distributor_cat_name = array();
			$distributor_new_cat_name = array();
			
			if($distributor_cat)
			{
				foreach($distributor_cat as $k=>$v)
				{
					$temp = $shopGoodCatModel->getOneByWhere(array('shop_goods_cat_id'=>$v));
					if($temp)
					{
						$distributor_cat_name[] = $temp['shop_goods_cat_name'];
					}					
				}
				
			}
			$distributor_cat_name = implode(',',$distributor_cat_name);
			
			if($distributor_new_cat)
			{
				foreach($distributor_new_cat as $k=>$v)
				{
					$temp = $shopGoodCatModel->getOneByWhere(array('shop_goods_cat_id'=>$v));
					if($temp)
					{
						$distributor_new_cat_name[] = $temp['shop_goods_cat_name'];
					}				
				}
				
			}
			$distributor_new_cat_name = implode(',',$distributor_new_cat_name);
 
    		$shop_id = $distributor_info['shop_id'];
    	}
		
    	$cat_row = array();
    	$shop_cat=array();
		$shop_info = array();
    	
		if($shop_id)
		{
    		$shop_info = $shopBaseModel->getOneByWhere(array('shop_id'=>$shop_id));
    		$cat_row['shop_id'] = $shop_id;
    		$shop_cat  = $shopGoodCatModel->getGoodCatList($cat_row, array());
    	}
		
    	include $this->view->getView();
		
    }
    
	//添加
    public function addSupplier()
	{
    	$shop_id = request_int('shop_id');
    	$shop_cat = request_row('chk');
    	$shopGoodCatModel = new Shop_GoodCatModel();
 		
 		/* if(!empty($shop_cat))
		{
 			foreach ($shop_cat as $key => $value) 
			{
 				$cat_info = $shopGoodCatModel->getOneByWhere(array('shop_goods_cat_id'=>$value));
 				$cat_name [] = $cat_info['shop_goods_cat_name'];
 			}
 		} */
		$cat_ids = implode(',',$shop_cat);
 		
 		$supplier_row = array();
 		$supplier_row['shop_id'] =  $shop_id;
 		$supplier_row['distributor_id'] = Perm::$shopId;
 		$supplier_row['shop_distributor_time'] = get_date_time();
 		
		if(request_string('act') && request_string('act') == "edit")
		{
 			$supplier_row['distributor_new_cat_ids'] = $cat_ids;
			$shop_distributor_id = request_string('shop_distributor_id');
 			$flag = $this->shopDistributorModel->editShopDistributor($shop_distributor_id,$supplier_row);
			
 		}else
		{
			$supplier_row['distributor_enable'] = 0;
			$supplier_row['distributor_new_cat_ids'] = $cat_ids;
 			$flag = $this->shopDistributorModel->addShopDistributor($supplier_row);
 		}
 		
 		if($flag !== false)
		{
 			$status = 200;
 			$msg  = __('success');
			
 		}else{
	    	$status = 250;
	    	$msg = __('failure');
			
    	}
    	$data = array();
    	$this->data->addBody(-140, $data,$msg,$status);
    }
}    