<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Union_OrderModel extends Union_Order
{
	const DISABLE = -1;   //失效
	const WAIT_PAY = 1;  //待付款
	const PAYED    = 2;  //已付款
	const WAIT_PREPARE_GOODS = 3;      //待发货     等待卖家发货	     配货
	const WAIT_CONFIRM_GOODS = 4;      //已发货     等待买家确认收货	 出库
	const RECEIVED           = 5;                //已签收     买家已签收	     已签收
	const FINISH             = 6;                  //已完成     交易成功	         交易成功
	const CANCEL             = 7;                  //已取消     交易关闭	         交易关闭
	const RETURN_ORDER             = 8;		//买家申请退货
	const FINISH_RETURN_ORDER    = 9;		//商家确认退货（退货完成）

	/**
	 * 读取分页列表
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($card_name = null,$appid = null,$beginDate = null,$endDate = null, $page=1, $rows=100, $sort='asc')
	{
		
	}
    
    /**
     *  获取白条订单
     */
    public function getBtOrder($cond_row, $order_row = array(), $page=1, $rows=1000, $flag=true){
        $bt_order = $this->listByWhere($cond_row,$order_row,$page,$rows,$flag);
        $order_ids = array();
        $list = array();
        if(isset($bt_order['items']) && $bt_order['records'] > 0){
            foreach ($bt_order['items'] as $key=>$value){
                $order_ids = explode(',', $value['inorder']);
                foreach ($order_ids as $val){
                    $list[$val] = $value;
                    $list[$val]['inorder'] = $val;
                }
            }
            if($list){
                $list = array_values($list);
            }
        }
        return $list;
    }

	public static function createUnionOrderId()
    {
        return "U" . date("Ymdhis", time()) . rand(100, 999);  //18位
    }

}
?>