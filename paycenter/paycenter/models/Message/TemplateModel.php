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

			$data["items"][$key]["is_phone"] = _(Message_TemplateModel::$messagePhone[$value["is_phone"]]);
			$data["items"][$key]["is_mail"]  = _(Message_TemplateModel::$messageMail[$value["is_mail"]]);
			$data["items"][$key]["is_email"] = _(Message_TemplateModel::$messageEmail[$value["is_email"]]);
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
    public function getTemplateInfo($cond_row,$pattern = array() , $replacement = array()){
        $data = $this->getOneByWhere($cond_row);
        if(!$data){
            return array();
        }
        if($pattern && $replacement){
            $data['content_email'] = preg_replace($pattern, $replacement, $data['content_email']);
            $data['content_phone'] = preg_replace($pattern, $replacement, $data['content_phone']);
            $data['content_mail'] = preg_replace($pattern, $replacement, $data['content_mail']);
        }
        return $data;
    }

}

?>