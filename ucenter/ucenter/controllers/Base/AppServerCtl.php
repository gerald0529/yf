<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Base_AppServerCtl extends Yf_AppController
{
    public $baseAppServerModel = null;

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
        $this->baseAppServerModel = new Base_AppServerModel();
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
    public function AppServerList()
    {
        $user_id = Perm::$userId;

		$page = $_REQUEST['page'];
		$rows = $_REQUEST['rows'];
		$sort = $_REQUEST['sord'];


		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->baseAppServerModel->getAppServerList('*', $page, $rows, $sort);
		}
		else
		{
			$data = $this->baseAppServerModel->getAppServerList('*', $page, $rows, $sort);
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

		$server_id = $_REQUEST['server_id'];
		$rows = $this->baseAppServerModel->getAppServer($server_id);

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
        $data['server_id']              = $_REQUEST['server_id']          ; // 服务器id        
        $data['server_prefix']          = $_REQUEST['server_prefix']      ; // 服务器前缀 ：一区
        $data['server_name']            = $_REQUEST['server_name']        ; // 服务器名称      
        $data['server_url']             = $_REQUEST['server_url']         ; // 服务器url       
        $data['server_order']           = $_REQUEST['server_order']       ; // 服务器列表排序  
        $data['app_id']                 = $_REQUEST['app_id']             ; // 所属游戏id      
        $data['company_id']             = $_REQUEST['company_id']         ; // 运营商id        
        $data['server_type']            = $_REQUEST['server_type']        ; // 服类型,1:new,2:hot,3:满 4:维护
        $data['server_state']           = $_REQUEST['server_state']       ; // 服务器状态,0:备运   1:开服中 2、停服,3:服务器宕机
        $data['socket_ip']              = $_REQUEST['socket_ip']          ; // socket 的ip地址 
        $data['socket_port']            = $_REQUEST['socket_port']        ; // socket的端口号  
        $data['server_stop_start_time'] = $_REQUEST['server_stop_start_time']; // 停服开始时间    
        $data['server_stop_end_time']   = $_REQUEST['server_stop_end_time']; // 停服结束时间    
        $data['server_stop_tip']        = $_REQUEST['server_stop_tip']    ; // 服务器宕机提示  
        $data['app_version_package']    = $_REQUEST['app_version_package']; // CPP中定义的版本, 决定是否显示
        $data['company_name']           = $_REQUEST['company_name']       ; // 公司名称        
        $data['company_phone']          = $_REQUEST['company_phone']      ; // 电话            
        $data['contacter']              = $_REQUEST['contacter']          ; // 联系人          
        $data['sign_time']              = $_REQUEST['sign_time']          ; // 签约时间        
        $data['account_num']            = $_REQUEST['account_num']        ; // 账号个数        
        $data['db_host']                = $_REQUEST['db_host']            ; // 数据库IP        
        $data['db_name']                = $_REQUEST['db_name']            ; // 数据库名        
        $data['db_passwd']              = $_REQUEST['db_passwd']          ; // 数据库密码      
        $data['upload_path']            = $_REQUEST['upload_path']        ; // 附件存放地址    
        $data['business_agent']         = $_REQUEST['business_agent']     ; // 业务代表        
        $data['price']                  = $_REQUEST['price']              ; // 费用            
        $data['effective_date_start']   = $_REQUEST['effective_date_start']; // 有效期开始与结束
        $data['effective_date_end']     = $_REQUEST['effective_date_end'] ; // 有效期开始与结束1


        $server_id = $this->baseAppServerModel->addAppServer($data, true);

        if ($server_id)
        {
            $msg = 'success';
            $status = 200;
        }
        else
        {
            $msg = 'failure';
            $status = 250;
        }

        $data['server_id'] = $server_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 删除操作
     *
     * @access public
     */
    public function remove()
    {
        $server_id = $_REQUEST['server_id'];

        $flag = $this->baseAppServerModel->removeAppServer($server_id);

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

        $data['server_id'] = $server_id;

        $this->data->addBody(-140, $data, $msg, $status);
    }

    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['server_id']              = $_REQUEST['server_id']          ; // 服务器id        
        $data['server_prefix']          = $_REQUEST['server_prefix']      ; // 服务器前缀 ：一区
        $data['server_name']            = $_REQUEST['server_name']        ; // 服务器名称      
        $data['server_url']             = $_REQUEST['server_url']         ; // 服务器url       
        $data['server_order']           = $_REQUEST['server_order']       ; // 服务器列表排序  
        $data['app_id']                 = $_REQUEST['app_id']             ; // 所属游戏id      
        $data['company_id']             = $_REQUEST['company_id']         ; // 运营商id        
        $data['server_type']            = $_REQUEST['server_type']        ; // 服类型,1:new,2:hot,3:满 4:维护
        $data['server_state']           = $_REQUEST['server_state']       ; // 服务器状态,0:备运   1:开服中 2、停服,3:服务器宕机
        $data['socket_ip']              = $_REQUEST['socket_ip']          ; // socket 的ip地址 
        $data['socket_port']            = $_REQUEST['socket_port']        ; // socket的端口号  
        $data['server_stop_start_time'] = $_REQUEST['server_stop_start_time']; // 停服开始时间    
        $data['server_stop_end_time']   = $_REQUEST['server_stop_end_time']; // 停服结束时间    
        $data['server_stop_tip']        = $_REQUEST['server_stop_tip']    ; // 服务器宕机提示  
        $data['app_version_package']    = $_REQUEST['app_version_package']; // CPP中定义的版本, 决定是否显示
        $data['company_name']           = $_REQUEST['company_name']       ; // 公司名称        
        $data['company_phone']          = $_REQUEST['company_phone']      ; // 电话            
        $data['contacter']              = $_REQUEST['contacter']          ; // 联系人          
        $data['sign_time']              = $_REQUEST['sign_time']          ; // 签约时间        
        $data['account_num']            = $_REQUEST['account_num']        ; // 账号个数        
        $data['db_host']                = $_REQUEST['db_host']            ; // 数据库IP        
        $data['db_name']                = $_REQUEST['db_name']            ; // 数据库名        
        $data['db_passwd']              = $_REQUEST['db_passwd']          ; // 数据库密码      
        $data['upload_path']            = $_REQUEST['upload_path']        ; // 附件存放地址    
        $data['business_agent']         = $_REQUEST['business_agent']     ; // 业务代表        
        $data['price']                  = $_REQUEST['price']              ; // 费用            
        $data['effective_date_start']   = $_REQUEST['effective_date_start']; // 有效期开始与结束
        $data['effective_date_end']     = $_REQUEST['effective_date_end'] ; // 有效期开始与结束1


        $server_id = $_REQUEST['server_id'];
		$data_rs = $data;

        unset($data['server_id']);

        $flag = $this->baseAppServerModel->editAppServer($server_id, $data);
        $this->data->addBody(-140, $data_rs);
    }
}
?>