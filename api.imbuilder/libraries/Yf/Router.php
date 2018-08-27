<?php
/**
 * 路由器
 * 
 * 控制程序访问文件地址，集中管理URL请求类,简单功能.完善中.. ...
 * 
 * @category   Framework
 * @package    Controller
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2010, 黄新泽
 * @version    1.0
 * @todo       
 */
class Yf_Router extends Yf_Controller
{

    /**
     * App控制器
     * 
     * @access public
     * @var string|null
     */
    public $controller = null;

    /**
     * Constructor
     *
     * @param  string $ctl 控制器目录
     * @param  string $met 控制器方法
     * @param  string $typ 返回数据类型
     * @access public
     */
    public function __construct(&$ctl, &$met, &$typ)
    {
        parent::__construct($ctl, $met, $typ);
    }

    /**
     * 检测访问是否合法
     *
     * @access public
     */
    public function checkUrl()
    {
        global $ccmd_rows;

        $Yf_Registry = Yf_Registry::getInstance();

        $int_row   = $Yf_Registry['int_row'];
        $float_row = $Yf_Registry['float_row'];

		/*
        //过滤类型处理
        if (isset($ccmd_rows[$_REQUEST['ctl']][$_REQUEST['met']]))
        {
            //todo 可以考虑unset为设置的字段，防止开发过中乱传字段

            foreach ($ccmd_rows[$_REQUEST['ctl']][$_REQUEST['met']] as $key=>$item)
            {
                //判断是否是int
                if (isset($_REQUEST[$key]) && in_array($item, $int_row))
                {
                    $_REQUEST[$key] = intval($_REQUEST[$key]);
                }
                elseif (isset($_REQUEST[$key]) && in_array($item, $float_row)) //判断是否是float
                {
                    $_REQUEST[$key] = floatval($_REQUEST[$key]);
                }
            }
        }
        else
        {
            error_header(404, 'Page Not Found');
            throw new Yf_ProtocalException(sprintf(_('协议不存在: %s %s') , $_REQUEST['ctl'], $_REQUEST['met']));
            //die('发生错误，请检查控制器路径!');
        }
		*/

        //判断程序文件是否存在
        if (is_file($this->path))
        {
        }
        else
        {
            //$data_rows = array('body'=>'<div class="tips" style="font-weight:bold; margin: 3px 0 3px 0;">功能开发中.....</div> <div class="tips">file does not exists:' . $this->path . '</div>', 'head'=>array('error'=>true, 'msg'=>'请求发生错误', 'num'=>0));
            error_header(404, 'Page Not Found');
            throw new Yf_ProtocalException(sprintf(_('file: %s does not exists') , $this->path));
            //die('文件 ' . $this->path . ' does not exists');
        }
    }

    /**
     * 路由启动，返回控制器结果
     *
     * @var array  Controllers::dataRows  返回Ajax数据格式
     * @return array $rs
     * @access public
     */
    public function service()
    {
        $rs = $this->getData();

        return $rs;
    }

    /**
     * 设置控制器路径
     * 
     * @param string $ctl 控制器目录
     * @param string $index 运行文件
     * @return string path
     * @access    public
     */
    public function setPath(&$ctl='main', &$act='index')
    {
        $this->path = CTL_PATH . '/' . implode('/', explode('_', $ctl)) . '.php';
        $this->ctl = &$ctl;

        return $this->path;
    }

    /**
     * 取得控制器路径
     * 
     * @return string path
     * @access    public
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * 取得控制器类名称
     * 
     * @return string $this->className
     * @access    public
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * 得到Ajax数据格式
     *
     * @return    boolarray $data_rows  Ajax格式数据
     * @access    public
     * @see       Yf_Data
     */
    public function getData()
    {
            /*
            $d = null;

            //runtime 编译考虑
            if (!class_exists($this->className, false))
            {
                //控制器，手动导入，为了非class兼容。
                include $this->path;
            }

            //class存在，对象方式加载
            if (class_exists($this->className, false))
            {
            }
            */

            try
            {
                $this->checkUrl();

                $this->controller = new $this->className($this->ctl, $this->met, $this->typ);
                $this->controller->run();
				$d = $this->controller->getData();

                //$PluginManager =  Yf_Registry::get('hook');
                //$PluginManager->trigger('data_to_client', $d);

				$protocol_data = Yf_Data::encodeProtocolData($d, $this->typ);

				//正常的，触发log
				$PluginManager = Yf_Plugin_Manager::getInstance();
				$PluginManager->trigger('log');

                return $protocol_data;
            }
            catch(Yf_ProtocalException $e)
            {
                if ('cli' != SAPI)
                {
					if (Yf_Registry::get('error_url') && 'e'==$this->typ)
					{
						location_to(Yf_Registry::get('error_url'));
						//print_r($e->getMessage());
						die();
					}

                    $Data = new Yf_Data();

                    $Data->setError($e->getMessage(), $e->getCode(), $e->getId());
                    $d = $Data->getDataRows();

                    $protocol_data = Yf_Data::encodeProtocolData($d);

                    return $protocol_data;
                }
                else
                {
                    print_r($e->getMessage());
                }
            }
            catch(Exception $e)
            {
                if ('cli' != SAPI)
                {
					if (Yf_Registry::get('error_url') && 'e'==$this->typ)
					{
						location_to(Yf_Registry::get('error_url'));
						//print_r($e->getMessage());
						die();
					}

                    $Data = new Yf_Data();

                    $Data->setError($e->getMessage(), $e->getCode());
                    $d = $Data->getDataRows();

                    $protocol_data = Yf_Data::encodeProtocolData($d);

                    return $protocol_data;
                }
                else
                {
                    print_r($e->getMessage());
                }
            }
    }
}
?>