<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}

/**
 * @author     windfnn
 */
class Distribution_Buyer_GoodsCtl extends Buyer_Controller
{
    public $directseller_model = null;
    public $directseller_goodsModel = null;

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
        $this->directseller_model = new Distribution_ShopDirectsellerModel();
        $this->directseller_goodsModel = new Distribution_ShopDirectsellerGoodsCommonModel();
    }

    /**
     * 首页
     *
     * @access public
     */
    public function index()
    {
        //获取推广店铺的ID
        $cond_row['directseller_id'] = Perm::$userId;
        $cond_row['directseller_enable'] = 1;
        $shops = $this->directseller_model->getByWhere($cond_row);

        $shop_ids = array_column($shops, 'shop_id');

        $cond_good_row['shop_id:in'] = $shop_ids;
        $cond_good_row['common_is_directseller'] = 1;

        if (request_string('keywords')) {
            $cond_good_row['common_name:LIKE'] = '%' . request_string('keywords') . '%'; //商品名称搜索
        }

        $cond_good_row['common_state'] = Goods_CommonModel::GOODS_STATE_NORMAL;  //正常上架商品
        $cond_good_row['common_verify'] = Goods_CommonModel::GOODS_VERIFY_ALLOW;  //审核通过

        $Yf_Page = new Yf_Page();
        $Yf_Page->listRows = 10;
        $rows = $Yf_Page->listRows;
        $offset = request_int('firstRow', 0);
        $page = ceil_r($offset / $rows);

        $act = request_string('act');
        $actorder = request_string('actorder', 'DESC');

        if ($act !== '') {
            //销量
            if ($act == 'sales') {
                $order_row['common_salenum'] = $actorder;
            }
            //佣金排序
            if ($act == 'commission') {
                if (request_string('actorder')) {
                    $order_row['common_cps_commission'] = $actorder;
                } else {
                    $order_row['common_cps_commission'] = 'ASC';
                }
            }
            //时间排序
            if ($act == 'uptime') {
                $order_row['common_add_time'] = $actorder;
            }
        } else {
            $order_row['common_id'] = 'DESC';
        }

        //获取推广商品
        $data = array();
        $Goods_CommonModel = new Goods_CommonModel();
        $data = $Goods_CommonModel->getCommonList($cond_good_row, $order_row, $page, $rows);
        $data['user_id'] = Perm::$userId;

        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav = $Yf_Page->prompt();

        if ($this->typ == "json") {
            $this->data->addBody(-140, $data);
        } else {
            include $this->view->getView();
        }
    }

    /*
     * 编辑推广产品
     */
    public function editGoods()
    {
        $common_id = request_int('common_id');
        $shop_id = request_int('shop_id');
        $data['directseller_id'] = Perm::$userId;
        $data['common_id'] = $common_id;
        $data['shop_id'] = $shop_id;

        $goodsImages = $this->directseller_goodsModel->getGoodsImages($data);
        $data['goods_images'] = @explode(',', $goodsImages['directseller_images_image']);

        $op = request_string('op');
        if ($op == 'save') {
            //保存图片
            $image_list = request_row('image');
            $images = implode(',', array_filter($image_list));
            $field_row['shop_directseller_goods_common_code'] = 'u' . Perm::$userId . 's' . $shop_id . 'c' . $common_id;
            $field_row['shop_id'] = $shop_id;
            $field_row['common_id'] = $common_id;
            $field_row['directseller_id'] = Perm::$userId;
            $field_row['directseller_images_image'] = $images;

            if (!empty($goodsImages)) {
                $this->directseller_goodsModel->editGoodsImages($goodsImages['shop_directseller_goods_common_code'], $field_row);
            } else {
                $this->directseller_goodsModel->addGoodsImages($field_row);
            }

            header("location: " . Yf_Registry::get('url') . '?ctl=Distribution_Buyer_Goods&met=index');
        }

        if ($this->typ == "json") {
            $this->data->addBody(-140, $data);
        } else {
            include $this->view->getView();
        }
    }
}

?>
