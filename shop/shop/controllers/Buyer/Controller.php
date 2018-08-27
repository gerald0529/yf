<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class Buyer_Controller extends Controller
{
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


		if ('json' != $typ)
		{
			$this->initBuyerInfo();
			$this->initData();
			$this->web             = $this->webConfig();
			$this->web['web_logo'] = $this->web['buyer_logo'];
		}
	}

	public function initBuyerInfo()
	{

		//共用数据
		$uid = Perm::$userId;

		$this->userInfoModel = new User_InfoModel();
		//会员用户
		$this->user = $this->userInfoModel->getUserMore($uid);

		//订单状态
		$this->orderBaseModel = new Order_BaseModel();

		$cond_row['order_is_virtual']     = Order_BaseModel::ORDER_IS_REAL; //实物订单
		$cond_row['order_buyer_hidden:<'] = Order_BaseModel::IS_BUYER_HIDDEN;//没有删除的
		$cond_row['buyer_user_id']        = $uid;
		$cond_row['order_status']         = Order_StateModel::ORDER_WAIT_PAY;        //待付款

		$this->order = $this->orderBaseModel->getCount($cond_row);

		$this->count['count1'] = $this->order;

		$cond_row['order_status'] = Order_StateModel::ORDER_WAIT_CONFIRM_GOODS;    //待收货

		$this->order = $this->orderBaseModel->getCount($cond_row);

		$this->count['count2'] = $this->order;


		$cond_row['order_status'] = Order_StateModel::ORDER_FINISH;    //已完成

		$cond_row['order_buyer_evaluation_status'] = 0; //买家未评价
		$this->order = $this->orderBaseModel->getCount($cond_row);

		$this->count['count3'] = $this->order;


		$cond_row['order_status'] = Order_StateModel::ORDER_CANCEL;    //已取消

		$this->order = $this->orderBaseModel->getCount($cond_row);

		$this->count['count4'] = $this->order;
		//会员未读消息
		$this->userMessageModel               = new User_MessageModel();
		$order_row                            = array();
		$order_row['user_message_receive_id'] = $uid;
		$order_row['message_islook']          = 0;
		
		$this->Message                 = $this->userMessageModel->getCount($order_row);
		$this->countMessage['receive'] = $this->Message;
		//系统消息
		$this->messageModel           = new MessageModel();
		$order_row                    = array();
		$order_row['message_user_id'] = $uid;
		$order_row['message_islook']  = 0;
		$order_row['message_mold']    = 0;
		
		$this->Message                 = $this->messageModel->getCount($order_row);
		$this->countMessage['message'] = $this->Message;
		//系统公告
		$this->articleBaseModel      = new Article_BaseModel();
		$order_row                   = array();
		$order_row['article_type']   = 1;
		$order_row['article_islook'] = 0;
		$order_row['article_status'] = 1;
		
		$this->Message                      = $this->articleBaseModel->getCount($order_row);
		
		$user_am = $this->user['info']['user_am'];

		if($user_am){
			$row = explode(",",$user_am);
			$order_row                   = array();
			$order_row['article_id:in']   = $row;
			$this->Umessage                      = $this->articleBaseModel->getCount($order_row);
			$this->Message  = $this->Message*1 - $this->Umessage*1;
		}
        if($this->Message<0){
            $this->Message=0;
        }
		$this->countMessage['article']      = $this->Message;
		$this->countMessage['countMessage'] = $this->countMessage['receive'] * 1 +$this->countMessage['message'] * 1 + $this->countMessage['article'] * 1;

	}
	
	
}

?>