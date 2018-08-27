<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_Reg_OptionValueCtl extends Yf_AppController
{
    public $regOptionValueModel = null;

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
        $this->regOptionValueModel = new Reg_OptionValueModel();
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
			$data = $this->regOptionValueModel->getOptionValueList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->regOptionValueModel->getOptionValueList($cond_row, $order_row, $page, $rows);
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

		$reg_option_value_id = request_int('reg_option_value_id');
		$rows = $this->regOptionValueModel->getOptionValue($reg_option_value_id);

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
        $data['reg_option_value_id']    = request_string('reg_option_value_id'); // 选项值          
        $data['reg_option_id']          = request_string('reg_option_id') ; // 选项id          
        $data['reg_option_value_image'] = request_string('reg_option_value_image'); // 选项值图片      
        $data['reg_option_value_order'] = request_string('reg_option_value_order'); //                 
        $data['reg_option_value_name']  = request_string('reg_option_value_name'); // 选项值名称      


        $reg_option_value_id = $this->regOptionValueModel->addOptionValue($data, true);

        if ($reg_option_value_id)
        {
			$msg = _('success');
			$status = 200;
		}
        else
        {
			$msg = _('failure');
			$status = 250;
		}

        $data['reg_option_value_id'] = $reg_option_value_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $reg_option_value_id = request_int('reg_option_value_id');

        $flag = $this->regOptionValueModel->removeOptionValue($reg_option_value_id);

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

        $data['reg_option_value_id'] = array($reg_option_value_id);

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['reg_option_value_id']    = request_string('reg_option_value_id'); // 选项值          
        $data['reg_option_id']          = request_string('reg_option_id') ; // 选项id          
        $data['reg_option_value_image'] = request_string('reg_option_value_image'); // 选项值图片      
        $data['reg_option_value_order'] = request_string('reg_option_value_order'); //                 
        $data['reg_option_value_name']  = request_string('reg_option_value_name'); // 选项值名称      


        $reg_option_value_id = request_int('reg_option_value_id');
		$data_rs = $data;

        unset($data['reg_option_value_id']);

        $flag = $this->regOptionValueModel->editOptionValue($reg_option_value_id, $data);
        $this->data->addBody(-140, $data_rs);
    }
}
?>