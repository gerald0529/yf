<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Api_Trade_ReportCtl extends Api_Controller
{

    public $Report_BaseModel    = null;
    public $Report_SubjectModel = null;
    public $Report_TypeModel    = null;
    public $Goods_BaseModel     = null;
    public $Goods_CommonModel   = null;

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
        $this->Report_BaseModel    = new Report_BaseModel();
        $this->Report_SubjectModel = new Report_SubjectModel();
        $this->Report_TypeModel    = new Report_TypeModel();
        $this->Goods_BaseModel     = new Goods_BaseModel();
        $this->Goods_CommonModel   = new Goods_CommonModel();
    }

    public function getTypeList()
    {
        $page     = request_int('page', 1);
        $rows     = request_int('rows', 10);
        $oname    = request_string('sidx');
        $osort    = request_string('sord');
        $cond_row = array();
        $sort     = array();
        $data     = array();
        $data     = $this->Report_TypeModel->getCatList($cond_row, $sort, $page, $rows);
        $this->data->addBody(-140, $data);
    }

    public function addTypeBase()
    {
        $field['report_type_name'] = request_string("report_type_name");
        $field['report_type_desc'] = request_string("report_type_desc");
        $flag                      = $this->Report_TypeModel->addCat($field, true);
        if ($flag)
        {
            $msg    = __('success');
            $status = 200;
        }
        else
        {
            $msg    = __('failure');
            $status = 250;
        }

        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function editType()
    {
        $id   = request_int("id");
        $data = $this->Report_TypeModel->getOne($id);
        $this->data->addBody(-140, $data);
    }

    public function editTypeBase()
    {
        $id                        = request_int("report_type_id");
        $field['report_type_name'] = request_string("report_type_name");
        $field['report_type_desc'] = request_string("report_type_desc");
        $flag                      = $this->Report_TypeModel->editCat($id, $field);
        if ($flag)
        {
            $msg    = __('success');
            $status = 200;
        }
        else
        {
            $msg    = __('failure');
            $status = 250;
        }

        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function delType()
    {
        $id   = request_int("id");
        $flag = $this->Report_TypeModel->removeCat($id);
        if ($flag)
        {
            $msg    = __('success');
            $status = 200;
        }
        else
        {
            $msg    = __('failure');
            $status = 250;
        }

        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function getSubjectList()
    {
        $page     = request_int('page', 1);
        $rows     = request_int('rows', 10);
        $oname    = request_string('sidx');
        $osort    = request_string('sord');
        $cond_row = array();
        $sort     = array();
        $data     = array();
        $data     = $this->Report_SubjectModel->getCatList($cond_row, $sort, $page, $rows);
        $this->data->addBody(-140, $data);
    }

    public function addSubjectBase()
    {
        $field['report_subject_name'] = request_string("report_subject_name");
        $field['report_type_id']      = request_int("report_type_id");
        $type                         = $this->Report_TypeModel->getOne($field['report_type_id']);
        $field['report_type_name']    = $type['report_type_name'];
        $flag                         = $this->Report_SubjectModel->addCat($field, true);
        if ($flag)
        {
            $msg    = __('success');
            $status = 200;
        }
        else
        {
            $msg    = __('failure');
            $status = 250;
        }

        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function addSubject()
    {
        $id   = request_int("id");
        $data = $this->Report_TypeModel->getByWhere();
        $this->data->addBody(-140, $data);
    }

    public function editSubject()
    {
        $id           = request_int("id");
        $data         = $this->Report_SubjectModel->getOne($id);
        $data['type'] = $this->Report_TypeModel->getByWhere();
        $this->data->addBody(-140, $data);
    }

    public function editSubjectBase()
    {
        $id                           = request_int("report_subject_id");
        $field['report_subject_name'] = request_string("report_subject_name");
        $field['report_type_id']      = request_int("report_type_id");
        $type                         = $this->Report_TypeModel->getOne($field['report_type_id']);
        $field['report_type_name']    = $type['report_type_name'];
        $flag                         = $this->Report_SubjectModel->editCat($id, $field);
        if ($flag)
        {
            $msg    = __('success');
            $status = 200;
        }
        else
        {
            $msg    = __('failure');
            $status = 250;
        }

        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function delSubject()
    {
        $id   = request_int("id");
        $flag = $this->Report_SubjectModel->removeCat($id);
        if ($flag)
        {
            $msg    = __('success');
            $status = 200;
        }
        else
        {
            $msg    = __('failure');
            $status = 250;
        }

        $data = array();

        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function getReportList()
    {
        $goods_name          = request_string("goods_name");
        $shop_name           = request_string("shop_name");
        $user_account        = request_string("user_account");
        $report_subject_name = request_string("report_subject_name");
        $report_type_name    = request_string("report_type_name");

        $page     = request_int('page', 1);
        $rows     = request_int('rows', 10);
        $oname    = request_string('sidx');
        $osort    = request_string('sord');
        $cond_row = array();
        $sort     = array();
        if ($oname != "number")
        {
            $sort[$oname] = $osort;
        }

        if ($goods_name)
        {
            $cond_row['goods_name:LIKE'] = '%' . $goods_name . '%';
        }
        if ($shop_name)
        {
            $cond_row['shop_name'] = $shop_name;
        }
        if ($user_account)
        {
            $cond_row['user_account'] = $user_account;
        }
        if ($report_subject_name)
        {
            $cond_row['report_subject_name'] = $report_subject_name;
        }
        if ($report_type_name)
        {
            $cond_row['report_type_name'] = $report_type_name;
        }
        $cond_row['report_state'] = Report_BaseModel::REPORT_DO;
        $data                     = array();
        $data                     = $this->Report_BaseModel->getCatList($cond_row, $sort, $page, $rows);
        $this->data->addBody(-140, $data);
    }

    public function getReportDoneList()
    {
        $goods_name          = request_string("goods_name");
        $shop_name           = request_string("shop_name");
        $user_account        = request_string("user_account");
        $report_subject_name = request_string("report_subject_name");
        $report_type_name    = request_string("report_type_name");

        $page     = request_int('page', 1);
        $rows     = request_int('rows', 10);
        $oname    = request_string('sidx');
        $osort    = request_string('sord');
        $cond_row = array();
        $sort     = array();
        if ($oname != "number")
        {
            $sort[$oname] = $osort;
        }

        if ($goods_name)
        {
            $cond_row['goods_name:LIKE'] = '%' . $goods_name . '%';
        }
        if ($shop_name)
        {
            $cond_row['shop_name'] = $shop_name;
        }
        if ($user_account)
        {
            $cond_row['user_account'] = $user_account;
        }
        if ($report_subject_name)
        {
            $cond_row['report_subject_name'] = $report_subject_name;
        }
        if ($report_type_name)
        {
            $cond_row['report_type_name'] = $report_type_name;
        }
        $cond_row['report_state'] = Report_BaseModel::REPORT_DONE;
        $data                     = array();
        $data                     = $this->Report_BaseModel->getCatList($cond_row, $sort, $page, $rows);
        $this->data->addBody(-140, $data);
    }

    public function detail()
    {
        $data['id'] = request_int('id');
        $id         = request_int('id');
        $data       = $this->Report_BaseModel->getOne($id);
        $this->data->addBody(-140, $data);
    }


    public function doReport()
    {
        $id                             = request_int('report_id');
        $handle                         = request_string("handle");
        $field['report_handle_message'] = request_string("report_handle_message");
        $field['report_state']          = Report_BaseModel::REPORT_DONE;
        if ($handle == "pass")
        {
            $field['report_handle_state'] = Report_BaseModel::REPORT_USEFUL;

            $edit_flag = $this->Report_BaseModel->editCat($id, $field);
            $goods                       = $this->Report_BaseModel->getOne($id);
            $commom                      = $this->Goods_BaseModel->getOne($goods['goods_id']);
            $goods_field['common_state'] = Goods_CommonModel::GOODS_STATE_ILLEGAL;
            $edit_flag2                   = $this->Goods_CommonModel->editCommon($commom['common_id'], $goods_field);

            if ($edit_flag)
            {
                $Shop_BaseModel = new Shop_BaseModel();
                $shop_info = $Shop_BaseModel->getOne($goods['shop_id']);
                $message = new MessageModel();
                $msg = $message->sendMessage('Commodity violation is under the shelf',$shop_info['user_id'], $shop_info['user_name'], $order_id = NULL, $shop_name = NULL, 1, 2, $end_time = Null,$common_id=NULL,$goods_id=NULL,$field['report_handle_message']);

                //加入拼团商品判断，商品违规下架后，相关拼团活动取消
                $pintuanModel = new PinTuan_Base;
                $pintuanModel->cancelByCommonId($commom['common_id']);
                $msg    = __('success');
                $status = 200;

            }
            else
            {
                $msg    = __('failure');
                $status = 250;
            }

        }
        else
        {
            $field['report_handle_state'] = Report_BaseModel::REPORT_USELESS;
            $flag                         = $this->Report_BaseModel->editCat($id, $field);
            if ($flag)
            {
                $msg    = __('success');
                $status = 200;
            }
            else
            {
                $msg    = __('failure');
                $status = 250;
            }
        }
        $data = array();
        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function look()
    {
        $data['id'] = request_int('id');
        $id         = request_int('id');
        $data       = $this->Report_BaseModel->getReportLook($id);
        $this->data->addBody(-140, $data);
    }
}

?>