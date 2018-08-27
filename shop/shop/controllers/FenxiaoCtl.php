<?php

/**
 *  新分销
 *  @author Str <tech40@yuanfeng021.com>
 */
class FenxiaoCtl extends Controller
{
    public $fenxiaoModel;

    public function __construct(&$ctl, $met, $typ)
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL & ~(E_STRICT | E_NOTICE));
        parent::__construct($ctl, $met, $typ);

        $this->fenxiaoModel = new Fenxiao;
    }


    public function getGoodsValues()
    {
        $cat_id = request_string('cat_id');
        $common_id = request_string('common_id');
        $values = $this->fenxiaoModel->getLatestGoodsValues($cat_id, $common_id);

        $this->data->addBody(-140, ['values'=> $values]);
    }

    public function test()
    {
        $values = $this->fenxiaoModel->cancelOrder(2934);
    }
    
    /**
     * 分销中心 -- 佣金记录
     */
    public function FenxiaoCommission()
    {
        $user_id = Perm::$userId;
        
		$data = $this->fenxiaoModel->getFenxiaoCommission($user_id);
        $data['search_status'] = request_string('status');
        if ($this->typ == "json") {
			$this->data->addBody(-140, $data);
		} else {
			include $this->view->getView();
		}
    }
    
    /**
     * 分销中心 -- 我的推广
     */
    public function FenxiaoList()
    {
        $user_id = Perm::$userId;
        
		$data = $this->fenxiaoModel->getFenxiaoList($user_id);
        
        if ($this->typ == "json") {
			$this->data->addBody(-140, $data);
		} else {
			include $this->view->getView();
		}
    }
    
    /**
     * 分销中心 -- 我的业绩
     */
    public function FenxiaoOrder(){
        
        $user_id = Perm::$userId;
        $data = $this->fenxiaoModel->getFenxiaoOrder($user_id);
        
        if ($this->typ == "json") {
			$this->data->addBody(-140, $data);
		} else {
			include $this->view->getView();
		}
    }
    
    /*
	* 分销中心-WAP端
	*/
	public function wapIndex()
	{
		$userId = Perm::$userId;
		$User_InfoModel = new User_InfoModel();
		$data = $User_InfoModel->getOne($userId);
        $level_user_id = $this->fenxiaoModel->getFenxiaoUserId($userId);
		//获取推广用户
        $data['invitors']  = count($level_user_id['level_1']);
		//用户推广订单数
		$data['promotion_order_nums'] = $this->fenxiaoModel->getFenxiaoOrderCount($userId);
		//计算佣金
        $user_fenxiao_commission = $this->fenxiaoModel->getUserSumCommission($userId);
        $data['user_fenxiao_commission'] = !$user_fenxiao_commission[$userId] ? 0 : $user_fenxiao_commission[$userId];
        
		$shopBaseModel = new Shop_BaseModel();
		$shop_base = $shopBaseModel->getBaseOneList(array('shop_id'=>Perm::$shopId));
		$data['shop_name'] = $shop_base['shop_name'] ? $shop_base['shop_name'] : '';
        $data['shop_id'] = Perm::$shopId;

		if ($this->typ == "json")
		{
			$this->data->addBody(-140, $data);
		} else {
			include $this->view->getView();
		}
	}
}