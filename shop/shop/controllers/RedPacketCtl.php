<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author yesai
     */
    class RedPacketCtl extends Controller
    {
        public $redPacketTempModel = null;
        public $redPacketBaseModel = null;
        public $userInfoModel = null;
        
        public function __construct(&$ctl, $met, $typ)
        {
            parent::__construct($ctl, $met, $typ);
            $this->initData();
            $this->web = $this->webConfig();
            $this->nav = $this->navIndex();
            $this->cat = $this->catIndex();
            
            $this->redPacketTempModel = new RedPacket_TempModel();
            $this->redPacketBaseModel = new RedPacket_BaseModel();
            $this->userInfoModel = new User_InfoModel();
        }
        
        /*获取平台优惠券列表*/
        public function redPacket()
        {
            $cond_row = array();
            $order_row = array();
            
            //分页
            $Yf_Page = new Yf_Page();
            $Yf_Page->listRows = request_int('listRows') ? request_int('listRows') : 10;
            $rows = $Yf_Page->listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            
            $cond_row['redpacket_t_end_date:>='] = get_date_time();
            $cond_row['redpacket_t_state'] = RedPacket_TempModel::VALID;
            
            $orderby = request_string('orderby');
            switch ($orderby) {
                case 'exchangenumasc':
                    $order_row['redpacket_t_giveout'] = 'ASC';
                    break;
                case 'exchangenumdesc':
                    $order_row['redpacket_t_giveout'] = 'DESC';
                    break;
                case 'denominationdesc':
                    $order_row['redpacket_t_price'] = 'DESC';
                    break;
                case 'denominationasc':
                    $order_row['redpacket_t_price'] = 'ASC';
                    break;
                default:
                    {
                        $order_row['redpacket_t_recommend'] = "DESC";
                        $order_row['redpacket_t_add_date'] = "DESC";
                        break;
                    }
            }
            
            if (request_int('isable') && Perm::checkUserPerm()) {
                $user_info = $this->userInfoModel->getUserInfo(array('user_id' => Perm::$userId));//用户信息，包含用户等级
                $cond_row['redpacket_t_user_grade_limit:<='] = $user_info['user_grade'];
            }
            
            $data['redpacket'] = $this->redPacketTempModel->getRedPacketTempList($cond_row, $order_row, $page, $rows);
            $Yf_Page->totalRows = $data['redpacket']['totalsize'];
            $page_nav = $Yf_Page->prompt();
            
            if ($data['redpacket']["items"]) {
                foreach ($data['redpacket']["items"] as $key => $value) {
                    $data['redpacket']["items"][$key]['start_date'] = date("Y-m-d", strtotime($value['redpacket_t_start_date']));
                    $data['redpacket']["items"][$key]['end_date'] = date('Y-m-d', strtotime($value['redpacket_t_end_date']));
                }
            }
            
            if (Perm::checkUserPerm()) {
                $this->userResourceModel = new User_ResourceModel();
                $this->voucherTempModel = new Voucher_TempModel();
                $this->voucherBaseModel = new Voucher_BaseModel();
                $this->pointsLogModel = new Points_LogModel();
                $this->pointsCartModel = new Points_CartModel();
                $this->pointsOrderModel = new Points_OrderModel();
                $this->voucherPriceModel = new Voucher_PriceModel();
                
                $data['user_info'] = $this->userInfoModel->getUserInfo(array('user_id' => Perm::$userId));         //用户信息，包含用户等级
                $data['user_resource'] = $this->userResourceModel->getUserResource(array('user_id' => Perm::$userId)); //获取用户经验值和积分
                $data['ava_voucher_num'] = $this->voucherBaseModel->getAvaVoucherCountByUserId(Perm::$userId);           //用户可用代金券数量
                $data['ava_redpacket_num'] = $this->redPacketBaseModel->getAllRedPacketCountByUserId(Perm::$userId);       //已兑换订单数量
                $data['points_order_num'] = $this->pointsOrderModel->getUserPointsGoodsCount(Perm::$userId);              //已兑换订单数量
                $data['points_cart_num'] = $this->pointsCartModel->getUserPointsCartCount(Perm::$userId);                //购物车数量
                
                $User_GradeModel = new User_GradeModel();
                $user_grade_row = $User_GradeModel->getGradeList();
                $current_grade = $user_grade_row[$data['user_info']['user_grade']]; //当前等级信息
                $next_grade = $user_grade_row[$data['user_info']['user_grade'] + 1];  //下一等级信息
                $growth_diff = $data['user_resource']['user_growth'] - $current_grade['user_grade_demand'];//当前经验值与等级初始值之差
                $diff_grade_growth = $next_grade['user_grade_demand'] - $current_grade['user_grade_demand']; //两个不同等级之间的成长值差
                $data['growth']['grade_growth_start'] = $current_grade['user_grade_demand'];
                $data['growth']['grade_growth_end'] = $next_grade['user_grade_demand'];
                $data['growth']['next_grade_growth'] = $next_grade['user_grade_demand'] - $data['user_resource']['user_growth'];//距离下一级差多少经验值
                $data['growth']['grade_growth_per'] = 0.00;
                if ($diff_grade_growth > 0) {
                    $data['growth']['grade_growth_per'] = sprintf("%.2f", $growth_diff / $diff_grade_growth) * 100;
                }
                
            }
            
            if ('e' == $this->typ) {
                include $this->view->getView();
            } else {
                $this->data->addBody(-140, $data);
            }
        }
        
        /*读取优惠券*/
        public function getRedPacketById()
        {
            $redpacket_t_id = request_int('id', 0);
            $data = $this->redPacketTempModel->getRedPacketTempInfoById($redpacket_t_id);
            
            if ('e' == $this->typ) {
                $this->view->setMet('detail');
                include $this->view->getView();
            } else {
                $this->data->addBody(-140, $data);
            }
        }
        
        /*领取优惠券*/
        public function receiveRedPacket()
        {
            if (Perm::checkUserPerm()) {
                $user_id = Perm::$userId;
                $red_packet_t_id = request_int('red_packet_t_id');
                $cond_row = array();
                $cond_row['redpacket_t_id'] = $red_packet_t_id;
                $cond_row['redpacket_t_state'] = RedPacket_TempModel::VALID;
                $cond_row['redpacket_t_end_date:>='] = get_date_time();
                //获取平台优惠券详情
                $row = $this->redPacketTempModel->getRedPacketTempInfoByWhere($cond_row);
                //获取用户信息
                $user_info = $this->userInfoModel->getUserInfo(array('user_id' => Perm::$userId));
                
                if ($user_info['user_grade'] < $row['redpacket_t_user_grade_limit']) {
                    $msg = __('你的会员级别不可领取');
                    $data = array();
                    $this->data->addBody(-140, $data, $msg, $status);
                } else {
                    $redpacket_start_time = strtotime($row['redpacket_t_start_date']);
                    $redpacket_now_time = time();
                    if ($redpacket_now_time < $redpacket_start_time) {
                        $msg = __('未到领取时间');
                        $data = array();
                        $this->data->addBody(-140, $data, $msg, $status);
                    } else {
                        if ($row) {
                            $ava_flag = true;
                            if ($row['redpacket_t_total'] == $row['redpacket_t_giveout'] && $row['redpacket_t_giveout'] != 0) {
                                $ava_flag = false;
                                $msg = __('红包已被领完');
                            } else {
                                if ($row['redpacket_t_eachlimit'])  //如果限制每个人的限领张数
                                {
                                    $cond_row_base = array();
                                    $cond_row_base['redpacket_t_id'] = $red_packet_t_id;
                                    $cond_row_base['redpacket_owner_id'] = $user_id;
                                    $is_have_num = $this->redPacketBaseModel->getRedPacketNumByWhere($cond_row_base);
                                    if ($is_have_num >= $row['redpacket_t_eachlimit']) {
                                        $ava_flag = false;
                                        $msg = __('你已经达到领取数量限制');
                                    }
                                }
                            }
                            
                            if ($ava_flag) {
                                $rs_row = array();
                                $this->redPacketBaseModel->sql->startTransactionDb(); //开启事务
                                
                                $field_row['redpacket_code'] = $this->redPacketBaseModel->get_rpt_code($user_id);
                                $field_row['redpacket_t_id'] = $red_packet_t_id;
                                $field_row['redpacket_title'] = $row['redpacket_t_title'];
                                $field_row['redpacket_desc'] = $row['redpacket_t_desc'];
                                $field_row['redpacket_start_date'] = $row['redpacket_t_start_date'];
                                $field_row['redpacket_end_date'] = $row['redpacket_t_end_date'];
                                $field_row['redpacket_price'] = $row['redpacket_t_price'];
                                $field_row['redpacket_t_orderlimit'] = $row['redpacket_t_orderlimit'];
                                $field_row['redpacket_state'] = RedPacket_BaseModel::UNUSED;
                                $field_row['redpacket_active_date'] = get_date_time();
                                $field_row['redpacket_owner_id'] = $user_id;
                                $field_row['redpacket_owner_name'] = Perm::$row['user_account'];
                                $add_flag = $this->redPacketBaseModel->addRedPacket($field_row, true);
                                check_rs($add_flag, $rs_row);
                                
                                $edit_row = array();
                                $edit_row['redpacket_t_giveout'] = 1;
                                $update_flag = $this->redPacketTempModel->editRedPacketTemplate($red_packet_t_id, $edit_row, true);
                                check_rs($update_flag, $rs_row);
                                
                                if (is_ok($rs_row) && $this->redPacketBaseModel->sql->commitDb()) {
                                    $flag = true;
                                    //发送站内信
                                    //$message = new MessageModel();
                                    //$message->sendMessage('Voucher', Perm::$userId, Perm::$row['user_account'], '', $voucher_t_row['shop_name'], 0, 4, $voucher_t_row['voucher_t_end_date']);
                                } else {
                                    $this->redPacketBaseModel->sql->rollBackDb();
                                    $flag = false;
                                }
                            }
                        } else {
                            $flag = false;
                            $msg = __('优惠券不存在');
                        }
                    }
                }
                
            } else {
                $flag = false;
                $msg = __('用户尚未登录');
            }
            
            if ($flag) {
                $msg = $msg ? $msg : __('领取成功');
                $status = 200;
            } else {
                $msg = $msg ? $msg : __('领取失败');
                $status = 250;
            }
            $data = array();
            $this->data->addBody(-140, $data, $msg, $status);
        }
        
    }

?>