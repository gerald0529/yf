<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * Api接口, 让webpos调用
 *
 *
 * @category   Game
 * @package    User
 * @author
 * @copyright
 * @version    1.0
 * @todo
 */
class WebPosApi_VoucherCtl extends WebPosApi_Controller
{
    public $voucherBaseModel = null;
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

        $this->voucherBaseModel = new Voucher_BaseModel();
    }

    /**
     * 代金券领取列表
     *
     * @access public
     *
     */
    public function voucher_list()
    {
        $cond_row = array();
        $cond_row['voucher_owner_id'] = request_int('user_id');
        $cond_row['voucher_shop_id'] = request_int('shop_id');
        $cond_row['voucher_state'] = Voucher_BaseModel::UNUSED;
        $cond_row['voucher_start_date:<'] = date('Y-m-d H:i:s');
        $cond_row['voucher_end_date:>'] = date('Y-m-d H:i:s');
        $order_row = array('voucher_state' => 'asc', 'voucher_active_date' => 'desc');
        $data = $this->voucherBaseModel->getUserVoucherList($cond_row, $order_row);
        $data['items'] = $this->getVoucherData($data['items']);

        return $this->data->addBody(-140, $data);

    }
    /**
     *  代金券数据
     */
    private function getVoucherData($data)
    {
        if (!is_array($data) || !$data) {
            return array();
        }
        $shop_id_row = array_column($data, 'voucher_shop_id');
        if (!$shop_id_row) {
            return array();
        }
        $Shop_BaseModel = new Shop_BaseModel();
        $shop_rows = $Shop_BaseModel->getBase($shop_id_row);
        if (!$shop_rows) {
            return array();
        }
        foreach ($data as $key => $value) {
            $data[$key]['voucher_shop_name'] = $shop_rows[$value['voucher_shop_id']]['shop_name'];
            $data[$key]['voucher_shop_logo'] = $shop_rows[$value['voucher_shop_id']]['shop_logo'];
            $data[$key]["voucher_state_label"] = __(Voucher_BaseModel::$voucherState[$value["voucher_state"]]);

            $data[$key]["voucher_limit"] = number_format($data[$key]["voucher_limit"]);
            $data[$key]["voucher_end_date"] = date('Y-m-d', strtotime($data[$key]["voucher_end_date"]) + 1);
            $data[$key]["v_end_date"] = date('Y.m.d', strtotime($data[$key]["voucher_end_date"]) + 1);
            $data[$key]["v_start_date"] = date('Y.m.d', strtotime($data[$key]["voucher_start_date"]));
        }
        return $data;
    }
    /*
     * 代金券状态值改变
     * id代金券编号
     * order_id 订单编号
     * */
    public function change_status(){
        $voucher_id = request_int('id');
        $order_id = request_string('order_id');
        $flag = $this->voucherBaseModel->editVoucher($voucher_id, ['voucher_state' => Voucher_BaseModel::USED,'voucher_order_id'=>$order_id]);
        $rs_row = array();
        check_rs($flag, $rs_row);
        $fl = is_ok($rs_row);

        if ($fl) {
            $status = 200;
            $msg = __('success');

        } else {
            $status = 250;
            $msg = __('failure');

        }
        $data = array('fl' => $fl);

        return $this->data->addBody(-140, $data, $msg, $status);
    }

}

?>