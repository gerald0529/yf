<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_VisitorModel extends User_Visitor
{
	//查询当天是否有重复
	public function getVisitor($master_id="",$visitor_user_account="",$visitor_time=""){
		$this->sql->setWhere('master_id',$master_id);
		$this->sql->setWhere('visitor_user_account',$visitor_user_account);
		$this->sql->setWhere('visitor_time',$visitor_time);
		$data_rows = $this->get('*');
		return $data_rows;
	}
	//查询当前用户所有访问
	public function getVisitorAll($master_id=""){
		$this->sql->setWhere('master_id',$master_id);
		$data_rows = $this->get('*');

		return $data_rows;
	}
}
?>