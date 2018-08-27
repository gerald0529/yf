<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

class WeiXinCtl extends Yf_AppController
{
	public $appid     = null;
	public $appsecret = null;
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);
		$key      = Yf_Registry::get('ucenter_api_key');
		$url         = Yf_Registry::get('ucenter_api_url');
			$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Connect&met=getConnectConfig&typ=json',$url));
		if(empty($rs)){
			$this->data->setError('code 获取失败');eixt;
		}
		$this->appid     = $rs['data']['wechat']['app_id'];
		$this->appsecret = $rs['data']['wechat']['app_key'];

	}
		/**
	 * callback 回调函数
	 *
	 * @access public
	 */
	public function callback()
	{

		$code = request_string('code', null);
		$op = request_string('op');

		//获取微信用户信息
		if($code){
			$data = $this->getWXuser($code);

			$url         = Yf_Registry::get('ucenter_api_url');
			$uurl = sprintf('%s?ctl=Connect_Bind&met=callback&from=%s&type=%s&typ=json&code=%s&status=%s&bind_id=%s&access_token=%s&openid=%s&bind_avator=%s&bind_nickname=%s&bind_gender=%s&fr=%s&callback=%s&op=%s',$url,'paycenter', 'weixin',$code,$data['status'],$data['bind_id'],$data['access_token'],$data['openid'],$data['bind_avator'],$data['bind_nickname'],$data['bind_gender'],request_string('from'),urlencode(request_string('callback')),$op);
			location_to($uurl);

		}else{
			$this->data->setError('code 获取失败');
		}
	}
	/**
	 * getWXuser 微信互联登录 - 获取用户信息
	 *
	 * @access public
	 */
	public function getWXuser($code)
	{
		$data = array();
		$token_url        = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appid . '&secret=' . $this->appsecret . '&code=' . $code . '&grant_type=authorization_code';
		$access_token_row = json_decode(file_get_contents($token_url), true);

		if (!$access_token_row || !empty($access_token_row['errcode']))
		{
			throw new Yf_ProtocalException($access_token_row['errmsg']);
			$data['status'] = 250;
		}
		else
		{
			$user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token_row['access_token'] . '&openid=' . $access_token_row['openid'] . '&lang=zh_CN';
			$user_info_row = json_decode(@file_get_contents($user_info_url), true);

			$data['status'] = 200;
			$data['bind_id'] = sprintf('%s_%s', 'weixin', $user_info_row['unionid']);
			$data['access_token'] = $access_token_row['access_token'];
			$data['openid'] = $user_info_row['openid'];
			$data['bind_avator'] = $user_info_row['headimgurl'];
			$data['bind_nickname'] = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $user_info_row['nickname']);
			$data['bind_gender'] = $user_info_row['sex'];

		}

		return $data;
	}

}