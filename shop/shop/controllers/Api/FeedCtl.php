<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_FeedCtl extends Yf_AppController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		$this->feedBaseModel     = new Feed_BaseModel();
		$this->userBaseModel     = new User_BaseModel();
	}
    
    
    /**
     * 列表数据
     *
     * @access public
     */
    public function lists()
    {
        $page = request_int('page');
        $rows = request_int('rows');
        $sort = request_int('sord');
        
        $cond_row  = array();
        $order_row = array('feed_time'=>'DESC');

        $data = $this->feedBaseModel->getBaseList($cond_row, $order_row, $page, $rows);
        foreach($data['items'] as $k=>$v)
        {
            $user_info = $this->userBaseModel->getOne($v['user_id']);
            $data['items'][$k]['user_name'] = $user_info['user_account'];
        }

        $this->data->addBody(-140, $data);
    }
}

?>