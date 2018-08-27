<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}



/**
 * Api接口, 让App等调用
 *
 *获取版权
 * @category
 * @version    1.0
 * @todo
 */
class Api_WapCtl extends Yf_AppController
{

	public function version()
	{
        $data['copyright'] = Web_ConfigModel::value('copyright');
        $data['icp_number'] = Web_ConfigModel::value('icp_number');
        $data['statistics_code'] = Web_ConfigModel::value('statistics_code');
        //1企业 2个人 3全部
        $data['join_type'] = Web_ConfigModel::value('join_type'); 
        $callback = $_GET['callback'];
        $data['version'] = SHOP_VERSION;
        $this->data->addBody(-140, $data);
    }

    public function version_im()
    {
        $data['im'] = Yf_Registry::get('im_statu')?:0;
        $callback = $_GET['callback'];
        $this->data->addBody(-140, $data);
    }

    //获取shop后台设置的商城图标和当前版本信息
    public function versionImage()
    {
        $data = [];
        $data['shop_logo'] = Web_ConfigModel::value('setting_logo') ? Web_ConfigModel::value('setting_logo') : '';
        $data['version']   = SHOP_VERSION;
        
        $this->data->addBody(-140, $data);
    }
}

?>