<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Mb_TplLayoutCtl extends Api_Controller
{
    public $mbTplLayoutModel;

    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        $this->mbTplLayoutModel = new Mb_TplLayoutModel();
    }

    public function tplLayoutList()
    {
        $sub_site_id = request_int('sub_site_id', 0);
        $layout_list = $this->mbTplLayoutModel->getByWhere( array('sub_site_id'=>$sub_site_id), array('mb_tpl_layout_order' => 'ASC') );

        $data = array();

        if ( is_array($layout_list) )
        {
            //如果为goods类型，则取出对应商品信息
            if ( !empty($layout_list) )
            {
                $goodsCommonModel = new Goods_CommonModel();
                foreach($layout_list as $item_id => $item_data )
                {
                    if ( $item_data['mb_tpl_layout_type'] == 'goods' )
                    {
                        $common_ids = $item_data['mb_tpl_layout_data'];
                        $common_list = $goodsCommonModel->getByWhere( array('common_id:IN' => $common_ids) );
                        if ( !empty($common_list) )
                        {
                            $layout_data = array();
                            foreach ($common_list as $common_id => $common_data)
                            {
                                $layout_data[$common_id]['goods_id'] = $common_data['common_id'];
                                $layout_data[$common_id]['goods_name'] = $common_data['common_name'];
                                $layout_data[$common_id]['goods_price'] = $common_data['common_price'];
                                $layout_data[$common_id]['goods_image'] = $common_data['common_image'];
                            }

                            $layout_list[$item_id]['mb_tpl_layout_data'] = array_values($layout_data);
                        }
                    }
                }

                $data['items'] = array_values($layout_list);
            }
            $msg    = __('success');
            $status = 200;
        }
        else
        {
            $msg    = __('failure');
            $status = 250;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }


    /**
     * 手机端模板
     * “广告条版块”只能添加一个
     */
    public function addTplLayout()
    {
        $item_type = request_string('item_type');
        $sub_site_id = request_int('sub_site_id', 0);
        if ( !empty($item_type) )
        {
            if ($item_type == 'adv_list')
            {
                $check_data = $this->mbTplLayoutModel->getByWhere( array('mb_tpl_layout_type' => 'adv_list','sub_site_id' => $sub_site_id) );

                if ( !empty($check_data) )
                {
                    return $this->data->addBody(-140, array(), __('广告条板块只能添加一个'), 250);
                }
            }

            $insert_data['mb_tpl_layout_type']   = $item_type;
            $insert_data['mb_tpl_layout_enable'] = 0;
            $insert_data['mb_tpl_layout_order']  = 99;
            $insert_data['sub_site_id']  = $sub_site_id;
            $mb_tpl_layout_id = $this->mbTplLayoutModel->addTplLayout($insert_data, true);

            if ($mb_tpl_layout_id)
            {
                $insert_data['mb_tpl_layout_id'] = $mb_tpl_layout_id;
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

    public function removeTplLayout()
    {
        $item_id = request_int('item_id');
        $flag = $this->mbTplLayoutModel->removeTplLayout($item_id);

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

    public function editSortTplLayout()
    {
        $item_id_string = request_string('item_id_string');
        $item_id_array = explode(',', $item_id_string);

        foreach ($item_id_array as $k => $item_id)
        {
            $this->mbTplLayoutModel->editTplLayout($item_id, array('mb_tpl_layout_order' => $k));
        }

        $this->data->addBody(-140, array(), __('success'), 200);
    }

    public function editUsableTplLayout()
    {
        $item_id = request_int('item_id');
        $usable = request_string('usable');

        $update_data['mb_tpl_layout_id'] = $item_id;
        $update_data['mb_tpl_layout_enable'] = $usable == 'usable' ? Mb_TplLayoutModel::USABLE : Mb_TplLayoutModel::UNUSABLE;

        $flag = $this->mbTplLayoutModel->editTplLayout($item_id, $update_data);

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

    public function editTplLayout()
    {
        $item_id = request_int('item_id');

        $update_data['mb_tpl_layout_data'] = request_row('layout_data');
        $update_data['mb_tpl_layout_title'] = request_row('layout_title');

        $flag = $this->mbTplLayoutModel->editTplLayout($item_id, $update_data);

        if ($flag !== false)
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
?>