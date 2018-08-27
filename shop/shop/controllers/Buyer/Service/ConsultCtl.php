<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Buyer_Service_ConsultCtl extends Buyer_Controller
{
	public $consultBaseModel  = null;
	public $goodsBaseModel    = null;
	public $consultReplyModel = null;
	public $consultTypeModel  = null;

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
		$this->consultBaseModel = new Consult_BaseModel();
		$this->goodsBaseModel   = new Goods_BaseModel();
		$this->consultTypeModel = new Consult_TypeModel();

	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		$Yf_Page             = new Yf_Page();
		$Yf_Page->listRows   = 10;
		$rows                = $Yf_Page->listRows;
		$offset              = request_int('firstRow', 0);
		$page                = ceil_r($offset / $rows);
		$consult_state       = request_int("state");
		$cond_row['user_id'] = Perm::$userId;         //店铺ID
		if ($consult_state)
		{
			$cond_row['consult_state'] = $consult_state;
		}
		$data = $this->consultBaseModel->getBaseList($cond_row, array(
			'answer_time' => 'desc',
			'question_time' => 'desc'
		), $page, $rows);

//        foreach ($data['items'] as $k => $v)
//        {
//            $cond_row2['consult_id']    = $v['consult_id'];
//            $data['items'][$k]['reply'] = $this->Consult_ReplyModel->getByWhere($cond_row2, array("answer_time" => "asc"));
//        }

		$data['state']      = $consult_state;
		$Yf_Page->totalRows = $data['totalsize'];
		$page_nav           = $Yf_Page->prompt();

		$d = $data;
		if ($this->typ == "json")
		{
			$this->data->addBody(-140, $d);
		}
		else
		{
			include $this->view->getView();
		}
	}

	public function add()
	{
		$goods_id  = request_int("gid");
		$data      = $this->goodsBaseModel->getGoodsDetailByGoodId($goods_id);
		$type      = $this->consultTypeModel->getByWhere(array(), array('consult_type_sort' => 'ASC'));
		$d['data'] = $data;
		$d['type'] = $type;
		if ($this->typ == "json")
		{
			$this->data->addBody(-140, $d);
		}
		else
		{
			include $this->view->getView();
		}
	}

	public function addConsult()
	{
		$data['shop_id']          = request_int("shop_id");
		$data['shop_name']        = request_string("shop_name");
		$data['goods_id']         = request_int("goods_id");
		$data['goods_name']       = request_string("goods_name");
		$data['user_id']          = Perm::$userId;
		$data['user_account']     = Perm::$row['user_account'];
		$data['consult_question'] = request_string("consult_question");
		$data['question_time']    = get_date_time();
		$data['consult_type_id']  = request_int("consult_type_id");
		$data['no_show_user']     = request_int("no_show_user");

		$matche_row = array();
		//有违禁词
		if (Text_Filter::checkBanned($data['consult_question'], $matche_row))
		{
			$data   = array();
			$msg    = __('failure');
			$status = 250;
			$this->data->addBody(-140, array(), $msg, $status);
			return false;
		}

		$type                      = $this->consultTypeModel->getOne($data['consult_type_id']);
		$data['consult_type_name'] = $type['consult_type_name'];

		$flag = $this->consultBaseModel->addBase($data);

		if ($flag)
		{
			$status = 200;
			$msg    = __('success');
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	public function delConsult()
	{
		$consult_id = request_int("id");
		$consult    = $this->consultBaseModel->getOne($consult_id);
		if ($consult['user_id'] == Perm::$userId)
		{
			$flag = $this->consultBaseModel->removeBase($consult_id);
			if ($flag)
			{
				$status = 200;
				$msg    = __('success');
			}
			else
			{
				$status = 250;
				$msg    = __('failure');
			}
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function reply()
	{
		$consult_id = request_int("consult_id");
		$data       = $this->consultBaseModel->getOne($consult_id);
		$data['id'] = $consult_id;
		if ($this->typ == "json")
		{
			$this->data->addBody(-140, $data);
		}
		else
		{
			include $this->view->getView();
		}
	}

	public function replyConsult()
	{
		$consult_id     = request_int("consult_id");
		$consult_answer = request_string("consult_answer");
		$consult        = $this->consultBaseModel->getOne($consult_id);
		if ($consult['user_id'] == Perm::$userId)
		{
			$data2['consult_answer']       = $consult_answer;
			$data2['consult_id']           = $consult_id;
			$data2['answer_time']          = date("Y-m-d H:i:s");
			$data2['answer_user_id']       = Perm::$userId;
			$data2['answer_user_account']  = Perm::$row['user_account'];
			$data2['answer_user_identify'] = Consult_ReplyModel::REPLY_BUYER;
			$flag                          = $this->consultReplyModel->addReply($data2);

			if ($flag)
			{
				$status = 200;
				$msg    = __('success');
			}
			else
			{
				$status = 250;
				$msg    = __('failure');
			}
		}
		else
		{
			$status = 250;
			$msg    = __('failure');
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}
}

?>