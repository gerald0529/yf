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
class Seller_Goods_EvaluationCtl extends Seller_Controller
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		$this->goodsEvaluationModel = new Goods_EvaluationModel();
	}

	public function evaluation()
	{
		$evaluation_goods_id = request_int("evaluation_goods_id");
		$type                = request_string('type');

		if ($evaluation_goods_id)
		{
			$this->view->setMet('explain');

			$data = $this->getEvaluationInfo();
		}
		else
		{
			$Yf_Page           = new Yf_Page();
			$Yf_Page->listRows = 10;
			$rows              = $Yf_Page->listRows;
			$offset            = request_int('firstRow', 0);
			$page              = ceil_r($offset / $rows);

			$evalution_row = array();
			$goods_name    = request_string('goods_name');        //商品名称
			$member_name   = request_string('member_name');          //评价人
			

			$evaluation_goods_id = request_int('evaluation_goods_id');

			$shop_id = Perm::$shopId;
			//获取店铺信息
			$Shop_BaseModel = new Shop_BaseModel();
			$shop_base = $Shop_BaseModel->getOne($shop_id);

			//$shop_id = 1;
			$cond_row = array();

			$cond_row['shop_id'] = $shop_id;

			if ($goods_name)
			{
				$cond_row['goods_name:'] = '%' . $goods_name . '%';
			}

			if ($member_name)
			{
				$cond_row['member_name:'] = '%' . $member_name . '%';
			}
			
			$order_row = array('status' => 'DESC');
			$order_row = array('evaluation_goods_id' => 'DESC');
			
			fb($cond_row);
			$data = $this->goodsEvaluationModel->getEvaluationList($cond_row, $order_row, $page, $rows);
			
			$Yf_Page->totalRows = $data['totalsize'];
			$page_nav           = $Yf_Page->prompt();

		}

		fb($data);
		fb("评价列表");

		include $this->view->getView();
	}

	/**
	 * 获取评价列表
	 *
	 * @access public
	 */
	public function getEvaluationList()
	{
		$page = request_int('page', 1);
		$rows = request_int('rows', 100);

		$evalution_row = array();
		$goods_name    = request_string('goods_name');        //商品名称
		$member_name   = request_string('member_name');          //评价人

		$evaluation_goods_id = request_int('evaluation_goods_id');

		$shop_id = Perm::$shopId;

		$cond_row = array();

		$cond_row['shop_id'] = $shop_id;

		if ($goods_name)
		{
			$cond_row['goods_name:'] = '%' . $goods_name . '%';
		}

		if ($member_name)
		{
			$cond_row['member_name:'] = '%' . $member_name . '%';
		}

		$order_row = array('evaluation_goods_id' => 'DESC');
		fb($cond_row);
		$data = $this->goodsEvaluationModel->getEvaluationList($cond_row, $order_row, $page, $rows);

		if ($data)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$this->data->addBody(-140, $data, $msg, $status);

		return $data;
	}

	//获取评价信息
	public function getEvaluationInfo()
	{
		$evaluation_goods_id = request_int('evaluation_goods_id');

		$data = $this->goodsEvaluationModel->getOne($evaluation_goods_id);
		
		$data['image_row'] = explode(',', $data['image']);

		if ($data)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$this->data->addBody(-140, $data, $msg, $status);

		return $data;
	}

	public function addEvaluationExplain()
	{
		$id     = request_int('evaluation_goods_id');
		$con    = request_string('con');
		$status = request_int('status');

		$edit_row = array(
			'explain_content' => $con,
			'status' => $status,
			'update_time' => get_date_time(),
		);
		$flag     = $this->goodsEvaluationModel->editEvalution($id, $edit_row);

		if ($flag === false)
		{
			$status = 250;
			$msg    = __('failure');
		}
		else
		{
			$status = 200;
			$msg    = __('success');
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>