<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Yf <service@yuanfeng.cn>
 */
class UserCtl extends Yf_AppController
{

	/**
	 * Constructor
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		$this->userInfoModel  = new User_InfoModel();
		$this->userBaseModel    = new User_BaseModel();
		$this->messageTemplateModel = new Message_TemplateModel();


		$this->web             = $this->webConfig();
		$this->web['web_logo'] = $this->web['site_logo'];

	}

	//默认设置
	public function webConfig()
	{
		$web['site_logo']       = Web_ConfigModel::value("site_logo");//首页logo
		$web['web_name']       = Web_ConfigModel::value("site_name");//首页名称
		$web['buyer_logo']     = Web_ConfigModel::value("setting_buyer_logo");//会员中心logo
		$web['seller_logo']    = Web_ConfigModel::value("setting_seller_logo");//卖家中心logo
		$web['goods_image']    = Web_ConfigModel::value("photo_goods_logo");//商品图片
		$web['shop_head_logo'] = Web_ConfigModel::value("photo_shop_head_logo");//店铺头像
		$web['shop_logo']      = Web_ConfigModel::value("photo_shop_logo");//店铺标志
		$web['user_avatar']    = Web_ConfigModel::value("photo_user_avatar");//默认头像

		return $web;
	}

	/**
	 *获取会员信息
	 *
	 * @access public
	 */
	public function getUserInfo()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];

		$data = $this->userInfoModel->getInfoDetail($user_name);
		$data = $data[$user_name];
		
		$privacy = $this->userPrivacyModel->getPrivacy($user_id);
		$privacy = @$privacy[$user_id];


		//会员用户
		$user_grade_id = $data['user_grade'];

		if ($user_grade_id)
		{
			$this->userGradeModel = new User_GradeModel();
			$this->user['grade']  = $this->userGradeModel->getOne($user_grade_id);
		}

		if (empty($user['grade']))
		{
			$this->user['grade']['user_grade_name'] = '普通会员';
		}

		//获取一级地址
		$district_parent_id = request_int('pid', 0);
		$baseDistrictModel  = new Base_DistrictModel();
		$district           = $baseDistrictModel->getDistrictTree($district_parent_id);

		/*
		$re = $this->userInfoModel->getOne($user_id);
		$resource = $this->userResourceModel->getOne($user_id);

		$re['user_growth'] = $resource['user_growth'];

		$user_grade_id = $re['user_grade'];

		$data = $this->userGradeModel->getOne($user_grade_id);

		$data = $this->userGradeModel->getUserExpire($data);

		$gradeList = $this->userGradeModel->getGradeList();

		$data = $this->userGradeModel->getGradeGrowth($data,$gradeList,$re);
		*/

		include $this->view->getView();
	}

	/**
	 * 修改会员信息
	 *
	 * @access public
	 */
	public function editUserInfo()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];

		$year    = request_int('year');
		$month   = request_int('month');
		$day     = request_int('day');
		$user_qq = request_string('user_qq');
		$user_ww = request_string('user_ww');
		$rows    = request_row('privacy');
		

		$edit_user_row['user_birth']      = $year . "-" . $month . "-" . $day;
		$edit_user_row['user_gender']     = request_int('user_gender');
		$edit_user_row['user_truename']   = request_string('user_truename');
		$edit_user_row['user_provinceid'] = request_int('province_id');
		$edit_user_row['user_cityid']     = request_int('city_id');
		$edit_user_row['user_areaid']     = request_int('area_id');
		$edit_user_row['user_area']       = request_string('address_area');
		$edit_user_row['user_qq']         = $user_qq;
		//$edit_user_row['user_ww'] = $user_ww;
		
		//开启事物
		$rs_row = array();
		$this->userInfoModel->sql->startTransactionDb();
		
		$flagPrivacy = $this->userPrivacyModel->editPrivacy($user_id, $rows);
		check_rs($flagPrivacy, $rs_row);
		$flag = $this->userInfoModel->editInfoDetail($user_name, $edit_user_row);
		check_rs($flag, $rs_row);
		
		$flag = is_ok($rs_row);

		if ($flag && $this->userInfoModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$this->userInfoModel->sql->rollBackDb();
			$status = 250;
			$msg    = _('failure');
			
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 *获取会员头像
	 *
	 * @access public
	 */
	public function getUserImg()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];

		$data = $this->userInfoModel->getInfoDetail($user_name);;
		$this->user['info'] = $data[$user_name];
		
		include $this->view->getView();
	}

	/**
	 * 修改会员头像
	 *
	 * @access public
	 */
	public function editUserImg()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];
		
		$edit_user_row['user_avatar'] = request_string('user_avatar');

		$flag = $this->userInfoModel->editInfoDetail($user_name, $edit_user_row);

		if ($flag === false)
		{
			$status = 250;
			$msg    = _('failure');
		}
		else
		{
			$status = 200;
			$msg    = _('success');
		}
		
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 *获取会员等级
	 *
	 * @access public
	 */
	public function getUserGrade()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];
		
		$re       = $this->userInfoModel->getOne($user_id);
		$resource = $this->userResourceModel->getOne($user_id);
		
		$re['user_growth'] = $resource['user_growth'];

		$user_grade_id = $re['user_grade'];

		$data = $this->userGradeModel->getOne($user_grade_id);
		
		$data = $this->userGradeModel->getUserExpire($data);

		$gradeList = $this->userGradeModel->getGradeList();

		$data = $this->userGradeModel->getGradeGrowth($data, $gradeList, $re);

		include $this->view->getView();
	}

	/**
	 *获取会员标签
	 *
	 * @access public
	 */
	public function tag()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];

		$order_row['user_id'] = $user_id;
		
		$data = $this->userTagRecModel->getTagRecList($order_row);
		$re   = array();
		if ($data['items'])
		{
			$user_tag_ids = array_column($data['items'], 'user_tag_id');

			$tag = array();

			if ($data['items'])
			{
				$tag['user_tag_id:not in'] = $user_tag_ids;
			}

			$tag_row['user_tag_id:in'] = $user_tag_ids;

			$ce = $this->userTagModel->getTagList($tag_row);

			$user_tag = array_column($ce['items'], 'user_tag_id');

			foreach ($data['items'] as $key => $val)
			{
				
				if (in_array($val['user_tag_id'], $user_tag))
				{
					$data['items'][$key]['user_tag_name'] = $ce['items'][$key]['user_tag_name'];
				}
			}

			$re = $this->userTagModel->getTagList($tag);
		}
		include $this->view->getView();
	}

	/**
	 *编辑会员兴趣标签
	 *
	 * @access public
	 */
	public function editTagRec()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];

		$id_row = array();
		$id_row = request_row('mid');

		$edit_rec_row['user_id']      = $user_id;
		$edit_rec_row['tag_rec_time'] = get_date_time();


		//开启事物
		$rs_row = array();
		$this->userTagRecModel->sql->startTransactionDb();

		$order_row['user_id'] = $user_id;

		$de = $this->userTagRecModel->getTagRecList($order_row);

		$user_tag = array_column($de['items'], 'tag_rec_id');


		$updata_flag = $this->userTagRecModel->removeRec($user_tag);
		check_rs($updata_flag, $rs_row);

		foreach ($id_row as $v)
		{
			$edit_rec_row['user_tag_id'] = $v;
			$flag                        = $this->userTagRecModel->addRec($edit_rec_row);
		}
		check_rs($flag, $rs_row);

		$flag = is_ok($rs_row);
		if ($flag && $this->userTagRecModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$this->userTagRecModel->sql->rollBackDb();
			$msg    = _('failure');
			$status = 250;
		}


		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 *获取会员地址信息
	 *
	 * @access public
	 */
	public function address()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];

		$act = request_string('act');

		//获取一级地址
		$district_parent_id = request_int('pid', 0);
		$baseDistrictModel  = new Base_DistrictModel();
		$district           = $baseDistrictModel->getDistrictTree($district_parent_id);
		
		if ($act == 'edit')
		{
			$userId          = Perm::$userId;
			$user_address_id = request_int('id');
			

			$data = $this->userAddressModel->getAddressInfo($user_address_id);
		}
		elseif ($act == 'add')
		{
			$userId = Perm::$userId;
			
			$data = array();
		}
		elseif ($act == 'edit_delivery')
		{
			$userId = Perm::$userId;
			$data   = array();
		}
		else
		{
			$order_row['user_id'] = $user_id;

			$data = $this->userAddressModel->getAddressList($order_row);
		}

		$this->data->addBody(-140, $data);


		include $this->view->getView();
	}

	/**
	 *编辑会员地址信息
	 *
	 * @access public
	 */
	public function editAddressInfo()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];

		$user_address_id      = request_int('user_address_id');
		$user_address_contact = request_string('user_address_contact');
		$user_address_area    = request_string('address_area');
		$user_address_address = request_string('user_address_address');
		$user_address_phone   = request_string('user_address_phone');
		$user_address_default = request_string('user_address_default');

		$edit_address_row['user_id']                  = $user_id;
		$edit_address_row['user_address_contact']     = $user_address_contact;
		$edit_address_row['user_address_province_id'] = request_int('province_id');
		$edit_address_row['user_address_city_id']     = request_int('city_id');
		$edit_address_row['user_address_area_id']     = request_int('area_id');
		$edit_address_row['user_address_area']        = $user_address_area;
		$edit_address_row['user_address_address']     = $user_address_address;
		$edit_address_row['user_address_phone']       = $user_address_phone;
		$edit_address_row['user_address_default']     = $user_address_default;
		$edit_address_row['user_address_time']        = date("Y-m-d H:i:s", time());

		//验证用户
		$cond_row = array(
			'user_id' => $user_id,
			'user_address_id' => $user_address_id,
		);

		$re = $this->userAddressModel->getByWhere($cond_row);

		if (!$re)
		{
			$msg    = _('failure');
			$status = 250;
		}
		else
		{
			//开启事物
			$rs_row = array();
			$this->userAddressModel->sql->startTransactionDb();
			if ($user_address_default == '1')
			{

				$order_row['user_id']              = $user_id;
				$order_row['user_address_default'] = '1';
				$de                                = $this->userAddressModel->getAddressList($order_row);

				if (!empty($de))
				{
					$updata_flag = $this->userAddressModel->editAddressInfo($de);
				}
			}
			check_rs($updata_flag, $rs_row);

			$flag = $this->userAddressModel->editAddress($user_address_id, $edit_address_row);
			
			check_rs($flag, $rs_row);
			
			$flag = is_ok($rs_row);
			if ($flag && $this->userAddressModel->sql->commitDb())
			{
				$status = 200;
				$msg    = _('success');
			}
			else
			{
				$this->userAddressModel->sql->rollBackDb();
				$msg    = _('failure');
				$status = 250;
			}

			$edit_address_row['user_address_id'] = $user_address_id;
			$data                                = $edit_address_row;
			$this->data->addBody(-140, $data, $msg, $status);
		}

	}
	
	/**
	 *增加会员地址信息
	 *
	 * @access public
	 */
	public function addAddressInfo()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];

		$user_address_contact = request_string('user_address_contact');
		$user_address_area    = request_string('address_area');
		$user_address_address = request_string('user_address_address');
		$user_address_phone   = request_string('user_address_phone');
		$user_address_default = request_string('user_address_default');

		$edit_address_row['user_id']                  = $user_id;
		$edit_address_row['user_address_contact']     = $user_address_contact;
		$edit_address_row['user_address_province_id'] = request_int('province_id');
		$edit_address_row['user_address_city_id']     = request_int('city_id');
		$edit_address_row['user_address_area_id']     = request_int('area_id');
		$edit_address_row['user_address_area']        = $user_address_area;
		$edit_address_row['user_address_address']     = $user_address_address;
		$edit_address_row['user_address_phone']       = $user_address_phone;
		$edit_address_row['user_address_default']     = $user_address_default;
		$edit_address_row['user_address_time']        = get_date_time();

		$cond_row['user_id'] = $user_id;
		
		$re = $this->userAddressModel->getCount($cond_row);

		if ($re > 19)
		{
			
			$status = 250;
			$msg    = _('failure');
			
		}
		else
		{

			//开启事物
			$rs_row = array();
			$this->userAddressModel->sql->startTransactionDb();
			
			//判断是否设默认，默认改变前面的状态
			if ($user_address_default == '1')
			{

				$order_row['user_id']              = $user_id;
				$order_row['user_address_default'] = '1';
				$de                                = $this->userAddressModel->getAddressList($order_row);

				if (!empty($de))
				{
					$updata_flag = $this->userAddressModel->editAddressInfo($de);
				}
			}
			check_rs($updata_flag, $rs_row);
			$flag = $this->userAddressModel->addAddress($edit_address_row, true);
			check_rs($flag, $rs_row);
			$flag = is_ok($rs_row);
			if ($flag && $this->userAddressModel->sql->commitDb())
			{
				$edit_address_row['user_address_id'] = $flag;
				$status                              = 200;
				$msg                                 = _('success');
			}
			else
			{
				$this->userAddressModel->sql->rollBackDb();
				
				$status = 250;
				$msg    = _('failure');
			}
		}

		$data = $edit_address_row;
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 *删除会员地址信息
	 *
	 * @access public
	 */
	public function delAddress()
	{
		$user_id   = Perm::$row['user_id'];
		$user_name = Perm::$row['user_name'];

		$user_address_id = request_string('id');

		//验证用户
		$cond_row = array(
			'user_id' => $user_id,
			'user_address_id' => $user_address_id
		);
		$re       = $this->userAddressModel->getByWhere($cond_row);

		if ($re)
		{
			$flag = $this->userAddressModel->removeAddress($user_address_id);
		}
		else
		{
			$flag = false;
		}

		if ($flag !== false)
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$status = 250;
			$msg    = _('failure');
		}

		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 *查找好友
	 *
	 * @access public
	 */
	public function friend()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];
		$act       = request_string('op');

		$user_name = request_string("searchname");

		if ($act == 'follow')
		{
			$cond_row['user_id'] = $user_id;
			$order_row           = array('friend_addtime' => 'DESC');

			$Yf_Page           = new Yf_Page();
			$Yf_Page->listRows = 30;
			$rows              = $Yf_Page->listRows;
			$offset            = request_int('firstRow', 0);
			$page              = ceil_r($offset / $rows) + 1;

			$friend_list = $this->userFriendModel->getFriendAllDetail($cond_row, $order_row, $page, $rows);

			$Yf_Page->totalRows = $friend_list['totalsize'];
			$page_nav           = $Yf_Page->prompt();

			$this->view->setMet('follow');

		}
		elseif ($act == 'fan')
		{
			$cond_row['friend_id'] = $user_id;
			$order_row             = array('friend_addtime' => 'DESC');

			$Yf_Page           = new Yf_Page();
			$Yf_Page->listRows = 30;
			$rows              = $Yf_Page->listRows;
			$offset            = request_int('firstRow', 0);
			$page              = ceil_r($offset / $rows) + 1;

			$friend_list = $this->userFriendModel->getBeFriendAllDetail($cond_row, $order_row, $page, $rows);

			$Yf_Page->totalRows = $friend_list['totalsize'];
			$page_nav           = $Yf_Page->prompt();

			$this->view->setMet('fan');

		}
		else
		{

			if ($user_name)
			{
				$type            = 'user_name:LIKE';
				$cond_row[$type] = '%' . $user_name . '%';
				$order_row       = array();

				$Yf_Page           = new Yf_Page();
				$Yf_Page->listRows = 30;
				$rows              = $Yf_Page->listRows;
				$offset            = request_int('firstRow', 0);
				$page              = ceil_r($offset / $rows) + 1;

				$cond_row['user_id:!='] = $user_id;

				$user_list = $this->userInfoModel->getInfoList($cond_row, $order_row, $page, $rows);

				$friend_row                 = array();
				$friend_row['friend_id:IN'] = array_column($user_list['items'], 'user_id');

				$friend_row['user_id'] = $user_id;

				$friend_list = $this->userFriendModel->getFriendAll($friend_row);

				$friend_id = array();
				$friend_id = array_column($friend_list, 'friend_id');

				//获取已经加好友的
				foreach ($user_list['items'] as $key => $val)
				{

					if (in_array($val['user_id'], $friend_id))
					{
						$user_list['items'][$key]['status'] = 1;
					}
					else
					{
						$user_list['items'][$key]['status'] = 0;
					}
				}

				$Yf_Page->totalRows = $user_list['totalsize'];
				$page_nav           = $Yf_Page->prompt();
			}
			else
			{
				//推荐标签列表
				$cond_row['user_tag_recommend'] = 1;
				$sort                           = array('user_tag_sort' => 'DESC');

				$data = $this->userTagModel->getTagList($cond_row, $sort, 1, 10);

				if (!empty($data['items']))
				{
					foreach ($data['items'] as $key => $val)
					{
						$Yf_Page           = new Yf_Page();
						$Yf_Page->listRows = 10;
						$rows              = $Yf_Page->listRows;
						$offset            = request_int('firstRow', 0);
						$page              = ceil_r($offset / $rows) + 1;

						//查询已经是好友的，排除掉
						$friend_row['user_id'] = $user_id;

						$friend_list = $this->userFriendModel->getFriendAll($friend_row);

						$user = array();

						$user = array_column($friend_list, 'friend_id');

						array_push($user, $user_id);
						$tag_row['user_id:not in'] = $user;
						$tag_row['user_tag_id']    = $val['user_tag_id'];
						$order_row                 = array();

						$tag = $this->userTagRecModel->getTagRecList($tag_row, $order_row, $page, $rows);
						
						//标签下除好友的总会员个数
						$count                        = count($tag['items']);
						$data['items'][$key]['count'] = $count;

						foreach ($tag['items'] as $k => $v)
						{
							$user_row['user_id'] = $v['user_id'];

							$detail                     = $this->userInfoModel->getUserInfo($user_row);
							$tag['items'][$k]['detail'] = $detail;
						}
						$data['items'][$key]['user'] = $tag;
					}
				}

			}

		}
		include $this->view->getView();
	}
	
	/**
	 *关注标签下面没有关注的会员
	 *
	 * @access public
	 */
	public function addFriends()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];

		$cond_row['user_id']        = $user_id;
		$cond_row['friend_addtime'] = get_date_time();
		//查询已经是好友的，排除掉
		$friend_row['user_id'] = $user_id;

		$friend_list = $this->userFriendModel->getFriendAll($friend_row);
		
		$user = array();
		$user = array_column($friend_list, 'friend_id');

		array_push($user, $user_id);
		$tag_row['user_id:not in'] = $user;
		$tag_row['user_tag_id']    = request_int('id');
		$order_row                 = array();

		$tag = $this->userTagRecModel->getTagRecList($tag_row, $order_row);

		//开启事物
		$rs_row = array();
		$this->userFriendModel->sql->startTransactionDb();

		foreach ($tag['items'] as $key => $val)
		{
			$user_id = $val['user_id'];
			$detail  = $this->userInfoModel->getInfoDetail($user_name);
			$detail  = $detail[$user_id];
			
			$cond_row['friend_id']    = $val['user_id'];
			$cond_row['friend_name']  = $detail['user_name'];
			$cond_row['friend_image'] = $detail['user_avatar'];
			
			$flag = $this->userFriendModel->addFriend($cond_row);
		}
		check_rs($flag, $rs_row);
		$flag = is_ok($rs_row);
		if ($flag && $this->userFriendModel->sql->commitDb())
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$this->userFriendModel->sql->rollBackDb();
			$msg    = _('failure');
			$status = 250;
		}


		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 *关注一个会员
	 *
	 * @access public
	 */
	public function addFriendDetail()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];

		$cond_row['user_id']        = $user_id;
		$cond_row['friend_addtime'] = get_date_time();
		
		$userId = request_int('id');
		$detail = $this->userInfoModel->getOne($userId);

		$cond_row['friend_id']    = $userId;
		$cond_row['friend_name']  = $detail['user_name'];
		$cond_row['friend_image'] = $detail['user_avatar'];

		$flag = $this->userFriendModel->addFriend($cond_row);

		if ($flag)
		{
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
		}


		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 *取消一个会员
	 *
	 * @access public
	 */
	public function cancelFriendDetail()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];
		
		$user_friend_id = request_int('id');

		$cond_row['user_id']        = $user_id;
		$cond_row['user_friend_id'] = $user_friend_id;
		
		$de = $this->userFriendModel->getFriendInfo($cond_row);

		if (!$de)
		{
			
			$msg    = _('failure');
			$status = 250;
		}
		else
		{
			$flag = $this->userFriendModel->removeFriend($user_friend_id);

			if ($flag !== false)
			{
				$status = 200;
				$msg    = _('success');
			}
			else
			{
				
				$msg    = _('failure');
				$status = 250;
			}
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}

	/**
	 *获取会员安全信息
	 *
	 * @access public
	 */
	public function security()
	{
		//$str = Email::send_mail('553898963@qq.com','test','哈哈','测试');
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];
		$op        = request_string('op');


		if ($op == 'email')
		{
			$this->view->setMet('email');
		}
		elseif ($op == 'mobile')
		{
			$this->view->setMet('mobile');
		}
		elseif ($op == 'mobiles')
		{
			$name = _('手机');
			$this->view->setMet('security_identity');
		}
		elseif ($op == 'emails')
		{
			$name = _('邮箱');
			$this->view->setMet('security_identity');
		}
		elseif ($op == 'passwd')
		{
			$name = '密码';
			$this->view->setMet('passwd');
		}

		$data = $this->userInfoModel->getOne($user_name);

		$de = $this->userBaseModel->getOne($user_id);

		$data = array_merge($data, $de);

		$data['user_level_id'] = 0;

		if ($data['user_mobile_verify'])
		{
			$data['user_level_id']++;
		}

		if ($data['user_email_verify'])
		{
			$data['user_level_id']++;
		}


		include $this->view->getView();
	}


	/**
	 *修改密码
	 *
	 * @access public
	 */
	public function passwd()
	{
		//$str = Email::send_mail('553898963@qq.com','test','哈哈','测试');
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];
		$op        = request_string('op');

		if ($op == 'email')
		{
			$this->view->setMet('email');
		}
		elseif ($op == 'mobile')
		{
			$this->view->setMet('mobile');
		}
		elseif ($op == 'passwd')
		{
			$name = '密码';
			$this->view->setMet('passwd');
		}

		$user_id = Perm::$userId;

		$data = $this->userInfoModel->getInfoDetail($user_name);
		$data = $data[$user_name];

		$de = $this->userBaseModel->getInfo($user_id);
		$de = $de[$user_id];

		$data = array_merge($data, $de);

		$data['user_level_id'] = 0;

		if ($data['user_mobile_verify'])
		{
			$data['user_level_id']++;
		}

		if ($data['user_email_verify'])
		{
			$data['user_level_id']++;
		}


		include $this->view->getView();
	}

	//手机绑定验证
	public function getMobile()
	{
		$user_id                 = Perm::$userId;
		$user_name               = Perm::$row['user_name'];
		$cond_row['user_mobile'] = request_string('verify_field');
		$cond_row['user_id:!=']  = $user_id;
		
		$de = $this->userInfoModel->getByWhere($cond_row);

		if ($de)
		{
			
			$msg    = _('failure');
			$status = 250;
		}
		else
		{

			$status = 200;
			$msg    = _('success');
			
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}
	
	//生成验证码,并且发送验证码
	public function getMobileYzm()
	{

		$mobile = request_string('mobile');

		$cond_row['code'] = 'verification';
		
		$de = $this->messageTemplateModel->getTemplateDetail($cond_row);

		$me = $de['content_phone'];

		$code_key = $mobile;
		$code     = VerifyCode::getCode($code_key);
		$me       = str_replace("[weburl_name]", $this->web['web_name'], $me);
		$me       = str_replace("[yzm]", $code, $me);

		$str = Sms::send($mobile, $me, $de['baidu_tpl_id'],['weburl_name'=>$this->web['web_name'],'yzm'=>$code]);

		$status = $code;
		$data   = array();
		$this->data->addBody(-140, $data, $str, $status);

	}
	
	//检测验证码
	public function checkMobileYzm()
	{

		$yzm    = request_string('yzm');
		$mobile = request_string('mobile');
		
		if (VerifyCode::checkCode($mobile, $yzm))
		{
			
			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
			
		}
		$data = array();
		
		$this->data->addBody(-140, $data, $msg, $status);

	}


	//绑定手机
	public function editMobileInfo()
	{
		$user_id     = Perm::$userId;
		$user_name = Perm::$row['user_name'];

		$user_mobile = request_string('user_mobile');
		$yzm         = request_string('yzm');

		if (!VerifyCode::checkCode($user_mobile, $yzm))
		{
			$msg    = _('failure');
			$status = 240;
		}
		else
		{
			$edit_user_row['user_mobile']        = $user_mobile;
			$edit_user_row['user_mobile_verify'] = 1;

			$de = $this->userInfoModel->getOne($user_id);
			if (!$de)
			{
				$msg    = _('failure');
				$status = 250;
			}
			else
			{
				$flag = $this->userInfoModel->editInfoDetail($user_name, $edit_user_row);

				if ($flag == false)
				{
					$msg    = _('failure');
					$status = 250;

				}
				else
				{

					$status = 200;
					$msg    = _('success');
				}
			}
		}
		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);

	}


	//邮箱绑定验证
	public function getEmail()
	{
		$user_id                = Perm::$userId;
		$user_name              = Perm::$row['user_name'];
		$cond_row['user_email'] = request_string('verify_field');
		$cond_row['user_id:!='] = $user_id;
		
		$de = $this->userInfoModel->getByWhere($cond_row);

		if ($de)
		{
			
			$msg    = _('failure');
			$status = 250;
		}
		else
		{

			$status = 200;
			$msg    = _('success');
			
		}

		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}
	
	//邮箱生成验证码,并且发送验证码
	public function getEmailYzm()
	{

		$email = request_string('email');

		$cond_row['code'] = 'verification';
		
		$de = $this->messageTemplateModel->getTemplateDetail($cond_row);

		$me = $de['content_email'];

		$code_key = $email;
		$code     = VerifyCode::getCode($code_key);
		$me       = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $me);
		$me       = str_replace("[yzm]", $code, $me);
		$title    = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $title);

		$str = Email::send_mail($email, Perm::$row['user_name'], $de['title'], $me);


		$status = $code;
		$msg    = '';
		$data   = array();
		$this->data->addBody(-140, $data, $str, $status);

	}
	
	//邮箱检测验证码
	public function checkEmailYzm()
	{

		$yzm   = request_string('yzm');
		$email = request_string('email');
		
		if (VerifyCode::checkCode($email, $yzm))
		{

			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$msg    = _('failure');
			$status = 250;
			
		}
		$data = array();
		
		$this->data->addBody(-140, $data, $msg, $status);

	}


	//绑定邮箱
	public function editEmailInfo()
	{
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];

		$user_email = request_string('user_email');
		$yzm        = request_string('yzm');

		$flag = false;

		if (request_string('user_email'))
		{
			if (!VerifyCode::checkCode($user_email, $yzm))
			{
				$msg    = _('failure');
				$status = 240;
			}
			else
			{
				$edit_user_row['user_email']        = request_string('user_email');
				$edit_user_row['user_email_verify'] = 1;

				$flag = $this->userInfoModel->editInfoDetail($user_name, $edit_user_row);

				if ($flag == false)
				{
					$msg    = _('failure');
					$status = 250;
				}
				else
				{
					$status = 200;
					$msg    = _('success');
				}
			}
		}
		else
		{
			$msg = _('邮箱地址不存在!');

			$status = 250;
		}


		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}


	//解除绑定,生成验证码,并且发送验证码
	public function getYzm()
	{

		$type = request_string('type');
		$val  = request_string('val');

		$cond_row['code'] = 'Lift verification';

		$de = $this->messageTemplateModel->getTemplateDetail($cond_row);
		if ($type == 'mobile')
		{
			$me = $de['content_phone'];

			$code_key = $val;
			$code     = VerifyCode::getCode($code_key);
			$me       = str_replace("[weburl_name]", $this->web['web_name'], $me);
			$me       = str_replace("[yzm]", $code, $me);

			$str = Sms::send($val, $me, $de['baidu_tpl_id'],['weburl_name'=>$this->web['web_name'],'yzm'=>$code]);
		}
		else
		{
			$me    = $de['content_email'];
			$title = $de['title'];

			$code_key = $val;
			$code     = VerifyCode::getCode($code_key);
			$me       = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $me);
			$me       = str_replace("[yzm]", $code, $me);
			$title    = str_replace("[weburl_name]", Web_ConfigModel::value("site_name"), $title);

			$str = Email::send_mail($val, Perm::$row['user_account'], $title, $me);
		}
		$status = 200;
		$data   = array($code);
		$msg    = "success";
		$this->data->addBody(-140, $data, $msg, $status);

	}

	//检测解除验证码
	public function checkYzm()
	{

		$yzm  = request_string('yzm');
		$type = request_string('type');
		$val  = request_string('val');

		if (VerifyCode::checkCode($val, $yzm))
		{

			$status = 200;
			$msg    = _('success');
		}
		else
		{
			$msg    = _('failure');
			$status = 250;

		}
		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);

	}

	//解除绑定
	public function editAllInfo()
	{
		$type      = request_string('type');
		$yzm       = request_string('yzm');
		$val       = request_string('val');
		$user_id   = Perm::$userId;
		$user_name = Perm::$row['user_name'];

		$flag = false;

		if (!VerifyCode::checkCode($val, $yzm))
		{
			$msg    = _('failure');
			$status = 240;
		}
		else
		{
			if ($type == 'passwd')
			{
				$password       = request_string('password');

				if ($password)
				{
					$edit_user_row['password'] = md5($password);


					$de = $this->userBaseModel->getOne($user_id);
					$flag = $this->userBaseModel->editBase($user_id, $edit_user_row);
				}
				else
				{
					$msg    = _('密码不能为空');
					$status = 250;
				}
			}
			else
			{
				if ($type == 'mobile')
				{
					$edit_user_row['user_mobile']        = '';
					$edit_user_row['user_mobile_verify'] = 0;
				}
				else if ($type == 'email')
				{
					$edit_user_row['user_email']        = '';
					$edit_user_row['user_email_verify'] = 0;
				}
				else
				{
					$edit_user_row['user_email']        = '';
					$edit_user_row['user_email_verify'] = 0;
				}


				$de = $this->userInfoModel->getOne($user_name);
				$flag = $this->userInfoModel->editInfoDetail($user_name, $edit_user_row);
			}


			if ($flag == false)
			{
				$msg    = _('failure');
				$status = 250;

			}
			else
			{

				$status = 200;
				$msg    = _('success');
			}
		}
		$data = array();

		$this->data->addBody(-140, $data, $msg, $status);
	}
}

?>