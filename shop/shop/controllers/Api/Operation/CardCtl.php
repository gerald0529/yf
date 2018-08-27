<?php

if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author
 */
class Api_Operation_CardCtl extends Api_Controller
{

	const PAY_SITE = "";
	public $contractTypeModel = null;


	/**
	 * 初始化方法，构造函数
	 *
	 * @access public
	 */
	public function getCardList()
	{
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['page']      = request_int('page', 1);
		$formvars['rows']      = request_int('rows', 10);
		$formvars['cardName']  = request_string('cardName');
		$formvars['beginDate'] = request_string('beginDate');
		$formvars['endDate']   = request_string('endDate');
		$formvars['app_id'] = Yf_Registry::get('paycenter_app_id');

		//$rs   = get_url_with_encrypt($key, sprintf('%sindex.php?ctl=Info&met=getCardBaseList&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$rs   = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Paycen_PayCard&met=getCardBaseList&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		fb($rs);
		$data = $rs['data'];
		$this->data->addBody(-140, $data);
	}

	public function getCardInfoList()
	{
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['card_id'] = request_int('id');
		$formvars['page']    = request_int('page', 1);
		$formvars['rows']    = request_int('rows', 10);

		$rs   = get_url_with_encrypt($key, sprintf('%s?ctl=Info&met=getCardInfoList&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$data = $rs['data'];
		$this->data->addBody(-140, $data);
	}

	public function delCard()
	{
		$card_id  = request_int('card_id');
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['card_id'] = $card_id;
		$formvars['app_id'] = Yf_Registry::get('paycenter_app_id');

		$rs              = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Paycen_PayCard&met=delCardBase&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$msg             = $rs['msg'];
		$status          = $rs['status'];
		$data = $rs['data'];
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function addCardBase()
	{
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['card_id']         = request_string("card_id");
		$formvars['card_name']  = request_string("card_name");
		$formvars['card_num']   = request_int("card_num");
		$formvars['card_start_time'] = request_string("card_start_time");
		$formvars['card_end_time']   = request_string("card_end_time");
		$formvars['card_desc']  = request_string("card_desc");
		$formvars['money']      = request_int("money");
		$formvars['point']      = request_int("point");
		$formvars['app_id'] = Yf_Registry::get('paycenter_app_id');

		$rs              = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Paycen_PayCard&met=addCardBase&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$msg             = $rs['msg'];
		$status          = $rs['status'];
		$data = $rs['data'];
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function editCardBase()
	{
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['card_id']         = request_string("card_id");
		$formvars['card_name']  = request_string("card_name");
		$formvars['card_num']   = request_int("card_num");
		$formvars['card_start_time'] = request_string("card_start_time");
		$formvars['card_end_time']   = request_string("card_end_time");
		$formvars['card_desc']  = request_string("card_desc");
		$formvars['money']      = request_float("money");
		$formvars['point']      = request_float("point");
		$formvars['app_id'] = Yf_Registry::get('paycenter_app_id');

		$rs              = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Paycen_PayCard&met=editCardBase&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$msg             = $rs['msg'];
		$status          = $rs['status'];
		$data['card_id'] = request_string("card_id");
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function getDetail()
	{
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['card_id'] = request_string("id");
		$formvars['app_id'] = Yf_Registry::get('paycenter_app_id');

		$rs             = get_url_with_encrypt($key, sprintf('%s?ctl=Api_Paycen_PayCard&met=getCardlist&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$data           = $rs['data'];
		$this->data->addBody(-140, $data);
	}

	public function manageCard()
	{
		$key      = Yf_Registry::get('paycenter_api_key');
		$formvars = array();

		$formvars['id'] = request_string("id");

		$rs   = get_url_with_encrypt($key, sprintf('%s?ctl=Info&met=getCardBase&typ=json', Yf_Registry::get('paycenter_api_url')), $formvars);
		$data = $rs['data'];
		$this->data->addBody(-140, $data);
	}


}

?>