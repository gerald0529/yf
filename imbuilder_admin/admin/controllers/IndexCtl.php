<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class IndexCtl extends Yf_AppController
{
    public function index()
    {
        $this->baseConfigModel = new Base_ConfigModel();
        $data = $this->baseConfigModel->getConfigList();
        foreach($data['items'] as $key=>$val)
        {
            $config[$val['config_key']]['config_data'] = $val['config_data'];
            $config[$val['config_key']]['config_datatype'] = $val['config_datatype'];
        }
        include $this->view->getView();
    }

    public function main()
    {
        $a = _("asa");
        include $this->view->getView();
    }
}
?>