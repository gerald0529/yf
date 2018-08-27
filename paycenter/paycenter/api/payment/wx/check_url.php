
<?php

require_once '../../../configs/config.ini.php';
require_once LIB_PATH . '/Api/wx/lib/WxPay.Api.php';


//初始化日志
//$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
//$log = Log::Init($logHandler, 15);
//echo $_REQUEST["code"];
if(isset($_REQUEST["code"]) && $_REQUEST["code"] != ""){

    $code = $_REQUEST["code"];
    $Union_OrderModel = new Union_OrderModel();
    $data = $Union_OrderModel->getOne($code);

    if($data['order_state_id'] == Union_OrderModel::PAYED)
    {

        $status = 200;
        $msg    = _('success');
    }
    else
    {
        $msg    = _('failure');
        $status = 250;

    }
    $data = array(
        'msg' => $msg,
        'status' => $status,
    );


    echo json_encode($data);
    //exit();
}
?>
