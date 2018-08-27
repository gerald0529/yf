<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Mb_CatImageCtl extends Api_Controller
{
    public $mbCatImageModel;

    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);

        $this->mbCatImageModel = new Mb_CatImageModel();
    }

    public function catImageList()
    {
        $cat_image_list = $this->mbCatImageModel->listByWhere();

        //取出关联cat_name

        $data = array();

        if ( !empty($cat_image_list) )
        {
            $cat_ids = array_column($cat_image_list['items'], 'cat_id');
            $goodsCatModel = new Goods_CatModel();
            $cat_list = $goodsCatModel->getByWhere( array('cat_id:IN' => $cat_ids) );

            foreach ($cat_image_list['items'] as $key => $cat_img_data)
            {
                $cat_image_list['items'][$key]['cat_name'] = $cat_list[$cat_img_data['cat_id']]['cat_name'];
            }

            $data = $cat_image_list;
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
    public function addCatImage()
    {
        $param                           = request_row('param');

        $insert_data['cat_id']          = $param['cat_id'];
        $insert_data['mb_cat_image']    = $param['mb_cat_image'];
        $insert_data['cat_adv_image']   = $param['cat_adv_image'];
        $insert_data['cat_adv_url']     = $param['cat_adv_url'];

        $mb_cat_image_id = $this->mbCatImageModel->addCatImage($insert_data, true);

        $data = array();

        if ($mb_cat_image_id)
        {
            $insert_data['mb_cat_image_id'] = $mb_cat_image_id;
            $data = $insert_data;
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

    public function removeCatImage()
    {
        $mb_cat_image_id = request_int('mb_cat_image_id');
        $flag = $this->mbCatImageModel->removeCatImage($mb_cat_image_id);

        $data = array();
        if ($flag)
        {
            $data['mb_cat_image_id'] = $mb_cat_image_id;
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

    public function editCatImage()
    {
        $param           = request_row('param');
        $mb_cat_image_id = $param['mb_cat_image_id'];

        $update_data['cat_id']          = $param['cat_id'];
        $update_data['mb_cat_image']    = $param['mb_cat_image'];
        $update_data['cat_adv_image']   = $param['cat_adv_image'];
        $update_data['cat_adv_url']     = $param['cat_adv_url'];

        $flag = $this->mbCatImageModel->editCatImage($mb_cat_image_id, $update_data);

        $data = array();
        if ($flag !== false)
        {
            $update_data['mb_cat_image_id'] = $mb_cat_image_id;
            $data = $update_data;
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

}
?>