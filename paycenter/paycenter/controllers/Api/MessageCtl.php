<?php

class Api_MessageCtl extends Api_Controller
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
		$data = $this->messageTemplateModel->getTemplateList();
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

		$order_row['id']   = $id;

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
