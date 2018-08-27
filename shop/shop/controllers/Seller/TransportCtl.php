<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * Description of Seller_TransportCtl
     * 运费模板设置
     *
     * @author     Str <tech40@yuanfeng.cn>
     * @version    shop3.1.3
     *
     */
    class Seller_TransportCtl extends Seller_Controller
    {
        public function __construct(&$ctl, $met, $typ)
        {
            parent::__construct($ctl, $met, $typ);
        }
        
        /**
         * 显示物流工具列表页
         */
        public function transport()
        {
            $act = request_string('act');
            $shop_id = Perm::$shopId;
            $id = request_int('id');
            if ($act === 'transport_default') {
                $data = $this->transport_default($id, $shop_id);
            } else {
                $data = $this->template($shop_id);
                $this->view->setMet('template');
            }
            include $this->view->getView();
        }
        
        /**
         *  运费模板的规则页面
         *  $id 模板id
         */
        private function transport_default($id, $shop_id)
        {
            $data = array();
            //获取地区数组
            $Base_DistrictModel = new Base_DistrictModel();
            $province = $Base_DistrictModel->getAllDistrict();
            $district_region = array();
            foreach ($province as $value) {
                if (!$value['district_region']) {
                    $district_region['其他'][] = $value;
                } else {
                    $district_region[$value['district_region']][] = $value;
                }
            }
            $data['district'] = $district_region;
            if ($id) {
                $Transport_TemplateModel = new Transport_TemplateModel();
                $template = $Transport_TemplateModel->getOne(array('id' => $id, 'shop_id' => Perm::$shopId));
                $Transport_RuleModel = new Transport_RuleModel();
                $rule = $Transport_RuleModel->getByWhere(array('transport_template_id' => $id));
                
                $data['template'] = $template;
                $data['rule'] = $rule;
            }
            return $data;
        }
        
        /**
         *  运费模板
         */
        private function template($shop_id)
        {
            $Transport_TemplateModel = new Transport_TemplateModel();
            $template_list = $Transport_TemplateModel->getByWhere(array('shop_id' => $shop_id));
            return $template_list;
        }
        
        /**
         * 售卖区域页面
         *
         */
        public function tplarea()
        {
            $act = request_string('act');
            $shop_id = Perm::$shopId;
            $id = request_int('id');
            if ($act === 'area') {
                $data = $this->area($id, $shop_id);
                $this->view->setMet('area');
            } else {
                $data = $this->transport_area($shop_id);
                $this->view->setMet('transportArea');
            }
            include $this->view->getView();
        }
        
        /**
         * 设置售卖区域
         *
         * @param type $id
         * @param type $shop_id
         *
         * @return type
         */
        private function area($id, $shop_id)
        {//获取地区数组
            $Base_DistrictModel = new Base_DistrictModel();
            $province = $Base_DistrictModel->getAllDistrict();
            $data = array();
            $data['district'] = $province;
            
            if (!$id) {
                return $data;
            }
            $type_model = new Transport_AreaModel();
            $area = $type_model->getOne($id);
            if ($area['shop_id'] != $shop_id) {
                return $data;
            }
            $area['all_city'] = $area['area_ids'] == 0 ? 0 : 1;
            $area['area_ids_arr'] = explode(',', $area['area_ids']);
            
            $data['data'] = $area;
            return $data;
        }
        
        /**
         * 售卖区域模板
         *
         * @param type $shop_id
         *
         * @return type
         */
        private function transport_area($shop_id)
        {
            $type_model = new Transport_AreaModel();
            $data = $type_model->getByWhere(array('shop_id' => $shop_id));
            if (!$data) {
                return array();
            }
            foreach ($data as $key => $value) {
                $area_ids = array();
                if ($value['area_ids'] == 0) {
                    $data[$key]['area_name'] = __('全国');
                } else {
                    $district_name = $type_model->getDistrictName($value['area_ids']);
                    $data[$key]['area_name'] = mb_strimwidth($district_name, 0, 20, '...', 'utf8');
                }
            }
            return $data;
        }
        
        /**
         * 添加和编辑运费模板
         *
         * 1.添加和修改模板
         * 2.批量删除规则
         * 3.批量添加规则
         *
         * @return type
         */
        public function transportSubmit()
        {
            $type = 'kd'; //kd表示快递,暂时不对运费做区分
            $transport = request_row('transport');
            $areas = request_row('areas');
            $shop_id = Perm::$shopId;
            $Transport_TemplateModel = new Transport_TemplateModel();
            $Transport_TemplateModel->sql->startTransactionDb();
            //1.添加和修改模板
            $template_data = array();
            $template_data['status'] = request_int('template_status');
            $template_data['name'] = request_string('template_name');
            if (!$template_data['name']) {
                return $this->data->addBody(-140, array(), __('模板名称不能为空'), 250);
            }
            $template_data['shop_id'] = $shop_id;
            $template_id = request_int('template_id');
            $rs_rows = array();
            if (!$template_id) {
                $res_info = $Transport_TemplateModel->templateAdd($template_data);
            } else {
                $res_info = $Transport_TemplateModel->templateModify($template_id, $template_data);
            }
            if ($res_info['result']) {
                $template_id = $res_info['result'];
            } else {
                $Transport_TemplateModel->sql->rollBackDb();
                return $this->data->addBody(-140, array(), __($res_info['msg']), 250);
            }
            $Transport_RuleModel = new Transport_RuleModel();
            //2.批量删除规则
            $flag2 = $Transport_RuleModel->delAllRule($template_id);
            check_rs($flag2, $rs_rows);
            //3.批量添加规则
            if (isset($transport[$type]) && $transport[$type]) {
                $k = 0;
                foreach ($transport[$type] as $key => $value) {
                    if (!$areas[$type][$key]) {
                        $Transport_TemplateModel->sql->rollBackDb();
                        return $this->data->addBody(-140, array(), __('运送地区不能为空'), 250);
                    }
                    $area_array = array();
                    $area_array = explode('|||', $areas[$type][$key]);
                    $rule_data = array();
                    $rule_data['transport_template_id'] = $template_id;
                    $rule_data['area_name'] = isset($area_array[1]) ? $area_array[1] : '';
                    $rule_data['area_ids'] = isset($area_array[0]) ? $area_array[0] : '';
                    $rule_data['default_num'] = $value['default_num'];
                    $rule_data['default_price'] = $value['default_price'];
                    $rule_data['add_num'] = $value['add_num'];
                    $rule_data['add_price'] = $value['add_price'];
                    $rule_data['update_time'] = date('Y-m-d H:i:s');
                    $k++;
                    $flag = $Transport_RuleModel->addRule($rule_data);
                    check_rs($flag, $rs_rows);
                }
            }
            if (is_ok($rs_rows) && $Transport_TemplateModel->sql->commitDb()) {
                return $this->data->addBody(-140, array(), __('设置成功'), 200);
            } else {
                $Transport_TemplateModel->sql->rollBackDb();
                return $this->data->addBody(-140, array('rs' => $rs_rows), __('设置失败'), 250);
            }
        }
        
        /**
         * 删除运费模板
         *
         * @return type
         */
        public function delTemplate()
        {
            $shop_id = Perm::$shopId;
            $transport_template_id = request_row('id');
            
            if (is_array($transport_template_id)) {
                $transport_template_id = pos($transport_template_id);
            }
            
            $Transport_TemplateModel = new Transport_TemplateModel();
            $template_info = $Transport_TemplateModel->getOne($transport_template_id);
            
            if ($template_info['status'] == Transport_TemplateModel::TRANSPORT_TEMPLATE_OPEN) {
                $data = array();
                $msg = __('模板在开启状态下，无法删除！');
                $status = 250;
                return $this->data->addBody(-140, $data, $msg, $status);
            } else {
                $flag = $Transport_TemplateModel->templateDel($transport_template_id, $shop_id);
                if ($flag === false) {
                    $data = array();
                    $msg = __('删除失败');
                    $status = 250;
                    return $this->data->addBody(-140, $data, $msg, $status);
                } else {
                    $data = array();
                    $msg = __('删除成功');
                    $status = 200;
                    return $this->data->addBody(-140, $data, $msg, $status);
                }
            }
        }
        
        /**
         * 修改和添加售卖区域模板
         *
         * @return type
         */
        public function areaSubmit()
        {
            $id = request_int('area_id');
            $all_city = request_int('all_city');
            $name = request_string('area_name');
            $shop_id = Perm::$shopId;
            if ($all_city == 0) {
                //全国
                $area_ids = 0;
            } else {
                $area_city = request_row('city');
                $area_province = request_row('province');
                if (!$area_city) {
                    return $this->data->addBody(-140, array(), __('请选择城市地区'), 250);
                }
                $city_ids = implode(',', $area_city);
                $province_ids = is_array($area_province) && $area_province ? implode(',', $area_province) : '';
                $area_ids = trim($city_ids . ',' . $province_ids, ',');
            }
            $data = array(
                'name' => $name,
                'area_ids' => $area_ids,
                'shop_id' => $shop_id
            );
            $Transport_AreaModel = new Transport_AreaModel();
            if ($id) {
                //编辑
                $info = $Transport_AreaModel->getOne($id);
                if ($info['shop_id'] != $shop_id) {
                    return $this->data->addBody(-140, array(), __('数据有误'), 250);
                }
                $result = $Transport_AreaModel->areaEdit($id, $data);
            } else {
                $result = $Transport_AreaModel->areaAdd($data);
            }
            $status = $result['result'] ? 200 : 250;
            return $this->data->addBody(-140, array(), __($result['msg']), $status);
        }
        
        /**
         * 删除售卖区域模板
         *
         * @return type
         */
        public function delArea()
        {
            $shop_id = Perm::$shopId;
            $type_id = request_row('id');
            $Transport_AreaModel = new Transport_AreaModel();
            $flag = $Transport_AreaModel->typeDel(current($type_id), $shop_id);
            if (!$flag) {
                return $this->data->addBody(-140, array(), __('删除失败'), 250);
            } else {
                return $this->data->addBody(-140, array(), __('删除成功'), 200);
            }
        }
        
        /**
         *  选择地区
         */
        public function chooseTranDialog()
        {
            $shop_id = Perm::$shopId;
            $transport_list = $this->transport_area($shop_id);
            include $this->view->getView();
        }
        
    }

?>