<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}

/**
 * Api接口, 让webpos调用
 *
 *
 * @category   Game
 * @package    User
 * @author
 * @copyright
 * @version    1.0
 * @todo
 */
class WebPosApi_RedpacketCtl extends WebPosApi_Controller
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
        $user_id = request_int('user_id');
        $cond_row['redpacket_owner_id']    = $user_id;
        //根据优惠券状态搜索
        $redpacket_state = RedPacket_BaseModel::UNUSED;
        if($redpacket_state)
        {
            $cond_row['redpacket_state']    = $redpacket_state;
        }
        $order_row['redpacket_start_date'] = "ASC";

        $data =  $this->redPacketBaseModel->getRedPacketList($cond_row, $order_row);

        foreach($data['items'] as $key=>$value)
        {
            $data['items'][$key]['start_data']=substr($value['redpacket_start_date'],0,10);
            $data['items'][$key]['end_data']=substr($value['redpacket_end_date'],0,10);
            $cond_red[] = $value['redpacket_t_id'];
            $da =  $this->redPacketTempModel->getRedPacketTempInfoById($value['redpacket_t_id'], $order_redpacket);

            $data['items'][$key]['redpacket_t_img'] = $da['redpacket_t_img'];
        }

        $this->data->addBody(-140, $data);

    }

}

?>