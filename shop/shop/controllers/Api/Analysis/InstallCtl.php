<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Analysis_InstallCtl  extends Api_Controller
{

	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
        
	}
    
    /**
     *  开启统计时，会员初始化查询
     */
	public function memberlist()
	{
        $page = request_int('page', 1);
		$rows = request_int('rows', 1000);
        $userInfoModel     = new User_InfoModel();
        $userBaseModel     = new User_BaseModel();
		$user_base_data = $userBaseModel->listByWhere(array(), array('user_id'=>'asc'), $page, $rows);
        $data_group = array();
        if(isset($user_base_data['items']) && $user_base_data['items']){
            $user_ids = array();
            $user_accounts =array();
            foreach ($user_base_data['items'] as $value1){
                $user_ids[] = $value1['user_id'];
                $user_accounts[$value1['user_id']] = $value1['user_account'];
            }
            $cond_row = array();
            $cond_row['user_id:IN'] = $user_ids;
            $user_info_data = $userInfoModel->listByWhere($cond_row, array('user_id'=>'asc'), $page, $rows);
            $data = array();
            $i = 1;
            foreach ($user_info_data['items'] as $key=>$value2){
                if(!isset($user_accounts[$value2['user_id']])){
                    continue;
                }
                $data[$key]['user_id'] = $value2['user_id'];
                $data[$key]['user_account'] = $user_accounts[$value2['user_id']];
                $data[$key]['ip'] = $value2['user_ip'];
                $data[$key]['date'] = $value2['user_regtime'];
                $data[$key]['sex'] = $value2['user_sex'];
                $data[$key]['area'] = $value2['user_area'];
                if($key != 0  && $key%100 == 0){
                    $i ++;
                }
                $data_group['data'][$i][] = $data[$key];
            }
        }
        $data_group['records'] = $user_base_data['records'];
		$this->data->addBody(-140, $data_group);
	}
    
    /**
     *  开启统计时，店铺初始化查询
     */
	public function shoplist()
	{
        $page = request_int('page', 1);
		$rows = request_int('rows', 1000);

		$cond_row = array(
			"shop_self_support" => "false"
		);
        $cond_row["shop_status:in"]=  array("0","3");
		
		$cond_row['shop_type'] = 1; //非供应商店铺

        $shopBaseModel = new Shop_BaseModel();
        $data = $shopBaseModel->listByWhere($cond_row, array(),$page, $rows);
        if(isset($data['items'])){
            $ids = array();
            $analytics = array();
            $data_group = array();
            foreach ($data['items'] as $key => $value){
                $ids[] = $value['shop_id'];
                $analytics[$key]['shop_id'] = $value['shop_id'];
                $analytics[$key]['shop_name'] = $value['shop_name'];
                $analytics[$key]['date'] = $value['shop_create_time'];
            }
            $shop_company_model =  new Shop_CompanyModel();
            $shop_company_data = $shop_company_model->listByWhere(array('shop_id:IN'=>$ids));
            $address_ids = array();
            if(isset($shop_company_data['items'])){
                foreach ($shop_company_data['items'] as $val){
                    $address_ids[$val['shop_id']]['area'] = $val['shop_company_address'];
                }
            }
            $i = 1;
            $data_group['records'] = $data['records'];
            foreach ($analytics as $k => $v){
                if(isset($address_ids[$v['shop_id']]['area'])){
                    $analytics[$k]['area'] = $address_ids[$v['shop_id']]['area'];
                }else{
                    $analytics[$k]['area'] = '';
                }
                if($k != 0  && $k%100 == 0){
                    $i ++;
                }
                $data_group['data'][$i][] = $analytics[$k];
            }
        }
        
        if($data){
            $status = 200;
            $msg    = __('success');
        }else{
            $status = 250;
            $msg    = __('没有数据');;
        }
        $this->data->addBody(-140, $data_group, $msg, $status);
    }
    
    /**
     *  开启统计时，店铺初始化查询
     */
    public function goodslist(){
        $page = request_int('page', 1);
		$rows = request_int('rows', 1000);

        $Goods_Base = new Goods_Base();
        $data              = $Goods_Base->listByWhere(array(), array(), $page, $rows);
        if(isset($data['items'])){
            $ids = array();
            $analytics = array();
            $data_group = array();
            $data_group['records'] = $data['records'];
            foreach ($data['items'] as $key => $value){
                $ids[] = $value['common_id'];
                $analytics[$key]['goods_id'] = $value['goods_id'];
                $analytics[$key]['shop_id'] = $value['shop_id'];
                $analytics[$key]['common_id'] = $value['common_id'];
                $analytics[$key]['name'] = $value['goods_name'];
                $analytics[$key]['price'] = $value['goods_price'];
                $analytics[$key]['salenum'] = $value['goods_salenum'];
                $analytics[$key]['image'] = $value['goods_image'];
                $analytics[$key]['state'] = $value['goods_is_shelves'];
                $analytics[$key]['type'] = $value['cat_id'];
                $goods_spec = $value['goods_spec'] ? $value['goods_spec'] : array();
                if($goods_spec){
                    $goods_spec = array_shift($goods_spec);
                }
                $analytics[$key]['spec'] = implode(',', $goods_spec);
            }
            $ids = array_unique($ids);
            $common_model =  new Goods_CommonModel();
            $common_data = $common_model->listByWhere(array('common_id:IN'=>$ids));
            $date_ids = array();
            if(isset($common_data['items'])){
                foreach ($common_data['items'] as $val){
                    $date_ids[$val['common_id']]['common_add_time'] = $val['common_add_time'];
                }
            }
            $i = 1;
            foreach ($analytics as $k => $v){
                if(isset($date_ids[$v['common_id']]['common_add_time'])){
                    $analytics[$k]['date'] = $date_ids[$v['common_id']]['common_add_time'];
                }
                if($k != 0  && $k%50 == 0){
                    $i ++;
                }
                $data_group['data'][$i][] = $analytics[$k];
            }
        }
        if ($data){
            $status = 200;
            $msg    = __('success');
        } else {
            $status = 250;
            $msg    = __('没有满足条件的结果哦');
        }
        $this->data->addBody(-140, $data_group, $msg, $status);
    }
}

?>