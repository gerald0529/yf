<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}
    
    /**
     * @author     Xinze <xinze@live.cn>
     */
    class VoucherCtl extends Controller
    {
        public $userInfoModel = null;
        public $userResourceModel = null;
        public $voucherTempModel = null;
        public $voucherBaseModel = null;
        public $pointsLogModel = null;
        public $pointsCartModel = null;
        public $pointsOrderModel = null;
        public $voucherPriceModel = null;
        public $mode_fun_flag = true;    //模块功能是否开启
        
        public function __construct(&$ctl, $met, $typ)
        {
            parent ::__construct($ctl, $met, $typ);
            $this -> initData();
            $this -> web = $this -> webConfig();
            $this -> nav = $this -> navIndex();
            $this -> cat = $this -> catIndex();
            $this -> userInfoModel = new User_InfoModel();
            $this -> userResourceModel = new User_ResourceModel();
            $this -> voucherTempModel = new Voucher_TempModel();
            $this -> voucherBaseModel = new Voucher_BaseModel();
            $this -> pointsLogModel = new Points_LogModel();
            $this -> pointsCartModel = new Points_CartModel();
            $this -> pointsOrderModel = new Points_OrderModel();
            $this -> voucherPriceModel = new Voucher_PriceModel();
            //pointshop_isuse 积分中心
            //pointprod_isuse 积分兑换功能
            //voucher_allow  代金券功能
            //两者同时开启，买家才开启进行积分商品兑换
            $this -> mode_fun_flag = (Web_ConfigModel ::value('pointshop_isuse') && Web_ConfigModel ::value('pointprod_isuse') && Web_ConfigModel ::value('voucher_allow'));
            if (!$this -> mode_fun_flag) {
                $this -> showMsg("代金券兑换功能已经关闭!");
            }
        }
        
        public function vList()
        {
            $data = array();
            $cond_row = array();
            $order_row = array();
            $cond_row['voucher_t_state'] = Voucher_TempModel::VALID;
            $cond_row['voucher_t_end_date:>='] = get_date_time();
            if (request_int('store_id')) {
                $cond_row['shop_id'] = request_int('store_id');
            }
            if (request_int('vc_id')) {
                $cond_row['shop_class_id'] = request_int('vc_id');
            }
            if (request_int('price')) {
                $cond_row['voucher_t_price'] = request_int('price');
            }
            if (request_int('points_min')) {
                $cond_row['voucher_t_points:>='] = request_int('points_min');
            }
            if (request_int('points_max')) {
                $cond_row['voucher_t_points:<='] = request_int('points_max');
            }
            if (request_int('isable') && Perm ::checkUserPerm()) {
                $user_info = $this -> userInfoModel -> getUserInfo(array('user_id' => Perm ::$userId));//用户信息，包含用户等级
                $cond_row['voucher_t_user_grade_limit:<='] = $user_info['user_grade'];
            }
            //分页
            $Yf_Page = new Yf_Page();
            $Yf_Page -> listRows = request_int('listRows') ? request_int('listRows') : 12;
            $rows = $Yf_Page -> listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);
            if (request_int('page')) {
                $page = request_int('page');
            }
            //排序
            $orderby = request_string('orderby');
            switch ($orderby) {
                case 'exchangenumasc':
                    $order_row['voucher_t_giveout'] = 'ASC';
                    break;
                case 'exchangenumdesc':
                    $order_row['voucher_t_giveout'] = 'DESC';
                    break;
                case 'pointsasc':
                    $order_row['voucher_t_points'] = 'ASC';
                    break;
                case 'pointsdesc':
                    $order_row['voucher_t_points'] = 'DESC';
                    break;
                default:
                    {
                        $order_row['voucher_t_price'] = 'ASC';
                        $order_row['voucher_t_limit'] = 'ASC';
                        $order_row['voucher_t_end_date'] = 'DESC';
                        $order_row['voucher_t_add_date'] = 'ASC';
                        break;
                    }
            }
            $data['voucher'] = $this -> voucherTempModel -> getVoucherTempList($cond_row, $order_row, $page, $rows);
            $Yf_Page -> totalRows = $data['voucher']['totalsize'];
            $page_nav = $Yf_Page -> prompt();
            $Shop_ClassModel = new Shop_ClassModel();
            $data['shop_cat'] = $Shop_ClassModel -> getClassWhere();//店铺分类
            $data['price_range'] = $this -> voucherPriceModel -> getVoucherDenomination();
            if (Perm ::checkUserPerm()) {
                $data['user_info'] = $this -> userInfoModel -> getUserInfo(array('user_id' => Perm ::$userId));//用户信息，包含用户等级
                $data['user_resource'] = $this -> userResourceModel -> getUserResource(array('user_id' => Perm ::$userId));//获取用户经验值和积分
                $data['ava_voucher_num'] = $this -> voucherBaseModel -> getAvaVoucherCountByUserId(Perm ::$userId);   //用户可用代金券数量
                $data['points_order_num'] = $this -> pointsOrderModel -> getUserPointsGoodsCount(Perm ::$userId);   //已兑换订单数量
                $data['points_cart_num'] = $this -> pointsCartModel -> getUserPointsCartCount(Perm ::$userId);     //购物车数量
                $User_GradeModel = new User_GradeModel();
                $user_grade_row = $User_GradeModel -> getGradeList();
                $current_grade = $user_grade_row[$data['user_info']['user_grade']]; //当前等级信息
                $next_grade = $user_grade_row[$data['user_info']['user_grade'] + 1];  //下一等级信息
                $growth_diff = $data['user_resource']['user_growth'] - $current_grade['user_grade_demand'];//当前经验值与等级初始值之差
                $diff_grade_growth = $next_grade['user_grade_demand'] - $current_grade['user_grade_demand']; //两个不同等级之间的成长值差
                $data['growth']['grade_growth_start'] = $current_grade['user_grade_demand'];
                $data['growth']['grade_growth_end'] = $next_grade['user_grade_demand'];
                $data['growth']['next_grade_growth'] = $next_grade['user_grade_demand'] - $data['user_resource']['user_growth'];//距离下一级差多少经验值
                $data['growth']['grade_growth_per'] = $diff_grade_growth != 0 ? sprintf("%.2f", $growth_diff / $diff_grade_growth) * 100 : 0;
            }
            $this -> view -> setMet('vlist');
            if ('e' == $this -> typ) {
                include $this -> view -> getView();
            } else {
                $data['price_range'] = array_values($data['price_range']);
                $this -> data -> addBody(-140, $data);
            }
        }
        
        public function getVoucherById()
        {
            $voucher_t_id = request_int('vid');
            $cond_row['voucher_t_id'] = $voucher_t_id;
            $cond_row['voucher_t_state'] = Voucher_TempModel::VALID;
            fb($cond_row);
            $voucher_t_row = $this -> voucherTempModel -> getVoucherTempInfoByWhere($cond_row);
            $user_info = $this -> userInfoModel -> getUserInfo(array("user_id" => Perm ::$userId));
            $data = $voucher_t_row;
            if ('e' == $this -> typ) {
                $this -> view -> setMet('detail');
                include $this -> view -> getView();
            } else {
                $this -> data -> addBody(-140, $data);
            }
        }
        
        public function receiveVoucher()
        {
            if (Perm ::checkUserPerm()) {
                $voucher_t_id = request_int('vid');
                $cond_row['voucher_t_id'] = $voucher_t_id;
                $cond_row['voucher_t_state'] = Voucher_TempModel::VALID;
                $cond_row['voucher_t_end_date:>='] = get_date_time();
                $voucher_t_row = $this -> voucherTempModel -> getVoucherTempInfoByWhere($cond_row);
                $user_info = $this -> userInfoModel -> getUserInfo(array("user_id" => Perm ::$userId));
                if ($voucher_t_row) {
                    $avaliable_flag = true;
                    //当前用户等级是否满足代金券等级限制
                    if ($user_info['user_grade'] >= $voucher_t_row['voucher_t_user_grade_limit']) {
                        //如果代金券有领取数量限制，查询已领的数量
                        if ($voucher_t_row['voucher_t_eachlimit'] > 0) {
                            $cond_row_voucher['voucher_t_id'] = $voucher_t_id;
                            $cond_row_voucher['voucher_owner_id'] = Perm ::$userId;
                            $owner_voucher_num = $this -> voucherBaseModel -> getVoucherNumByWhere($cond_row_voucher);
                            //已经领取的数量小于代金券模板限定的可领取数量
                            if ($owner_voucher_num < $voucher_t_row['voucher_t_eachlimit']) {
                                $avaliable_flag = true;
                            } else {
                                $avaliable_flag = false;
                                $msg = __('您已达到代金券领取数量限制！');
                            }
                        } else {
                            $avaliable_flag = true;
                        }
                    } else {
                        $avaliable_flag = false;
                        $msg = __('对不起，您的等级不够！');
                    }
                    if ($voucher_t_row['voucher_t_giveout'] == $voucher_t_row['voucher_t_total']) {
                        $avaliable_flag = false;
                        $msg = __('代金券已被领完！');
                    }
                    if ($voucher_t_row['voucher_t_access_method'] == Voucher_TempModel::GETBYPOINTS) {
                        $user_resource = $this -> userResourceModel -> getOne(Perm ::$userId);//获取用户经验值和积分
                        if ($voucher_t_row['voucher_t_points'] > $user_resource['user_points']) {
                            $avaliable_flag = false;
                            $msg = __('积分不足，无法兑换！');
                        }
                    }
                    if ($avaliable_flag) {
                        $rs_row = array();
                        $this -> voucherTempModel -> sql -> startTransactionDb();
                        $field_row['voucher_t_id'] = $voucher_t_row['voucher_t_id'];
                        $field_row['voucher_title'] = $voucher_t_row['voucher_t_title'];
                        $field_row['voucher_desc'] = $voucher_t_row['voucher_t_desc'];
                        $field_row['voucher_start_date'] = $voucher_t_row['voucher_t_start_date'];
                        $field_row['voucher_end_date'] = $voucher_t_row['voucher_t_end_date'];
                        $field_row['voucher_price'] = $voucher_t_row['voucher_t_price'];
                        $field_row['voucher_limit'] = $voucher_t_row['voucher_t_limit'];
                        $field_row['voucher_shop_id'] = $voucher_t_row['shop_id'];
                        $field_row['voucher_state'] = Voucher_BaseModel::UNUSED;
                        $field_row['voucher_active_date'] = date('Y-m-d H:i:s');
                        //$field_row['voucher_type'] = $voucher_t_row[''];
                        $field_row['voucher_owner_id'] = Perm ::$userId;
                        $field_row['voucher_owner_name'] = $user_info['user_name'];
                        $add_flag = $this -> voucherBaseModel -> addVoucher($field_row, true); //用户增加代金券
                        check_rs($add_flag, $rs_row);
                        //更新代金券已领取数量
                        $update_field_row['voucher_t_giveout'] = 1;
                        $update_flag = $this -> voucherTempModel -> editVoucherTemplate($voucher_t_id, $update_field_row, true);
                        check_rs($update_flag, $rs_row);
                        //积分兑换的代金券需要扣除用户的积分，同时添加积分记录
                        if ($voucher_t_row['voucher_t_access_method'] == Voucher_TempModel::GETBYPOINTS) {
                            $cut_points_row['user_points'] = '-' . $voucher_t_row['voucher_t_points'];
                            $edit_points_flag = $this -> userResourceModel -> editResource(Perm ::$userId, $cut_points_row, true);
                            check_rs($edit_points_flag, $rs_row);
                            $field_row_p_log['points_log_type'] = 2;
                            $field_row_p_log['class_id'] = 8;
                            $field_row_p_log['user_id'] = Perm ::$userId;
                            $field_row_p_log['user_name'] = $user_info['user_name'];
                            $field_row_p_log['points_log_points'] = $voucher_t_row['voucher_t_points'];
                            $field_row_p_log['points_log_time'] = get_date_time();
                            $field_row_p_log['points_log_desc'] = __('兑换代金券');
                            $add_log_flag = $this -> pointsLogModel -> addLog($field_row_p_log, true);
                            check_rs($add_log_flag, $rs_row);
                        }
                        if (is_ok($rs_row) && $this -> voucherTempModel -> sql -> commitDb()) {
                            $flag = true;
                            //发送站内信 优惠券到账
                            $message = new MessageModel();
                            $message -> sendMessage('Voucher', Perm ::$userId, Perm ::$row['user_account'], '', $voucher_t_row['shop_name'], 0, MessageModel::USER_MESSAGE, $voucher_t_row['voucher_t_end_date']);
                        } else {
                            $this -> voucherTempModel -> sql -> rollBackDb();
                            $flag = false;
                        }
                    } else {
                        $flag = false;
                    }
                } else {
                    $flag = false;
                    $msg = __('代金券不存在！');
                }
            } else {
                $flag = false;
                $msg = __('用户尚未登录！');
            }
            if ($flag) {
                $msg = $msg ? $msg : __('领取成功');
                $status = 200;
            } else {
                $msg = $msg ? $msg : __('领取失败');
                $status = 250;
            }
            $data = $this -> voucherTempModel -> getOne($voucher_t_id);
            $this -> data -> addBody(-140, $data, $msg, $status);
        }
    }

?>
