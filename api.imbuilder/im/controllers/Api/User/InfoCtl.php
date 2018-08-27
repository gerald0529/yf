<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Api_User_InfoCtl extends Yf_AppController
{
	public $userInfoModel     = null;
	public $userBaseModel     = null;
	private $rest = null;

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
		
		$this->userInfoModel     = new User_InfoModel();
		$this->userBaseModel     = new User_BaseModel();

	}
	public function initAccount()
	{
		$im_config_row = Yf_Registry::get('im_config_row');

		$account_sid = $im_config_row['account_sid'];
		$account_token = $im_config_row['account_token'];
		$app_id = $im_config_row['app_id'];
		$server_ip = $im_config_row['server_ip'];
		$server_port = $im_config_row['server_port'];
		$soft_version = $im_config_row['soft_version'];

		// 初始化REST SDK
		$this->rest = new Yuntongxun_Rest($server_ip, $server_port, $soft_version);
		$this->rest->setAccount($account_sid, $account_token);
		$this->rest->setAppId($app_id);
	}

	/**
	 *获取用户信息
	 */
	public function getFriends()
	{
		$user_name    = request_string('user_name');
		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 20;
		$sort = isset($_REQUEST['sort']) ? intval($_REQUEST['sort']) : 'asc';
		$User_BaseModel = new User_BaseModel();
		$data = $User_BaseModel->getUserBaseList($user_name,$page,$rows,$sort);
			foreach($data['items'] as $key=>$value)
			{
				if($value['user_gender']==0)
				{
					$data['items'][$key]['user_gender']='女';
				}
				else
				{
					$data['items'][$key]['user_gender']='男';
				}
				$user_reg_time = $value['user_reg_time'];
				$data['items'][$key]['user_reg_time'] = date('Y-m-d h:i:s',$user_reg_time);
				$user_lastlogin_time = $value['user_lastlogin_time'];
				$data['items'][$key]['user_lastlogin_time'] = date('Y-m-d h:i:s',$user_lastlogin_time);
			}

		if ($data)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140,$data,$msg,$status);
	}
	/*
	 * 推送消息
	 */
	public function send()
	{
		$receiver_name = $_REQUEST['vendor_type_name']; //收信人

		$name = explode(',',$receiver_name);
		$num = count($name);
		if($num<=1)
		{
			$r_name = $name[0];
		}
		else
		{
			$r_name = json_encode($name, true);
		}
		$contant = $_REQUEST['vendor_type_desc'];   //信息内容

		$url            = Yf_Registry::get('imbuilder_api_url');
		$key            = Yf_Registry::get('imbuilder_erp_key');

		$data['app_id'] = Yf_Registry::get('app_id');
		$data['ctl'] = 'ImApi';
		$data['met'] = 'pushMsg';
		$data['typ'] = 'json';
		$data['receiver'] = $r_name;
		$data['push_type'] = 1;
		$data['msg_content'] = $contant;
		$result = get_url_with_encrypt($key,$url,$data);
		if($result)
		{
			$e = strip_tags($result['d'][1]);
			if($e =='push msg success!')
			{
				$msg = 'success';
				$status = 200;
			}
			else
			{
				$msg = $e;
				$status = 250;
			}
		}
		else
		{
			$msg = '发送失败';
			$status =250;
		}
		$data = array();
		$this->data->addBody(-140,$data,$msg,$status);


	}


	/*
	 * 获取消息列表
	 */
	public function getList()
	{
		$page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
		$rows = isset($_REQUEST['rows']) ? intval($_REQUEST['rows']) : 20;
		$sort = isset($_REQUEST['sort']) ? intval($_REQUEST['sort']) : 'asc';
		$msg_sender = isset($_REQUEST['skey']) ? $_REQUEST['skey'] : null;
		$msg_receiver = isset($_REQUEST['skey1']) ? $_REQUEST['skey1'] : null;
//		$beginDate = request_string('beginDate');

		$User_MsgModel = new User_MsgModel();
		$msg_list = $User_MsgModel->getMsgList($page,$rows,$sort,$msg_sender,$msg_receiver);

		if ($msg_list)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		fb($msg_list);
		$this->data->addBody(-140, $msg_list, $msg, $status);
	}

	public function friendsList()
	{
		$page = request_int('page',1);
		$rows = request_int('rows',10);
		$user_name   = isset($_REQUEST['skey']) ? $_REQUEST['skey'] : null;

		$Sns_BaseModel = new Sns_BaseModel();

		$Sns_CommentModel = new Sns_CommentModel();

		$data = $Sns_BaseModel->getBaseList($user_name,$page,$rows);
		$itemsl = $data['items'];

		foreach ($itemsl as $key => $value)
		{
			//内容
			$items[$key]['sns'] = $value;
			$sns_user_name = $value['user_name'];
			if($value['sns_img'])
			{
				$items[$key]['sns']['img'] = explode(',', $value['sns_img']);
			}else
			{
				$items[$key]['sns']['img'] = array();
			}

			//发布动态者信息
			if($sns_user_name)
			{
				$User_InfoModel = new User_InfoModel();
				$user_info_row = $User_InfoModel->getInfo($sns_user_name);
				$user_info_row = array_values($user_info_row);
				$items[$key]['sns_user'] = $user_info_row[0];
			}
			else
			{
				$items[$key]['sns_user'] = array();
			}

			//点赞人数
			$sns_like_user = $value['sns_like_user'];
			$like_user_row = explode(',', $sns_like_user);

			$User_BaseModel = new User_BaseModel();
			$like_user_name = '';
			foreach ($like_user_row as $ke => $val)
			{
				$user_info = array();

				if($val)
				{
					$user_info = $User_BaseModel->getUser($val);

					if($user_info)
					{
						$like_user_name .= $user_info[$val]['user_account'].',';
					}
				}
			}
			$like_user_name =  substr($like_user_name, 0, -1) ;
			$items[$key]['like_user_name'] = $like_user_name;

			//获取评论
			$comment = $Sns_CommentModel->getCommentBySid($value['sns_id']);
			$items[$key]['comment'] = array_values($comment);

		}

		$data['items'] = array_values($items);
		$msg    = 'success';
		$status = 200;
		fb($data);
//
		if ($data){
			$result['cmd_id']=-140;
			$result['status']=200;
			$result['msg']='success';
			$result['data']=$data;
		}
		if($result['status']==200)
		{
			$rows = $result['data']['items'];
			if(!empty($rows)){
				foreach($rows as $key => $value)
				{
					$data_rs[$key]['id'] = $value['sns']['sns_id'];
					$data_rs[$key]['user_id'] = $value['sns']['user_id'];
					$data_rs[$key]['user_name'] = $value['sns']['user_name'];
					$data_rs[$key]['sns_title'] = $value['sns']['sns_title'];
					$data_rs[$key]['sns_content'] = $value['sns']['sns_content'];
					$data_rs[$key]['sns_create_time'] = date('Y-m-d h:i:s',$value['sns']['sns_create_time']);
					$data_rs[$key]['sns_comment_count'] = $value['sns']['sns_comment_count'];  //评论人数
					$data_rs[$key]['sns_copy_count'] = $value['sns']['sns_copy_count'];//转发人数
					$data_rs[$key]['sns_like_count'] = $value['sns']['sns_like_count'];//点赞人数
					foreach($value['sns']['img'] as $k=>$v)
					{
						$data_rs[$key]['img'][$k] = $v;
					}
				}
			}
			if(!empty($data_rs))
			{
				$msg = 'success';
				$status = 200;
			}
		}
		else
		{
			$data_rs = array();
			$msg = $result['msg'];
			$status = $result['status'];
		}
		$data_re['page'] = $result['data']['page'];
		$data_re['total'] = $result['data']['total'];
		$data_re['totalsize'] = $result['data']['totalsize'];
		$data_re['records'] = $result['data']['records'];
		$data_re['items'] = $data_rs;

		$this->data->addBody(-140,$data_re,$msg,$status);
	}

	/**
	 * 获取评论详情
	 *
	 * @access public
	 */
	public function commentList()
	{
		//		*************************************
		$id = $_REQUEST['id'];
		$Sns_BaseModel = new Sns_BaseModel();
		$sns_info = $Sns_BaseModel->getBase($id);
		$sns_info = array_values($sns_info);
		if($sns_info[0]['sns_img'])
		{
			$sns['img'] = explode(',', $sns_info[0]['sns_img']);
		}else
		{
			$sns['img'] = array();
		}


		$Sns_CommentModel = new Sns_CommentModel();
		$comment = $Sns_CommentModel->getCommentBySid($id);
		$sns_info['comment'] = array_values($comment);

//		*************************************
			$comment = $sns_info['comment'];
			if($comment)
			{
				foreach($comment as $key=>$value)
				{
					$items[$key]['id'] = $value['commect_id'];
					$items[$key]['commect_id'] = $value['commect_id'];
					$items[$key]['user_name'] = $value['user_name'];
					$items[$key]['commect_addtime'] = date('Y-m-d h:i:s',$value['commect_addtime']);
					$items[$key]['commect_content'] = $value['commect_content'];
				}
				$data_rs['items'] = $items;
				$msg = 'success';
				$status = 200;
			}
			else {
				$data_rs = array();
				$msg = '没有查询到回复内容';
				$status = 250;
			}
		$this->data->addBody(-140,$data_rs,$msg,$status);

	}
	/*
	 * 获取图片
	 *
	 */
	public function getImagesById()
	{
		$id = $_REQUEST['id'];
		$Sns_BaseModel = new Sns_BaseModel();
		$sns_info = $Sns_BaseModel->getBase($id);
		$sns_info = array_values($sns_info);

		if($sns_info[0]['sns_img'])
		{
			$sns['img'] = explode(',', $sns_info[0]['sns_img']);
		}else
		{
			$sns['img'] = array();
		}
		$msg    = 'success';
		$status = 200;
		$this->data->addBody(-140, $sns, $msg, $status);
	}


	/**
	 * 修改会员信息
	 *
	 * @access public
	 */
	public function editUserInfo()
	{
		$user_id = request_int('user_id');

		/*
		'app_id' => '105',
		'rtime' => 1471925935,
		'user_area' => '河北 唐山市 丰润区',
		'user_areaid' => '1150',
		'user_avatar' => 'http://127.0.0.1/pcenter/trunk/image.php/ucenter/data/upload/media/plantform/image/20160813/1471057867864788.jpg!120x120.jpg',
		'user_birthday' => '1989-10-03',
		'user_cityid' => '74',
		'user_delete' => 0,
		'user_email' => '323@fdsfa.com',
		'user_mobile' => '',
		'user_provinceid' => '3',
		'user_qq' => '15524721181',
		'user_realname' => 'zsd12111',
		'user_sex' => '0',
		'key' => 'HANZaFR0Aw08PV1U02RzCW114UWXa26AUiIO',
		*/
		$user_email    = request_string('user_email');
		$user_mobile    = request_string('user_mobile');

		$user_truename = request_string('user_realname');
		$user_area = request_string('user_area');
		$user_area_explode = explode(' ', $user_area);
		$user_sex      = request_int('user_sex');
		$user_qq       = request_string('user_qq');
		$user_logo     = request_string('user_avatar');
		$user_birth     = request_string('user_birthday');

		$user_delete   = request_int('user_delete');

		//$cond_row['user_passwd'] = md5($user_passwd);
		$edit_user_row['user_mobile']     = $user_mobile;
		$edit_user_row['user_email']    = $user_email;
		$edit_user_row['user_province']    = $user_area_explode[0];
		$edit_user_row['user_city']    = $user_area_explode[1];
		$edit_user_row['user_gender']    = $user_sex;
		$edit_user_row['user_birth']     = $user_birth;
		//$edit_user_row['user_sex']      = $user_sex;
		$edit_user_row['user_truename'] = $user_truename;
		$edit_user_row['user_qq']       = $user_qq;
		$edit_user_row['user_avatar']     = $user_logo;
//		echo '<pre>';print_r($edit_user_row);exit;
		//$edit_user_row['user_province']     = $user_logo;
		//$edit_user_row['user_city']     = $user_logo;
		//$edit_user_row['user_areaid']     = $user_logo;
		//$edit_user_row['user_area']     = $user_logo;
		//$edit_user_row['user_birth']     = $user_logo;

		$edit_base_row = array();
		if($user_delete)
		{
			$edit_base_row['user_delete'] = $user_delete;
		}

		//isset($_REQUEST['user_delete']) ? $edit_base_row['user_delete'] = $user_delete : '';

		//开启事物
		$rs_row = array();
		$this->userInfoModel->sql->startTransactionDb();
		if ($edit_base_row)
		{
			$update_flag = $this->userBaseModel->editBase($user_id, $edit_base_row);
			check_rs($update_flag, $rs_row);
		}
		$data = array();
		if ($edit_user_row)
		{
			$user_info = current($this->userBaseModel->getAccount($user_id));
			$user_name = $user_info['user_account'];
			$flag = $this->userInfoModel->editInfo($user_name, $edit_user_row);
			$data11[0] = $flag;
			check_rs($flag, $rs_row);
		}
			
		$flag = is_ok($rs_row);
		$data11[1] = $flag;
		//$this->data->addBody(-140, $edit_user_row, $flag, $status);
		if ($flag !== false && $this->userInfoModel->sql->commitDb())
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
     * 修改用户名
     * @param $user_id    用户ID
     * @param $userName   新用户名
     */
    public function modifUserName()
    {
        $userName = $_REQUEST['userName']; //修改后的名字
        $user_id = $_REQUEST['user_id'];
        $userName = 'bukebuke';$user_id=10404;
        $preg_check = '/^\w{5,20}$/';
        if(!preg_match($preg_check, $userName)){
            return $this->data->addBody(-140, [], '用户名不合法', 250);
        }
        $this->userBaseModel = new User_BaseModel();
        //根据用户ID获取用户名
        $user = $this->userBaseModel->getOne($user_id);
        if(!$user){
            return $this->data->addBody(-140, [], '用户信息有误', 250);
        }
        $user_name = $user['user_account']; //修改前的名字
        $this->userBaseModel->sql->startTransactionDb();
        $rs_rows = [];
        //修改base表
        $res1 = $this->userBaseModel->editUser($user_id, ['user_account'=>$userName]);
        $res1 = $res1 === false ? false : true;
        check_rs($res1, $rs_rows);
        //修改info表
        $res2 = $this->userInfoModel->editInfo($user_name, ['user_name'=>$userName]);
        $res2 = $res2 === false ? false : true;
        check_rs($res2, $rs_rows);
        //修改push表
        $user_push_model = new User_Push();
        $push_sql = 'update '.$user_push_model->_tableName.' set user_name="'.$userName.'" where user_id='.$user_id;
        $res3  = $user_push_model->sql->exec($push_sql);
        $res3 = $res3 === false ? false : true;
        check_rs($res3, $rs_rows);
        //修改chatlog 表
        $chatlog_model = new Chatlog();
        $push_sql = 'update '.$chatlog_model->_tableName.' set receiver="'.$userName.'" where receiver="'.$user_name.'"';
        $res4  = $user_push_model->sql->exec($push_sql);
        $res4 = $res4 === false ? false : true;
        check_rs($res4, $rs_rows);
        
        if (is_ok($rs_rows) && $this->userBaseModel->sql->commitDb()){
            return $this->data->addBody(-140, [], __('修改成功'), 200);
        }else{
            $this->userBaseModel->sql->rollBackDb();
            return $this->data->addBody(-140, $rs_rows, __('修改失败'), 250);
        }

    }
	
}

?>