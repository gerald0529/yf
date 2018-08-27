<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * Api接口
 *
 *
 * @category   Game
 * @package    User
 * @author     Xinze <xinze@live.cn>
 * @copyright  Copyright (c) 2015, 黄新泽
 * @version    1.0
 * @todo
 */
class UserApiCtl extends Yf_AppController
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
	}

	public function getUserInfo()
	{
		$user_name = request_string('user_account', Perm::$row['user_account']);

		$flag = true;
		$user_info_row = array();

		$cacheid = request_string('yf_cache') ;
		$cacheid = false;
		if($cacheid)
		{
			$cacheid = 'user_info'.Perm::$userId;
			$Cache = Yf_Cache::create('user_info');

			$user_info_row = $Cache->get($cacheid);

			if ($user_info_row)
			{
				$flag = false;
			}
		}

		if($flag)
		{
			$User_BaseModel = new User_BaseModel();
			$user_id_row = $User_BaseModel->getUserIdByName($user_name);
			//fb($user_id_row);
			if ($user_id_row)
			{
				$user_info_rows = $User_BaseModel->getUser($user_id_row);
			}

			if(!$user_info_rows)
			{
				$this->data->setError('账号不存在');
				return false;
			}
			else
			{
				$user_info_row = array_pop($user_info_rows);
				fb($user_info_rows);
				//查找用户详情
				$User_InfoModel = new User_InfoModel();
				$user_info_detail = $User_InfoModel->getInfo($user_name);
				$user_info_detail = array_values($user_info_detail);
				$user_info_detail_row = $user_info_detail[0];

				$user_info_row = array_merge($user_info_row,$user_info_detail_row);
				fb($user_info_detail_row);
				if(!$user_info_row['user_province']){
					$user_info_row['user_province']='';
				}
				if(!$user_info_row['user_city']){
					$user_info_row['user_city']='';
				}


			}

		}

		$this->data->addBody(100, $user_info_row);

		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}

	}

	public function getGuestInfo()
	{
		$user_name = trim(request_string('user_account')) ? trim(request_string('user_account')) : trim(request_string('user_name'));

		if(!$user_name)
		{
            return $this->data->addBody(100, array(),'账号不存在',250);
		}
        
        $User_InfoModel = new User_InfoModel();
        $user_info = $User_InfoModel->getOneByWhere(array('user_mobile'=>$user_name));
        $user_name_row = $user_info?$user_info['user_name']:$user_name;
        
        
		$user_id = Perm::$row['user_id'];

		$User_BaseModel = new User_BaseModel();
		$User_FriendModel=new User_FriendModel();

		$user_id_row = $User_BaseModel->getUserIdByName($user_name_row);

		if ($user_id_row)
		{
			$user_info_rows = $User_BaseModel->getUser($user_id_row);
			foreach($user_info_rows as $k=>$v){
				$flag=$User_FriendModel->checkFriend($user_id,$k);
				$user_info_rows[$k]['flag']=$flag;

			}
		}
		if(!$user_info_rows)
		{
			$this->data->setError('账号不存在!');
			return false;
		}
		else
		{
			$user_info_row = array_values($user_info_rows);
			$user_RnameModel=new User_RnameModel();
			$sns_row=new Sns_BaseModel();
			foreach ($user_info_row as $key => $value)
			{
				$info_row = $User_InfoModel->getInfo($user_info_row[$key]['user_account']);

				$flag=$user_RnameModel->getRnameId($user_id,$user_info_row[$key]['user_id']);
				if($flag){
					$datas=$user_RnameModel->getRname($flag);
					//备注名
					$info_row[$user_info_row[$key]['user_account']]['rename']=$datas[$flag[0]]['content'];
				}else{
					$info_row[$user_info_row[$key]['user_account']]['rename']='';
				}
				// var_dump($info_row);die;
				//根据账户名获id
				$img=$sns_row->getImgs($user_info_row[$key]['user_account']);
				$info_row[$user_info_row[$key]['user_account']]['flag']=$user_info_row[$key]['flag'];
				$info_row[$user_info_row[$key]['user_account']]['share_img']=$img;
				$info_row[$user_info_row[$key]['user_account']]['user_id']=$user_id_row['0'];
				$info_row = array_values($info_row);
				if(!$info_row[0]['user_province']){
					$info_row[0]['user_province']='';
				}
				if(!$info_row[0]['user_city']){
					$info_row[0]['user_city']='';
				}
				$user_info_detail_rows = $info_row[0];
			}
//			echo '<pre>';print_r($user_info_detail_rows);exit;
			fb($user_info_detail_rows);
			$this->data->addBody(100, $user_info_detail_rows);
		}
	}

	

	public function editUserInfo()
	{
		$user_name = Perm::$row['user_account'];
		$user_id = Perm::$row['user_id'];
		$password = request_string('pwd');
		$user_base_row = array();
		if ($password)
		{
			$user_base_row['password'] = md5($password);
		}

		$nickname    = request_string('name');
		$user_gender= request_string('sex', null);
		$user_birth = request_string('birth');
		$user_sign = request_string('sign');

		$mm=$_FILES['background'];
		file_put_contents('./aa.txt', $mm);
		file_put_contents('./bb.txt', $_FILES['logo']);
		Yf_Log::log('$user_id : ' . $user_id, Yf_Log::INFO, 'member');
		Yf_Log::log('$_FILES : ' . json_encode($_FILES), Yf_Log::INFO, 'member');
		Yf_Log::log('$_REQUEST : ' . json_encode($_REQUEST), Yf_Log::INFO, 'member');

		$user_row = array();
		$data = array();


		if (isset($_FILES['logo']))
		{
			//处理上传logo
			$upload = new HTTP_Upload('en');
			$files  = $upload->getFiles();

			if (PEAR::isError($files))
			{
				$data['msg'] = '用户logo上传错误';
				$flag = false;
			}
			else
			{
				foreach ($files as $file)
				{
					if ($file->isValid())
					{
						$p = '/data/member/';

						$ist = "1";

						switch ($ist)
						{
							case "1":
							{
								$p .= date('Y') . '/' . date('m') . '/' . date('d') . '/';
								break;
							}
							case "2":
							{
								$p .= date('Y') . '/' . date('m') . '/';
								break;
							}
							case "3":
							{
								$p .= date('Y') . '/';
								break;
							}
							default:
							{
								break;
							}
						}

						$path = APP_PATH . $p;

						if (!file_exists($path))
						{
							make_dir_path($path);
						}

						$file->setName('uniq');

						$file_name = $file->moveTo($path);

						if (PEAR::isError($file_name))
						{
							$flag = false;
							$data['msg'] = $file->getMessage();
						}
						else
						{
							$logo = Yf_Registry::get('base_url')  . '/'. APP_DIR_NAME . $p .  $file->upload['name'];

							$user_info['user_avatar'] = $logo;

							$Image_Resize = new Image_Resize();
							$Image_Resize->load($logo);
							$type = $Image_Resize->get_type($logo);
							$flag = $Image_Resize->resize('40','40');

							$logo_name = substr($logo,0,strrpos($logo,'.'));
							$logo_name = $logo_name.'_40.'.$type;
							imagejpeg($flag,$logo_name);

							$user_info['user_avatar_thumb'] = $logo_name;
						}
					}
					else
					{
						$flag = false;
						$data['msg'] = '用户logo发生错误 :' . $_FILES['upload']['name'] . '|' .  $file->errorMsg();;
					}
				}

			}
		}
		if (isset($_FILES['background']))
		{
			//处理上传logo
			$upload = new HTTP_Upload('en');
			$files  = $upload->getFiles();

			if (PEAR::isError($files))
			{
				$data['msg'] = '用户背景上传错误';
				$flag = false;
			}
			else
			{
				foreach ($files as $file)
				{
					if ($file->isValid())
					{
						$p = '/data/member/';

						$ist = "1";

						switch ($ist)
						{
							case "1":
							{
								$p .= date('Y') . '/' . date('m') . '/' . date('d') . '/';
								break;
							}
							case "2":
							{
								$p .= date('Y') . '/' . date('m') . '/';
								break;
							}
							case "3":
							{
								$p .= date('Y') . '/';
								break;
							}
							default:
							{
								break;
							}
						}

						$path = APP_PATH . $p;

						if (!file_exists($path))
						{
							make_dir_path($path);
						}

						$file->setName('uniq');

						$file_name = $file->moveTo($path);

						if (PEAR::isError($file_name))
						{
							$flag = false;
							$data['msg'] = $file->getMessage();
						}
						else
						{
							$logo = Yf_Registry::get('base_url')  . '/'. APP_DIR_NAME . $p .  $file->upload['name'];
							$user_info['background'] = $logo;
						}
					}
					else
					{
						$flag = false;
						$data['msg'] = '用户背景更换失败 :' . $_FILES['upload']['name'] . '|' .  $file->errorMsg();;
					}
				}

			}
		}
		Yf_Log::log('$user_info : ' . json_encode($user_info), Yf_Log::INFO, 'member');

		//性别
		if ($user_gender !== null)
		{
			$user_info['user_gender'] = $user_gender;
		}

		//昵称
		if ($nickname)
		{
			$user_info['nickname'] = $nickname;
		}

		//生日
		if ($user_birth)
		{
			$user_info['user_birth'] = $user_birth;
		}

		//用户签名
		if ($user_sign)
		{
			$user_info['user_sign'] = $user_sign;
		}
		$User_BaseModel = new User_BaseModel();
		$User_InfoModel = new User_InfoModel();

		$flags = false; $flag_detail = false;

		//修改密码
		if ($user_base_row)
		{
			$flags = $User_BaseModel->editUser($user_id, $user_base_row);
		}
		else
		{
			$flags = 1;
		}

		//修改用户信息
		if ($user_info)
		{
			//修改user_info
			if ($user_info)
			{
				$flag_detail = $user_info_rows = $User_InfoModel->editInfo($user_name, $user_info);

			}

		}
		else
		{
			$flag_detail = 1;
		}

		if($flags && $flag_detail)
		{
			$user_info_rows = $User_InfoModel->getInfo($user_name);
			$user_info_row = array_pop($user_info_rows);

			$msg         = '用户信息修改成功';
			$status      = 200;
			$data = array_merge($data, $user_info_row);
		}
		else
		{
			$msg         = '用户信息修改失败';
			$status      = 250;
		}
		$data['flags'] = $flags;
		$data['flag_detail'] = $flag_detail;
		if($user_info['user_avatar'])
		{
			$data['logo'] = $user_info['user_avatar'];
		}
		if($user_info['background']){
			$data['background']=$user_info['background'];
		}

		Yf_Log::log('$msg : ' . $msg, Yf_Log::INFO, 'member');
		Yf_Log::log('$data : ' . json_encode($data), Yf_Log::INFO, 'member');
		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function editUserAvatar()
	{
		$user_name = Perm::$row['user_account'];

		if (isset($_FILES['logo']))
		{
			//处理上传logo
			$upload = new HTTP_Upload('en');
			$files  = $upload->getFiles();

			if (PEAR::isError($files))
			{
				$data['msg'] = '用户logo上传错误';
				$flag = false;
			}
			else
			{
				foreach ($files as $file)
				{
					if ($file->isValid())
					{
						$p = '/data/member/';

						$ist = "1";

						switch ($ist)
						{
							case "1":
							{
								$p .= date('Y') . '/' . date('m') . '/' . date('d') . '/';
								break;
							}
							case "2":
							{
								$p .= date('Y') . '/' . date('m') . '/';
								break;
							}
							case "3":
							{
								$p .= date('Y') . '/';
								break;
							}
							default:
							{
								break;
							}
						}

						$path = APP_PATH . $p;

						if (!file_exists($path))
						{
							make_dir_path($path);
						}

						$file->setName('uniq');

						$file_name = $file->moveTo($path);

						if (PEAR::isError($file_name))
						{
							$flag = false;
							$data['msg'] = $file->getMessage();
						}
						else
						{
							$logo = Yf_Registry::get('base_url')  . '/'. APP_DIR_NAME . $p .  $file->upload['name'];

							$user_info['user_avatar'] = $logo;

							$Image_Resize = new Image_Resize();
							$Image_Resize->load($logo);
							$type = $Image_Resize->get_type($logo);
							$flag = $Image_Resize->resize('40','40');

							$logo_name = substr($logo,0,strrpos($logo,'.'));
							$logo_name = $logo_name.'_40.'.$type;
							imagejpeg($flag,$logo_name);

							$user_info['user_avatar_thumb'] = $logo_name;
						}
					}
					else
					{
						$flag = false;
						$data['msg'] = '用户logo发生错误 :' . $_FILES['upload']['name'] . '|' .  $file->errorMsg();;
					}
				}

			}

			$User_InfoModel = new User_InfoModel();
			$flag_detail = $User_InfoModel->editInfo($user_name, $user_info);
		}


		if($flag_detail)
		{
			$msg         = '用户头像修改成功';
			$status      = 200;
		}
		else
		{
			$msg         = '用户头像修改失败';
			$status      = 250;
		}

		$this->data->addBody(-140, $user_info, $msg, $status);
	}

	public function getFriendList()
	{
		$user_name = Perm::$row['user_account'];
		$user_id = Perm::$userId;
		$user_detail_rows = array();
		$User_FriendModel = new User_FriendModel();
		$user_friend_rows = $User_FriendModel->getUserFriendIdById($user_id);
		fb($user_friend_rows);

		foreach ($user_friend_rows as $key => $value)
		{
			$xin_array[$value['friend_id']] = $value['user_friend_is_xin'];
			$xin_limit[$value['friend_id']] = $value['user_friend_xin_limit'];
			$friend_id_arr[$value['friend_id']] = $value['user_friend_id'];
		}

		$user_friend_id_row = array_filter_key('friend_id', $user_friend_rows);
		if ($user_friend_id_row)
		{
			$User_BaseModel = new User_BaseModel();
			$d = $User_BaseModel->getUser($user_friend_id_row);
			foreach ($d as $dk => $dv)
			{
				$d_row[$dv['user_account']] = $dk;
			}
			$user_friend_name_row = array_filter_key('user_account', $d);
			// var_dump($user_friend_name_row);die;

			if ($user_friend_name_row)
			{
				$User_InfoModel = new User_InfoModel();
				$user_detail_rows = $User_InfoModel->getInfo($user_friend_name_row);
				$user_BaseModel=new User_BaseModel();
				$user_RnameModel=new User_RnameModel();
				foreach($user_detail_rows as $k=>$v){
					//根据用户名获取用户的id
					$fid=$user_BaseModel->getUserIdByAccount($k);
					$flag=$user_RnameModel->getRnameId($user_id,$fid[0]);
					if($flag){
						$datas=$user_RnameModel->getRname($flag);
						$user_detail_rows[$k]['rename']=$datas[$flag[0]]['content'];
					}else{
						$user_detail_rows[$k]['rename']='';
					}
					if(!$v['nickname']){
						$user_detail_rows[$k]['nickname']='';
					}
				}
			}
		}

		foreach ($user_detail_rows as $key => $value)
		{
			$user_detail_rows[$key]['user_id'] = $d_row[$key];
			$user_detail_rows[$key]['user_friend_is_xin'] = $xin_array[$d_row[$key]];
			$user_detail_rows[$key]['user_friend_xin_limit'] = $xin_limit[$d_row[$key]];
			$user_detail_rows[$key]['user_friend_id'] = $friend_id_arr[$d_row[$key]];
			$user_detail_rows[$key]['friend_status'] = 1;
		}

		if($user_detail_rows)
		{
			$Sns_BaseModel = new Sns_BaseModel();
			$timeline_id = array();
			foreach($user_detail_rows as $key=>$value)
			{
				$sns_base = current($Sns_BaseModel->getLastBaseByUserId($value['user_id']));
				if($sns_base)
				{
					$now_time = time();
					$action_time = $sns_base['sns_create_time'];
					if($now_time != $action_time)
					{
						$gap_time = ceil(($now_time - $action_time)/24/60/60);
						if($gap_time>=7 && $gap_time<30)
						{
							$gap_time = '1周';
						}
						elseif($gap_time>=30 && $gap_time<365)
						{
							$gap_time = '1月';
						}
						elseif($gap_time<7)
						{
							$gap_time = $gap_time.'天';
						}
					}
					$user_detail_rows[$key]['gap_time'] = $gap_time;
				}
				else
				{
					$user_detail_rows[$key]['gap_time'] = '';
				}
			}
		}
		$this->data->addBody(100, reset_null_recursive(array_values($user_detail_rows)));
	}

        //最近联系人
	public function getNewMesList()
	{
//		$user_id = Perm::$userId;
                $user_name = Perm::$row['user_account'];
          
		$user_detail_message_rows = array();
		$User_MsgModel = new Chatlog();
                $user_message_rows = $User_MsgModel->getLinkman($user_name);
                if($user_message_rows){
                    foreach ($user_message_rows as $key => $value) {
                        $link_name = $value['sender'] == $user_name ? $value['receiver'] : $value['sender'];
                        $user_message_id_row[$value['id']] = $link_name;
                    }
                }
                krsort($user_message_id_row);
		$user_message_id_row = array_unique($user_message_id_row);

                $User_InfoModel = new User_InfoModel();
                $user_detail_message_rows = $User_InfoModel->getInfoByName($user_message_id_row);
		$this->data->addBody(100, reset_null_recursive(array_values($user_detail_message_rows)));
	}

	public function addFriend()
	{
		$user_name = Perm::$row['user_account'];
		$user_id = Perm::$row['user_id'];
		$id = request_int('push_id');
		$friend_id = request_int('user_id');
		$friend_account = request_string('user_account');

		$data = array();

		$User_BaseModel = new User_BaseModel();
		//开启事务
		$User_BaseModel->sql->startTransactionDb();

        if($user_name == $friend_account){
            $msg = '不能关注自己！';
            $flag = false;
        }
		else
		{
			$friend_row = array();

			//获取添加为好友的用户信息
			if ($friend_id)
			{
				$friend_rows = $User_BaseModel->getUser($friend_id);

				if ($friend_rows)
				{
					$friend_row = array_pop($friend_rows);
				}

			}
			elseif($friend_account)
			{
				$friend_row = $User_BaseModel->getInfoByName($friend_account);
			}
			fb($friend_row);
			if ($friend_row)
			{
				$User_FriendModel = new User_FriendModel();

				//判断记录中是否存在我是请求方好友的情况存在。
				//在好友表中有一个friend_status字段存在，0不是互为好友 1互为好友。
				//存在a和b本来是互为好友，但是后来a删除了b。那么删除b为a的好友记录。将a为b的好友记录friend_status该为0.
				$find_row = array();
				$find_row['user_id'] = $user_id;
				$find_row['friend_id'] = $friend_row['user_id'];
				$is_friend = $User_FriendModel->getByWhere($find_row);
				if($is_friend)
				{
					$User_FriendModel->editFriend(array_keys($is_friend),array('friend_status'=>1));
				}
				else
				{
					$d = array();
					$d['user_id'] = $user_id;
					$d['friend_id'] = $friend_row['user_id'];
					$d['friend_status']=1;
					$rs = $User_FriendModel->addFriend($d,true);
				}


				$find_row = array();
				$find_row['user_id'] = $friend_row['user_id'];
				$find_row['friend_id'] = $user_id;
				$is_friend = $User_FriendModel->getByWhere($find_row);
				if($is_friend)
				{
					$User_FriendModel->editFriend(array_keys($is_friend),array('friend_status'=>1));
				}
				else
				{
					$dd=array();
					$dd['friend_id']=$user_id;
					$dd['user_id']=$friend_row['user_id'];
					$dd['friend_status']=1;
					$res = $User_FriendModel->addFriend($dd,true);
				}

				//修改推送信息的状态
				$user_Push = new User_PushModel();
				$flag=$user_Push->eidtFriendStatus($id);

				$User_InfoModel = new User_InfoModel();
				$user_detail_rows = $User_InfoModel->getInfo($friend_row['user_account']);
				$datas=$User_BaseModel->getUserIdByAccount($friend_row['user_account']);
				$user_detail_rows[$friend_row['user_account']]['user_id']=$datas['0'];
				$user_detail_row = array_pop($user_detail_rows);

				//查找user_friend_is_xin 和 user_friend_xin_limit
				/*$user_xin_info = $User_FriendModel->getFriend($rs);

				$user_detail_row['user_friend_is_xin'] = $user_xin_info[$rs]['user_friend_is_xin'];
				$user_detail_row['user_friend_xin_limit'] = $user_xin_info[$rs]['user_friend_xin_limit'];*/

				$data = $user_detail_row;
			}
			else
			{
				$msg = '无此用户';
				$flag = false;
			}
		}

		if($flag && $User_BaseModel->sql->commitDb())
		{
			$msg    = '添加好友成功';
			$status = 200;
		}
		else
		{
			$User_BaseModel->sql->rollBackDb();
			$msg    = $msg ? $msg : '添加好友失败';
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);

	}


	public function removeFriend()
	{
		$user_name = Perm::$row['user_account'];
		$user_id = Perm::$row['user_id'];

		$friend_id = request_int('user_id');
		$friend_account = request_string('user_account');
		$user_push=new User_PushModel();
		$User_BaseModel = new User_BaseModel();
		$friend_row = array();

		if ($friend_id)
		{
			$friend_rows = $User_BaseModel->getUser($friend_id);

			if ($friend_rows)
			{
				$friend_row = array_pop($friend_rows);
			}

		}
		elseif($friend_account)
		{
			$friend_row = $User_BaseModel->getInfoByName($friend_account);
		}

		if ($friend_row)
		{
			$User_FriendModel = new User_FriendModel();

			$d = array();
			$d['user_id'] = $user_id;
			$d['friend_id'] = $friend_row['user_id'];

			$user_friend_rows = $User_FriendModel->getUserFriendIdById($user_id, $friend_row['user_id']);

			//修改另一组好友关系的friend_status
			$edit_friend = $User_FriendModel->getUserFriendIdById($friend_row['user_id'], $user_id);
			if($edit_friend)
			{
				$edit_friend_id = array_keys($edit_friend);
				$re = $User_FriendModel->editFriend($edit_friend_id,array('friend_status'=>0));
			}

			if ($user_friend_rows)
			{
				$user_friend_id_row = array_keys($user_friend_rows);
				$rs = $User_FriendModel->removeFriend($user_friend_id_row);
				//删除对应的好友推送消息
				$user_push->removePushUser($user_id,$friend_id);
				$rs=1;$re=1;
				if ($rs !== false && $re !== false)
				{
					//1.查找发表过的动态
					$Sns_BaseModel = new Sns_BaseModel();
					$sns_id = $Sns_BaseModel->getBaseByUserId($friend_row['user_id']);
					
					$Sns_TimelineModel = new Sns_TimelineModel();
					$Sns_TimelineModel->removeTimeByUidSid($user_id,$sns_id);

					//2.查找好友备注
					$User_RnameModel = new User_RnameModel();
					$user_rename = $User_RnameModel->getRnameId($user_id,$friend_id);
					if($user_rename)
					{
						$User_RnameModel->removeRname($user_rename);
					}

					$this->data->addBody(100, $d);
				}
				else
				{
					$this->data->setError('删除好友失败');
				}
			}
			else
			{
				$this->data->setError('删除好友失败');
			}

		}
		else
		{
			$this->data->setError('无此用户');
			return false;
		}

	}

	//sns中的关注好友即为app中推送好友的同时添加对方为好友
	public function addFriendSns()
	{
		$user_account = Perm::$row['user_account'];
		$user_id = Perm::$row['user_id'];
		$id = request_int('push_id');
		$friend_id = request_int('user_id');
		$friend_account = request_string('user_account');

		$User_FriendModel = new User_FriendModel();
		if($friend_id == $user_id){
			$msg = _('failure');
			$status = 250;
		}
		else
		{
			$User_FriendModel->sql->startTransactionDb();

			//1.添加推送表的信息
			$user_Push=new User_PushModel();
			$flag=$user_Push->checkUser($user_id,$friend_id);
			if($flag)
			{
				$field = array('user_id' => $user_id, 'fuid' => $friend_id, 'user_name' => $user_account, 'funame' => $friend_account, 'addtime' => time());
				$user_Push->addPush($field);
			}

			//2.将对方添加为自己的好友
			$add_friend = array();
			$add_friend['user_id'] = $user_id;
			$add_friend['friend_id'] = $friend_id;
			$add_friend['friend_status']=0;
			$res = $User_FriendModel->addFriend($add_friend);

			if($res && $User_FriendModel->sql->commitDb())
			{
				$msg    = _('success');
				$status = 200;
			}
			else
			{
				$User_FriendModel->sql->rollBackDb();
				$msg    = _('failure');
				$status = 250;
			}
		}


		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);

	}


	//用户激活了那些服务
	public function getUserAppServer()
	{
		$app_id = $_REQUEST['app_id'];
		$session_id = $_REQUEST['session_id'];
		$user_name = $_REQUEST['user_name'];
		$company_id = $_REQUEST['company_id'];

		if(!is_int_numeric($app_id))
		{
			$this->data->setError('参数错误');
			return false;
		}

		$User_AppServerModel = new User_AppServerModel();

		$arr_condition = array();
		$arr_condition['user_name'] = $user_name;
		$arr_condition['app_id'] = $app_id;
		//$arr_condition['company_id'] = $company_id;

		$arr_server_id = array();
		$user_app_server_rows = $User_AppServerModel->getUserAppServerByCondition($arr_condition);

//        foreach($arr_user_app_server_list as $key => $value)
//        {
//            if(!in_array($value['server_id'], $arr_server_id))
//            {
//                $arr_server_id[] = $value['server_id'];
//            }
//        }

		$this->data->addBody(100, $user_app_server_rows);
	}

	public function returnVersion()
	{
		echo $_REQUEST['version'];
		die();
	}


	//判断手机号是否已注册
	public function judgeUser()
	{
		$user_id = Perm::$row['user_id'];

		$mobile =  $_REQUEST['mobile'];

		$array = explode(',', $mobile);

		//获取当前用户的好友
		$User_FriendModel = new User_FriendModel();
		$friend_row = $User_FriendModel->getUserFriendIdById($user_id);
		foreach ($friend_row as $key => $value)
		{
			$friend_id_row[] = $value['friend_id'];
		}

		//判断手机号是否已注册
		$User_BaseModel = new User_BaseModel();
		foreach ($array as $key => $value)
		{
			$uid = $User_BaseModel->getUserIdByAccount($value);

			if($uid)
			{
				//判断此用户是否是好友
				if(in_array($uid[0], $friend_id_row))
				{
					$data[$key][0]=$value;
					$data[$key][1]=3;
				}
				else
				{
					$data[$key][0]=$value;
					$data[$key][1]=2;
				}
			}
			else
			{
				$data[$key][0]=$value;
				$data[$key][1]=1;
			}
		}

		$this->data->addBody(100, $data);

	}

	//给没有注册的手机发推送信息
	public function sendMsg()
	{
		$mobile =  $_REQUEST['mobile'];
		//发送短消息
		$contents = '请快去下载App并注册会员吧！';

		$result = Sms::send($mobile, $contents);

		$data = array();

		$this->data->addBody(100, $data);
	}

	//修改授信
	public function editFriendXin()
	{
		$user_id = Perm::$row['user_id'];
		$user_account = Perm::$row['user_account'];

		$user_friend_id = $_REQUEST['user_friend_id'];
		$is_xin = $_REQUEST['is_xin'];    //是否授信 1-未授信  2-已授信
		$xin_limit = $_REQUEST['xin_limit'];  //授信额度
		$flag = 0;

		$User_InfoModel = new User_InfoModel();				
		$user_info = $User_InfoModel->getInfo($user_account);

		$user_xin_limit = array_filter_key('limit_remain', $user_info);
		$user_limit_remain = $user_xin_limit[0];   //当前用户平台授信额度

		$User_FriendModel = new User_FriendModel();
		$friend_info = $User_FriendModel->getFriend($user_friend_id);

		$friend_xin = array_filter_key('user_friend_xin_limit', $friend_info);
		$friend_xin_limit = $friend_xin[0];   //当前好友授信额度

		if($is_xin)
		{
			$field['user_friend_is_xin'] = $is_xin;
			if($is_xin == 1)
			{

				//将好友的授信额度返回
				$cha1 = $user_limit_remain + $friend_xin_limit;
				$user_field1['limit_remain'] = $cha1;
				$User_InfoModel->editInfo($user_account,$user_field1);

				$field['user_friend_xin_limit'] = 0;
				$flag = $User_FriendModel->editFriend($user_friend_id,$field);

			}
		}
		if($xin_limit && $is_xin!=1)
		{
			//计算授信额度是否在超过可授信额度
			if(($friend_xin_limit + $user_limit_remain) >= $xin_limit)
			{
				$field['user_friend_xin_limit'] = $xin_limit;
				$flag = $User_FriendModel->editFriend($user_friend_id,$field);

				//修改当前用户的剩余平台信用额度

				$cha = $friend_xin_limit - $xin_limit;
				$cha1 = $user_limit_remain + $cha;

				$user_field['limit_remain'] = $cha1;
				$User_InfoModel->editInfo($user_account,$user_field);
			}
			else
			{
				$flag = 0 ;
			}
		}

		if($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}
	//根据用户id查询用户信息
	public function getUserInfos(){
		$user_id=request_int('user_id');
		$User_Base=new User_BaseModel();
		$User_Info=new User_InfoModel();
		$data=$User_Base->getUser($user_id);
		$user_account=$data[$user_id]['user_account'];
		$datas=$User_Info->getInfo($user_account);
		$datas=array_pop($datas);
		if(true)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$data = array();
		$this->data->addBody(-140, $datas, $msg, $status);
	}
	//根据用户id查询用户信息
	public function getUserInfosByName()
	{
		$user_account = request_string('user_name');
		$User_Base=new User_BaseModel();
		$User_Info=new User_InfoModel();
		$data=$User_Info->getInfo($user_account);
		$data=array_pop($data);
		
		if($data)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 用户获取群组信息
	 * @param user_id int 用户id
	 * 2017-07-05 hp
	 */
	public function getUserGroupList(){
		$user_id = request_int('user_id');//用户id
		$User_GroupModel = new User_GroupModel();
		$User_InfoModel = new User_InfoModel();
		$User_BaseModel = new User_BaseModel();
		$group_data = $User_GroupModel->getByWhere();
		//查询出所有群组，然后将群组成员字符串组成新数组，循环找出用户已加入的群组id
		$group_user_name = array_column($group_data, 'group_user');
		foreach($group_user_name as $key=>$val)
		{
			if(strpos($val, "$user_id") !== false)
			{
				$group_id_rows[] = $key;
			}
		}
		$data = [];
		//如果有群组信息，则返回群组信息
		if($group_id_rows)
		{
			$cond_row['group_id:IN'] = $group_id_rows;
			$group_list = $User_GroupModel->getByWhere($cond_row);
			$group_user_row = array_values(array_map(function($val){return explode(',', $val['group_user']);}, $group_list));
			$group_list = array_values($group_list);
			foreach($group_user_row as $k=>$v)
			{
				$cond_user_id_row['user_id:IN'] = $v;
				$user_info_row = $User_BaseModel->getByWhere($cond_user_id_row);
				$cond_user_name_row['user_name:IN'] = array_values(array_column($user_info_row, 'user_account'));
				$user_name_row = $User_InfoModel->getByWhere($cond_user_name_row);
				$user_avatar_row = array_values(array_column($user_name_row, 'user_avatar'));
				foreach($user_avatar_row as $uk=>$uv)
				{
					if(!isset($uv))
					{
						$user_avatar_row[$uk] = Web_ConfigModel::value("user_default_avatar");
					}
				}
				$group_list[$k]['user_avatar'] = implode(',', $user_avatar_row);
			}
			$data = array_values($group_list);
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = '您还没有加入群组';
			$status = 250;
		}
		fb($data);
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//修改用户的所属的省和市
	public function editUserAddress(){
		$user_account = Perm::$row['user_account'];
		$province=request_string('province');
		$province=explode(',',$province);
		$address['user_province']=$province[0];
		$address['user_city']=$province[1];
		$user_Info=new User_InfoModel();
		$flag=$user_Info->editInfo($user_account,$address);
		$data=$user_Info->getInfo($user_account);
		$data=array_pop($data);
		if($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}
	
	public function UcenterEditUserImg()
	{
		$user_name = Perm::$row['user_account'];
		$user_id = Perm::$row['user_id'];


		//本地读取远程信息
		$url                       = Yf_Registry::get('ucenter_api_url');
		$ucenter_app_id            = Yf_Registry::get('ucenter_app_id');

		$user_app = new User_App();
		$user_app_key = current($user_app->getApp($ucenter_app_id));
		$key = $user_app_key['app_key'];

		$formvars                  = array();

		$formvars['user_id']  = $user_id;
		$formvars['app_id']        = $ucenter_app_id;

		$formvars['ctl'] = 'Api_User';
		$formvars['met'] = 'editUserImg';
		$formvars['typ'] = 'json';

		if (isset($_FILES['logo']))
		{
			//处理上传logo
			$upload = new HTTP_Upload('en');
			$files  = $upload->getFiles();


				foreach ($files as $file)
				{
					if ($file->isValid())
					{
						$p = '/data/member/';

						$ist = "1";

						switch ($ist)
						{
							case "1":
							{
								$p .= date('Y') . '/' . date('m') . '/' . date('d') . '/';
								break;
							}
							case "2":
							{
								$p .= date('Y') . '/' . date('m') . '/';
								break;
							}
							case "3":
							{
								$p .= date('Y') . '/';
								break;
							}
							default:
							{
								break;
							}
						}

						$path = APP_PATH . $p;

						if (!file_exists($path))
						{
							make_dir_path($path);
						}

						$file->setName('uniq');

						$file_name = $file->moveTo($path);

						$logo = Yf_Registry::get('base_url')  . '/'. APP_DIR_NAME . $p .  $file->upload['name'];

						$user_info['user_avatar'] = $logo;

					}

				}

			$User_InfoModel = new User_InfoModel();
			$flag_detail = $User_InfoModel->editInfo($user_name, $user_info);

		}
		$data = array();

		if($flag_detail)
		{
			$msg         = '用户头像修改成功';
			$status      = 200;
			$formvars['user_avatar'] = $logo;
			$init_rs         = get_url_with_encrypt($key, $url, $formvars);
			if (200 == $init_rs['status'] && $init_rs['data'])
			{
				$User_InfoModel = new User_InfoModel();
				$data = $User_InfoModel->getUserInfo($user_name);
				$msg = 'success';
				$status = 200;
			}
			else
			{
				$msg = 'failure';
				$status = 250;
			}
		}
		else
		{
			$msg         = '用户头像修改失败';
			$status      = 250;
		}


		$this->data->addBody(-140, $data, $msg, $status);
	}

	public function UcenterEditUserInfo()
	{
		$user_name = Perm::$row['user_account'];
		$user_id = Perm::$row['user_id'];
		$User_InfoModel = new User_InfoModel();
		$user_info_old = $User_InfoModel->getUserInfo($user_name);
		$url = Yf_Registry::get('ucenter_api_url');
		$ucenter_app_id = Yf_Registry::get('ucenter_app_id');
		$user_app = new User_App();
		$user_app_key = current($user_app->getApp($ucenter_app_id));
		$key = $user_app_key['app_key'];

		//$key = 'HANZaFR0Aw08PV1U20RzCW411UWXa26AUiIO';
		$nickname    = request_string('name');
		$user_gender = request_int('sex',3);
		$user_birth = request_string('birth');
		$user_sign = request_string('sign');
		$user_area = request_string('province');
		$formvars            = array();
		$user_info            = array();

		//地区信息
		if (!$user_area)
		{
			$formvars['user_area'] = $user_info_old['user_province'].' '.$user_info_old['user_city'];
			$formvars['user_province'] = $user_info_old['user_province'];
			$formvars['user_city'] = $user_info_old['user_city'];
			$user_info['user_province'] = $user_info_old['user_province'];
			$user_info['user_city'] = $user_info_old['user_city'];
		}
		else
		{
			$user_area_split = explode(' ', $user_area);
			$user_province = $user_area_split[0];
			$user_city = $user_area_split[1];
			$formvars['user_area'] = $user_area;
			$formvars['user_province'] = $user_province;
			$formvars['user_city'] = $user_city;
			$user_info['user_province'] = $user_province;
			$user_info['user_city'] = $user_city;
		}

		//性别
		if ($user_gender == 3)
		{
			$user_info['user_gender'] = $user_info_old['user_gender'];
			$formvars['user_gender'] = $user_info_old['user_gender'];
		}
		else
		{
			$user_info['user_gender'] = $user_gender;
			$formvars['user_gender'] = $user_gender;
		}

		//昵称
		if ($nickname)
		{
			$user_info['nickname'] = $nickname;
			$formvars['nickname'] = $nickname;
		}
		else
		{
			$user_info['nickname'] = $user_info_old['nickname'];
			$formvars['nickname'] = $user_info_old['nickname'];
		}

		//生日
		if ($user_birth)
		{
			$user_info['user_birth'] = $user_birth;
			$formvars['user_birth'] = $user_birth;
		}
		else
		{
			$user_info['user_birth'] = $user_info_old['user_birth'];
			$formvars['user_birth'] = $user_info_old['user_birth'];
		}

		//用户签名
		if ($user_sign)
		{
			$user_info['user_sign'] = $user_sign;
			$formvars['user_sign'] = $user_sign;
		}
		else
		{
			$user_info['user_sign'] = $user_info_old['user_sign'];
			$formvars['user_sign'] = $user_info_old['user_sign'];
		}
		$flag_detail = false;

		//修改用户信息
		if ($user_info)
		{
			//修改user_info
			if ($user_info)
			{
				$flag_detail = $user_info_rows = $User_InfoModel->editInfo($user_name, $user_info);
			}
		}
		else
		{
			$flag_detail = 0;
		}

		$data = array();
		if($flag_detail)
		{
			$msg         = '用户信息修改成功';
			$status      = 200;
			$formvars['user_id'] = $user_id;
			$formvars['app_id'] = $ucenter_app_id;
			$formvars['ctl'] = 'Api_User';
			$formvars['met'] = 'editUserInfoDetail';
			$formvars['typ'] = 'json';

			$init_rs         = get_url_with_encrypt($key, $url, $formvars);

			if ($init_rs)
			{
				$data = $User_InfoModel->getUserInfo($user_name);
				$msg = 'success';
				$status = 200;
				//更新状态app server 信息及状态
			}
			else
			{
				$msg = 'failure';
				$status = 250;
			}
		}
		else
		{
			$msg         = '用户信息修改失败';
			$status      = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	//修改用户version
	public function editUserVersion()
	{
		$user_id = Perm::$userId;
		$version = request_int('version');
		$User_BaseModel = new User_BaseModel();

		$field = array();
		$field['message_version'] = $version;
		$init_rs = $User_BaseModel->editUser($user_id,$field);

		if ($init_rs)
		{
            //修改聊天状态
            $user_model = new User_BaseModel();
            $user_info = $user_model->getOneByWhere(array('user_id'=>$user_id));
            if($user_info['user_account']){
                $addMessage = new User_MsgModel();
                $res = $addMessage->editUserReadStatus($user_info['user_account']);
            }
			$msg = 'success';
			$status = 200;
		}
		else
		{
			$msg = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, array(), $msg, $status);


	}

}
?>