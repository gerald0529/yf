<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Buyer_RedPacketCtl extends Buyer_Controller
{
    public $redPacketBaseModel = null;
	
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
        $this->redPacketBaseModel = new RedPacket_BaseModel();
        $this->redPacketTempModel   = new RedPacket_TempModel();
	}

	/*获取卖家领取的平台优惠券列表*/
    public function redPacket()
    {
        $cond_row  = array();
        $order_row = array();
		
        //分页
		$Yf_Page            = new Yf_Page();
		$Yf_Page->listRows = request_int('listRows')?request_int('listRows'):12;
		$rows               = $Yf_Page->listRows;
		$offset             = request_int('firstRow', 0);
		$page               = ceil_r($offset / $rows);
		
        $cond_row['redpacket_owner_id']    = Perm::$userId;
        //根据优惠券状态搜索
        $redpacket_state = request_int('state');
		if($redpacket_state)
		{
			$cond_row['redpacket_state']    = $redpacket_state;
		}
        $order_row['redpacket_start_date'] = "ASC";

        $data =  $this->redPacketBaseModel->getRedPacketList($cond_row, $order_row, $page,  $rows);
        
        foreach($data['items'] as $key=>$value)
        {
          $data['items'][$key]['start_data']=substr($value['redpacket_start_date'],0,10);
          $data['items'][$key]['end_data']=substr($value['redpacket_end_date'],0,10);
          $cond_red[] = $value['redpacket_t_id'];
          $da =  $this->redPacketTempModel->getRedPacketTempInfoById($value['redpacket_t_id'], $order_redpacket);

          $data['items'][$key]['redpacket_t_img'] = $da['redpacket_t_img'];
        }
        	
		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();

		if ('e' == $this->typ)
		{
			include $this->view->getView();
		}
		else
		{
			$this->data->addBody(-140, $data);
		}
    }



}
?>