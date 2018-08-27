<?php if (!defined('ROOT_PATH'))
{
    exit('No Permission');
}
/**
 * 一定要继承Api_Controller
 * 
 * 
 */
class Api_CommonCtl extends Api_Controller
{
    
    /**
     * 发送手机验证码
     */
    public function getVerifyCode()
	{
		$type = request_string('type');
		$val  = request_string('val');
		$temp_code = request_string('code','getcode');
		$cond_row = array('code'=>$temp_code);
        $messageTemplateModel = new Message_TemplateModel();
		$de = $messageTemplateModel->getTemplateDetail($cond_row);
        if(!$de){
            return $this->data->addBody(-140, array(), _('信息创建失败'), 200);
        }
		if ($type == 'mobile') {

			$code_key = $val;
			$code     = VerifyCode::getCode($code_key, null, 6);
			$me       = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $de['content_phone']);
			$me       = str_replace("[yzm]", $code, $me);
			$str = Sms::send($val, $me, $de['baidu_tpl_id'],['weburl_name'=>Web_ConfigModel::value("site_name"),'yzm'=>$code]);
		} else {
			$code_key = $val;
			$VerifyCode = new VerifyCode();
			$code     = $VerifyCode->getCode($code_key, null, 6);
			$me       = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $de['content_email']);
			$me       = str_replace("[yzm]", $code, $me);
			$title    = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $de['title']);
			$Email = new Email();
			$str = $Email->send_mail($val, Perm::$row['user_account'], $title, $me);
		}
        if(!$str){
            $status = 250;
            $code = '';
            $msg =  __('信息发送失败');
        } else {
            $status = 200;
            $msg =  __('信息发送成功');
        }
        $data = array('code'=>$code);
		return $this->data->addBody(-140, $data, $msg, $status);

	}
}