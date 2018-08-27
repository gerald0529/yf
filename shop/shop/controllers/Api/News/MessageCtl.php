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
 * @author     Yf <service@yuanfeng.cn>
 * @copyright  Copyright (c) 2015远丰仁商
 * @version    1.0
 * @todo
 */
class Api_News_MessageCtl extends Api_Controller
{
	public $messageTemplateModel = null;

	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function init()
	{
		$this->messageTemplateModel = new Message_TemplateModel();
	}

	/**
	 * 获取消息模板列表
	 *
	 * @access public
	 */

	public function getMessageList()
	{
		$type              = request_int('type');
		$order_row['type'] = $type;
		//暂时屏蔽这三个功能，这三个功能暂时还没有
		if($type == 2)
		{
			$name = ['退款自动处理提醒', '退货自动处理提醒', '退货未收货自动处理提醒'];
		}
		elseif($type == 1)
		{
			$name = ['预定订单尾款支付提醒', '余额变动提醒', '充值卡余额变动提醒'];
		}
		$order_row['name:NOT IN'] = $name;
		$data = $this->messageTemplateModel->getTemplateList($order_row);
		$this->data->addBody(-140, $data);
	}

	/**
	 * 设置消息模板
	 *
	 * @access public
	 */
	public function getTemplateInfo()
	{
		$id   = request_string('id');
		$type = request_int('type');

		$order_row['id']   = $id;
		$order_row['type'] = $type;

		$data = $this->messageTemplateModel->getTemplateDetail($order_row);

		$this->data->addBody(-140, $data);
	}

	/**
	 * 编辑站内信消息
	 *
	 * @access public
	 */
	public function editTemplateMail()
	{
		$id                    = request_string('id');
		$field['type']         = request_int('type');
		$field['force_mail']   = request_int('force_mail');
		$field['content_mail'] = request_string('content_mail');
		$field['is_mail']      = request_int('is_mail');
        

		$flag = $this->messageTemplateModel->editTemplate($id, $field);

		if ($flag !== false)
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

	/**
	 * 编辑短信消息
	 *
	 * @access public
	 */
	public function editTemplatePhone()
	{
		$id                     = request_string('id');
		$field['type']          = request_int('type');
		$field['force_phone']   = request_int('force_phone');
		$field['content_phone'] = request_string('content_phone');
		$field['is_phone']      = request_int('is_phone');
        $field['baidu_tpl_id']      = request_string('baidu_tpl_id');

		$flag = $this->messageTemplateModel->editTemplate($id, $field);

		if ($flag !== false)
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

	/**
	 * 编辑邮件消息
	 *
	 * @access public
	 */
	public function editTemplateEmail()
	{
		$id                     = request_string('id');
		$field['type']          = request_int('type');
		$field['force_email']   = request_int('force_email');
		$field['title']         = request_string('title');
		$field['content_email'] = request_string('content_email');
		$field['is_email']      = request_int('is_email');

		$flag = $this->messageTemplateModel->editTemplate($id, $field);

		if ($flag !== false)
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
}

?>