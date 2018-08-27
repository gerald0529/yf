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
        $cache_type = request_int('cache');
        //验证参数
        $check_info = $this->checkInfo($temp_code,$type,$val);
        if(!$check_info['status']){
            $msg = $check_info['msg'] ? $check_info['msg'] : '数据有误';
            return $this->data->addBody(-140, array(), _($msg), 250);
        }
        //创建验证码(这里有坑，缓存信息读写需要一致)
        if($cache_type == 1){
            $code = VerifyCode::getCode($val);
        } else {
            $group='verify_code';
            $code = mt_rand(100000, 999999);
            $config_cache = Yf_Registry::get('config_cache');
            if (!file_exists($config_cache[$group]['cacheDir'])){
                mkdir($config_cache[$group]['cacheDir'],0755);
            }
            $Cache_Lite = new Cache_Lite_Output($config_cache[$group]);
            //$result = $Cache_Lite->save($code, $val);  根据技术部反馈，此处造成商家app注册手机验证码一直报错
            $result = $Cache_Lite->save($val,$code,'default');
            $code = !$result ? '' : $code;
        }
        
        if(!$code){
            return $this->data->addBody(-140, array(), _('验证码创建验证码'), 250);
        }
        
        //获取模板
        $message_info = $this->getMsgContent($temp_code, $code);
        if(!$message_info){
            return $this->data->addBody(-140, array(), _('信息内容创建失败'), 250);
        }
        
        //发送
		if ($type == 'mobile') {
            
			$str = Sms::send($val, $message_info['content_phone'], $message_info['baidu_tpl_id'],['weburl_name'=>Web_ConfigModel::value("site_name"),'yzm'=>$temp_code]);
		} else {
            
			$Email = new Email();
			$str = $Email->send_mail($val, Perm::$row['user_account'], $message_info['title'], $message_info['content_email']);
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
    
    /**
     * 根据模板code获取对应的模板信息
     * @param type $temp_code
     * @param type $check_code
     * @return string
     */
    private function getMsgContent($temp_code,$check_code){
        $message_info = array();
        $message_model = new Message_TemplateModel();
        if($temp_code == 'getcode'){
            $pattern = array('/\[weburl_name\]/','/\[yzm\]/');
            $replacement = array(Web_ConfigModel::value("site_name"),$check_code);
            $message_info = $message_model->getTemplateInfo(array('code'=>'regist_verify'), $pattern ,$replacement);
        } else {
            $message_info['content_phone'] = '您的验证码是：' . $check_code . '。请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
            $message_info['content_email'] = '您的验证码是：' . $check_code . '。请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
            $message_info['title'] = '注册验证';
        }
        
        return $message_info;
    }
    
    /**
     * 
     * @param type $temp_code
     * @param type $check_code
     * @return string
     */
    private function checkInfo($temp_code,$type,$val){
        $result = array('msg'=>'验证通过','status'=>true);
        if($temp_code == 'regist_verify'){
            $User_InfoDetail = new User_InfoDetailModel();
			$user_rows = $User_InfoDetail->checkUserName($val);
            if($user_rows){
                $msg = $type == 'emial' ? '该邮箱已注册' : '该手机号已注册';
                $result =  array('msg'=>$msg,'status'=>false);
            }
        }
        return $result;
    }
}