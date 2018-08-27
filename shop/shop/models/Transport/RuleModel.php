<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}
/**
 * Description of Transport_TemplateModel
 * 运费模板设置 Logical model
 * @author Str <tech40@yuanfeng021.com>
 * @version    shop3.1.3
 * 
 */
class Transport_RuleModel extends Transport_Rule
{
    //1按重量  2按件数    3按体积   
    const TRANSPORT_RULE_WEIGHT = 1;        
    const TRANSPORT_RULE_COUNT= 2; 
    const TRANSPORT_RULE_VOLUME = 3;           
               
    
    /**
     * 按模板ID批量删除规则
     * @return type
     */
    public function delAllRule($template_id){
        $rule_ids = $this->getId(array('transport_template_id'=>$template_id));
        if(!$rule_ids){
            return true;
        }
        $flag = $this->remove($rule_ids);
        return $flag;
    }
    
    /**
     * 获取开启运费模板的规则
     * @param type $city_id
     * @param type $shop_id
     * @return array
     */
    public function getOpenRuleInfo($city_id,$shop_id){
        $list = $this->getOpenRuleList($shop_id);
        if($list['rule_list']){
            foreach ($list['rule_list'] as $value){
                $city_row = explode(',', $value['area_ids']);
                if (in_array($city_id, $city_row)){
                    $list['rule_info'] = $value;
                    break;
                }
            }
        }
        return $list;
    }

	/**
     * 获取开启运费模板的规则
     * @param type $shop_id
     * @return array
     */
    public function getOpenRuleList($shop_id){
        $template_model = new Transport_TemplateModel();
        $template_list = $template_model->getOpenTemplate($shop_id);
        if($template_list){
            $list = $this->getByWhere(array('transport_template_id'=>$template_list['id']));
        }else{
            $list = array();
        }
        $template_list['rule_list'] = $list;
        return $template_list;
    }
    
    /**
     * 计算运费
     * @param type $chose_transport 运费规则
     * $type 类型 weight， count
     * @return type
     */
    public function countCost($chose_transport = array(),$type = array()){
        //$t_count 运费计算规则总数（总重量/总数量/总体积）
        $t_count = 0;
        if($chose_transport['rule_type'] == self::TRANSPORT_RULE_WEIGHT){
            $t_count = !$type['weight'] ? 0 : $type['weight'];
        }else if($chose_transport['rule_type'] == self::TRANSPORT_RULE_COUNT){
            $t_count = !$type['count'] ? 0 : $type['count'];
        }
        //计算首重
        $diff_num = $t_count - $chose_transport['default_num'];
        $cost     = $chose_transport['default_price'];

        if ($diff_num > 0 && $chose_transport['add_num'] > 0){
            $cost += ceil(($diff_num / $chose_transport['add_num'])) * $chose_transport['add_price'];
        }
        return $cost;
    }

}

?>