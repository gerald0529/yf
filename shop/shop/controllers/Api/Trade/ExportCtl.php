<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * Api接口, 让App等调用, 所有导出方法都放在此控制器
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Api_Trade_ExportCtl extends Yf_AppController
{
    const PAY_SITE = "http://paycenter.yuanfeng021.com/";
    //const PAY_SITE	 = "http://localhost/repos/paycenter/";
    public $Order_BaseModel         = null;
    public $Order_ReturnModel       = null;
    public $Order_ReturnReasonModel = null;
    public $Order_GoodsModel        = null;

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
        $this->Order_BaseModel         = new Order_BaseModel();
        $this->Order_ReturnModel       = new Order_ReturnModel();
        $this->Order_ReturnReasonModel = new Order_ReturnReasonModel();
        $this->Order_GoodsModel        = new Order_GoodsModel();

    }


    public function getReturnWaitExcel()
    {

        $type                = request_int("otyp", Order_ReturnModel::RETURN_TYPE_ORDER);
        $return_code         = request_string("return_code");
        $seller_user_account = request_string("seller_user_account");
        $buyer_user_account  = request_string("buyer_user_account");
        $order_goods_name    = request_string("order_goods_name");
        $order_number        = request_string("order_number");
        $start_time          = request_string("start_time");
        $end_time            = request_string("end_time");
        $min_cash            = request_float("min_cash");
        $max_cash            = request_float("max_cash");

        $oname    = request_string('sidx');
        $osort    = request_string('sord');
        $cond_row = array();
        $sort     = array();
//        if($oname != "number") {
//            $sort[$oname] = $osort;
//        }

        if ($return_code)
        {
            $cond_row['return_code'] = $return_code;
        }
        if ($seller_user_account)
        {
            $cond_row['seller_user_account'] = $seller_user_account;
        }
        if ($buyer_user_account)
        {
            $cond_row['buyer_user_account'] = $buyer_user_account;
        }
        if ($order_goods_name)
        {
            $cond_row['order_goods_name:LIKE'] = '%' . $order_goods_name . '%';
        }
        if ($start_time)
        {
            $cond_row['return_add_time:>='] = $start_time;
        }
        if ($end_time)
        {
            $cond_row['return_add_time:<='] = $end_time;
        }
        if ($min_cash)
        {
            $cond_row['return_cash:>='] = $min_cash;
        }
        if ($max_cash)
        {
            $cond_row['return_cash:<='] = $max_cash;
        }
        $cond_row['return_state'] = Order_ReturnModel::RETURN_SELLER_GOODS;
        $cond_row['return_type']  = $type;
        $con                      = array();
        $con                      = $this->Order_ReturnModel->getReturnExcel($cond_row, $sort);
        $tit                      = array(
            "序号",
            "退单编号",
            "退单金额",
            "佣金金额",
            "申请原因",
            "申请时间",
            "涉及商品",
            "商家处理备注",
            "商家处理时间",
            "订单编号",
            "买家",
            "商家"
        );
        $key                      = array(
            "return_code",
            "return_cash",
            "return_commision_fee",
            "return_reason",
            "return_add_time",
            "order_goods_name",
            "return_shop_message",
            "return_shop_time",
            "order_number",
            "buyer_user_account",
            "seller_user_account"
        );
        $this->excel("退款退货单", $tit, $con, $key);
    }

    public function getReturnAllExcel()
    {
        $type                = request_int("otyp", Order_ReturnModel::RETURN_TYPE_ORDER);
        $return_code         = request_string("return_code");
        $seller_user_account = request_string("seller_user_account");
        $buyer_user_account  = request_string("buyer_user_account");
        $order_goods_name    = request_string("order_goods_name");
        $order_number        = request_string("order_number");
        $start_time          = request_string("start_time");
        $end_time            = request_string("end_time");
        $min_cash            = request_float("min_cash");
        $max_cash            = request_float("max_cash");

        $oname    = request_string('sidx');
        $osort    = request_string('sord');
        $cond_row = array();
        $sort     = array();
//        if($oname != "number") {
//            $sort[$oname] = $osort;
//        }

        if ($return_code)
        {
            $cond_row['return_code'] = $return_code;
        }
        if ($seller_user_account)
        {
            $cond_row['seller_user_account'] = $seller_user_account;
        }
        if ($buyer_user_account)
        {
            $cond_row['buyer_user_account'] = $buyer_user_account;
        }
        if ($order_goods_name)
        {
            $cond_row['order_goods_name:LIKE'] = '%' . $order_goods_name . '%';
        }
        if ($start_time)
        {
            $cond_row['return_add_time:>='] = $start_time;
        }
        if ($end_time)
        {
            $cond_row['return_add_time:<='] = $end_time;
        }
        if ($min_cash)
        {
            $cond_row['return_cash:>='] = $min_cash;
        }
        if ($max_cash)
        {
            $cond_row['return_cash:<='] = $max_cash;
        }
        $cond_row['return_type'] = $type;
        $con                     = array();
        $con                     = $this->Order_ReturnModel->getReturnExcel($cond_row, $sort);
        $this->data->addBody(-140, $con);
        $tit = array(
            "序号",
            "退单编号",
            "退单金额",
            "佣金金额",
            "申请原因",
            "申请时间",
            "涉及商品",
            "商家处理备注",
            "商家处理时间",
            "订单编号",
            "买家",
            "商家"
        );
        $key = array(
            "return_code",
            "return_cash",
            "return_commision_fee",
            "return_reason",
            "return_add_time",
            "order_goods_name",
            "return_shop_message",
            "return_shop_time",
            "order_number",
            "buyer_user_account",
            "seller_user_account"
        );
        $this->excel("退款退货单", $tit, $con, $key);
    }
    
    
    function excel($title, $tit, $con, $key)
    {
        ob_end_clean();   //***这里再加一个
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("mall_new");
        $objPHPExcel->getProperties()->setLastModifiedBy("mall_new");
        $objPHPExcel->getProperties()->setTitle($title);
        $objPHPExcel->getProperties()->setSubject($title);
        $objPHPExcel->getProperties()->setDescription($title);
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle($title);
        $letter = array(
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T'
        );
        foreach ($tit as $k => $v)
        {
            $objPHPExcel->getActiveSheet()->setCellValue($letter[$k] . "1", $v);
        }
        foreach ($con as $k => $v)
        {
            $objPHPExcel->getActiveSheet()->setCellValue($letter[0] . ($k + 2), $k + 1);
            foreach ($key as $k2 => $v2)
            {

                $objPHPExcel->getActiveSheet()->setCellValue($letter[$k2 + 1] . ($k + 2), $v[$v2]);
            }
        }
        ob_end_clean();   //***这里再加一个
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$title.xls\"");
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
        die();
    }
}