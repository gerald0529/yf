<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Yf <service@yuanfeng.cn>
 */
class Message_TemplateModel extends Message_Template
{
	public static $messagePhone = array(
		"0" => '关闭',
		"1" => '开启'
	);
	public static $messageMail  = array(
		"0" => '关闭',
		"1" => '开启'
	);
	public static $messageEmail = array(
		"0" => '关闭',
		"1" => '开启'
	);

	/**
	 * 读取分页列表
	 *
	 * @param  array $cond_row 查询条件
	 * @param  array $order_row 排序信息
	 * @param  array $page 当前页码
	 * @param  array $rows 每页记录数
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getTemplateList($cond_row = array(), $order_row = array(), $page = 1, $rows = 100)
	{
		
		$data = $this->listByWhere($cond_row, $order_row, $page, $rows);
		foreach ($data["items"] as $key => $value)
		{

			$data["items"][$key]["is_phone"] = __(Message_TemplateModel::$messagePhone[$value["is_phone"]]);
			$data["items"][$key]["is_mail"]  = __(Message_TemplateModel::$messageMail[$value["is_mail"]]);
			$data["items"][$key]["is_email"] = __(Message_TemplateModel::$messageEmail[$value["is_email"]]);
		}
		return $data;
	}

	/**
	 * 读取详情
	 *
	 * @param  array $order_row 主键值
	 * @return array $data 返回的查询内容
	 * @access public
	 */
	public function getTemplateDetail($order_row = array())
	{
		$data = $this->getOneByWhere($order_row);
		return $data;
	}
	
    /**
     * 获取一个信息模板的内容
     * @param type $cond_row 查询条件
     * @param type $pattern 要查找内容
     * @param type $replacement 替换后的内容
     */
    public function getTemplateInfo($cond_row,$content_data = array()){
        $data = $this->getOneByWhere($cond_row);
        if(!$data){
            return array();
        }
        
        if($content_data){
            foreach ($content_data as $key => $value){
                $data['content_phone'] = str_replace($key, $value, $data['content_phone']);
                $data['content_email'] = str_replace($key, $value, $data['content_email']);
                $data['content_mail'] = str_replace($key, $value, $data['content_mail']);
            }
        }
        return $data;
    }
    
    /**
     * 发短信和邮件
     * @param type $receiver  手机号或者邮箱
     * @param type $send_type  发送类型 phone  email
     * @param type $tpl_code  信息模板码
     * @param type array $content_data 替换模板的内容  
     * @return type
     */
    public function sendMessage($receiver,$send_type, $tpl_code,$content_data = array()){
        if(!$receiver || !$send_type || !$tpl_code || !$content_data){
            return array('status'=>250, 'msg'=>__('数据有误'));
        }
        $tpl_info = $this->getTemplateInfo(array('code'=>$tpl_code),$content_data);
        
        $is_send = 'is_'.$send_type;
        $content = 'content_'.$send_type;
        //判断是否开启发送
        if(!$tpl_info[$is_send] || !$tpl_info[$content]){
            return array('status'=>250, 'msg'=>__('信息内容创建失败'));
        }
        if($send_type === 'phone'){
            $result = Sms::send($receiver, $tpl_info[$content], $tpl_info['baidu_tpl_id'],['code'=>$tpl_code]);
        }
        if($send_type === 'email'){
            $title = $this->getTplContent($tpl_info['title']);
            $result = Email::sendMail($receiver,'', $title, $tpl_info[$content]);
        }
        if($result){
            return array('status'=>200, 'msg'=>__('发送成功'));
        }else{
            return array('status'=>250, 'msg'=>__('发送失败'));
        }
    }
    
    /**
     * 模板通用内容转换，需要时添加进$content_data
     * @param type $content
     * @return type
     */
    public function getTplContent($content){
        $content_data = array(
            '[weburl_name]'=>Web_ConfigModel::value("site_name")
        );
 
        foreach ($content_data as $key => $value){
            $content = str_replace($key, $value, $content);
        }
        
        return $content;
    }
}

?>