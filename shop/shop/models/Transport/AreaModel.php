<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * 售卖区域模板
 * @author     Xinze <xinze@live.cn>
 */
class Transport_AreaModel extends Transport_Area
{
    
    /**
     *  判断是否售卖
     * @param type $id
     * @param type $area_id  市级（产品中售卖区域暂时精确到市级，如果修改到县级，则使用县级的id）
     * @return boolean
     */
    public function isSale($id,$area_id){
        if(!$id){
            return true;
        }else{
            $areaList = $this->getOne($id);
        } 
        if(!isset($areaList['area_ids'])){
            return false;
        }
        //0表示全国
        if($areaList['area_ids'] == 0){
            return true;
        }
        $area_ids = explode(',', $areaList['area_ids']);
        if(in_array($area_id, $area_ids)){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 添加售卖地区模板
     * @param type $data
     * @return type
     */
    public function areaAdd($data){
        $check = $this->getByWhere(array('shop_id'=>$data['shop_id'],'name'=>$data['name']));
        if($check){
            return array('msg'=>'模板名称已存在','result'=>false);
        }
        $res = $this->addArea($data,true);
        if($res > 0){
            return array('msg'=>'添加成功','result'=>true);
        }else{
            return array('msg'=>'添加失败','result'=>false);
        }
    }

    /**
     * 编辑售卖区域模板
     * @param type $id
     * @param type $data
     * @return type
     */
    public function areaEdit($id,$data){
        $check = $this->getByWhere(array('id:!='=>$id,'shop_id'=>$data['shop_id'],'name'=>$data['name']));
        if($check){
            return array('msg'=>'模板名称已存在','result'=>false);
        }
        $res = $this->editArea($id,$data);
        if($res !== false){
            return array('msg'=>'编辑成功','result'=>true);
        }else{
            return array('msg'=>'编辑失败','result'=>false);
        }
    }
    
    /**
     * 删除售卖区域模板
     * @param type $id
     * @param type $shop_id
     * @return boolean
     */
    public function typeDel($id,$shop_id){
        $info = $this->getOne($id);
        if(!$shop_id || $info['shop_id'] != $shop_id){
            return false;
        }
        $result = $this->removeArea($id);
        return $result;
        
    }
    
    /**
     * 获取地区名称
     * @param type $id
     * @return string
     */
    public function getDistrictName($id){
        if(!$id){
            return '';
        }
      
        $district_model = new Base_DistrictModel();
        $list = $district_model->getName($id);
        if(!$list){
            return '';
        }
        $name = '';
        foreach ($list as $value){
            $name .= $value.',';
        }
        return trim($name,',');
    }
    
    /**
     * 返回当前模板是否可用
     * @param type $id
     * @param type $shop_id
     * @return boolean
     */
    public function checkArea($id,$shop_id){
        if(!$id || $shop_id){
            return false;
        }
        $info = $this->getOne($id);
        if($info['shop_id'] != $shop_id){
            return false;
        }else{
            return true;
        }
    }
    
    /**
     * 获取售卖区域的模板
     * @param type $area_id
     */
    public function getAreaTemplate($area_id) {
        $data = $this->getByWhere(array('area_ids' => 0));
        if(!$data){
            $data = array();
        }
        if($area_id > 0){
            $data1 = $this->getByWhere(array("area_ids:LIKE"=> '%'.$area_id.'%'));
            if($data1){
                foreach($data1 as $key => $val){
                    $transport_city_row = explode(',',$val['area_ids']);
                    if(!in_array($area_id,$transport_city_row)){
                        unset($data1[$key]);
                    }
                }
                $data = array_merge($data1,$data);
            }
        }
        return $data;
    }

}

?>