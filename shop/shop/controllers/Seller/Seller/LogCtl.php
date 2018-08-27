<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     叶赛
 * 卖家操作日志控制器类
 */

class Seller_Seller_LogCtl extends Seller_Controller
{
    public $sellerLogModel = null;

    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
        $this->sellerLogModel = new Seller_LogModel();
    }

    public function logList()
    {
        $cond_row = array();
        $seller_name                = request_string('seller_name');
        $log_content                = request_string('log_content');
        $start_time                 = request_string('start_time');
        $end_time                   = request_string('end_time');

        if($seller_name)
        {
            $cond_row['log_seller_name:LIKE'] = '%'.$seller_name.'%';
        }
        if($log_content)
        {
            $cond_row['log_content:LIKE'] = '%'.$log_content.'%';
        }
        if($start_time)
        {
            $cond_row['log_time:>='] = $start_time;
        }
        if($end_time)
        {
            $cond_row['log_time:<='] = $end_time;
        }
        $cond_row['log_shop_id']     = Perm::$shopId;

        $order_row = array();
        $order_row['log_id'] = 'DESC';

        //分页
        $Yf_Page                    = new Yf_Page();
        $Yf_Page->listRows          = 10;
        $rows                       = $Yf_Page->listRows;
        $offset                     = request_int('firstRow', 0);
        $page                       = ceil_r($offset / $rows);

        $data = $this->sellerLogModel->getSellerLogList($cond_row, $order_row,$page,$rows);
        $Yf_Page->totalRows = $data['totalsize'];
        $page_nav           = $Yf_Page->prompt();

        if('json' == $this->typ)
        {
            $this->data->addBody(-140, $data);
        }
        else
        {
            $this->view->setMet('log_list');
            include $this->view->getView();
        }
    }

}
