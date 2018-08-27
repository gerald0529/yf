<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class User_InfoModel extends User_Info
{
	const BT_VERIFY_NO = 0;  //未审核
	const BT_VERIFY_WAIT = 1;  //待审核
	const BT_VERIFY_PASS = 2;  //审核成功
	const BT_VERIFY_FAIL = 3;  //审核失败

    public static $user_identity_statu            = array(
		"0" => "未认证",
		"1" => "待审核",
		"2" => "认证成功",
		"3" => "认证失败",
	);

	public static $user_bt_statu            = array(
		"1" => "待审核",
		"2" => "审核通过",
		"3" => "审核拒绝",
	);

	public static $user_identity_type            = array(
		"1" => "身份证",
		"2" => "护照",
		"3" => "军官证",
	);
	/**
	 * 读取分页列表
	 *
	 * @param  int $user_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getInfoList($cond_row=array(),$order_row=array(), $page=1, $rows=100)
	{
                $getInfolist =  $this->listByWhere($cond_row,$order_row,$page,$rows);
				$sort = array(
					'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
					'field'     => 'user_active_time',       //排序字段
				);
				$arrSort = array();
				foreach($getInfolist['items'] AS $uniqid => $row){
					foreach($row AS $k=>$v){
						$arrSort[$k][$uniqid] = $v;
					}
				}
//		echo '<pre>';print_r($arrSort);exit;
				if($sort['direction']){
					array_multisort($arrSort[$sort['field']], constant($sort['direction']), $getInfolist['items']);
				}
//				$getInfolist['items'] = $arrSort;
                foreach ($getInfolist['items'] as $key => $value) {
					$getInfolist['items'][$key]['user_identity_statu_con'] = _(self::$user_identity_statu[$value["user_identity_statu"]]);
                    $getInfolist['items'][$key]['user_bt_status_con'] = _(self::$user_bt_statu[$value["user_bt_status"]]);
                    $getInfolist['items'][$key]['user_identity_card'] = $value['user_identity_card'].'&nbsp;'; //加一个空格转为string,防止数字过大被转义出错
                }
                return $getInfolist;
	}

	public function getUserInfo($user_id = null)
	{
		//先查找paycenter数据库中有没有改用户信息
		$data = $this->getOne($user_id);

		//如果paycenter中没有用户信息就远程
		if(!$data)
		{
			$key      = Yf_Registry::get('ucenter_api_key');
			$url         = Yf_Registry::get('ucenter_api_url');
			$ucenter_app_id = Yf_Registry::get('ucenter_app_id');
			$formvars = array();

			$formvars['app_id']					= $ucenter_app_id;
			$formvars['user_name']     = Perm::$row['user_account'];

			$rs = get_url_with_encrypt($key, sprintf('%s?ctl=Api_User&met=getUserInfoDetail&typ=json',$url), $formvars);
			fb($rs);
			if($rs['status'] == 200)
			{
				$rs_user = current($rs['data']);
				fb($rs_user);
				$add_user_info['user_id'] = $user_id;
				$add_user_info['user_nickname'] = $rs_user['user_name'];
				$add_user_info['user_active_time'] = date('Y-m-d H:i:s');
				$add_user_info['user_realname'] = $rs_user['user_truename'];
				$add_user_info['user_email'] = $rs_user['user_email'];
				$add_user_info['user_mobile'] = $rs_user['user_mobile'];
				$add_user_info['user_qq'] = $rs_user['user_qq'];
				$add_user_info['user_avatar'] = $rs_user['user_avatar'];
				$add_user_info['user_identity_card'] = $rs_user['user_idcard'];

				$this->addInfo($add_user_info);

				$data = $add_user_info;
				$data['user_identity_statu'] = User_InfoModel::BT_VERIFY_NO;
			}
		}

		$data['user_identity_statu_con'] = self::$user_identity_statu[$data['user_identity_statu']];
		
		return $data;
	}

}
?>