<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}



/**
 * 初始化任务接口
 *
 * 
 */
class Api_TaskCtl extends Api_Controller
{
    /**
     *  售卖区域
     * 1. 将原运费规则中的地区添加至新的售卖区域表
     * 2. 商品表中的售卖区域更新为新的售卖区域
     */
    public function transport(){ 
        set_time_limit(0);
        if(SHOP_VERSION >= '3.1.3'){
            return $this->data->addBody(-140, array(), _('3.1.3以上版本不需要初始化'), 250);
        }
        //检查是否可以初始化
        $area_model = new Transport_AreaModel();
        $checkInstall= $area_model->getCount(array());
        if($checkInstall !== '0'){
            return $this->data->addBody(-140, array(), _('初始化失败，目标数据表不为空'), 250);
        }
        $Transport_TypeModel = new Transport_TypeModel();
        $count = $Transport_TypeModel->getCount(array());
        $size = 100; 
        $all_page = ceil($count/$size);
        $s_record = 0;
        $f_record = 0;
        for($page = 1; $page <= $all_page; $page ++){
            $tansport_list = $Transport_TypeModel->getTransportList(array(),array(),$page,$size);
            if($tansport_list['items']){
                foreach ($tansport_list['items'] as $value){
                    $area = array(); // 售卖区域
                    $area['name'] = $value['transport_type_name'];
                    $area['id'] = $value['transport_type_id'];   
                    $area['shop_id'] = $value['shop_id'];
                    $area['area_ids'] = $value['transport_item']['transport_item_city'] === 'default' ? 0 : $value['transport_item']['transport_item_city'];
                    $res = $area_model->addArea($area);
                    if($res){
                        $s_record ++;
                    }else{
                        $f_record ++;
                    }
                }
            }
        } 
        $goods_model = new Goods_CommonModel();
        $update_data = $goods_model->updateColumnToColumn('transport_type_id', 'transport_area_id');
        $status = $update_data === false ? 250 : 200;
        return $this->data->addBody(-140, array(), _('初始化完成，'.$s_record.'条成功记录，'.$f_record.'条失败记录'), $status);
        
    }

}

?>