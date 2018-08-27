<?php if (!defined('ROOT_PATH')) exit('No Permission');

/**
 * @author     yjj
 */
class Seller_Supplier_DistributorCtl extends Seller_Controller
{
    public $shopDistributorModel = null;
    public $shopDistributorLevelModel = null;

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
        $this->shopDistributorLevelModel = new Distribution_ShopDistributorLevelModel();
        //include $this->view->getView();
    }
    
    /*
	* 
	*分销商列表
	*
	*/
    public function index()
	{
    	
    	$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = 10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);
    	$shopBaseModel = new Shop_BaseModel();
    	
    	if(request_int('distributor_id'))
		{
    		$distributor_info = $this->shopDistributorModel->getOne(request_int('distributor_id'));
    		$distributor_shop_info = $shopBaseModel->getOneByWhere(array('shop_id'=>$distributor_info['distributor_id']));
			
			$shopGoodCatModel = new Shop_GoodCatModel();
			$cat_row['shop_id'] = Perm::$shopId;
    		$shop_cat  = $shopGoodCatModel->getGoodCatList($cat_row, array());
			
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
			
			$this->view->setMet('apply');
    	}else
		{
	    	$cond_row = array();
	    	$order_row = array();
	    	$cond_row['shop_id'] = Perm::$shopId;
	    	
			//审核状态
	    	if(request_int('state')==1)
			{
	    		$cond_row['distributor_enable'] =1; //审核通过
	    	}elseif(request_int('state')==2)
			{
	    		$cond_row['distributor_enable'] =0; //未审核
	    	}elseif(request_int('state')==3)
			{
	    		$cond_row['distributor_enable'] =-1;  //审核未通过
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
			
			//时间排序
	    	$order_row['shop_distributor_time']  = 'DESC';
	    	
			$data = $this->shopDistributorModel->listByWhere($cond_row, $order_row, $page, $rows);
	    	
	    	foreach($data['items'] as $key => $value) 
			{
				//获取分销商店铺信息、分销商等级折扣
	    		$shop_info = $shopBaseModel->getOneByWhere(array('shop_id'=>$value['distributor_id']));
				$level_info = $this->shopDistributorLevelModel->getOne($value['distributor_level_id']);
				
	    		$data['items'][$key]['user_name'] =  $shop_info['user_name']; //分销商名称
	    		$data['items'][$key]['mobile']     = $shop_info['shop_tel'];  //分销商联系方式
	    		$data['items'][$key]['shop_name'] = $shop_info['shop_name'];  //分销商店铺名称
				
				if($level_info)
				{
					$data['items'][$key]['level_name'] = $level_info['distributor_leve_name'];//分销等级名称
					$data['items'][$key]['level_rate'] = $level_info['distributor_leve_discount_rate'];//分销等级折扣
				}
	    	}
			
			//分销等级列表
			$level_list = $this->shopDistributorLevelModel->getByWhere(array('shop_id'=>Perm::$shopId));
			
	    	$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();
    	}
		
    	include $this->view->getView();
    }
    
	/*
	*
	*  分销商审核
	*
	*/
    public function edit_statu()
	{
    	$shop_distributor_id = request_int('shop_distributor_id');
    	
    	$MessageModel = new MessageModel();
		$ShopBaseModel = new Shop_BaseModel();
    	$shop_base       = $ShopBaseModel->getOne(Perm::$shopId);
    	
    	$shop_dist_base = $this->shopDistributorModel->getOne($shop_distributor_id);
    	$dist_shop_base   = $ShopBaseModel->getOne($shop_dist_base['distributor_id']);
    	
		$this->shopDistributorModel->sql->startTransactionDb();//开启事物
			
    	if(request_string('act') && request_string('act') == 'agree')
    	{
			$field_row['distributor_enable'] = 1;
			$field_row['distributor_cat_ids'] = $shop_dist_base['distributor_new_cat_ids']?:$shop_dist_base['distributor_cat_ids'];
			$field_row['distributor_new_cat_ids'] = '';	
    		$flag=$this->shopDistributorModel->editShopDistributor($shop_distributor_id,$field_row);
    		
    		//发送消息
			$MessageModel->sendMessage('dist apply statu',$dist_shop_base['user_id'], $dist_shop_base['user_name'], $order_id = NULL, $shop_name=null, 1, 1, $end_time = Null,$common_id=null);

    	}
    	elseif(request_string('act') && request_string('act') == 'del')
    	{
    		$flag=$this->shopDistributorModel->removeShopDistributor($shop_distributor_id);
    		
    		//删除分销商品
    		$goodsCommonModel   = new Goods_CommonModel();
    		$commons  =$goodsCommonModel -> getByWhere(array('shop_id'=>$dist_shop_base['shop_id'],'supply_shop_id'=>Perm::$shopId));
	    	
			if(!empty($commons))
			{	
	    		foreach ($commons as $key => $value) {
	    			$goodsCommonModel->removeCommon($value['common_id']);
	    			//发送消息
					$des = '供货商删除你的分销权限';
					$MessageModel->sendMessage('del goods',$dist_shop_base['user_id'], $dist_shop_base['user_name'], $order_id = NULL, $shop_name=null, 1, 1, $end_time = Null,$value['common_id'],$goods_id=null,$des);
	    		}
    		}

    	}
 
  		if($flag!==false && $this->shopDistributorModel->sql->commitDb())
		{
  			$status = 200;
  			$msg = __('success');
  		}else{
  			$this->shopDistributorModel->sql->rollBackDb();
  			$status = 250;
  			$msg = __('failure');
  		}
  		$data =array();
  		$this->data->addBody(-140, $data,$msg,$status);
    }
	
	/*
	*  编辑分销商分类
	*/
	function editDistributorCat()
	{
		$shop_id = Perm::$shopId;		
		$shop_distributor_id = request_string('shop_distributor_id');		
		$shop_cat = request_row('chk');
    	$shopGoodCatModel = new Shop_GoodCatModel();

		$supplier_row['distributor_cat_ids'] = implode(',',$shop_cat);
		$supplier_row['distributor_new_cat_ids'] = '';

		$flag = $this->shopDistributorModel->editShopDistributor($shop_distributor_id,$supplier_row);
		
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
    
	/*
	*
	*  增加分销商等级
	*
	*/
    public function add_shop_rate()
	{
    	$shop_distributor_id = request_int("shop_distributor_id");
    	$grade_id    = request_int('grade_id');
    	
		$field_row = array();
    	$field_row['distributor_level_id'] = $grade_id;
    	
    	$flag=$this->shopDistributorModel->editShopDistributor($shop_distributor_id,$field_row);
    	
    	
    	if($flag !== false)
		{
  			$status = 200;
  			$msg = __('success');
  		}else{
  			$status = 250;
  			$msg = __('failure');
  		}
  		$data =array();
  		$this->data->addBody(-140, $data,$msg,$status);
    }
    
    /*
	* 
	*不通过分销商申请，添加原因
	*
	*/
    public function apply_disagree()
	{
    	$shop_distributor_id = request_int("shop_distributor_id");
    	$reason = request_string("reason");
    	
    	$field_row = array();
    	$field_row['shop_distributor_reason'] = $reason;
    	$field_row['distributor_enable']   = -1;
    	
    	$flag = $this->shopDistributorModel->editShopDistributor($shop_distributor_id,$field_row);
    	
    	if($flag !== false)
		{
  			$status = 200;
  			$msg = __('success');
  		}else
		{
  			$status = 250;
  			$msg = __('failure');
  		}
		
  		$data =array();
    	$this->data->addBody(-140, $data,$msg,$status);
    }
    
    /*
	* 
	*分销商等级列表
	*
	*/
    public function setGrade()
	{ 
    	
    	$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = 10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);
 
    	$data = $this->shopDistributorLevelModel->listByWhere(array('shop_id'=>Perm::$shopId), array('distributor_leve_order'=>'ASC'), $page, $rows);
    	
    	$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();
    	
		include $this->view->getView();
    }
    
    /* 
	* 编辑等级
	*/
    public function addGrade()
	{
    	if(request_int('grade_id'))
		{
    		$grade_info = $this->shopDistributorLevelModel->getOne(request_int('grade_id'));
    	}
	    
	    include $this->view->getView();
    }
    
	/* 
	* 新增、编辑分销商等级
	*/
    public function editGrade()
	{
    	$grade_name = request_string('grade_name');
    	$grade_rate   = request_float('grade_rate');
    	$freeshipping = request_string('freeshipping');
    	$leve_order    =  request_int('leve_order');
    	$distributor_level_id = request_int("distributor_level_id")?request_int("distributor_level_id"):0;
 
    	$cond_row = array();
    	$cond_row['distributor_leve_name']            = $grade_name;
    	$cond_row['distributor_leve_discount_rate']  = $grade_rate;
    	$cond_row['distributor_leve_freeshipping']    =  $freeshipping;
    	$cond_row['distributor_leve_order']       = $leve_order;
    	$cond_row['shop_id']                      = Perm::$shopId;
		
    	if(request_string('act') && request_string('act') == "edit")
		{
    		$flag = $this->shopDistributorLevelModel->editShopDistributorLevel($distributor_level_id,$cond_row);
    	}else{
    		$flag = $this->shopDistributorLevelModel->addShopDistributorLevel($cond_row);
    	}
    	
    	if($flag !== false)
		{
  			$status = 200;
  			$msg = __('success');
  		}else{
  			$status = 250;
  			$msg = __('failure');
  		}
  		
		$data =array();
    	$this->data->addBody(-140, $data,$msg,$status);
    }
	
	/* 
	*  删除等级
	*/
    public function delGrade()
	{
    	$distributor_level_id = request_int('id');
    	$flag = $this->shopDistributorLevelModel->removeShopDistributorLevel($distributor_level_id);
    	
		if($flag !== false)
		{
  			$status = 200;
  			$msg = __('success');
  		}else{
  			$status = 250;
  			$msg = __('failure');
  		}
  		
		$data =array();
    	$this->data->addBody(-140, $data,$msg,$status);
    }
 
	/* 
	*  供货商查看某个分销商的分销业绩
	*/
    public function distributor_salenum()
	{
    	$Goods_CommonModel = new Goods_CommonModel();
    	
    	$Yf_Page           = new Yf_Page();
		$Yf_Page->listRows = 10;
		$rows              = $Yf_Page->listRows;
		$offset            = request_int('firstRow', 0);
		$page              = ceil_r($offset / $rows);
    	
		$cond_row = array();
    	$order_row = array();  	
    	$cond_row['supply_shop_id'] = Perm::$shopId; 
    	
    	if(request_int('distributor_id'))
		{
    		$dist_info = $this->shopDistributorModel->getOne(request_int('distributor_id'));
    		$cond_row['shop_id'] = $dist_info['distributor_id'];
    	}
    	
    	if(request_string('shop_name'))
		{
    		$cond_row['shop_name:LIKE']  = '%'.request_string('shop_name').'%';
    	}
    	
		if(request_string('goods_key'))
		{
    		$cond_row['common_name:LIKE']  = '%'.request_string('goods_key').'%';
    	}
 
    	$data = $Goods_CommonModel->listByWhere($cond_row, $order_row, $page, $rows);
    	$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();
 
		if($this->typ == 'json')
		{
			$this->data->addBody(-140, $data);
    	}else
		{
    		include $this->view->getView();
    	}	
    }
}    
