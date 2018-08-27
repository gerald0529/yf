<?php

/**
 * Created by PhpStorm.
 * User: rd04
 * Date: 2017/11/14
 * Time: 15:00
 */
class Api_Promotion_PinTuanCtl extends Api_Controller
{
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
    }

    /**
     * 获取拼团列表
     * @throws Exception
     */
    public function getPinTuanList()
    {
        $pintuanName = request_string('pintuan_name');
        $shopName = request_string('shop_name');
        $status = request_string('pintaun_status');

        $cond_row = array();

        if ($status != '-1') {
            $cond_row['status'] = $status;
        }

        if ($pintuanName) {
            $cond_row['name:LIKE'] = "%$pintuanName%";
        }
        
        if (request_string('shop_name')) {
            $shopModel = new Shop_BaseModel;
            $shopRows = $shopModel->getByWhere([
                'shop_name:LIKE'=> "%$shopName%"
            ]);

            if ($shopRows) {
                $shopIds = array_column($shopRows, 'shop_id');
                $cond_row['shop_id:IN'] = $shopIds;
            }
        }


        $pinTuanModel = new PinTuan_Base;
        $data = $pinTuanModel->getPinTuanList($cond_row);
        $this->data->addBody(-140, $data, 'success', 200);
    }

    /**
     * 获取套餐列表
     */
    public function getComboList()
    {
        $comboModel = new PinTuan_Combo;
        $data = $comboModel->listByWhere();
        $this->data->addBody(-140, $data, 'success', 200);
    }

    /**
     * 获取套餐详情
     */
    public function getDetail()
    {
        $detailModel = new PinTuan_Detail;
        $data = $detailModel->getByWhere([
            'pintuan_id'=> request_string('pintuan_id'),
        ]);

        if ($data) {
            $goodsModel = new Goods_BaseModel;
            $goodsIds = array_column($data, 'goods_id');
            $goodsRows = $goodsModel->getBase($goodsIds);
            foreach ($data as $k=> $item) {
                $data[$k]['goods_name'] = $goodsRows[$item['goods_id']]['goods_name'];
                $data[$k]['goods_stock'] = $goodsRows[$item['goods_id']]['goods_stock'];
            }
        }

        $data = array_values($data);
        $this->data->addBody(-140, $data, 'success', 200);
    }

    public function removePintuan()
    {
        $id = $_REQUEST['id'];

        $pinTuanModel = new PinTuan_Base;
        $flag = $pinTuanModel->removePinTuanActItem($id);

        if ($flag) {
            $msg = 'success';
            $status = 200;
        } else {
            $msg = 'failure';
            $status = 250;
        }

        $this->data->addBody(-140, [], $msg, $status);
    }
}