<?php

/**
 * 统计插件
 *
 *
 *
 * @category   Framework
 * @package    Analytics
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 *
 */
class Plugin_Analytics implements Yf_Plugin_Interface
{
	function __construct()
	{
		//注册这个插件
		//第一个参数是钩子的名称
		//第二个参数是pluginManager的引用
		//第三个是插件所执行的方法
        
        //关键字
		Yf_Plugin_Manager::getInstance()->register('analyticsKeywords', $this, 'keywords');
        //注册会员
        Yf_Plugin_Manager::getInstance()->register('analyticsMemberAdd', $this, 'memberAdd');
        //入驻商家
        Yf_Plugin_Manager::getInstance()->register('analyticsShopAdd', $this, 'shopAdd');
        //商家信誉
        Yf_Plugin_Manager::getInstance()->register('analyticsShopCredit', $this, 'shopCredit');
        //添加订单
        Yf_Plugin_Manager::getInstance()->register('analyticsOrderAdd', $this, 'orderAdd');
        //更新订单
        Yf_Plugin_Manager::getInstance()->register('analyticsUpdateOrderStatus', $this, 'updateOrderStatus');
        //添加商品
        Yf_Plugin_Manager::getInstance()->register('analyticsGoodsAdd', $this, 'goodsAdd');
        //更新更商品
        Yf_Plugin_Manager::getInstance()->register('analyticsGoodsEdit', $this, 'goodsEdit');
        //uv
        Yf_Plugin_Manager::getInstance()->register('analyticsUvCount', $this, 'uvCount');
        //商品收藏
        Yf_Plugin_Manager::getInstance()->register('analyticsProductCollect', $this, 'productCollect');
        //商品取消收藏
        Yf_Plugin_Manager::getInstance()->register('analyticsProductCancleCollect', $this, 'productCancleCollect');
        //商品评分
        Yf_Plugin_Manager::getInstance()->register('analyticsScore', $this, 'Score');
        //店铺收藏
        Yf_Plugin_Manager::getInstance()->register('analyticsShopCollect', $this, 'shopCollect');
        //店铺取消收藏
        Yf_Plugin_Manager::getInstance()->register('analyticsShopCancleCollect', $this, 'shopCancleCollect');
        
	}

	public static function desc()
	{
		return '统计功能.';
	}

    /**
     * 发送数据
     * 写入队列
     */
	private function _sendData($key,$url,$formvars)
	{
        //使用队列
        //$data
        //Yf_Queue::send($queue, $data);
        //暂时使用实时发送
        $init_rs = get_url_with_encrypt($key, $url, $formvars);
        return $init_rs;
	}
    
    
    //关键字搜索
    public function keywords($keywords){
        //本地同步远程信息
        if($keywords === '' ){
            return false;
        }
        $formvars = array();
        foreach($keywords as $key=>$value)
        {
            $formvars[$key] = $value;
        }
        $formvars['app_id']    = Yf_Registry::get('analytics_app_id');
        $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=keywords&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
        return $init_rs;
    }

    /**
     * 注册会员统计
     * @param array $formvars
     * @return boolean
     */
    public function memberAdd($formvars = array()){
        if(!$formvars){
            return false;
        }
        $formvars['app_id']    = Yf_Registry::get('analytics_app_id');
        $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=memberAdd&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
        return $init_rs;
    }

    /**
     *
     * @param array $formvars
     * @return boolean
     */
    public function shopAdd($formvars = array()){
        if(!$formvars){
            return false;
        }
        $formvars['app_id']    = Yf_Registry::get('analytics_app_id');
        $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=shopAdd&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
        return $init_rs;

    }

//    public function shopName($formvars = array()){
//        if(!$formvars){
//            return false;
//        }
//        $formvars['app_id']    = Yf_Registry::get('analytics_app_id');
//        $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=shopUpdate&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
//        return $init_rs;
//
//    }

    public function shopCredit($formvars = array()){
        if(!$formvars){
            return false;
        }
        //计算店铺动态评分
        $Shop_EvaluationModel = new Shop_EvaluationModel();
        $shop_evaluation      = $Shop_EvaluationModel->getByWhere(array('shop_id' => $formvars['shop_id']));
        $evaluation_num       = count($shop_evaluation);

        $desc_scores    = 0;    //描述相符评分
        $service_scores = 0;    //服务态度评分
        $send_scores    = 0;   //发货速度评分
        foreach ($shop_evaluation as $val){
            $desc_scores += $val['evaluation_desccredit'];
            $service_scores += $val['evaluation_servicecredit'];
            $send_scores += $val['evaluation_deliverycredit'];
        }
        if ($evaluation_num){
            $shop_desc_scores    = round($desc_scores / $evaluation_num, 2);
            $shop_service_scores = round($service_scores / $evaluation_num, 2);
            $shop_send_scores    = round($send_scores / $evaluation_num, 2);
        }else{
            $shop_desc_scores    = 5;
            $shop_service_scores = 5;
            $shop_send_scores    = 5;
        }
        $formvars['shop_desc_scores'] = $shop_desc_scores;
        $formvars['shop_service_scores'] = $shop_service_scores;
        $formvars['shop_send_scores'] = $shop_send_scores;
        $formvars['shop_credit'] = ($shop_send_scores + $shop_service_scores + $shop_send_scores)/3;
        $formvars['app_id']    = Yf_Registry::get('analytics_app_id');

        $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=shopUpdate&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
        return $init_rs;
    }

    public function orderAdd($data = array()){
        if(!isset($data['order_id']) || !$data['order_id']){
            return false;
        }
        if($data['type'] == 3){
            if(isset($data['chain_id'])){
                //查询自提店铺的地址信息
                $chain_model = new Chain_BaseModel();
                $chain_info = $chain_model->getOne($data['chain_id']);
                $data['addr'] = $chain_info['chain_province'].' '.$chain_info['chain_city'];
            }else{
                return false;
            }
        }
        $analytics_data = array(
            'union_order_id'=>$data['union_order_id'],
            'ip'=>$data['ip'],
            'addr'=>$data['addr'],
            'buyer_id'=>$data['user_id'],
            'orderinfo'=>array()
        );
        $order_ids = trim($data['order_id'],',');    
        $order_id_array = explode(',', $order_ids);
        foreach ($order_id_array as $key=>$value){
            if(!$value){
                continue;
            }
            $order_goods_model = new Order_GoodsModel();
            $order_goods = $order_goods_model->getGoodsListByOrderId($value);
            if(!isset($order_goods['items']) || !$order_goods['items']){
                continue;
            }
            $order_info = array();
            foreach ($order_goods['items'] as $k=>$v){
                $order_info[$value]['order_id'] = $v['order_id'];
                $order_info[$value]['shop_id'] = $v['shop_id'];
                $order_info[$value]['date'] = date('Y-m-d H:i:s');
                if(!isset($order_info[$value]['source'])){
                    $order_info[$value]['source'] = $this->getUserAgent();
                }
                $order_info[$value]['shop_name'] = '';
                if(isset($order_info[$value]['order_price'])){
                    $order_info[$value]['order_price'] += $v['order_goods_amount'];
                }else{
                    $order_info[$value]['order_price'] = $v['order_goods_amount'];
                }
                $order_info[$value]['goodsinfo'][$k] = array(
                    'goods_id'=>$v['goods_id'],
                    'num'=>$v['order_goods_num'],
                    'price'=>$v['order_goods_payment_amount']
                );
                
            }
            foreach ($order_info as $info){
                $analytics_data['orderinfo'][] = $info;
            }
        }
        $analytics_data['app_id']    = Yf_Registry::get('analytics_app_id');
        $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=orderAdd&typ=json', Yf_Registry::get('analytics_api_url')), $analytics_data);
        return $init_rs;
    }
    
    /**
     * 获取来源信息
     * @return string
     */
    public function getUserAgent(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/ipad/i',$agent)){
            return 'ipad';
        }else if(preg_match('/iphone\s*os/i',$agent)){
            return 'iphone';
        }else if(preg_match('/android|wp7|wp8|wp9|wp10|wp11|surface|nokia|sumsang/i',$agent)){
            return  'android';
        }else if(preg_match('/wbxml|wml/i',$_SERVER['HTTP_ACCEPT'])){
            return 'wap';
        }else{
            return 'PC';
        }
    }

    //发布商品
    public function goodsAdd($Goods){
        //本地同步远程信息
        if($Goods === '' ){
            return false;
        }
        $Goods_BaseModel = new Goods_BaseModel();
        $Goods_CatModel = new Goods_CatModel();
        $goods_ids = $Goods_BaseModel->getByWhere(array('common_id' => $Goods['common_id']));
        if (is_array($goods_ids) && $goods_ids)
        {
            foreach($goods_ids as $key=>$goods_base)
            {
                $formvars = array();
//                $goods_base = $Goods_BaseModel->getOne($value);
                $goods_cat = current($Goods_CatModel->getCat($goods_base['cat_id']));

                $formvars['shop_id'] = $goods_base['shop_id'];
                $formvars['product_id'] = $goods_base['goods_id'];
                $formvars['single_price'] = $goods_base['goods_price'];
                $formvars['goods_stock'] = $goods_base['goods_stock'];
                $formvars['status'] = $goods_base['goods_is_shelves'];
                $formvars['date'] = time();
                $formvars['name'] = $goods_base['goods_name'];
                $formvars['type'] = $goods_cat['cat_name'];
                $formvars['pic'] = $goods_base['goods_image'];
                $formvars['url'] = Yf_Registry::get('url').'?ctl=Goods_Goods&met=goods&type=goods&gid='.$goods_base['goods_id'];
                $goods_spec = $goods_base['goods_spec'] ? $goods_base['goods_spec'] : array();
                if($goods_spec){
                    $goods_spec = array_shift($goods_spec);
                    $formvars['spec'] = implode(',', $goods_spec);
                }
                $formvars['common_id'] = $Goods['common_id'];
                $formvars['app_id']    = Yf_Registry::get('analytics_app_id');
                $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=productAdd&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
            }
        }
    }

    //商品信息修改
    public function goodsEdit($Goods){
        //本地同步远程信息
        if($Goods === '' ){
            return false;
        }

        $Goods_BaseModel = new Goods_BaseModel();
        $Goods_CatModel = new Goods_CatModel();
        $goods_ids = $Goods_BaseModel->getByWhere(array('common_id' => $Goods['common_id']));
        if (is_array($goods_ids) && $goods_ids)
        {
            foreach($goods_ids as $key=>$goods_base)
            {
                $formvars = array();
    //            $goods_base = $Goods_BaseModel->getOne($value);
                $goods_cat = current($Goods_CatModel->getCat($goods_base['cat_id']));

                $formvars['shop_id'] = $goods_base['shop_id'];
                $formvars['product_id'] = $goods_base['goods_id'];
                $formvars['single_price'] = $goods_base['goods_price'];
                $formvars['goods_stock'] = $goods_base['goods_stock'];
                $formvars['status'] = $goods_base['goods_is_shelves'];
                $formvars['name'] = $goods_base['goods_name'];
                $formvars['type'] = $goods_cat['cat_name'];
                $formvars['pic'] = $goods_base['goods_image'];
                $formvars['url'] = Yf_Registry::get('url').'?ctl=Goods_Goods&met=goods&type=goods&gid='.$goods_base['goods_id'];
                $goods_spec = $goods_base['goods_spec'] ? $goods_base['goods_spec'] : array();
                if($goods_spec){
                    $goods_spec = array_shift($goods_spec);
                }
                $formvars['spec'] = implode(',', $goods_spec);
                $formvars['common_id'] = $Goods['common_id'];
                $formvars['app_id']    = Yf_Registry::get('analytics_app_id');
                $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=productUpdate&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
            }
        }
    }
    
    //UV和PV
    public function uvCount($uvCount)
    {
        //本地同步远程信息
        if($uvCount === '' ){
            return false;
        }

        $Goods_Base = new Goods_BaseModel;
        $goodsbase = current($Goods_Base->getBase($uvCount['product_id']));
        $uvCount['shop_id'] = $goodsbase['shop_id'];
        $uvCount['goods_salenum'] = $goodsbase['goods_salenum'];
        
        $formvars = array();
        foreach($uvCount as $key=>$value)
        {
            $formvars[$key] = $value;
        }
        $formvars['app_id']    = Yf_Registry::get('analytics_app_id');
        $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=totalCount&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
//        echo '<pre>';print_r($init_rs);exit;
        return $init_rs;
    }
    
    //收藏商品数
    public function productCollect($data)
    {
        //本地同步远程信息
        if($data === '' ){
            return false;
        }

        $Goods_BaseModel = new Goods_BaseModel();
        $goods_base_data = $Goods_BaseModel->getOne($data['product_id']);
        $data['shop_id'] = $goods_base_data['shop_id'];
        $data['goods_collect'] = $goods_base_data['goods_collect'];

        $formvars = array();
        foreach($data as $key=>$value)
        {
            $formvars[$key] = $value;
        }
        $formvars['app_id']    = Yf_Registry::get('analytics_app_id');
        $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=productFollow&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
        return $init_rs;
    }

    //收藏商品数
    public function productCancleCollect($data)
    {
        //本地同步远程信息
        if($data === '' ){
            return false;
        }

        $Goods_BaseModel = new Goods_BaseModel();
        $goods_base_data = $Goods_BaseModel->getOne($data['product_id']);
        $data['shop_id'] = $goods_base_data['shop_id'];
        $data['goods_collect'] = $goods_base_data['goods_collect'];
        
        $formvars = array();
        foreach($data as $key=>$value)
        {
            $formvars[$key] = $value;
        }
        $formvars['app_id']    = Yf_Registry::get('analytics_app_id');
        $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=productCancleFollow&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
        return $init_rs;
    }

    //商品评分
    public function Score($data)
    {
        //本地同步远程信息
        if($data === '' ){
            return false;
        }

        $Goods_BaseModel = new Goods_BaseModel();
        $goods_base_data = $Goods_BaseModel->getOne($data['product_id']);
        $data['shop_id'] = $goods_base_data['shop_id'];

        $formvars = array();
        foreach($data as $key=>$value)
        {
            $formvars[$key] = $value;
        }
//        echo '<pre>';print_r($formvars);exit;
        $formvars['app_id']    = Yf_Registry::get('analytics_app_id');
        $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=Score&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
        return $init_rs;
    }

    //店铺收藏
    public function shopCollect($data)
    {
        //本地同步远程信息
        if($data === '' ){
            return false;
        }

        $Shop_EvaluationModel = new Shop_EvaluationModel();
        $cond_row['shop_id'] = $data['shop_id'];
        $shop_evaluation      = $Shop_EvaluationModel->getByWhere($cond_row);
        $count = count($shop_evaluation);
        $evaluation_desccredit_sum = array_sum(array_map(function($val){return $val['evaluation_desccredit'];}, $shop_evaluation));
        $evaluation_servicecredit_sum = array_sum(array_map(function($val){return $val['evaluation_servicecredit'];}, $shop_evaluation));
        $evaluation_deliverycredit_sum = array_sum(array_map(function($val){return $val['evaluation_deliverycredit'];}, $shop_evaluation));
        $sum = ($evaluation_desccredit_sum + $evaluation_servicecredit_sum + $evaluation_deliverycredit_sum)/($count*3);
        $data['shop_credit'] = round($sum, 2);

        $formvars = array();
        foreach($data as $key=>$value)
        {
            $formvars[$key] = $value;
        }
        
        $formvars['app_id']    = Yf_Registry::get('analytics_app_id');
        $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=shopCollect&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
        return $init_rs;
    }

    //店铺取消收藏
    public function shopCancleCollect($data)
    {
        //本地同步远程信息
        if($data === '' ){
            return false;
        }

        $Shop_EvaluationModel = new Shop_EvaluationModel();
        $cond_row['shop_id'] = $data['shop_id'];
        $shop_evaluation      = $Shop_EvaluationModel->getByWhere($cond_row);
        $count = count($shop_evaluation);
        $evaluation_desccredit_sum = array_sum(array_map(function($val){return $val['evaluation_desccredit'];}, $shop_evaluation));
        $evaluation_servicecredit_sum = array_sum(array_map(function($val){return $val['evaluation_servicecredit'];}, $shop_evaluation));
        $evaluation_deliverycredit_sum = array_sum(array_map(function($val){return $val['evaluation_deliverycredit'];}, $shop_evaluation));
        $sum = ($evaluation_desccredit_sum + $evaluation_servicecredit_sum + $evaluation_deliverycredit_sum)/($count*3);
        $data['shop_credit'] = round($sum, 2);

        $formvars = array();
        foreach($data as $key=>$value)
        {
            $formvars[$key] = $value;
        }
        //echo '<pre>';print_r($formvars);exit;
        $formvars['app_id']    = Yf_Registry::get('analytics_app_id');
        $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=shopCancleCollect&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
        return $init_rs;
    }
    
    /**
     *  更新订单状态
     */
    public function updateOrderStatus($formvars = array()){
        if(!$formvars){
            return false;
        }
        $formvars['app_id']    = Yf_Registry::get('analytics_app_id');
        $init_rs = $this->_sendData(Yf_Registry::get('analytics_api_key'), sprintf('%s?ctl=Api_Shop_Statistics&met=updateOrderStatus&typ=json', Yf_Registry::get('analytics_api_url')), $formvars);
        return $init_rs;
    }
    
    
    
}

?>