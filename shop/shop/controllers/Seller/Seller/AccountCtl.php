<?php if (!defined('ROOT_PATH')) {
    exit('No Permission');
}

/**
 * @author     叶赛
 * 卖家账号控制器类
 */
class Seller_Seller_AccountCtl extends Seller_Controller
{
    public $sellerBaseModel = null;
    public $sellerGroupModel = null;

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
        $this->sellerBaseModel = new Seller_BaseModel();
        $this->sellerGroupModel = new Seller_GroupModel();
    }

    /*获取店铺卖家用户列表*/
    public function accountList()
    {
        if (request_string('act') == 'add') {
            $cond_row = array();
            $cond_row['shop_id'] = Perm::$shopId;
            $data = $this->sellerGroupModel->getSellerGroup($cond_row);

            if (empty($data)) {
                //权限组不存在，需要先创建权限组
                location_to(Yf_Registry::get('url') . '?ctl=Seller_Seller_Group&met=groupList&act=add&typ=e');
            }

            $this->view->setMet('account_add');
        } elseif (request_string('act') == 'edit') {
            $data = array();
            $seller_id = request_int('seller_id');
            $cond_row = array();
            $cond_row['seller_id'] = $seller_id;
            $cond_row['shop_id'] = Perm::$shopId;
            $seller_info = $this->sellerBaseModel->getOneByWhere($cond_row);
            $data['seller_info'] = $seller_info;
            if (empty($seller_info)) {
                location_go_back('卖家账号不存在');
            }

            $cond_row = array();
            $cond_row['shop_id'] = Perm::$shopId;
            $seller_group_list = $this->sellerGroupModel->getSellerGroup($cond_row);
            $data['seller_group_list'] = $seller_group_list;
            if (empty($seller_group_list)) {
                location_go_back('请先建立账号组');
            }

            $this->view->setMet('account_edit');
        } else {
            $cond_row = array();
            $order_row = array();
            $cond_row['shop_id'] = Perm::$shopId;
            $cond_row['seller_group_id:>'] = 0;
            $order_row['seller_login_time'] = 'DESC';

            //分页
            $Yf_Page = new Yf_Page();
            $Yf_Page->listRows = 10;
            $rows = $Yf_Page->listRows;
            $offset = request_int('firstRow', 0);
            $page = ceil_r($offset / $rows);

            $data = $this->sellerBaseModel->getBaseList($cond_row, $order_row, $page, $rows);
            $Yf_Page->totalRows = $data['totalsize'];
            $page_nav = $Yf_Page->prompt();

            if ($data['items']) {
                $seller_group_id = array_column($data['items'], 'seller_group_id');
                $seller_group_rows = $this->sellerGroupModel->getByWhere(array('group_id:IN' => $seller_group_id));
                foreach ($data['items'] as $key => $value) {
                    if (in_array($value['seller_group_id'], array_keys($seller_group_rows))) {
                        $data['items'][$key]['group_name'] = $seller_group_rows[$value['seller_group_id']]['group_name'];
                    } else {
                        $data['items'][$key]['group_name'] = __('未知的用户组');
                    }
                }
            }

            $this->view->setMet('account_list');
        }

        if ('json' == $this->typ) {
            $this->data->addBody(-140, $data);
        } else {
            include $this->view->getView();
        }
    }

    /*保存添加的卖家账号信息
     *保存之前需要验证改账号的合法性
    **/
    public function saveAccount()
    {
        $data = array();
        $rs_row = array(true);

        $seller_name = request_string('seller_name');
        $password = request_string('password');
        $group_id = request_int('group_id');

        $user_info = $this->checkSellerMember($seller_name, $password); //验证账户合法性
        $data['user'] = $user_info;
        if (!$user_info) {
            check_rs(false, $rs_row);
            $msg_label = __('用户验证失败');
        }

        if (is_ok($rs_row) && !$this->checkIsSelfSupport($user_info['user_id'])) {
            check_rs(false, $rs_row);
            $msg_label = __('自营店铺不能添加');    //一个用户不能管理多个店铺
        }

        if (is_ok($rs_row) && $this->isSellerNameExist($seller_name)) {
            check_rs(false, $rs_row);
            $msg_label = __('卖家账号已存在');    //一个用户不能管理多个店铺
        }

        if (is_ok($rs_row)) {
            $field_row = array();
            $field_row['seller_name'] = $user_info['user_name'];
            $field_row['user_id'] = $user_info['user_id'];
            $field_row['seller_group_id'] = $group_id;
            $field_row['shop_id'] = Perm::$shopId;
            $field_row['seller_is_admin'] = 0;
            $field_row['seller_login_time'] = get_date_time();
            $flag = $this->sellerBaseModel->addBase($field_row, true);
        } else {
            $flag = false;
        }

        if ($flag) {
            $msg = __('添加成功！');
            $status = 200;
        } else {
            $msg = isset($msg_label) ? $msg_label : __('添加失败！');
            $status = 250;
        }

        $data['seller_id'] = $flag;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**保存编辑后的卖家账号信息
     * 验证提交的用户信息
     * 验证提交的用户组信息
     */
    public function editAccountSave()
    {
        $rs_row = array(true);

        $seller_id = request_int('seller_id');
        $group_id = request_int('group_id');

        $cond_row = array();
        $cond_row['seller_id'] = $seller_id;
        $cond_row['shop_id'] = Perm::$shopId;
        $seller_info = $this->sellerBaseModel->getOneByWhere($cond_row);
        if (empty($seller_info)) {
            check_rs(false, $rs_row);
            $msg_label = __('参数有误');
        }

        $cond_row = array();
        $cond_row['group_id'] = $group_id;
        $cond_row['shop_id'] = Perm::$shopId;
        $group_info = $this->sellerGroupModel->getOneByWhere($cond_row);
        if (is_ok($rs_row) && empty($group_info)) {
            check_rs(false, $rs_row);
            $msg_label = __('参数有误');
        }

        if (is_ok($rs_row)) {
            $field_row = array();
            $field_row['seller_group_id'] = $group_id;
            $this->sellerBaseModel->editBase($seller_id, $field_row);
            $flag = true;
        } else {
            $flag = false;
        }

        if ($flag) {
            $msg = __('编辑成功！');
            $status = 200;
        } else {
            $msg = isset($msg_label) ? $msg_label : __('编辑失败！');
            $status = 250;
        }
        $data['seller_id'] = $seller_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /*
     * 删除卖家账号
     * id
    */
    public function removeAccount()
    {
        $ids = request_row('id');
        $seller_id = current($ids);
        $cond_row = array();
        $cond_row['seller_id'] = $seller_id;
        $cond_row['shop_id'] = Perm::$shopId;
        $seller_base = $this->sellerBaseModel->getOneByWhere($cond_row);
        if (!empty($seller_base)) {
            $flag = $this->sellerBaseModel->removeBase($seller_id);
        } else {
            $flag = false;
        }


        if ($flag) {
            $msg = __('删除成功！');
            $status = 200;
        } else {
            $msg = __('添加失败！');
            $status = 250;
        }

        $data['seller_id'] = $seller_id;
        $this->data->addBody(-140, $data, $msg, $status);
    }

    //ajax 验证用户合法性
    public function checkSeller()
    {
        $seller_name = request_string('seller_name');
        $password = request_string('password');
        $flag = $this->checkSellerMember($seller_name, $password);

        if ($flag) {
            $msg = __('success！');
            $status = 200;
        } else {
            $msg = __('用户不存在！');
            $status = 250;
        }
        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);
    }

    //检查卖家账号是否已经存在
    private function isSellerNameExist($seller_name)
    {
        $cond_row = array();
        $cond_row['seller_name'] = $seller_name;

        return $this->sellerBaseModel->isSellerExist($cond_row);
    }


    /**验证卖家账号信息，包括两部分
     * 1、根据用户名和密码验证该用户是否存在
     * 2、根据用户ID验证该用户是否有已经拥有卖家身份，是否与某一个店铺绑定
     */
    private function checkSellerMember($user_name, $password)
    {
        $user_info = $this->checkSellerPassword($user_name, $password);
        if ($user_info && !$this->isSellerMemberExist($user_info['user_id'])) {
            return $user_info;
        } else {
            return false;
        }
    }


    private function checkIsSelfSupport($user_id)
    {
        $shopBaseModel = new Shop_BaseModel();
        $cond_row['user_id'] = $user_id;
        $shop_base = $shopBaseModel->getOneByWhere($cond_row);

        if ($shop_base['shop_self_support'] === "true") {
            return false;
        } else {
            return true;
        }
    }


    //此处需要向ucenter发送查询请求,类内调用
    private function checkSellerPassword($user_name, $password)
    {
        //本地读取远程信息
        $key = Yf_Registry::get('shop_api_key');
        $url = Yf_Registry::get('ucenter_api_url');
        $app_id = Yf_Registry::get('ucenter_app_id');

        $formvars = array();
        $formvars['user_name'] = $user_name;
        $formvars['password'] = $password;
        $formvars['app_id'] = $app_id;


        fb($formvars);
        $rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User&met=checkUserAccount&typ=json', $url), $formvars);
        fb($rs);
        if (200 == $rs['status']) {
            return $user_info = $rs['data'];
        } else {
            return false;
        }
    }

    //验证卖家账号是否已经存在,类内调用
    private function isSellerMemberExist($seller_id)
    {
        $cond_row = array();
        $cond_row['seller_id'] = $seller_id;
        return $this->sellerBaseModel->isSellerExist($cond_row);
    }

}
