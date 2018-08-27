<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_FenxiaoCtl extends Yf_AppController
{
    public $fenxiaoModel;

    public function __construct(&$ctl, $met, $typ)
    {
        parent::__construct($ctl, $met, $typ);
        $this->fenxiaoModel = new Fenxiao;
    }


    /**
     * 列表数据
     *
     * @access public
     */
    public function getStatus()
    {
        $status = Web_ConfigModel::value('Plugin_Fenxiao');
        $this->data->addBody(-140, ['status'=> $status]);
    }

    /**
     * 获取分类的分佣比例
     */
    public function getCatValues()
    {
        $cat_id = request_string('id');
        $values = $this->fenxiaoModel->getCatValues($cat_id);

        $this->data->addBody(-140, ['values'=> $values]);
    }

    /**
     * 获取分类分佣比例
     * @param $cat_id
     * @param $shop_id
     * @return array
     */
    public function getCatValuesList()
    {
        $cat_id = request_row('cat_array');

        $list = $this->fenxiaoModel->getCatValuesList($cat_id);

        $this->data->addBody(-140, ['values'=> $list]);

    }
}

?>