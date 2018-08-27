<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Reg_OptionCtl extends Yf_AppController
{
    public $regOptionModel = null;

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

        //include $this->view->getView();
        $this->regOptionModel = new Reg_OptionModel();
    }

    /**
     * 首页
     * 
     * @access public
     */
    public function index()
    {
        include $this->view->getView();
    }
    
    /**
     * 管理界面
     * 
     * @access public
     */
    public function manage()
    {
        include $this->view->getView();
    }

    /**
     * 列表数据
     * 
     * @access public
     */
    public function lists()
    {
        $user_id = Perm::$userId;

		$page = request_int('page');
		$rows = request_int('rows');
		$sort = request_int('sord');

		$cond_row  = array();
		$order_row = array();

		$data = array();

		if ($skey = request_string('skey'))
		{
			$data = $this->regOptionModel->getOptionList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->regOptionModel->getOptionList($cond_row, $order_row, $page, $rows);
		}


		$this->data->addBody(-140, $data);
    }

    /**
     * 读取
     * 
     * @access public
     */
    public function get()
    {
        $user_id = Perm::$userId;

		$reg_option_id = request_int('reg_option_id');
		$rows = $this->regOptionModel->getOption($reg_option_id);

		$data = array();

		if ($rows)
		{
			$data = array_pop($rows);
		}

		$this->data->addBody(-140, $data);
    }

    /**
     * 添加
     *
     * @access public
     */
    public function add()
    {
        $data['reg_option_id']          = request_string('reg_option_id') ; // 选项值          
        $data['reg_option_name']        = request_string('reg_option_name'); //                 
        $data['reg_option_order']       = request_string('reg_option_order'); //                 
        $data['option_id']              = request_string('option_id')     ; // 选项id          
        $data['reg_option_required']    = request_string('reg_option_required'); // 是否必须(BOOL):0-非必填;1-必填
        $data['reg_option_placeholder'] = request_string('reg_option_placeholder'); // placeholder     
        $data['reg_option_datatype']    = request_string('reg_option_datatype'); // data_type
        $data['reg_option_value']       = request_string('reg_option_value'); // data_type
        $data['reg_option_active']       = request_string('reg_option_active'); // data_type
        
        if(!$data['reg_option_name'] || in_array($data['reg_option_name'], array(_('性别'),_('手机'),_('邮箱'),_('真实姓名'),_('生日'),_('所在地区'),_('QQ')))){
            $check_name = false;
        }else{
            $check_name = $this->regOptionModel->getByWhere(array('reg_option_name'=>$data['reg_option_name']));
        }
        if(is_array($check_name) && $check_name){
            $data = array();
            $msg = _('该属性名已经存在');
			$status = 250;
        }else{

            $reg_option_id = $this->regOptionModel->addOption($data, true);

            if ($reg_option_id)
            {
                $msg = _('success');
                $status = 200;
            }
            else
            {
                $msg = _('failure');
                $status = 250;
            }

            $data['reg_option_id'] = $reg_option_id;
        }

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $reg_option_id = request_int('reg_option_id');

        $flag = $this->regOptionModel->removeOption($reg_option_id);

        if ($flag)
		{
			$msg = _('success');
			$status = 200;
		}
		else
		{
			$msg = _('failure');
			$status = 250;
		}

        $data['reg_option_id'] = array($reg_option_id);

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['reg_option_id']          = request_string('reg_option_id') ; // 选项值          
        $data['reg_option_name']        = request_string('reg_option_name'); //                 
        $data['reg_option_order']       = request_string('reg_option_order'); //                 
        $data['option_id']              = request_string('option_id')     ; // 选项id          
        $data['reg_option_required']    = request_string('reg_option_required'); // 是否必须(BOOL):0-非必填;1-必填
        $data['reg_option_placeholder'] = request_string('reg_option_placeholder'); // placeholder     
        $data['reg_option_datatype']    = request_string('reg_option_datatype'); // data_type       
        $data['reg_option_value']       = request_string('reg_option_value'); // data_type
        $data['reg_option_active']      = request_string('reg_option_active'); // data_type
        if(!$data['reg_option_name'] || in_array($data['reg_option_name'], array(_('性别'),_('手机'),_('邮箱'),_('真实姓名'),_('生日'),_('所在地区'),_('QQ')))){
            $check_name = false;
        }else{
            $check_name = $this->regOptionModel->getByWhere(array('reg_option_name'=>$data['reg_option_name'],'reg_option_id:!='=>$data['reg_option_id']));
        }
        if(is_array($check_name) && $check_name){
            $data_rs = array();
            $msg = _('该属性名已经存在');
            $status = 250;
        }else{

            $reg_option_id = request_int('reg_option_id');
            $data_rs = $data;

            unset($data['reg_option_id']);

            $flag = $this->regOptionModel->editOption($reg_option_id, $data);
            $data['reg_option_id'] = $reg_option_id;
            $msg = _('success');
			$status = 200;
        }
        $this->data->addBody(-140, $data, $msg, $status);
    }

    public function enable()
    {
        $reg_option_id = request_int('reg_option_id');
        $reg_option_id_enable = request_int('reg_option_id_enable');

        $userInfoModel = new User_InfoModel();

        if($reg_option_id)
        {
            $data['reg_option_active']      = $reg_option_id_enable; // data_type

            $flag = $this->regOptionModel->editOption($reg_option_id, $data);

            if(false !== $flag)
            {
                $msg = 'success';
                $status = 200;
            }
            else
            {
                $msg = 'failure';
                $status = 250;
            }
        }
        $this->data->addBody(-140,array(),$msg,$status);
    }
}
?>