<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Base_AppVersionCtl extends Yf_AppController
{
    public $baseAppVersionModel = null;
    
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
        $this->baseAppVersionModel = new Base_AppVersionModel();
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
            $data = $this->baseAppVersionModel->getAppVersionList($cond_row, $order_row, $page, $rows);
        }
        else
        {
            $data = $this->baseAppVersionModel->getAppVersionList($cond_row, $order_row, $page, $rows);
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
        
        $app_id = request_int('app_id');
        $rows = $this->baseAppVersionModel->getAppVersion($app_id);
        
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
        $data['app_id']                 = request_string('app_id')        ; // 应用id
        $data['php_version']            = request_string('php_version')   ; // php程序版本
        $data['php_svn_version']        = request_string('php_svn_version'); // php程序版本
        
        
        $app_id = $this->baseAppVersionModel->addAppVersion($data, true);
        
        if ($app_id)
        {
            $msg = _('success');
            $status = 200;
        }
        else
        {
            $msg = _('failure');
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
        $app_id = request_int('app_id');
        
        $flag = $this->baseAppVersionModel->removeAppVersion($app_id);
        
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
        
        $data['app_id'] = array($app_id);
        
        $this->data->addBody(-140, $data, $msg, $status);
    }
    
    /**
     * 修改
     *
     * @access public
     */
    public function edit()
    {
        $data['app_id']                 = request_string('app_id')        ; // 应用id
        $data['php_version']            = request_string('php_version')   ; // php程序版本
        $data['php_svn_version']        = request_string('php_svn_version'); // php程序版本
        
        
        $app_id = request_int('app_id');
        $data_rs = $data;
        
        unset($data['app_id']);
        
        $flag = $this->baseAppVersionModel->editAppVersion($app_id, $data);
        $this->data->addBody(-140, $data_rs);
    }
    
    // $version_row['state']   0:最新，不用更新，  1:可更新  2:必须重新安装
    public function version()
    {
        $app_id = request_int('app_id');
        $client_version = request_string('version');
        
        
        $Base_AppVersion = new Base_AppVersion();
        $base_app_version_row = $Base_AppVersion->getOne($app_id);
        
        if(!$base_app_version_row)
        {
            return $this->data->setError(_('游戏版本配置表错误'));
        }
        
        $Base_AppResources = new Base_AppResources();
        
        //当前版本信息
        $current_resource_row = $Base_AppResources->getOneByWhere(array('app_id'=>$app_id, 'app_version'=>$client_version));
        
        
        
        $app_version = $base_app_version_row['app_version'];
        $version_rows = array();
        $version_row = array();
        
        
        if(version_compare($app_version, $client_version, 'gt'))//客户端的版本不是最新版本
        {
            $base_app_rows_resources_all = $Base_AppResources->getByWhere(array('app_id'=>$app_id, 'app_resource_id:>'=>$current_resource_row['app_resource_id'], 'app_release'=>1));
            
            $app_reinstall = 0;
            
            if ($base_app_rows_resources_all && array_column($base_app_rows_resources_all, 'app_reinstall'))
            {
                $app_reinstall = 1;
            }
            
            foreach ($base_app_rows_resources_all as $base_app_rows_resources)
            {
                $version_row['client_version'] = $client_version;
                $version_row['current_version'] = $base_app_rows_resources['app_version'];
                $version_row['latest_version'] = $app_version;
                
                $version_row['state'] = $app_reinstall;
                $version_row['filesize'] = $base_app_rows_resources['app_res_filesize'];;
                
                
                $packages = array();
                $packages['full'] = $base_app_rows_resources['app_package_url'];
                $packages['no_content'] = $base_app_rows_resources['app_package_url'];
                $packages['new_bundled'] = $base_app_rows_resources['app_package_url'];
                $packages['partial'] = false;
                $packages['rollback'] = false;
                
                $version_row['response'] = 'autoupdate';
                $version_row['download'] = $base_app_rows_resources['app_package_url'];
                $version_row['locale'] = 'zh_CN';
                $version_row['packages'] = $packages;
                
                $version_row['current'] = $base_app_rows_resources['app_version'];
                $version_row['version'] = $base_app_rows_resources['app_version'];
                
                $version_row['php_version'] = '5.3';
                $version_row['mysql_version'] = '5.0';
                $version_row['new_bundled'] = '4.4';  //当前版本小于new_bundled，则使用new_bundled zip version_compare( $client_version, $current->new_bundled, '<' )
                $version_row['partial_version'] = false;
                
                $version_row['new_files'] = true;
                
                
                //判断，自动生成zip包
                
                
                if ($app_version == $version_row['current_version'])
                {
                    $version_row['response'] = 'upgrade';
                    //unset($version_row['new_files']);
                    
                    $version_rows['latest'] = $version_row;
                }
                
                $version_rows['items'][] = $version_row;
            }
        }
        else
        {
            $base_app_rows_resources = $Base_AppResources->getOneByWhere(array('app_id'=>$app_id, 'app_version'=>$base_app_version_row['app_version']));
            
            $version_row['client_version'] = $client_version;
            $version_row['current_version'] = $app_version;
            $version_row['latest_version'] = $app_version;
            
            $version_row['state'] = 0;
            $version_row['filesize'] = $base_app_rows_resources['app_res_filesize'];;
            
            
            $packages = array();
            $packages['full'] = $base_app_rows_resources['app_package_url'];
            $packages['no_content'] = $base_app_rows_resources['app_package_url'];
            $packages['new_bundled'] = $base_app_rows_resources['app_package_url'];
            $packages['partial'] = false;
            $packages['rollback'] = false;
            
            $version_row['response'] = 'upgrade';
            $version_row['download'] = $base_app_rows_resources['app_package_url'];
            $version_row['locale'] = 'zh_CN';
            $version_row['packages'] = $packages;
            
            $version_row['current'] = $app_version;
            $version_row['version'] = $app_version;
            
            $version_row['php_version'] = '5.3';
            $version_row['mysql_version'] = '5.0';
            $version_row['new_bundled'] = '4.4';  //当前版本小于new_bundled，则使用new_bundled zip version_compare( $client_version, $current->new_bundled, '<' )
            $version_row['partial_version'] = false;
            
            $version_row['new_files'] = true;
            
            $version_rows['items'][] = $version_row;
            $version_rows['latest'] = $version_row;
        }
        
        //$client_version = '4.1.13';
        //$version_rows = decode_json('{"items":[{"response":"upgrade","download":"http:\/\/127.0.0.1\/ucenter\/r.zip","locale":"en_US","packages":{"full":"http:\/\/127.0.0.1\/ucenter\/r.zip","no_content":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.6.1-no-content.zip","new_bundled":"http:\/\/127.0.0.1\/ucenter\/r-nb.zip","partial":false,"rollback":false},"current":"4.6.1","version":"4.6.1","php_version":"5.2.4","mysql_version":"5.0","new_bundled":"4.4","partial_version":false},{"response":"autoupdate","download":"http:\/\/127.0.0.1\/ucenter\/r.zip","locale":"en_US","packages":{"full":"http:\/\/127.0.0.1\/ucenter\/r.zip","no_content":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.6.1-no-content.zip","new_bundled":"http:\/\/127.0.0.1\/ucenter\/r-nb.zip","partial":false,"rollback":false},"current":"4.6.1","version":"4.6.1","php_version":"5.2.4","mysql_version":"5.0","new_bundled":"4.4","partial_version":false,"new_files":true},{"response":"autoupdate","download":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.5.4.zip","locale":"en_US","packages":{"full":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.5.4.zip","no_content":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.5.4-no-content.zip","new_bundled":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.5.4-new-bundled.zip","partial":false,"rollback":false},"current":"4.5.4","version":"4.5.4","php_version":"5.2.4","mysql_version":"5.0","new_bundled":"4.4","partial_version":false,"new_files":true},{"response":"autoupdate","download":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.4.5.zip","locale":"en_US","packages":{"full":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.4.5.zip","no_content":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.4.5-no-content.zip","new_bundled":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.4.5-new-bundled.zip","partial":false,"rollback":false},"current":"4.4.5","version":"4.4.5","php_version":"5.2.4","mysql_version":"5.0","new_bundled":"4.4","partial_version":false,"new_files":true},{"response":"autoupdate","download":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.3.6.zip","locale":"en_US","packages":{"full":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.3.6.zip","no_content":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.3.6-no-content.zip","new_bundled":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.3.6-new-bundled.zip","partial":false,"rollback":false},"current":"4.3.6","version":"4.3.6","php_version":"5.2.4","mysql_version":"5.0","new_bundled":"4.4","partial_version":false,"new_files":true},{"response":"autoupdate","download":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.2.10.zip","locale":"en_US","packages":{"full":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.2.10.zip","no_content":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.2.10-no-content.zip","new_bundled":"https:\/\/downloads.wordpress.org\/release\/wordpress-4.2.10-new-bundled.zip","partial":false,"rollback":false},"current":"4.2.10","version":"4.2.10","php_version":"5.2.4","mysql_version":"5.0","new_bundled":"4.4","partial_version":false,"new_files":true}],"translations":[]}');
        
        $this->data->addBody(-101, $version_rows);
    }
    
    public function returnVersion()
    {
        echo $_REQUEST['version'];
        die();
    }
    
    public function checkSums()
    {
        $app_id  = request_int('app_id');
        $locale  = request_string('locale');
        $client_version = request_string('version');
        
        $Base_AppVersion = new Base_AppVersion();
        $base_app_version_row = $Base_AppVersion->getOne($app_id);
        
        $lastest_version     = $base_app_version_row['app_version'];
        $lastest_svn_version = $base_app_version_row['app_svn_version'];
        
        //当前版本信息
        $Base_AppResources = new Base_AppResources();
        $current_resource_row = $Base_AppResources->getOneByWhere(array('app_id'=>$app_id, 'app_version'=>$client_version));
        
        $upgrade_version = $current_resource_row['app_version'];
        $upgrade_svn_version = $current_resource_row['app_svn_version'];
        
        $version_path = sprintf('%s/app_release_version/%s/%s', ROOT_PATH, $app_id, $upgrade_version);
        
        if (file_exists($version_path))
        {
            //计算md5 file
            $data = Yf_Utils_File::getPhpFile($version_path, '*');
            
            $file_md5 = array();
            $file_row = array();
            
            foreach ($data as $item)
            {
                $file = str_replace($version_path . '/', '', $item);
                
                if ('db.sql' != $file)
                {
                    $file_md5[$file] = md5_file($item);
                }
                
                $file_row[] = $file;
            }
            
            $check_sums = array('checksums'=>$file_md5);
            
            
            $install_file = sprintf('app_release_version/%s/%s_v%s.zip', $app_id, $app_id, $upgrade_version);
            $install_path = sprintf('%s/%s', ROOT_PATH, $install_file);
            
            //判断，自动生成zip包
            if (!file_exists($install_path))
            {
                chdir($version_path);
                
                $obj = new Archive_Zip($install_path); // name of zip file
                
                $files = $file_row;   // files to store
                
                if ($obj->create($files))
                {
                    //echo 'Created successfully!';
                }
                else
                {
                    //echo 'Error in file creation';
                }
            }
        }
        else
        {
            $check_sums = array();
        }
        
        
        
        $this->data->addBody(-101, $check_sums);
    }
}
?>
