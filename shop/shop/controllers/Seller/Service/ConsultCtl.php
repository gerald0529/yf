<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author     Xinze <xinze@live.cn>
     */
    class Seller_Service_ConsultCtl extends Seller_Controller
    {
        public $consultBaseModel = null;
        public $consultTypeModel = null;
        
        /**
         * Constructor
         *
         * @param  string $ctl 控制器目录
         * @param  string $met 控制器方法
         * @param  string $typ 返回数据类型
         *
         * @access public
         */
        public function __construct(&$ctl, $met, $typ)
        {
            parent::__construct($ctl, $met, $typ);
            $this->consultBaseModel = new Consult_BaseModel();
            $this->consultTypeModel = new Consult_TypeModel();
        }
        
        /**
         * 首页
         *
         * @access public
         */
        public function index()
        {
            $Yf_Page = new Yf_Page();
            $Yf_Page->listRows = 10;
            $rows = $Yf_Page->listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            
            $cond_row['shop_id'] = Perm::$shopId;         //店铺ID
            
            $keyword = request_string("key");
            
            $state = request_int("status");
            $consult_type = request_int("type");
            if ($keyword) {
                $cond_row['goods_name:LIKE'] = "%" . $keyword . "%";
            }
            if ($state) {
                $cond_row['consult_state'] = $state;
            }
            if ($consult_type) {
                $cond_row['consult_type_id'] = $consult_type;
            }
            
            $data = $this->consultBaseModel->getBaseList($cond_row, array(
//                'answer_time' => 'desc',
                'question_time' => 'desc'
            ), $page, $rows);

//        foreach ($data['items'] as $k => $v)
//        {
//            $cond_row2['consult_id']    = $v['consult_id'];
//            $data['items'][$k]['reply'] = $this->Consult_ReplyModel->getByWhere($cond_row2, array("answer_time" => "ASC"));
//        }
            
            $Yf_Page->totalRows = $data['totalsize'];
            $page_nav = $Yf_Page->prompt();
            $data['key'] = $keyword;
            $data['state'] = $state;
            $data['type'] = $consult_type;
            $type = $this->consultTypeModel->getByWhere();
            
            $d['data'] = $data;
            $d['type'] = $type;
            if ($this->typ == "json") {
                $this->data->addBody(-140, $d);
            } else {
                include $this->view->getView();
            }
        }
        
        public function delConsult()
        {
            $consult_id = request_row("id");

            $consult = $this->consultBaseModel->getOne($consult_id);
            if ($consult['shop_id'] == Perm::$shopId) {
                $flag = $this->consultBaseModel->removeBase($consult_id);
                if ($flag) {
                    $status = 200;
                    $msg = __('success');
                } else {
                    $status = 250;
                    $msg = __('failure');
                }
            } else {
                $status = 250;
                $msg = __('failure');
            }
            
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
        public function reply()
        {
            $consult_id = request_int("consult_id");
            $data = $this->consultBaseModel->getOne($consult_id);
            $data['id'] = $consult_id;
            if ($this->typ == "json") {
                $this->data->addBody(-140, $data);
            } else {
                include $this->view->getView();
            }
        }
        
        public function replyConsult()
        {
            $consult_id = request_int("consult_id");
            $consult_answer = request_string("consult_answer");
            $consult = $this->consultBaseModel->getOne($consult_id);
            if ($consult['shop_id'] == Perm::$shopId) {
                $data['answer_time'] = date("Y-m-d H:i:s");
                $data['consult_state'] = Consult_BaseModel::CONSULT_REPLY;
                $data['consult_answer'] = $consult_answer;
                $data['answer_user_id'] = Perm::$shopId;
                $data['answer_user_name'] = Perm::$row['user_account'];
                
                $matche_row = array();
                //有违禁词
                if (Text_Filter::checkBanned($consult_answer, $matche_row)) {
                    $data = array();
                    $msg = __('failure');
                    $status = 250;
                    $this->data->addBody(-140, array(), $msg, $status);
                    return false;
                }
                
                $flag = $this->consultBaseModel->editBase($consult_id, $data);
                
                if ($flag) {
                    $status = 200;
                    $msg = __('success');
                    //商品咨询回复提醒
                    //$goods_name
                    $message = new MessageModel();
                    $message->sendMessage('Commodity advisory reply reminder', $consult['user_id'], $consult['user_account'], $order_id = NULL, $shop_name = NULL, 0, MessageModel::OTHER_MESSAGE, $end_time = Null, $common_id = NULL, $goods_id = NULL, $des = NULL, $start_time = Null, $consult['goods_name']);
                } else {
                    $status = 250;
                    $msg = __('failure');
                }
            } else {
                $status = 250;
                $msg = __('failure');
            }
            
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
        
        public function delAllConsult()
        {
            $consult_id = request_string("id");
            //开启事物
            $rs_row = array();
            $this->consultBaseModel->sql->startTransactionDb();
            
            //删除选中的分类
            foreach ($consult_id as $v) {
                $del_flag = $this->consultBaseModel->removeSelectedConsult($v);
                check_rs($del_flag, $rs_row);
            }
            $flag = is_ok($rs_row);
            if ($flag && $this->consultBaseModel->sql->commitDb()) {
                $status = 200;
                $msg = __('success');
            } else {
                $this->consultBaseModel->sql->rollBackDb();
                $status = 250;
                $msg = __('failure');
            }
            
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
            
        }
    }

?>