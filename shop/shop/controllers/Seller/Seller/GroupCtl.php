<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     叶赛
 * 卖家账号权限组控制器类
 */
class Seller_Seller_GroupCtl extends Seller_Controller
{
    public $sellerGroupModel = null;

    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
        $this->sellerGroupModel = new Seller_GroupModel();
    }

   /* 店铺卖家账号组列表*/
    public function groupList()
    {
        $data = array();
        if(request_string('act') == 'add')
        {
            $this->view->setMet('group_add');
        }
        elseif(request_string('act') == 'edit')
        {
            $group_id = request_int('group_id');

            $cond_row               = array();
            $cond_row['group_id']   = $group_id;
            $cond_row['shop_id']    = Perm::$shopId;
            $seller_group_info =  $this->sellerGroupModel->getOneByWhere($cond_row);
            if (empty($seller_group_info))
            {
                location_go_back('组不存在');
            }
            else
            {
                $data['group_info']     = $seller_group_info ;
                $data['group_limits']   = explode(',', $seller_group_info['limits']);
            }

            $this->view->setMet('group_add');
        }
        else
        {
            $cond_row               = array();
            $cond_row['shop_id']    = Perm::$shopId;

            $order_row = array();
            //分页
            $Yf_Page                    = new Yf_Page();
            $Yf_Page->listRows          = 10;
            $rows                       = $Yf_Page->listRows;
            $offset                     = request_int('firstRow', 0);
            $page                       = ceil_r($offset / $rows);

            $data =  $this->sellerGroupModel->getSellerGroupList($cond_row,$order_row,$page,$rows);
            $Yf_Page->totalRows = $data['totalsize'];
            $page_nav           = $Yf_Page->prompt();

            $this->view->setMet('group_list');
        }

        if('json' == $this->typ)
        {
            $this->data->addBody(-140, $data);
        }
        else
        {
            include $this->view->getView();
        }
    }


    //添加、编辑卖家权限组
    public function saveGroup()
    {
        $field_row = array();
        $field_row['group_name']  = request_string('seller_group_name');
        $limits =  request_row('limits');
        //Seller_Goods 和 Seller_Goods_TBImport 用到了 Seller_Goods_Cat 中的方法，所以必须有 Seller_Goods_Cat 权限
        if(in_array('Seller_Goods', $limits) || in_array('Seller_Goods_TBImport', $limits)){
            $limits[] = 'Seller_Goods_Cat';
        }
        
        $field_row['limits']      = implode(',',array_unique($limits));
        $field_row['shop_id']     = Perm::$shopId;

        $group_id = request_int('group_id',0);
        if ($group_id > 0)
        {
            $condition      = array();
            $condition['group_id'] = $group_id;
            $condition['shop_id']  = Perm::$shopId;
            $group_row = $this->sellerGroupModel->getOneByWhere($condition);
            if($group_row)
            {
                $this->sellerGroupModel->editGroup($group_id, $field_row);
                $flag = true;
            }

            $op_text        = __('编辑组');
            $op_group_id    = $group_id;
        }
        else
        {
            $flag  =  $this->sellerGroupModel->addGroup($field_row,true);
            $data['op_flag'] = $flag;
            $op_text = __('添加组');
            $op_group_id = $flag;
        }

        if ($flag)
        {
            $msg    = $op_text.__('成功');
            $status = 200;

        }
        else
        {
            $msg    = $op_text.__('失败！');
            $status = 250;
        }

        //添加卖家操作日志
        //$this->recordSellerLog($msg.'，组编号'.$op_group_id);

        $data['group_id'] = $group_id;
        $this->data->addBody(-140, $data, $msg, $status);
    }

    //删除权限组
    public function removeGroup()
    {
        $group_id = request_row('id');
        $group_id = current($group_id);

        $cond_row = array();
        $cond_row['group_id'] = $group_id;
        $cond_row['shop_id']  = Perm::$shopId;
        $group_row = $this->sellerGroupModel->getOneByWhere($cond_row);

        //删除账号组之前需要检查该账号组下是否存在卖家账号，如果存在，则不允许删除？
        if($group_row)
        {
          $flag =  $this->sellerGroupModel->removeGroup($group_id);
        }
        else
        {
          $flag = false;
        }

        if ($flag)
        {

          $msg    = __('删除组成功！');
          $status = 200;
        }
        else
        {
          $msg    = __('删除组失败！');
          $status = 250;
        }

        //添加卖家操作日志
        //$this->recordSellerLog($msg.'，组编号'.$group_id);

        $data['group_id']  = $group_id;
        $this->data->addBody(-140, $data, $msg, $status);
    }



}
