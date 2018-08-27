<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Base_AppCtl extends Yf_AppController
{
    public $baseAppModel = null;

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
        $this->baseAppModel = new Base_AppModel();
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
    public function AppList()
    {
        $user_id = Perm::$userId;

		$page = $_REQUEST['page'];
		$rows = $_REQUEST['rows'];
		$sort = $_REQUEST['sord'];


		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->baseAppModel->getAppList('*', $page, $rows, $sort);
		}
		else
		{
			$data = $this->baseAppModel->getAppList('*', $page, $rows, $sort);
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

		$app_id = $_REQUEST['app_id'];
		$rows = $this->baseAppModel->getApp($app_id);

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
        $data['app_id']                 = $_REQUEST['app_id']             ; // 服务ID          
        $data['app_name']               = $_REQUEST['app_name']           ; // 服务名称        
        $data['app_type']               = $_REQUEST['app_type']           ; // 服务类型        
        $data['app_seq']                = $_REQUEST['app_seq']            ; // 顺序号          
        $data['app_key']                = $_REQUEST['app_key']            ; // 服务密钥        
        $data['app_ip_list']            = $_REQUEST['app_ip_list']        ; // 服务 IP 列表    
        $data['app_url']                = $_REQUEST['app_url']            ; // 服务网址        
        $data['app_url_recharge']       = $_REQUEST['app_url_recharge']   ; //                 
        $data['app_url_order']          = $_REQUEST['app_url_order']      ; // 检查订单是否存在的url地址
        $data['app_logo']               = $_REQUEST['app_logo']           ; // LOGO 图片地址   
        $data['app_hosts']              = $_REQUEST['app_hosts']          ; // 域名列表        
        $data['return_fields']          = $_REQUEST['return_fields']      ; // 返回字段        


        $app_id = $this->baseAppModel->addApp($data, true);

        if ($app_id)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }

        $data['app_id'] = $app_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $app_id = $_REQUEST['app_id'];

        $flag = $this->baseAppModel->removeApp($app_id);

        if ($flag)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }

        $data['app_id'] = $app_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['app_id']                 = $_REQUEST['app_id']             ; // 服务ID          
        $data['app_name']               = $_REQUEST['app_name']           ; // 服务名称        
        $data['app_type']               = $_REQUEST['app_type']           ; // 服务类型        
        $data['app_seq']                = $_REQUEST['app_seq']            ; // 顺序号          
        $data['app_key']                = $_REQUEST['app_key']            ; // 服务密钥        
        $data['app_ip_list']            = $_REQUEST['app_ip_list']        ; // 服务 IP 列表    
        $data['app_url']                = $_REQUEST['app_url']            ; // 服务网址        
        $data['app_url_recharge']       = $_REQUEST['app_url_recharge']   ; //                 
        $data['app_url_order']          = $_REQUEST['app_url_order']      ; // 检查订单是否存在的url地址
        $data['app_logo']               = $_REQUEST['app_logo']           ; // LOGO 图片地址   
        $data['app_hosts']              = $_REQUEST['app_hosts']          ; // 域名列表        
        $data['return_fields']          = $_REQUEST['return_fields']      ; // 返回字段        


        $app_id = $_REQUEST['app_id'];
		$data_rs = $data;

        unset($data['app_id']);

        $flag = $this->baseAppModel->editApp($app_id, $data);
        $this->data->addBody(-140, $data_rs);
    }
}
?>