<?php if (!defined('ROOT_PATH')){exit('No Permission');}

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
class UserSnsCtl extends Yf_AppController
{
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

		//include $this->view->getView();
	}


	/**
	 * 朋友圈API - 获取动态信息
	 *
	 * @access public
	 */
	public function getSns_shanghai()
	{
		$user_id = request_int('user_id',Perm::$row['user_id']);
		$page = request_int('page',1);
		$rows = request_int('rows',10);
		//fb($user_id);
		$Sns_TimelineModel = new Sns_TimelineModel();
		$data = $Sns_TimelineModel->getTimelineList($user_id,$page, $rows);

		$items = $data['items'];

		$Sns_BaseModel = new Sns_BaseModel();

		$Sns_CommentModel = new Sns_CommentModel();


		foreach ($items as $key => $value)
		{
			//内容
			$sns = array_values($Sns_BaseModel->getBase($value['sns_id']));
			//fb($sns);
			if($sns && $sns[0]['is_del'] == 0)
			{
				$items[$key]['sns'] = $sns[0];
				$sns_user_name = $sns[0]['user_name'];
				if($sns[0]['sns_img'])
				{
					$items[$key]['sns']['img'] = array_filter(explode(',', $sns[0]['sns_img']));
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
				$sns_like_user = $sns[0]['sns_like_user'];
				$like_user_row = explode(',', $sns_like_user);
				if(in_array($user_id,$like_user_row))
				{
					$items[$key]['islike'] = 1;
				}
				else
				{
					$items[$key]['islike'] = 0;
				}
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
			else
			{
				unset($items[$key]);
			}
		}

		$data['items'] = array_values($items);
		$msg    = 'success';
		$status = 200;
		//fb($data);
		$this->data->addBody(-140, $data, $msg, $status);
	}
	
	/**
	 * 朋友圈API - 获取动态信息
	 *
	 * @access public
	 */
	public function getSns()
	{
		$user_id = request_int('user_id',Perm::$row['user_id']);
		$user_name=Perm::$row['user_account'];
		$page = request_int('page',1);
		$rows = request_int('rows',10);
		$kindType = request_int('kindType', 0);
		fb($user_id);
		fb($user_name);

		$User_FriendModel = new User_FriendModel();
		$Sns_BaseModel = new Sns_BaseModel();
		$Sns_TimelineModel = new Sns_TimelineModel();
		$Sns_CommentModel = new Sns_CommentModel();
		$Sns_ForwardModel = new Sns_ForwardModel();
		$Sns_CollectionModel = new Sns_CollectionModel();
		//找出所有解除好友关系的好友，然后朋友圈不显示这些好友动态
		$user_friends_data = $User_FriendModel->getByWhere(['user_id'=>$user_id, 'friend_status'=>0]);
		$friend_ids = array_column($user_friends_data, 'friend_id');
		$data = $Sns_TimelineModel->getTimelineList($user_id,$page,$rows);

		if($kindType == 2)
		{
			$data = $Sns_CollectionModel->getCollectionList($user_id,$page,$rows);
		}
		elseif($kindType == 1)
		{
			$data = $Sns_BaseModel->getBaseSns($user_id, $page, $rows, $sort='desc', $sns_lable=null, $sns_id=null, $friend_ids);
		}
		$items = $data['items'];
		fb($items);
		
		foreach ($items as $key => $value)
		{
			//内容
			$sns = $Sns_BaseModel->getOne($value['sns_id']);
			$items[$key]['sns'] = $sns;

			$sns_user_name = $sns['user_name'];
			$sns_user_id = $sns['user_id'];
			//如果是音乐帖和视频帖则无需解码
			$sns_type_row = array(7,8);
			if(!in_array($items[$key]['sns']['sns_type'], $sns_type_row))
			{
				$items[$key]['sns']['sns_content'] = base64_decode($items[$key]['sns']['sns_content']);
				$items[$key]['sns']['sns_new_title'] = '';
				$items[$key]['sns']['sns_h5'] = '';
			}
			else
			{
				$link =  current($this->RegExp($items[$key]['sns']['sns_content']));
				$title = substr($link,0,22)."……";
				$items[$key]['sns']['sns_new_title'] = $title;
				$items[$key]['sns']['sns_h5'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.urlencode($link).'&title='.urlencode($title);
			}
			if($sns['sns_img'])
			{
				$sns['sns_img'] = str_replace("amp;", "", $sns['sns_img']);
				$items[$key]['sns']['img'] = array_filter(explode(',', $sns['sns_img']));
			}else
			{
				$items[$key]['sns']['img'] = array();
			}
			if($sns['sns_lable'])
			{
				$items[$key]['sns']['lable'] = array_filter(explode(',', $sns['sns_lable']));

			}else
			{
				$items[$key]['sns']['lable'] = array();
			}

			//计算帖子的热度（转发，收藏，点赞）
			$items[$key]['sns']['hot_count'] = $sns['sns_copy_count'] + $sns['sns_like_count'] + $sns['sns_collection'];

			//判断该帖子是否是转载贴，如果是查找出被抓的帖子和原贴的信息
			if($sns['sns_forward'])
			{
				$forward = $Sns_ForwardModel->getForwardByFid($sns['sns_id']);
				$forward = current($forward);

				$forword_sns = $Sns_BaseModel->getBaseById($forward['sns_id']);
				$forword_sns = current($forword_sns);
				$forword_sns['sns_content'] = base64_decode($forword_sns['sns_content']);
				foreach($forword_sns['user_info']['user_sns'] as $fkey=>$fvalue)
				{
					$sns_type_row = array(7,8);
					if(!in_array($forword_sns['user_info']['user_sns'][$fkey]['sns_type'], $sns_type_row))
					{
						$forword_sns['user_info']['user_sns'][$fkey]['sns_content'] = base64_decode($forword_sns['user_info']['user_sns'][$fkey]['sns_content']);
					}
				}

				//判断转发的用户是否是自己的好友
				$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $forword_sns['user_id']);
				if($friend_status)
				{
					$forword_sns['is_friend'] = 1;
				}
				else
				{
					$forword_sns['is_friend'] = 0;
				}

				$source_sns = $Sns_BaseModel->getBaseById($forward['source_sns_id']);
				$source_sns = current($source_sns);
//				$source_sns['sns_content'] = base64_decode($source_sns['sns_content']);
//				foreach($source_sns['user_info']['user_sns'] as $skey=>$svalue)
//				{
//					$sns_type_row = array(7,8);
//					if(!in_array($source_sns['user_info']['user_sns'][$skey]['sns_type'], $sns_type_row))
//					{
//						$source_sns['user_info']['user_sns'][$skey]['sns_content'] = base64_decode($source_sns['user_info']['user_sns'][$skey]['sns_content']);
//					}
//				}

				$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $source_sns['user_id']);
				if($friend_status)
				{
					$source_sns['is_friend'] = 1;
				}
				else
				{
					$source_sns['is_friend'] = 0;
				}
				if($source_sns['sns_img'])
				{
					$source_sns['img'] = array_filter(explode(',', $source_sns['sns_img']));
				}else
				{
					$source_sns['img'] = array();
				}
				if($source_sns['sns_lable'])
				{
					$source_sns['lable'] = array_filter(explode(',', $source_sns['sns_lable']));
				}else
				{
					$source_sns['lable'] = array();
				}
				$items[$key]['sns']['forword_sns'] = $forword_sns;
				$items[$key]['sns']['source_sns'] = $source_sns;

			}else
			{
				$items[$key]['sns']['forword_sns']['sns_id'] = '';
				$items[$key]['sns']['forword_sns']['user_id'] = '';
				$items[$key]['sns']['forword_sns']['user_name'] = '';
				$items[$key]['sns']['forword_sns']['sns_title'] = '';
				$items[$key]['sns']['forword_sns']['sns_content'] = '';
				$items[$key]['sns']['forword_sns']['sns_img'] = '';
				$items[$key]['sns']['forword_sns']['sns_type'] = '';
				$items[$key]['sns']['forword_sns']['sns_create_time'] = '';
				$items[$key]['sns']['forword_sns']['sns_status'] = '';
				$items[$key]['sns']['forword_sns']['is_del'] = '';
				$items[$key]['sns']['forword_sns']['sns_privacy'] = '';
				$items[$key]['sns']['forword_sns']['sns_comment_count'] = '';
				$items[$key]['sns']['forword_sns']['sns_copy_count'] = '';
				$items[$key]['sns']['forword_sns']['sns_like_count'] = '';
				$items[$key]['sns']['forword_sns']['sns_like_user'] = '';
				$items[$key]['sns']['forword_sns']['sns_forward'] = '';
				$items[$key]['sns']['forword_sns']['sns_lable'] = '';
				$items[$key]['sns']['forword_sns']['sns_collection'] = '';
				$items[$key]['sns']['forword_sns']['sns_like_user_addtime'] = '';

				$items[$key]['sns']['source_sns']['sns_id'] = '';
				$items[$key]['sns']['source_sns']['user_id'] = '';
				$items[$key]['sns']['source_sns']['user_name'] = '';
				$items[$key]['sns']['source_sns']['sns_title'] = '';
				$items[$key]['sns']['source_sns']['sns_content'] = '';
				$items[$key]['sns']['source_sns']['sns_img'] = '';
				$items[$key]['sns']['source_sns']['sns_type'] = '';
				$items[$key]['sns']['source_sns']['sns_create_time'] = '';
				$items[$key]['sns']['source_sns']['sns_status'] = '';
				$items[$key]['sns']['source_sns']['is_del'] = '';
				$items[$key]['sns']['source_sns']['sns_privacy'] = '';
				$items[$key]['sns']['source_sns']['sns_comment_count'] = '';
				$items[$key]['sns']['source_sns']['sns_copy_count'] = '';
				$items[$key]['sns']['source_sns']['sns_like_count'] = '';
				$items[$key]['sns']['source_sns']['sns_like_user'] = '';
				$items[$key]['sns']['source_sns']['sns_forward'] = '';
				$items[$key]['sns']['source_sns']['sns_lable'] = '';
				$items[$key]['sns']['source_sns']['sns_collection'] = '';
				$items[$key]['sns']['source_sns']['sns_like_user_addtime'] = '';
			}

			//判断该帖子是否是用户的收藏贴
			$sns_user_collection = $Sns_CollectionModel->getCollectBySidUid($value['sns_id'], $user_id);
			if($sns_user_collection)
			{
				$items[$key]['is_collection'] = 1;
			}
			else
			{
				$items[$key]['is_collection'] = 0;
			}

			//判断该帖的发帖者是否是用户的好友
			$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $sns_user_id);
			if($friend_status)
			{
				$items[$key]['is_friend'] = 1;
			}
			else
			{
				$items[$key]['is_friend'] = 0;
			}

			//发布动态者信息
			$user_RnameModel=new User_RnameModel();
			$user_BaseModel=new User_BaseModel();
			$User_InfoModel = new User_InfoModel();
			if($sns_user_name)
			{
				$user_info_row = $User_InfoModel->getUserInfo($sns_user_name,$sns_user_id);
				//APP接收字段类型不能为null，所以判断用户信息字段类型并且替换
				if($user_info_row['user_name'] === null)
					$user_info_row['user_name'] = "";
				if($user_info_row['nickname'] === null)
					$user_info_row['nickname'] = "";
				if($user_info_row['user_site_domain'] === null)
					$user_info_row['user_site_domain'] = "";
				if($user_info_row['user_question'] === null)
					$user_info_row['user_question'] = "";
				if($user_info_row['user_answer'] === null)
					$user_info_row['user_answer'] = "";
				if($user_info_row['user_avatar'] === null)
					$user_info_row['user_avatar'] = "";
				if($user_info_row['user_avatar_thumb'] === null)
					$user_info_row['user_avatar_thumb'] = "";
				if($user_info_row['user_truename'] === null)
					$user_info_row['user_truename'] = "";
				if($user_info_row['user_tel'] === null)
					$user_info_row['user_tel'] = "";
				if($user_info_row['user_birth'] === null)
					$user_info_row['user_birth'] = "";
				if($user_info_row['user_email'] === null)
					$user_info_row['user_email'] = "";
				if($user_info_row['user_qq'] === null)
					$user_info_row['user_qq'] = "";
				if($user_info_row['user_msn'] === null)
					$user_info_row['user_msn'] = "";
				if($user_info_row['user_province'] === null)
					$user_info_row['user_province'] = "";
				if($user_info_row['user_city'] === null)
					$user_info_row['user_city'] = "";
				if($user_info_row['user_intro'] === null)
					$user_info_row['user_intro'] = "";
				if($user_info_row['user_sign'] === null)
					$user_info_row['user_sign'] = "";
				if($user_info_row['user_lastlogin_ip'] === null)
					$user_info_row['user_lastlogin_ip'] = "";
				if($user_info_row['user_like_games'] === null)
					$user_info_row['user_like_games'] = "";
				if($user_info_row['user_reg_ip'] === null)
					$user_info_row['user_reg_ip'] = "";
				if($user_info_row['user_getpsw_code'] === null)
					$user_info_row['user_getpsw_code'] = "";
				if($user_info_row['user_verify_code'] === null)
					$user_info_row['user_verify_code'] = "";
				if($user_info_row['user_phone_code'] === null)
					$user_info_row['user_phone_code'] = "";
				if($user_info_row['user_phone_code_stats'] === null)
					$user_info_row['user_phone_code_stats'] = "";
				if($user_info_row['user_idcard'] === null)
					$user_info_row['user_idcard'] = "";
				if($user_info_row['user_mobile'] === null)
					$user_info_row['user_mobile'] = "";
				if($user_info_row['user_binded'] === null)
					$user_info_row['user_binded'] = "";
				if($user_info_row['user_history_password'] === null)
					$user_info_row['user_history_password'] = "";
				if($user_info_row['user_history_ip'] === null)
					$user_info_row['user_history_ip'] = "";

				$fid=$user_BaseModel->getUserIdByAccount($sns_user_name);
				$flag=$user_RnameModel->getRnameId($user_id,$fid[0]);
				if($flag){
					$datas=$user_RnameModel->getRname($flag);
					$user_info_row['rename']=$datas[$flag[0]]['content'];
				}else{
					$user_info_row['rename']='';
				}
				$items[$key]['sns_user'] = $user_info_row;

				foreach($items[$key]['sns_user']['user_sns'] as $sukey=>$suvalue)
				{
					$items[$key]['sns_user']['user_sns'][$sukey]['sns_content'] = base64_decode($items[$key]['sns_user']['user_sns'][$sukey]['sns_content']);
				}
			}
			else
			{
				$items[$key]['sns_user'] = array();
			}

			//点赞人数
			$sns_like_user = $sns['sns_like_user'];
			$like_user_row = explode(',', $sns_like_user);
			if(in_array($user_id,$like_user_row))
			{
				$items[$key]['islike'] = 1;
			}
			else
			{
				$items[$key]['islike'] = 0;
			}
			$User_BaseModel = new User_BaseModel();
			$like_user_name = array();
			foreach ($like_user_row as $ke => $val)
			{
				$user_info = array();
				$like_info=array();
				if($val)
				{
					$user_info = $User_BaseModel->getUser($val);
					if($user_info)
					{
						$user_infos=$User_InfoModel->getInfo($user_info[$val]['user_account']);
						$fid=$user_BaseModel->getUserIdByAccount($user_info[$val]['user_account']);
						$flag=$user_RnameModel->getRnameId($user_id,$fid[0]);
						if($flag){
							$datas=$user_RnameModel->getRname($flag);
							$like_info['rename']=$datas[$flag[0]]['content'];
						}else{
							$like_info['rename']='';
						}

						$like_info['user_account']=$user_info[$val]['user_account'];
						$like_info['nickname']=$user_infos[$user_info[$val]['user_account']]['nickname'];
						$like_info['logo']=$user_infos[$user_info[$val]['user_account']]['user_avatar'];
						array_push($like_user_name,$like_info);
					}
				}
			}

			$items[$key]['like_user_name'] = $like_user_name;
			//获取评论
			$comment = $Sns_CommentModel->getCommentBySid($value['sns_id']);

			$items[$key]['comment'] = array_values($comment);

			foreach($items[$key]['comment'] as $kkk=>$vvv)
			{
				$items[$key]['comment'][$kkk]['commect_content'] = base64_decode($items[$key]['comment'][$kkk]['commect_content']);
				$flag=$user_RnameModel->getRnameId($user_id,$vvv['user_id']);
				if($flag)
				{
					$datas=$user_RnameModel->getRname($flag);
					$items[$key]['comment'][$kkk]['rename']=$datas[$flag[0]]['content'];
				}else{
					$items[$key]['comment'][$kkk]['rename']='';
				}

				//获取评论用户的用户信息
				$user_info = $User_InfoModel->getInfo($vvv['user_name']);
				$items[$key]['comment'][$kkk]['nickname']=$user_info[$vvv['user_name']]['nickname'];

				if($vvv['to_commect_id'])
				{
					$fid=$user_BaseModel->getUserIdByAccount($vvv['to_commect_name']);
					$flag=$user_RnameModel->getRnameId($user_id,$fid[0]);
					if($flag)
					{
						$datas=$user_RnameModel->getRname($flag);
						$items[$key]['comment'][$kkk]['to_rename']=$datas[$flag[0]]['content'];
					}else{
						$items[$key]['comment'][$kkk]['to_rename']='';
					}

					//获取评论用户的用户信息
					$user_info = $User_InfoModel->getInfo($vvv['to_commect_name']);
					$items[$key]['comment'][$kkk]['to_nickname']=$user_info[$vvv['to_commect_name']]['nickname'];
				}else
				{
					$items[$key]['comment'][$kkk]['to_rename']='';
					$items[$key]['comment'][$kkk]['to_nickname']='';
				}
			}
		}

		$User_InfoModel = new User_InfoModel();
		$background=$User_InfoModel->getInfo($user_name);
		$data['background']=$background[$user_name]['background'];
		$data['items'] = array_values($items);
		$msg    = 'success';
		$status = 200;
		fb($data);
		$this->data->addBody(-140, $data, $msg, $status);
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}

	/**
	 * 朋友圈API - 删除动态信息
	 *
	 * @access public
	 */
	public function deleteSns()
	{
		$sns_id = request_int('sns_id');

		$Sns_BaseModel = new Sns_BaseModel();
		$field['is_del'] = '1';
		$flag = $Sns_BaseModel->editBase($sns_id,$field);

		$Sns_TimelineModel = new Sns_TimelineModel();
		$data = $Sns_TimelineModel->removeTimeBySid($sns_id);


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

	/**
	 * 朋友圈API - 获取最新动态信息
	 *
	 * @access public
	 */
	public function updateSns()
	{
		$user_id = request_int('user_id',Perm::$row['user_id']);
		$status = request_int('status');  //1-获取新状态  2-获取旧状态
		$timeline_id = request_int('timeline_id');
		$page = request_int('page',1);
		$rows = request_int('rows',10);

		$Sns_TimelineModel = new Sns_TimelineModel();
		$data = $Sns_TimelineModel->updateTimelineList($user_id,$status,$timeline_id,$page, $rows);
		$items = $data['items'];

		$Sns_BaseModel = new Sns_BaseModel();

		$Sns_CommentModel = new Sns_CommentModel();


		foreach ($items as $key => $value) 
		{
			//内容
			$sns = array_values($Sns_BaseModel->getBase($value['sns_id']));
			$items[$key]['sns'] = $sns;
			$sns_type_row = array(7,8);
			if(!in_array($items[$key]['sns'][0]['sns_type'], $sns_type_row))
			{
				$items[$key]['sns'][0]['sns_content'] = base64_decode($items[$key]['sns'][0]['sns_content']);
			}
			else
			{
				$link =  current($this->RegExp($items[$key]['sns'][0]['sns_content']));
				$items[$key]['sns'][0]['new_title'] = "title";
				$items[$key]['sns'][0]['new_h5'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.$link;
			}

			//点赞人数
			$sns_like_user = $sns[0]['sns_like_user'];
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

			foreach ($comment as $k => $v)
			{
				$comment[$k]['commect_content'] = base64_decode($comment[$k]['commect_content']);
				if($v['to_commect_id'] == 0)
				{
					$comment[$k]['to_commect_name'] = '';
				}
				else
				{
					$commect_info = $Sns_CommentModel->getComment($v['to_commect_id']);
					$comment[$k]['to_commect_name'] = $commect_info[$v['to_commect_id']]['user_name'];
				}	

			}
			$items[$key]['comment'] = array_values($comment);
		}

		if($items)
		{
			$data['items'] = $items;
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$data = array();
			$msg    = 'failure';
			$status = 250;
		}
		fb($data);
		$this->data->addBody(-140, $data, $msg, $status);
	}

	
	/**
	 * 朋友圈API - 评论
	 *
	 * @access public
	 */
	public function addCommect()
	{
		$sns_id = request_int('sns_id');
		$user_id = request_int('user_id',Perm::$row['user_id']);
		$user_name = request_string('user_account',Perm::$row['user_account']);
		$commect_id = request_int('commect_id',0);
		$commect_name = request_string('commect_name','');
		$commect_content = request_string('commect_content');
		$commect_content = base64_encode($commect_content);
		$commect_state = request_int('commect_state',0);

		$field = array(
					'user_id' 			=> $user_id,
					'user_name'			=> $user_name,
					'sns_id' 			=> $sns_id,
					'commect_content'	=> $commect_content,
					'commect_addtime'	=> time(),
					'commect_state'		=> $commect_state,
					'commect_like'		=> 0,
					'to_commect_id'		=> $commect_id,
					'to_commect_name'   => $commect_name,
				);

		$Sns_CommentModel = new Sns_CommentModel();
		$flags = $Sns_CommentModel->addComment($field);
		$User_InfoModel = new User_InfoModel();
		$user_info = $User_InfoModel->getOne($user_name);
		$field['user_avatar'] = $user_info['user_avatar'];

		if($flags)
		{
			//在动态信息表中添加评论数
			$Sns_BaseModel = new Sns_BaseModel();
			$flag = $Sns_BaseModel->addCommentCount($sns_id);
		}
		else
		{
			$flag = 0;
		}


		//查找评论
		$comment_row = $Sns_CommentModel->getCommentBySid($sns_id);
		$User_BaseModel = new User_BaseModel();
		$User_RnameModel = new User_RnameModel();
		foreach ($comment_row as $key => $value)
		{
			$comment_row[$key]['commect_content'] = base64_decode($comment_row[$key]['commect_content']);
			//查找用户的备注名
			$user_rename = $User_RnameModel->getUserRname($user_id,$value['user_id']);

			if($user_rename)
			{
				$user_rename = current($user_rename);
				$comment_row[$key]['rename'] = $user_rename['content'];
			}
			else
			{
				$comment_row[$key]['rename'] = '';
			}

			if($value['to_commect_id'] == 0)
			{
				$comment_row[$key]['to_commect_name'] = '';
			}
			else
			{
				$commect_info = $Sns_CommentModel->getComment($value['to_commect_id']);
				$comment_row[$key]['to_commect_name'] = $commect_info[$value['to_commect_id']]['user_name'];

			}	

		}
		$comment_row = array_values($comment_row);
		fb($comment_row);
		if ($flag)
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
		$data['list'] = $comment_row;
		$field['commect_content'] = base64_decode($field['commect_content']);
		$data['add_comment'] = $field;
//		echo '<pre>';print_r($data);exit;
		fb($data);
		$this->data->addBody(-140, $data, $msg, $status);
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}

	/**
	 * 朋友圈API - 删除评论
	 *
	 * @access public
	 */
	public function delCommect()
	{
		$commect_id = request_int('commect_id');

		$Sns_CommentModel = new Sns_CommentModel();
		$flag = $Sns_CommentModel->removeComment($commect_id);
		if ($flag)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$date = array();
		$this->data->addBody(-140, $date, $msg, $status);
	}
	/**
	 * 朋友圈API - 赞
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function clickLike_shanghai()
	{
		$sns_id = request_int('sns_id');
		$user_id = request_int('user_id',Perm::$row['user_id']);

		$Sns_BaseModel = new Sns_BaseModel();

		$data = $Sns_BaseModel->getBaseById($sns_id);

		$like_user = $data[$sns_id]['sns_like_user'];

			$like_user_row = explode(',', $like_user);

			$key = array_search($user_id, $like_user_row);

			if($key)
			{
				//如果$key存在，即已点赞，现要取消赞,点赞人数减1
				unset($like_user_row[$key]);
				$Sns_BaseModel->editLikeCount($sns_id,1);
			}
			else
			{
				//如果$key不存在，即还未点赞，现要添加赞,点赞人数加1
				array_push($like_user_row,$user_id);
				$Sns_BaseModel->editLikeCount($sns_id,2);
			}

			$like_user = implode(',', $like_user_row);
		
		//将处理后的结果
		$field = array('sns_like_user' => $like_user, );

		$re = $Sns_BaseModel->editBase($sns_id,$field);

		//fb($like_user_row);
		$User_BaseModel = new User_BaseModel();
		$like_user_name = '';
		foreach ($like_user_row as $key => $value) 
		{
			$user_info = array();
			
			if($value)
			{
				$user_info = $User_BaseModel->getUser($value);

				if($user_info)
				{
					$like_user_name .= $user_info[$value]['user_account'].',';
				}
			}
		}
		$like_user_name =  substr($like_user_name, 0, -1) ;
		$date = array();
		$date[] = $like_user_name;
		if ($re)
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		
		$this->data->addBody(-140, $date, $msg, $status);
	}

	/**
	 * 朋友圈API - 赞
	 *
	 * @param  string $ctl 控制器目录
	 * @param  string $met 控制器方法
	 * @param  string $typ 返回数据类型
	 * @access public
	 */
	public function clickLike()
	{
		$sns_id = request_int('sns_id');
		$user_id = request_int('user_id',Perm::$row['user_id']);
		$Sns_BaseModel = new Sns_BaseModel();
		$Sns_LikeModel = new Sns_LikeModel();

		$data = array();

		//开启事务
		$Sns_BaseModel->sql->startTransactionDb();

		//在点赞表中查找该用户是否点过赞
		$id = $Sns_LikeModel->getLikeIdByUidSid($sns_id,$user_id);

		$sns_base = $Sns_BaseModel->getBaseById($sns_id);

		$like_user = $sns_base[$sns_id]['sns_like_user'];

		$like_user_row = explode(',', $like_user);

		$key = array_search($user_id, $like_user_row);

		if($id)
		{
			//如果$id存在，即已点赞，现要取消赞,点赞人数减1
			unset($like_user_row[$key]);
			$Sns_BaseModel->editLikeCount($sns_id,1);

			//删除点赞表中的点赞记录
			$Sns_LikeModel->removeLike($id);
			$state = 1;
			$data['hot_count'] = $sns_base[$sns_id]['sns_copy_count'] + $sns_base[$sns_id]['sns_like_count'] + $sns_base[$sns_id]['sns_collection']-1;
		}
		else
		{
			//如果$id不存在，即还未点赞，现要添加赞,点赞人数加1
			array_push($like_user_row,$user_id);
			$Sns_BaseModel->editLikeCount($sns_id,2);

			//在点赞表中增加点赞记录
			$add_row = array();
			$add_row['sns_id'] = $sns_id;
			$add_row['user_id'] = $user_id;
			$add_row['like_time'] = time();
			$Sns_LikeModel->addLike($add_row);

			$state = 2;
			$data['hot_count'] = $sns_base[$sns_id]['sns_copy_count'] + $sns_base[$sns_id]['sns_like_count'] + $sns_base[$sns_id]['sns_collection'] + 1;
		}
		//echo '<pre>';print_r($data['hot_count']);exit;
		$like_user = implode(',', $like_user_row);
		//将处理后的结果
		$field = array('sns_like_user' => $like_user, );

		$re = $Sns_BaseModel->editBase($sns_id,$field);

		$User_BaseModel = new User_BaseModel();
		$User_RnameModel = new User_RnameModel();

		$like_user_name = array();
		foreach ($like_user_row as $key => $value)
		{
			$user_info = array();
			$like_info=array();
			if($value)
			{
				$user_info = $User_BaseModel->getUser($value);

				if($user_info)
				{
					$User_InfoModel = new User_InfoModel();
					$user_infos=$User_InfoModel->getInfo($user_info[$value]['user_account']);
					$like_info['user_account']=$user_info[$value]['user_account'];
					$like_info['nickname']=$user_infos[$user_info[$value]['user_account']]['nickname'];

					//查找用户的备注名
					$user_rename = $User_RnameModel->getUserRname($user_id,$value);
					if($user_rename)
					{
						$user_rename = current($user_rename);
						$like_info['rename'] = $user_rename['content'];
					}
					else
					{
						$like_info['rename'] = '';
					}
					array_push($like_user_name,$like_info);
				}
			}
		}
		$like_user_name=array_values($like_user_name);


		$data['like_user']= $like_user_name;
		if ($re && $Sns_BaseModel->sql->commitDb())
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$Sns_BaseModel->sql->rollBackDb();
			$msg    = 'failure';
			$status = 250;
		}

		$data['state'] = $state;
		fb($data);
		$this->data->addBody(-140, $data, $msg, $status);
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}



	/**
	 * 朋友圈API - 发表图文
	 *
	 * @access public
	 */
	public function publishStatus_shanghai()
	{
		$user_id = request_int('user_id',Perm::$row['user_id']);
		$user_name = request_string('user_account',Perm::$row['user_account']);

		$sns_title = request_string('sns_title');
		$sns_content = request_string('sns_content');
		//图片
		$sns_img = array();
		$sns_type = request_int('sns_type',0);
		$sns_status = request_int('sns_status',0);
		$sns_privacy = request_int('sns_privacy',1);

		$data = array();

		$field = array(
					'user_id'=> $user_id,
					'user_name'=> $user_name,
					'sns_title'=> $sns_title,
					'sns_content' =>$sns_content,
					'sns_type'=>$sns_type,
					'sns_create_time'=>time(),
					'sns_status'=>$sns_status,
					'sns_privacy'=>$sns_privacy,
					);

		Yf_Log::log('$user_id : ' . $user_id, Yf_Log::INFO, 'publish');
		Yf_Log::log('$_FILES : ' . json_encode($_FILES), Yf_Log::INFO, 'publish');
		Yf_Log::log('$_REQUEST : ' . json_encode($_REQUEST), Yf_Log::INFO, 'publish');
		$Sns_BaseModel = new Sns_BaseModel();
		$da = $Sns_BaseModel->addBase($field,true);
		if($da)
		{
			$Sns_TimelineModel = new Sns_TimelineModel();

			$User_BaseModel = new User_BaseModel();

			$User_FriendModel = new User_FriendModel();

			if($sns_privacy == 0) //所有人可见
			{
				//查找出所有用户id
				$user_id_row = $User_BaseModel->getUser('*');
				$user_id_row = array_keys($user_id_row);

			}

			if($sns_privacy == 1) //好友可见
			{
				//查出所有好友id
				$user_friend_rows = array();
				$user_friend_rows = $User_FriendModel->getUserUserIdByFriendId($user_id);

				$user_id_row = array_filter_key('user_id', $user_friend_rows);

				$flagl = in_array($user_id, $user_id_row);

				if(!$flagl)
				{
					array_unshift($user_id_row, $user_id);
				}
				
			}

			if($sns_privacy == 2) //仅自己可见
			{
				$user_id_row[] = $user_id; 
			}

			$time = date('Y-m-d H:i:s', time());
			foreach ($user_id_row as $key => $value) 
			{
				$file = array();
				$file = array(
						'user_id' => $value,
						'sns_id'  => $da,
						'action_time' => $time,
						);
				$Sns_TimelineModel->addTimeline($file);
			}
			$flag = 1;
		}
		else
		{
			$flag = 0;
		}
		
		if ($flag)
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
	 * 朋友圈API - 发表图文
	 *
	 * @access public
	 */
	public function publishStatus()
	{
		$user_id = Perm::$row['user_id'];
		$user_name = Perm::$row['user_account'];

		$sns_title = request_string('sns_title');
		$sns_content = request_string('sns_content');
		$sns_type = request_int('sns_type',5);    //5文字帖 6图片帖 7音乐帖 8视频帖
		$sns_status = request_int('sns_status',0);
		$sns_privacy = request_int('sns_privacy',1); //隐私可见度 0所有人可见 1好友可见 2仅自己可见
		$data = array();
		$sns_type_row = array(7,8);
		if(!in_array($sns_type, $sns_type_row))
		{
			$sns_content = base64_encode($sns_content);
		}
		if($sns_type == 7)
		{
			$sns_content = $this->str_check($sns_content);
		}
		$field = array(
			'user_id'=> $user_id,
			'user_name'=> $user_name,
			'sns_title'=> $sns_title,
			'sns_content' =>$sns_content,
			'sns_create_time'=>time(),
			'sns_status'=>$sns_status,
			'sns_privacy'=>$sns_privacy,
		);
		Yf_Log::log('$user_id : ' . $user_id, Yf_Log::INFO, 'publish');
		Yf_Log::log('$_FILES : ' . json_encode($_FILES), Yf_Log::INFO, 'publish');
		Yf_Log::log('$_REQUEST : ' . json_encode($_REQUEST), Yf_Log::INFO, 'publish');

		if($_FILES)
		{
			$sns_img = array();
			$aa=json_encode($_FILES);
			/*$bb=implode('++++',$_FILES['img']);
			file_put_contents('./aa.txt', $aa);*/
			$data['aa'] = $_FILES;
			foreach($_FILES as $kk=>$vv){
				if ($vv)
				{
					//处理上传图片
					$upload = new HTTP_Upload('en');
					$files  = $upload->getFiles();

					if (PEAR::isError($files))
					{
						$data['msg'] = '图片上传错误';
						$flag = false;
					}
					else
					{
						foreach ($files as $file)
						{
							if ($file->isValid())
							{
								$p = '/data/sns/comment_img/';

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

								fb($path);
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
									$img = Yf_Registry::get('base_url')  . '/'. APP_DIR_NAME . $p .  $file->upload['name'];
									$data['img_name'] = $file->upload['name'];
									$data['img_name2'] = $_FILES['upload']['name'];

									$sns_img[] = $img;
								}

							}
							else
							{
								$flag = false;
								$data['msg'] = '图片发生错误' . $_FILES['upload']['name'];
							}
						}

					}
				}

			}

			Yf_Log::log('$sns_img: ' . json_encode($sns_img), Yf_Log::INFO, 'publish');

			$sns_img_str = implode(',', $sns_img);
			$field['sns_img'] = $sns_img_str;

			$field['sns_type'] = 6;  //图片贴
		}

		fb($field);

		$Sns_BaseModel = new Sns_BaseModel();
		$da = $Sns_BaseModel->addBase($field,true);

		fb($da);
		fb('aaaa');

		if($da)
		{
			$Sns_TimelineModel = new Sns_TimelineModel();

			$User_BaseModel = new User_BaseModel();

			$User_FriendModel = new User_FriendModel();

			if($sns_privacy == 0) //所有人可见
			{
				//查找出所有用户id
				$user_id_row = $User_BaseModel->getUser('*');
				$user_id_row = array_keys($user_id_row);

			}

			if($sns_privacy == 1) //好友可见
			{
				//查出所有好友id
				$user_friend_rows = array();
				$user_friend_rows = $User_FriendModel->getUserUserIdByFriendId($user_id);

				$user_id_row = array_filter_key('user_id', $user_friend_rows);

				$flagl = in_array($user_id, $user_id_row);

				if(!$flagl)
				{
					array_unshift($user_id_row, $user_id);
				}

			}

			if($sns_privacy == 2) //仅自己可见
			{
				$user_id_row[] = $user_id;
			}

			$time = date('Y-m-d H:i:s', time());
			foreach ($user_id_row as $key => $value)
			{
				$file = array();
				$file = array(
					'user_id' => $value,
					'sns_id'  => $da,
					'action_time' => $time,
				);
				$Sns_TimelineModel->addTimeline($file);
			}
			$flag = 1;
		}
		else
		{
			$flag = 0;
		}

		if ($flag)
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
	 * Sns中发布帖子的接口，由于sns中与im发布状态的方式不同，所以另写一个接口
	 * @author houpeng 2016.11.17
	 */
	public function publishSns()
	{
		$user_id = Perm::$row['user_id'];
		$user_name = Perm::$row['user_account'];

		$sns_img = request_string('sns_img');
		$sns_lable = request_string('sns_lable');
		$sns_title = request_string('sns_title');
		$sns_content = request_string('sns_content');
		$sns_type = request_int('sns_type');
		$sns_status = request_int('sns_status',0);
		$sns_privacy = request_int('sns_privacy',1);
		//将帖子内容编码
		$sns_type_row = array(7,8);
		if(!in_array($sns_type, $sns_type_row))
		{
			$sns_content = base64_encode($sns_content);
		}
		if($sns_type == 7)
		{
			$sns_content = $this->str_check($sns_content);
		}
		$data = array();
		$msg  = '';
		
		//文字帖
		if($sns_type == 5)
		{
			if(!$sns_title){
				$msg    = '请填写内容!';
				$status = 250;
			}

			if(!$sns_content){
				$msg    = '请上传内容!';
				$status = 250;
			}
		}

		if(!$msg){

			$field = array(
				'user_id'=> $user_id,
				'user_name'=> $user_name,
				'sns_title'=> $sns_title,
				'sns_content' =>$sns_content,
				'sns_img' =>$sns_img,
				'sns_type'=>$sns_type,
				'sns_create_time'=>time(),
				'sns_status'=>$sns_status,
				'sns_privacy'=>$sns_privacy,
				'sns_lable'=>$sns_lable,
			);

			$Sns_BaseModel = new Sns_BaseModel();
			$da = $Sns_BaseModel->addBase($field,true);

			if($da)
			{
				$Sns_TimelineModel = new Sns_TimelineModel();
				$User_BaseModel = new User_BaseModel();
				$User_FriendModel = new User_FriendModel();
				if($sns_privacy == 0) //所有人可见
				{
					//查找出所有用户id
					$user_id_row = $User_BaseModel->getUser('*');
					$user_id_row = array_keys($user_id_row);
				}
				if($sns_privacy == 1) //好友可见
				{
					//查出所有好友id
					$user_friend_rows = array();
					$user_friend_rows = $User_FriendModel->getUserUserIdByFriendId($user_id);
					$user_id_row = array_filter_key('user_id', $user_friend_rows);
					$flagl = in_array($user_id, $user_id_row);
					if(!$flagl)
					{
						array_unshift($user_id_row, $user_id);
					}
				}
				if($sns_privacy == 2) //仅自己可见
				{
					$user_id_row[] = $user_id;
				}
				$time = date('Y-m-d H:i:s', time());
				foreach ($user_id_row as $key => $value)
				{
					$file = array();
					$file = array(
						'user_id' => $value,
						'sns_id'  => $da,
						'action_time' => $time,
					);
					$Sns_TimelineModel->addTimeline($file);
				}
				$flag = 1;
			}
			else
			{
				$flag = 0;
			}

			if ($flag)
			{
				$msg    = 'success';
				$status = 200;
			}
			else
			{
				$msg    = 'failure';
				$status = 250;
			}
		}

		$data['sns_id'] = $da;
		$this->data->addBody(-140, $data, $msg, $status);
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}

	/**
	 * 朋友圈API - 分享
	 *
	 * @access public
	 */
	public function share()
	{
		$user_id = request_int('user_id',Perm::$row['user_id']);
		$user_name = request_string('user_account',Perm::$row['user_account']);

		$sns_title = request_string('sns_title');
		$sns_content = request_string('sns_content');
		//图片
		$sns_img =  request_string('sns_img');
		$sns_type = request_int('sns_type',0);
		$sns_status = request_int('sns_status',0);
		$sns_privacy = request_int('sns_privacy',1);
		$sns_type_row = array(7,8);
		if(!in_array($sns_type, $sns_type_row))
		{
			$sns_content = base64_encode($sns_content);
		}
		$data = array();

		$field = array(
					'user_id'=> $user_id,
					'user_name'=> $user_name,
					'sns_title'=> $sns_title,
					'sns_content' =>$sns_content,
					'sns_img' =>$sns_img,
					'sns_type'=>$sns_type,
					'sns_create_time'=>time(),
					'sns_status'=>$sns_status,
					'sns_privacy'=>$sns_privacy,
					);

		Yf_Log::log('$user_id : ' . $user_id, Yf_Log::INFO, 'publish');
		Yf_Log::log('$_FILES : ' . json_encode($_FILES), Yf_Log::INFO, 'publish');
		Yf_Log::log('$_REQUEST : ' . json_encode($_REQUEST), Yf_Log::INFO, 'publish');
		$Sns_BaseModel = new Sns_BaseModel();
		$da = $Sns_BaseModel->addBase($field,true);
		if($da)
		{
			$Sns_TimelineModel = new Sns_TimelineModel();

			$User_BaseModel = new User_BaseModel();

			$User_FriendModel = new User_FriendModel();

			if($sns_privacy == 0) //所有人可见
			{
				//查找出所有用户id
				$user_id_row = $User_BaseModel->getUser('*');
				$user_id_row = array_keys($user_id_row);

			}

			if($sns_privacy == 1) //好友可见
			{
				//查出所有好友id
				$user_friend_rows = array();
				$user_friend_rows = $User_FriendModel->getUserUserIdByFriendId($user_id);

				$user_id_row = array_filter_key('user_id', $user_friend_rows);

				$flagl = in_array($user_id, $user_id_row);

				if(!$flagl)
				{
					array_unshift($user_id_row, $user_id);
				}
				
			}

			if($sns_privacy == 2) //仅自己可见
			{
				$user_id_row[] = $user_id; 
			}

			$time = date('Y-m-d H:i:s', time());
			foreach ($user_id_row as $key => $value) 
			{
				$file = array();
				$file = array(
						'user_id' => $value,
						'sns_id'  => $da,
						'action_time' => $time,
						);
				$Sns_TimelineModel->addTimeline($file);
			}
			$flag = 1;
		}
		else
		{
			$flag = 0;
		}
		
		if ($flag)
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
	 * 朋友圈API - 收藏
	 */
	public function clickCollect()
	{
		$sns_id = request_int('sns_id');

		$user_id = request_int('u',Perm::$row['user_id']);
		$Sns_BaseModel = new Sns_BaseModel();
		$Sns_CollectionModel = new Sns_CollectionModel();
		$flag = false;
		//开启事务
		$Sns_BaseModel->sql->startTransactionDb();

		//判断是否是自己的帖子
		$sns_base = $Sns_BaseModel->getOne($sns_id);

		$hot_count = $sns_base['sns_copy_count'] + $sns_base['sns_like_count'] + $sns_base['sns_collection'];
		
		//按照sns_id 与 user_id 查找collect_id
		$id_row = $Sns_CollectionModel->getCollectBySidUid($sns_id,$user_id);

		if(empty($id_row))
		{
			$field = array(
				'sns_id' => $sns_id,
				'user_id' => $user_id,
				'collect_time' => time(),
			);

			//插入收藏表
			$Sns_CollectionModel->addCollection($field);

			//增加帖子的收藏数
			$flag = $Sns_BaseModel->editCollectCount($sns_id,2);

			$hot_count = $hot_count + 1;

		}else
		{
			$msg    = '已收藏';
			$status = 250;
		}

		if ($flag && $Sns_BaseModel->sql->commitDb())
		{
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$Sns_BaseModel->sql->rollBackDb();
			$msg    = $msg ? $msg : 'failure';
			$status = 250;
		}

		$data = array();
		$data['hot_count'] = $hot_count;
		$this->data->addBody(-140, $data, $msg, $status);
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}

	}

	/**
	 * 朋友圈API - 取消收藏
	 */
	public function delCollect()
	{
		$collect_id = request_int('collect_id');

		$sns_id  = request_int('sns_id');
		$user_id = request_int('u');

		$Sns_CollectionModel = new Sns_CollectionModel();
		$Sns_BaseModel = new Sns_BaseModel();

		//开启事务
		$Sns_CollectionModel->sql->startTransactionDb();

		//判断是否传递了collect_id，如果没有，按照user_id与sns_id查找collect_id
		if(!$collect_id)
		{
			$array = array();
			$array['user_id'] = $user_id;
			$array['sns_id'] = $sns_id;

			$collect_id = $Sns_CollectionModel->getCollectBySidUid($sns_id,$user_id);
		}

		$collect_base = $Sns_CollectionModel->getOne($collect_id);

		$sns_base = $Sns_BaseModel->getOne($collect_base['sns_id']);
		$hot_count = $sns_base['sns_copy_count'] + $sns_base['sns_like_count'] + $sns_base['sns_collection'];

		//按照sns_id 与 user_id 查找collect_id
		$flag = $Sns_CollectionModel->removeCollection($collect_id);
		//减少帖子的收藏数
		$Sns_BaseModel->editCollectCount($sns_base['sns_id'],1);

		fb($hot_count);
		if ($flag && $Sns_CollectionModel->sql->commitDb())
		{
			$hot_count = $hot_count-1;
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$Sns_CollectionModel->sql->rollBackDb();
			$msg    = 'failure';
			$status = 250;
		}

		$data = array();
		$data['hot_count'] = $hot_count;
		$this->data->addBody(-140, $data, $msg, $status);
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}

//	//时间戳
//	public function timeStamp()
//	{
//
//	}

	/**
	 * 朋友圈API - 获取收藏列表
	 */
	public function getCollectList()
	{
		$user_id = request_int('u');
		$page = request_int('page',1);
		$rows = request_int('rows',10);

		$Sns_CollectionModel = new Sns_CollectionModel();
		$coll_row = $Sns_CollectionModel->getCollectionList($user_id,$page,$rows);
		$items = $coll_row['items'];
		$Sns_BaseModel = new Sns_BaseModel();
		$Sns_CommentModel = new Sns_CommentModel();
		if($items)
		{
			foreach ($items as $key => $value)
			{
				//内容
				$sns = array_values($Sns_BaseModel->getBase($value['sns_id']));

				if($sns)
				{
					$items[$key]['sns'] = $sns[0];
					//如果是音乐帖或者视频帖，则不编码和解码 2017/4/25 19:10:57
					$sns_type = array(7,8);
					if(!in_array($items[$key]['sns']['sns_type'], $sns_type) && $sns[0]['sns_create_time'] > 1493118657)
					{
						$items[$key]['sns']['sns_content'] = base64_decode($items[$key]['sns']['sns_content']);
					}
					else
					{
						$items[$key]['sns']['new_content'] = $this->RegExp($items[$key]['sns']['sns_content']);
					}
					$sns_user_name = $sns[0]['user_name'];
					if($sns[0]['sns_img'])
					{
						$items[$key]['sns']['img'] = array_filter(explode(',', $sns[0]['sns_img']));
					}else
					{
						$items[$key]['sns']['img'] = array();
					}
				}
				else
				{
					$items[$key]['sns'] = array();
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
			}
		}
		else
		{
			$items[0]['collect_id'] = 0;
			$items[0]['sns_id'] = 0;
			$items[0]['user_id'] = $user_id;
			$items[0]['collect_time'] = 0;
			$items[0]['id'] = $user_id;
			$items[0]['sns']['sns_id'] = 0;
			$items[0]['sns']['user_id'] = 0;
			$items[0]['sns']['user_name'] = '';
			$items[0]['sns']['sns_title'] = '';
			$items[0]['sns']['sns_content'] = '';
			$items[0]['sns']['sns_img'] = '';
			$items[0]['sns']['sns_type'] = 0;
			$items[0]['sns']['sns_create_time'] = 0;
			$items[0]['sns']['sns_status'] = 0;
			$items[0]['sns']['is_del'] = 0;
			$items[0]['sns']['sns_privacy'] = 1;
			$items[0]['sns']['sns_comment_count'] = 0;
			$items[0]['sns']['sns_copy_count'] = 0;
			$items[0]['sns']['sns_like_count'] = 0;
			$items[0]['sns']['sns_like_user'] = '';
			$items[0]['sns']['sns_forward'] = 0;
			$items[0]['sns']['sns_lable'] = '';
			$items[0]['sns']['sns_collection'] = 0;
			$items[0]['sns']['sns_like_user_addtime'] = 0;
			$items[0]['sns']['id'] = 0;
			$items[0]['sns']['img'] = array();
			$items[0]['sns_user']['user_name'] = '';
			$items[0]['sns_user']['nickname'] = '';
			$items[0]['sns_user']['user_group'] = 0;
			$items[0]['sns_user']['user_site_id'] = 0;
			$items[0]['sns_user']['user_site_domain'] = '';
			$items[0]['sns_user']['user_question'] = '';
			$items[0]['sns_user']['user_answer'] = '';
			$items[0]['sns_user']['user_avatar'] = '';
			$items[0]['sns_user']['user_avatar_thumb'] = '';
			$items[0]['sns_user']['user_gender'] = 0;
			$items[0]['sns_user']['user_truename'] = '';
			$items[0]['sns_user']['user_tel'] = '';
			$items[0]['sns_user']['user_birth'] = '';
			$items[0]['sns_user']['user_email'] = '';
			$items[0]['sns_user']['user_qq'] = '';
			$items[0]['sns_user']['user_msn'] = '';
			$items[0]['sns_user']['user_province'] = '';
			$items[0]['sns_user']['user_city'] = '';
			$items[0]['sns_user']['user_intro'] = '';
			$items[0]['sns_user']['user_sign'] = '';
			$items[0]['sns_user']['user_reg_time'] = 0;
			$items[0]['sns_user']['user_count_login'] = 0;
			$items[0]['sns_user']['user_lastlogin_time'] = 0;
			$items[0]['sns_user']['user_lastlogin_ip'] = '';
			$items[0]['sns_user']['user_like_games'] = '';
			$items[0]['sns_user']['user_reg_ip'] = '';
			$items[0]['sns_user']['user_money'] = 0;
			$items[0]['sns_user']['user_credit'] = 0;
			$items[0]['sns_user']['user_getpsw_code'] = '';
			$items[0]['sns_user']['user_verify_code'] = '';
			$items[0]['sns_user']['user_phone_code'] = '';
			$items[0]['sns_user']['user_phone_code_stats'] = '';
			$items[0]['sns_user']['user_idcard'] = '';
			$items[0]['sns_user']['user_mobile'] = '';
			$items[0]['sns_user']['user_binded'] = '';
			$items[0]['sns_user']['user_history_password'] = '';
			$items[0]['sns_user']['user_history_ip'] = '';
			$items[0]['sns_user']['user_limit'] = 0;
			$items[0]['sns_user']['limit_remain'] = 0;
			$items[0]['sns_user']['background'] = '';
			$items[0]['sns_user']['id'] = 0;
		}
//		echo '<pre>';print_r($items);exit;
		$coll_row['items'] = $items;
		$msg    = 'success';
		$status = 200;
//		echo '<pre>';print_r($coll_row);exit;
		$this->data->addBody(-140, $coll_row, $msg, $status);
		fb($coll_row);
	}

	/**
	 * 获取当前用户收藏的帖子数量
	 */
	public function getCollectionCount()
	{
		$user_id = request_int('user_id',Perm::$row['user_id']);
		$Sns_CollectionModel = new Sns_CollectionModel();
		$collection_data = array_values($Sns_CollectionModel->getCollectSnsByUid($user_id));

		$collection_count = count($collection_data);
		$data['total'] = $collection_count;
		$data['count'] = $collection_count;

		$this->data->addBody(-140, $data);
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}

	/**
	 * 获取当前用户发布的帖子数量
	 * @param houpeng
	 */
	public function getPublishCount()
	{
		$user_id = request_int('user_id',Perm::$row['user_id']);
		$Sns_BaseModel = new Sns_BaseModel();
		$sns_data = $Sns_BaseModel->getBaseSns($user_id);
		$sns_count = count($sns_data['items']);
		$data['total'] = $sns_count;
		$data['count'] = $sns_count;

		$this->data->addBody(-140, $data);
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}

	/**
	 * 获取好友的动态
	 *@param $id 好友id
	 */
	public function getSnsInfo()
	{
		$user_id = request_int('user_id');
		$User_BaseModel = new User_BaseModel();
		$user_data = $User_BaseModel->getOne($user_id);

		$page=request_int('page',1);
		$rows=request_int('rows',20);
		$sns = new Sns_BaseModel();
		$id_row = $sns->getBaseByUserId($user_id);
		$data = array();

		if($id_row){
			$data = $sns->getBaseByOrder($id_row,$page,$rows);
			fb($data);

			foreach($data['items'] as $key=>$values){
				$time=$data['items'][$key]['sns_create_time'];
				$month=date('m',$time);
				$day=date('d',$time);
				$data['items'][$key]['month']=$month;
				$data['items'][$key]['day']=$day;
				$sns_type_row = array(7,8);
				if(!in_array($data['items'][$key]['sns_type'], $sns_type_row))
				{
					$data['items'][$key]['sns_content'] = base64_decode($data['items'][$key]['sns_content']);
				}
				else
				{
					$data['items'][$key]['new_content'] = $this->RegExp($data['items'][$key]['sns_content']);
				}
			}
			$msg    = 'success';
			$status = 200;
		}
		else
		{
			$msg    = 'failure';
			$status = 250;
		}
		$User_InfoModel = new User_InfoModel();
		$background = current($User_InfoModel->getInfo($user_data['user_account']));
		$data['background'] = $background['background'];
		fb($data);
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//修改好友的备注的备注
	public function getRname()
	{
		$id=Perm::$row['user_id'];
		$user_account=request_string('user_account');
		$user_BaseModel=new User_BaseModel();
		$fid_row=$user_BaseModel->getUserIdByAccount($user_account);
		$fid=$fid_row[0];
		$field_row['content']=request_string('content');
		$rname=new User_RnameModel();
		$id_row = $rname->getRnameId($id,$fid);
		if($id_row)
		{	$field_row=array('content'=>$field_row['content']);
			$data = $rname->editRname($id_row,$field_row);
		}
		else
		{
			$field_row['userid']=$id;
			$field_row['friendid']=$fid;
			$field_row['edit_time']=time();
			$data = $rname->addRname($field_row);
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
		$this->data->addBody(-140, $field_row, $msg, $status);
	}


	/**
	 * 修改好友聊天背景图或朋友圈背景图
	 * @param friend_id int 好友id
	 * 2017-07-05 hp
	 */
	public function changeUserBackground(){
		$user_id=Perm::$row['user_id'];
		$user_account=Perm::$row['user_account'];
		$friend_id = request_int('friend_id');
		$data=array();
		Yf_Log::log('$user_id : ' . $user_id, Yf_Log::INFO, 'member');
		Yf_Log::log('$_FILES : ' . json_encode($_FILES), Yf_Log::INFO, 'member');
		Yf_Log::log('$_REQUEST : ' . json_encode($_REQUEST), Yf_Log::INFO, 'member');
		if (isset($_FILES['logo']))
		{
			//处理上传logo
			$upload = new HTTP_Upload('en');
			$files  = $upload->getFiles();

			if (PEAR::isError($files))
			{
				$data['msg'] = '用户背景图片上传错误';
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
							$background = Yf_Registry::get('base_url')  . '/'. APP_DIR_NAME . $p .  $file->upload['name'];
							$user=array();
							$user['background']=$background;
						}
					}else
					{
						$flag = false;
						$data['msg'] = '更换背景发生错误 :' . $_FILES['upload']['name'] . '|' .  $file->errorMsg();
					}
				}
			}
		}
		if($user){
			if($friend_id)
			{
				$User_FriendModel = new User_FriendModel();
				$user_data = $User_FriendModel->getOneByWhere(['user_id'=>$user_id], ['friend_id'=>$friend_id]);
				if($user_data)
				{
					$cond_friend_row['chat_background'] = $user['background'];
					$flag = $User_FriendModel->editFriend($user_data['user_friend_id'], $cond_friend_row);
				}
				else
				{
					$flag = 0;
				}
			}
			else
			{
				$user_Info=new User_InfoModel();
				$flag=$user_Info->editInfo($user_account,$user);
			}
		}else{
			$flag=0;
		}
		if($flag){
			$msg = 'success';
			$status=200;
			$data['msg']='更换背景成功';
		}else{
			$msg = 'failure';
			$status=250;
		}
		if($user['background']){
			$data['background']=$user['background'];
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//获取电话簿信息 判断是否是好友
	public function checkMember(){
		$user_account=Perm::$row['user_account'];
		$user_id=Perm::$row['user_id'];
		//首先获取用户的好友列表id
		$User_FriendModel=new User_FriendModel();
		$User_BaseModel=new User_BaseModel();
		$user_str=request_string('phone_list');
		$user_row=explode(',',$user_str);
		$user_info=new User_InfoModel();
		$user=array();
		//首先判断该用户是否已经注册
		foreach($user_row as $k=>$v)
		{
			if($k > 0 && $v == $user_row[$k-1])
			{
				$user[$k]['user_account'] = $user[$k-1]['user_account'];
				$user[$k]['status'] = $user[$k-1]['status'];
				$user[$k]['nickname'] = $user[$k-1]['nickname'];
				$user[$k]['user_avatar'] = $user[$k-1]['user_avatar'];
				$user[$k]['user_id'] = '';
			}
			else
			{
				$data = $user_info->getInfoByMobile($v);
				fb($data);
				if($data)
				{
					$data = array_pop($data);
					$user[$k]['user_avatar'] = $data['user_avatar'];
					//根据账户名获取 账户id
					$id_row=$User_BaseModel->getUserIdByAccount($data['user_name']);
					$user[$k]['user_id'] = $id_row[0];
					//说明该用户已经注册,接着判断该用户是否在自己的好友列表里边
					$flag=$User_FriendModel->checkFriend($user_id,$id_row[0]);
					if($flag){
						//说明该用户已经是好友了
						$user[$k]['user_account']=$data['user_name'];
						$user[$k]['status']=1;  //用户已注册并且是好友
						if($data['nickname']){
							$user[$k]['nickname']=$data['nickname'];
						}else{
							$user[$k]['nickname']=$v;
						}
						if($data['user_avatar']){
							$user[$k]['user_avatar']=$data['user_avatar'];
						}else{
							$user[$k]['user_avatar']=Web_ConfigModel::value("user_default_avatar");
						}
					}else{
						$user[$k]['user_account']=$data['user_name'];
						$user[$k]['status']=2;  //用户已注册但不是好友
						if($data['nickname']){
							$user[$k]['nickname']=$data['nickname'];
						}else{
							$user[$k]['nickname']=$v;
						}
						if($data['user_avatar']){
							$user[$k]['user_avatar']=$data['user_avatar'];
						}else{
							$user[$k]['user_avatar']=Web_ConfigModel::value("user_default_avatar");
						}
					}
				}
				else
				{
					$user[$k]['user_account']=$v;
					$user[$k]['status']=3;  //用户未注册
					$user[$k]['nickname']=$v;
					$user[$k]['user_avatar']= Web_ConfigModel::value("user_default_avatar");
					$user[$k]['user_id'] = '';
				}
			}
		}
//		$a = count($user);
		$this->data->addBody(-140,$user);
	}

	//获取推荐的用户
	public function getRecommendUser()
	{
		//获取几位推荐用户
		$count = request_int('count',5);
		$user_id = Perm::$userId;

		$User_BaseModel = new User_BaseModel();
		$User_InfoModel = new User_InfoModel();
		$User_FriendModel=new User_FriendModel();

		//将所有可显示的帖子按照收藏数由大到小排列
		$Sns_BaseModel = new Sns_BaseModel();

		$sns_base = $Sns_BaseModel->getRecommendBlog($user_id);
		$sns_base = array_values($sns_base);
		//echo '<pre>';print_r($sns_base);exit;
		fb($sns_base);
		$data = array();
		$user_base = array();
		$i = 0;
		if($sns_base)
		{
			foreach($sns_base as $key => $val)
			{
				if(!in_array($val['user_id'],$data))
				{
					$data[] = $val['user_id'];
					$i++;
				}

				if($i == $count)
				{
					break;
				}
			}
		}

		if($data)
		{
			$user_base = $User_BaseModel->getUser($data);

			if($user_base)
			{
				foreach($user_base as $key => $val)
				{
					$user_info = $User_InfoModel->getUserInfo($val['user_account'],$val['user_id']);
					$user_base[$key] = $user_base[$key] + $user_info;

					//判断是否是我的好友
					$flag = $User_FriendModel->friend($user_id,$val['user_id']);
					if($flag)
					{
						$user_base[$key]['is_friend'] = 1;
					}else
					{
						$user_base[$key]['is_friend'] = 0;
					}
				}
			}
		}
		$user_base = array_values($user_base);
		fb($user_base);
		$this->data->addBody(-140, $user_base);
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}

	//获取推荐的帖子
	public function getRecommendSnsList()
	{
		$user_id = request_int('user_id');
		$page = request_string('page',1);
		$rows = request_string('rows',3);

		$Sns_BaseModel = new Sns_BaseModel();
		$User_InfoModel = new User_InfoModel();
		$User_FriendModel = new User_FriendModel();
		$Sns_CollectionModel = new Sns_CollectionModel();

		$data = $Sns_BaseModel->getRecommendBlogList($user_id, $page, $rows);

		if($data['items'])
		{
			foreach($data['items'] as $key => $val)
			{
				$data['items'][$key]['sns_img'] = array_filter(explode(',',$val['sns_img']));
				$data['items'][$key]['sns_lable'] = array_filter(explode(',',$val['sns_lable']));
				$sns_type_row = array(7,8);
				if(!in_array($data['items'][$key]['sns_type'], $sns_type_row))
				{
					$data['items'][$key]['sns_content'] = base64_decode($data['items'][$key]['sns_content']);
				}
				else
				{
					$link =  current($this->RegExp($data['items'][$key]['sns_content']));
					$data['items'][$key]['new_title'] = "title";
					$data['items'][$key]['new_h5'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.$link;
				}
				$user_info = current($User_InfoModel->getInfo($val['user_name']));
				$data['items'][$key]['user_avatar'] = $user_info['user_avatar'];
				$data['items'][$key]['user_sign'] = $user_info['user_sign'];

				//点赞人数
				$like_user_row = explode(',', $val['sns_like_user']);
				if(in_array($user_id, $like_user_row))
				{
					$data['items'][$key]['islike'] = 1;
				}
				else
				{
					$data['items'][$key]['islike'] = 0;
				}

				//判断给条状态当前用户是否已经收藏
				$sns_user_collection = $Sns_CollectionModel->getCollectBySidUid($val['sns_id'], $user_id);
				if($sns_user_collection)
				{
					$data['items'][$key]['sns_user_collection'] = 1;
				}
				else
				{
					$data['items'][$key]['sns_user_collection'] = 0;
				}

				$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $val['user_id']);
				if($friend_status)
				{
					$data['items'][$key]['user_friend_status'] = 1;
				}
				else
				{
					$data['items'][$key]['user_friend_status'] = 0;
				}

				$data['items'][$key]['hot_count'] = $val['sns_copy_count'] + $val['sns_like_count'] + $val['sns_collection'];
			}
		}
		fb($data);
		
		$this->data->addBody(-140, $data);

	}


	/**
	 * 获取帖子的所有评论
	 * @author
	 */
	public function getSnsComment()
	{
		$sns_id = request_int('sns_id');
		$user_id = Perm::$userId;
		$Sns_CommentModel = new Sns_CommentModel();
		$Sns_BaseModel = new Sns_BaseModel();
		$User_InfoModel = new User_InfoModel();
		$User_BaseModel = new User_BaseModel();
		$Sns_CollectionModel = new Sns_CollectionModel();
		$Sns_ForwardModel = new Sns_ForwardModel();
		$Sns_LikeModel = new Sns_LikeModel();
		$User_RnameModel = new User_RnameModel();

		$data = array();

		$sns_base = $Sns_BaseModel->getOne($sns_id);
		fb($sns_base);

		//评论
		$sns_comment = $Sns_CommentModel->getCommentBySid($sns_id);
		if($sns_comment)
		{
			$sns_comment = array_values($sns_comment);

			foreach($sns_comment as $key => $val)
			{
				//查找评论用户的用户信息
				$user_info = $User_InfoModel->getOne($val['user_name']);
				//查找备注名
				$user_rename = $User_RnameModel->getUserRname($user_id,$val['user_id']);
				if($user_rename)
				{
					$user_rename = current($user_rename);
					$user_info['rename'] = $user_rename['content'];
				}
				else
				{
					$user_info['rename'] = '';
				}
				$sns_comment[$key]['user_info'] = $user_info;
			}
		}

		//点赞
		$like_data = $Sns_LikeModel->getLikeBySid($sns_id);
		$like_user_data = array();
		if($like_data)
		{
			$collection_data = array_values($like_data);
			foreach($like_data as $k=>$v)
			{
				$like_user_data[$k]['user_id'] = $v['user_id'];
				$like_user_base = current($User_BaseModel->getUser($v['user_id']));
				$like_user_info = current($User_InfoModel->getInfo($like_user_base['user_account']));
				$like_user_data[$k]['user_avatar'] = $like_user_info['user_avatar'];
				$like_user_data[$k]['addtime'] = $v['addtime'];
				$like_user_data[$k]['state'] = 3;		//表示该用户是点赞帖子
			}

		}

		//转发
		$forward_data = $Sns_ForwardModel->getForwardrow($sns_id);
		$forward_user_data = array();
		if($forward_data)
		{
			$forward_data = array_values($forward_data);
			foreach($forward_data as $key=>$value)
			{
				$forward_sns_data = current($Sns_BaseModel->getBase($value['forward_sns_id']));
				$forward_user_data[$key]['user_id'] = $forward_sns_data['user_id'];
				$forward_user_info = current($User_InfoModel->getInfo($forward_sns_data['user_name']));
				$forward_user_data[$key]['user_avatar'] = $forward_user_info['user_avatar'];
				$forward_user_data[$key]['addtime'] = $value['addtime'];
				$forward_user_data[$key]['state'] = 2;		//表示该用户是转发的
			}
		}

		//收藏
		$collection_data = $Sns_CollectionModel->getCollectBySid($sns_id);
		$collection_user_data = array();
		if($collection_data)
		{
			$collection_data = array_values($collection_data);
			foreach($collection_data as $k=>$v)
			{
				$collection_user_data[$k]['user_id'] = $v['user_id'];
				$collection_user_base = current($User_BaseModel->getUser($v['user_id']));
				$collection_user_info = current($User_InfoModel->getInfo($collection_user_base['user_account']));
				$collection_user_data[$k]['user_avatar'] = $collection_user_info['user_avatar'];
				$collection_user_data[$k]['addtime'] = $v['addtime'];
				$collection_user_data[$k]['state'] = 1;		//表示该用户是收藏帖子
			}

		}

		$data['hot_count'] = $sns_base['sns_copy_count'] + $sns_base['sns_like_count'] + $sns_base['sns_collection'];


		$data['mix'] = array_merge($like_user_data,$collection_user_data, $forward_user_data);

		if($data['mix'])
		{
			foreach ($data['mix'] as $key => $value) {
				$data['mix'][$key]['addtime'] = date("Y-m-d H:i:s", $value['addtime']);
			}
			$datetime = array();
			foreach ($data['mix'] as $user) {
				$datetime[] = $user['addtime'];
			}
			array_multisort($datetime, SORT_DESC, $data['mix']);
		}

		$data['comment'] = $sns_comment;
		$data['forword_count'] = $sns_base['sns_copy_count'];
		$data['like_count'] = $sns_base['sns_like_count'];
		$data['collect_count'] = $sns_base['sns_collection'];
		$data['sns_id'] = $sns_id;

		fb($data);

		$this->data->addBody(-140, $data);
	}

	/**
	 * 朋友圈API - 搜索
	 *
	 * @access public
	 */
	//根据用户输入的标签或者是用户昵称来搜索对应的帖子
	public function getSnsList()
	{
		$page = request_int('page',1);
		$rows = request_int('rows',10);
		$user_id = request_int('user_id');
		//用来判断用户是按用户名搜索还是按标签搜索，1代表用户名，2代表标签, 3代表推荐帖子。默认是1
		$kind_type = request_int('kind_type',1);
		$search_name = request_string('search_name');
		$Sns_BaseModel = new Sns_BaseModel();
		$Sns_TimelineModel = new Sns_TimelineModel();
		$sns_id_row = array();
		$data['items'] = array();
		//判断是否是搜索状态，如果是搜索状态找出所有满足条件的sns_id
		if($search_name)
		{
			$data = $Sns_BaseModel->getSnsList($user_id, $search_name, $kind_type, $page, $rows);
			$sns_id_row = array_column($data['items'], 'sns_id');
			if($sns_id_row)
			{
				if(!$data['items'])
				{
					$data = $Sns_BaseModel->getSnsList($user_id, $search_name, $kind_type=2, $page, $rows);
				}
				else
				{
					$data_lable = $Sns_BaseModel->getSnsList($user_id, $search_name, $kind_type=2, $page, $rows);
					if($data_lable['items'])
					{
						$data['items'] = array_merge($data['items'], $data_lable['items']);
					}
				}
			}
			else
			{
				$data = $Sns_BaseModel->getSnsList($user_id, $search_name, $kind_type=2, $page, $rows);
			}

			if($data['items'])
			{
				$sns_id_row = array_column($data['items'], 'sns_id');
				$sns_base_row = $Sns_BaseModel->getBase($sns_id_row);
				rsort($sns_base_row);

				//获取所有用户信息
				$sns_user_row = array_column($sns_base_row,'user_name');
				$sns_userid_row = array_column($sns_base_row,'user_id');
				$sns_user_row = array_unique($sns_user_row);
				$User_BaseModel = new User_BaseModel();
				$User_InfoModel = new User_InfoModel();
				$Sns_ForwardModel = new Sns_ForwardModel();
				$Sns_CollectionModel = new Sns_CollectionModel();

				$sns_user_info_row = $User_InfoModel->getInfo($sns_user_row);

				//获取用户收藏量最高的三条带图的大家都可见的状态
				$sns_user_recond_sns = $Sns_BaseModel->getBaseSnsNextLimit($sns_userid_row, 1, $sort='desc', 1, $limit=3, 0);

				$array = array();
				$array_sns = array();
				foreach($sns_user_recond_sns as $surkey => $surval)
				{
					if($surval)
					{
						$surval['img'] = array_filter(explode(',',$surval['sns_img']));
					}
					else
					{
						$surval['img'] = array();
					}
					if(in_array($surval['user_id'],$array))
					{
						$array_sns[$surval['user_name']][] = $surval;
					}
					else
					{
						$array[] = $surval['user_id'];
						$array_sns[$surval['user_name']][] = $surval;
					}
				}
				$sns_user_info_row = array_values($sns_user_info_row);
				$array_sns = current($array_sns);

				foreach($sns_user_info_row as $suikey => $suival)
				{
					if($array_sns)
					{
						$sns_user_info_row[$suikey]['user_sns'] = $array_sns[$suikey];
					}
					else
					{
						$sns_user_info_row[$suikey]['user_sns'] = array();
					}
				}

				//获取自己的好友id
				$User_FriendModel = new User_FriendModel();
				$user_friend = $User_FriendModel->getUserFriendId($user_id);
				$user_friend_id_row = array_column($user_friend,'friend_id');

				//获取自己收藏的帖子id
				$Sns_CollectionModel = new Sns_CollectionModel();
				$sns_user_collection = $Sns_CollectionModel->getCollectSnsByUid($user_id);
				$user_collection_sid = array_column($sns_user_collection,'sns_id');

				foreach ($sns_base_row as $key => $value)
				{
					//内容
					$sns = $Sns_BaseModel->getOne($value['sns_id']);
					$data['items'][$key]['sns'] = $sns;
					$sns_user_name = $sns['user_name'];
					$sns_user_id = $sns['user_id'];
					if($sns['sns_img'])
					{
						$data['items'][$key]['sns']['img'] = array_filter(explode(',', $sns['sns_img']));
					}else
					{
						$data['items'][$key]['sns']['img'] = array();
					}
					if($sns['sns_lable'])
					{
						$data['items'][$key]['sns']['lable'] = array_filter(explode(',', $sns['sns_lable']));
					}else
					{
						$data['items'][$key]['sns']['lable'] = array();
					}
					$sns_type_row = array(7,8);
					if(!in_array($data['items'][$key]['sns']['sns_type'], $sns_type_row))
					{
						$data['items'][$key]['sns_content'] = base64_decode($data['items'][$key]['sns_content']);
						$data['items'][$key]['sns']['sns_content'] = base64_decode($data['items'][$key]['sns']['sns_content']);
					}
					else
					{
						$link =  current($this->RegExp($data['items'][$key]['sns_content']));
						$data['items'][$key]['new_title'] = "title";
						$data['items'][$key]['new_h5'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.$link;

						$link =  current($this->RegExp($data['items'][$key]['sns']['sns_content']));
						$data['items'][$key]['sns']['new_title'] = "title";
						$data['items'][$key]['sns']['new_h5'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.$link;
					}

					//计算帖子的热度（转发，收藏，点赞）
					$data['items'][$key]['sns']['hot_count'] = $sns['sns_copy_count'] + $sns['sns_like_count'] + $sns['sns_collection'];

					//判断该帖子是否是转载贴，如果是查找出被抓的帖子和原贴的信息
					if($sns['sns_forward'])
					{
						$forward = $Sns_ForwardModel->getForwardByFid($sns['sns_id']);
						$forward = current($forward);

						$forword_sns = $Sns_BaseModel->getBaseById($forward['sns_id']);
						$forword_sns = current($forword_sns);
						$sns_type_row = array(7,8);
						if(!in_array($forword_sns['sns_type'], $sns_type_row))
						{
							$forword_sns['sns_content'] = base64_decode($forword_sns['sns_content']);
						}
						//判断转发的用户是否是自己的好友
						$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $forword_sns['user_id']);
						if($friend_status)
						{
							$forword_sns['is_friend'] = 1;
						}
						else
						{
							$forword_sns['is_friend'] = 0;
						}

						$source_sns = $Sns_BaseModel->getBaseById($forward['source_sns_id']);
						$source_sns = current($source_sns);
						$sns_type_row = array(7,8);
						if(!in_array($source_sns['sns_type'], $sns_type_row))
						{
							$source_sns['sns_content'] = base64_decode($source_sns['sns_content']);
						}
						$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $source_sns['user_id']);
						if($friend_status)
						{
							$source_sns['is_friend'] = 1;
						}
						else
						{
							$source_sns['is_friend'] = 0;
						}
						if($source_sns['sns_img'])
						{
							$source_sns['img'] = array_filter(explode(',', $source_sns['sns_img']));
						}else
						{
							$source_sns['img'] = array();
						}
						if($source_sns['sns_lable'])
						{
							$source_sns['lable'] = array_filter(explode(',', $source_sns['sns_lable']));
						}else
						{
							$source_sns['lable'] = array();
						}
						foreach($forword_sns['user_info']['user_sns'] as $sfkey=>$sfvalue)
						{
							$sns_type_row = array(7,8);
							if(!in_array($forword_sns['user_info']['user_sns'][$sfkey]['sns_type'], $sns_type_row))
							{
								$forword_sns['user_info']['user_sns'][$sfkey]['sns_content'] = base64_decode($forword_sns['user_info']['user_sns'][$sfkey]['sns_content']);
							}
						}
						foreach($source_sns['user_info']['user_sns'] as $sskey=>$ssvalue)
						{
							$sns_type_row = array(7,8);
							if(!in_array($source_sns['user_info']['user_sns'][$sskey]['sns_type'], $sns_type_row))
							{
								$source_sns['user_info']['user_sns'][$sskey]['sns_content'] = base64_decode($source_sns['user_info']['user_sns'][$sskey]['sns_content']);
							}
						}
						$data['items'][$key]['sns']['forword_sns'] = $forword_sns;
						$data['items'][$key]['sns']['source_sns'] = $source_sns;

					}else
					{
						$data['items'][$key]['sns']['forword_sns'] = array();
						$data['items'][$key]['sns']['source_sns'] = array();
					}

					//判断该帖子是否是用户的收藏贴
					$sns_user_collection = $Sns_CollectionModel->getCollectBySidUid($value['sns_id'], $user_id);
					if($sns_user_collection)
					{
						$data['items'][$key]['is_collection'] = 1;
					}
					else
					{
						$data['items'][$key]['is_collection'] = 0;
					}

					//判断该帖的发帖者是否是用户的好友
					$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $sns_user_id);
					if($friend_status)
					{
						$data['items'][$key]['is_friend'] = 1;
					}
					else
					{
						$data['items'][$key]['is_friend'] = 0;
					}

					//发布动态者信息
					$user_RnameModel=new User_RnameModel();
					$user_BaseModel=new User_BaseModel();
					if($sns_user_name)
					{
						$User_InfoModel = new User_InfoModel();
						$user_info_row = $User_InfoModel->getUserInfo($sns_user_name,$sns_user_id);

						$fid=$user_BaseModel->getUserIdByAccount($sns_user_name);
						$flag=$user_RnameModel->getRnameId($user_id,$fid[0]);
						if($flag){
							$datas=$user_RnameModel->getRname($flag);
							$user_info_row['rename']=$datas[$flag[0]]['content'];
						}else{
							$user_info_row['rename']='';
						}
						$data['items'][$key]['sns_user'] = $user_info_row;
						foreach($data['items'][$key]['sns_user']['user_sns'] as $ukey=>$uvalue)
						{
							$sns_type_row = array(7,8);
							if(!in_array($data['items'][$key]['sns_user']['user_sns'][$ukey]['sns_type'], $sns_type_row))
							{
								$data['items'][$key]['sns_user']['user_sns'][$ukey]['sns_content'] = base64_decode($data['items'][$key]['sns_user']['user_sns'][$ukey]['sns_content']);
							}
						}
					}
					else
					{
						$data['items'][$key]['sns_user'] = array();
					}

					//点赞人数
					$sns_like_user = $sns['sns_like_user'];
					$like_user_row = explode(',', $sns_like_user);
					if(in_array($user_id,$like_user_row))
					{
						$data['items'][$key]['islike'] = 1;
					}
					else
					{
						$data['items'][$key]['islike'] = 0;
					}
					$User_BaseModel = new User_BaseModel();
					$like_user_name = array();
					foreach ($like_user_row as $ke => $val)
					{
						$user_info = array();
						$like_info=array();
						if($val)
						{
							$user_info = $User_BaseModel->getUser($val);
							if($user_info)
							{	$User_InfoModel = new User_InfoModel();
								$user_infos=$User_InfoModel->getInfo($user_info[$val]['user_account']);
								$fid=$user_BaseModel->getUserIdByAccount($user_info[$val]['user_account']);
								$flag=$user_RnameModel->getRnameId($user_id,$fid[0]);
								if($flag){
									$datas=$user_RnameModel->getRname($flag);
									$like_info['rename']=$datas[$flag[0]]['content'];
								}else{
									$like_info['rename']='';
								}

								$like_info['user_account']=$user_info[$val]['user_account'];
								$like_info['nickname']=$user_infos[$user_info[$val]['user_account']]['nickname'];
								$like_info['logo']=$user_infos[$user_info[$val]['user_account']]['user_avatar'];
								array_push($like_user_name,$like_info);
							}
						}
					}
					$data['items'][$key]['like_user_name'] = $like_user_name;
					$status = 200;
					$msg = 'success';
				}
			}
			else
			{
				$data = array();
				$status = 200;
				$msg = '没有符合要求的帖子';
			}
		}
		$data['items'] = array_values($data['items']);
		fb($data);
//		echo '<pre>';print_r(array($a,$b));exit;
		$this->data->addBody(-140, $data, $msg, $status);
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}
















	//======================SNS==========================//




	/**
	 * 关注好友  测试 2016.11.24
	 * @author houpng
	 * @access public
	 */
	public function concernUser()
	{
		$user_id = request_int('user_id');
		$friend_name = request_string('friend_name');
		$user_name = request_string('user_account');
		if($user_name == $friend_name)
		{
			$msg = '不能关注自己';
			$status = 250;
		}
		else
		{
			$User_BaseModel = new User_BaseModel();
			$User_FriendModel = new User_FriendModel();
			$friend_info = $User_BaseModel->getInfoByName($friend_name);
            fb($friend_info);
			$friend_id = $friend_info['user_id'];
			$user_row['user_id'] = $user_id;
			$user_row['friend_id'] = $friend_id;
			$data_rows = $User_FriendModel->getUserFriendIdById($user_id, $friend_id);
			//echo '<pre>';print_r($data_rows);exit;
			if(empty($data_rows))
			{
				$add_flag = $User_FriendModel->addFriend($user_row);
				if($add_flag)
				{
					$msg = '关注成功';
					$status = 200;
				}
				else
				{
					$msg = '关注失败';
					$status = 250;
				}
			}
			else
			{
				$data_rows = current($data_rows);
				$user_friend_id = $data_rows['user_friend_id'];
				$del_flag = $User_FriendModel->removeFriend($user_friend_id);
				if($del_flag)
				{
					$msg = '取消关注成功';
					$status = 200;
				}
				else
				{
					$msg = '取消关注失败';
					$status = 250;
				}
			}
		}
		//echo '<pre>';print_r($add_flag);exit;
		$data = array();
		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 * 获取当前转发帖子的内容
	 */
	public function getTransmit()
	{
		$sns_id = request_int('sns_id');
		$forward_user_id = request_int('user_id');
		$Sns_BaseModel = new Sns_BaseModel();
		$User_BaseModel = new User_BaseModel();
		$User_InfoModel = new User_InfoModel();
		$Sns_ForwardModel = new Sns_ForwardModel();
		$rows = current($Sns_ForwardModel->getForwardByFid($sns_id));
		//echo '<pre>';print_r($forward_user_id);exit;
		$data = array();
		if(!$rows)
		{
			$sns_data = current($Sns_BaseModel->getBase($sns_id));
			$user_id = $sns_data['user_id'];
		}
		else
		{
			$sns_data = current($Sns_BaseModel->getBase($rows['forward_sns_id']));
			$user_id = $sns_data['user_id'];							//原始帖用户ID
			/*$be_forward_sns_data = current($Sns_BaseModel->getBase($sns_id));
			$be_forward_user_id = $be_forward_sns_data['user_id'];		//当前被转发的用户ID*/
		}

		$forward = $Sns_ForwardModel->getForwardByFid($sns_id);
		$forward = current($forward);
		$source_sns = $Sns_BaseModel->getBaseById($forward['source_sns_id']);
		$source_sns = current($source_sns);

		if($user_id == $forward_user_id || $forward_user_id == $source_sns['user_id'])
		{
			$msg = 'failure';
			$status = 250;
		}
		else
		{
			$user_base = current($User_BaseModel->getUser($user_id));

			if($user_base)
			{
				$user_name = $user_base['user_account'];
				$user_info = current($User_InfoModel->getInfo($user_name));
				$data['user'] = $user_info;
			}
			$sns_data = current($Sns_BaseModel->getBase($sns_id));
			if($sns_data)
			{
				if($sns_data['sns_lable'])
				{
					$sns_data['sns_lable'] = array_filter(explode(',',$sns_data['sns_lable']));
				}
				else
				{
					$sns_data['sns_lable'] = array();
				}
				if($sns_data['sns_img'])
				{
					$sns_data['sns_img'] = array_filter(explode(',',$sns_data['sns_img']));
				}
				else
				{
					$sns_data['sns_img'] = array();
				}
				$sns_type_row = array(7,8);
				if(!in_array($sns_data['sns_type'], $sns_type_row))
				{
					$sns_data['sns_content'] = base64_decode($sns_data['sns_content']);
				}
				else
				{
					$link =  current($this->RegExp($sns_data['sns_content']));
					$sns_data['new_title'] = "title";
					$sns_data['new_h5'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.$link;
				}
				$data['sns'] = $sns_data;
				$sns_user_info = current($User_InfoModel->getInfo($sns_data['user_name']));
				if($sns_user_info)
				{
					$data['sns_user'] = $sns_user_info;
				}
				$msg = 'success';
				$status = 200;
			}
			else
			{
				$msg = 'failure';
				$status = 250;
			}
		}
		//echo '<pre>';print_r($data);exit;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * sns个人主页 2016.12.1
	 * @param $img_user_id表示点击用户头像传递的用户ID，$detail_user_id表示点击首页帖子的用户ID,$text_user_id表示点击全文链接的用户ID
	 * @author houpeng
	 */
	public function personalPage()
	{
		$page = request_int('page');//echo '<pre>';print_r($page);exit;
		$search_page = request_int('search_page');
		$text_page = request_int('text_page');
		$reply_page = request_int('reply_page');
		$no_page = request_int('no_page');
		if($search_page)
		{
			$page = $search_page;
		}
		elseif($text_page)
		{
			$page = $text_page;
		}
		elseif($reply_page)
		{
			$page = $reply_page;
		}
		elseif($no_page)
		{
			$page = $no_page;
		}
		$user_id = request_int('user_id');
		$user_friend_id = request_int('user_friend_id');
		$sns_id = request_int('sns_id');
		$reply_sns_id = request_int('reply_sns_id');
		$reply_user_id = request_int('reply_user_id');
		$img_user_id = request_int('img_user_id');
		$detail_sns_id = request_int('detail_sns_id');
		$detail_user_id = request_int('detail_user_id');
		$text_user_id = request_int('text_user_id');
		$text_sns_id = request_int('text_sns_id');
		$search_user_id = request_int('search_user_id');	//个人主页按照标签搜索帖子的用户ID
		$search_sns_id = request_int('search_sns_id');		//个人主页按照标签搜索帖子的当前帖子ID
		$img_status = request_int('img_status');	//为了区分点击预览图浏览帖子还是上一篇下一篇按钮
		$pre_tip = request_int('pre_tip');			//为了区分是点击上一页按钮还是下一页按钮

		if($img_user_id)
		{
			$user_id = $img_user_id;
		}
		elseif($detail_user_id)
		{
			$user_id = $detail_user_id;
		}
		elseif($text_user_id)
		{
			$user_id = $text_user_id;
		}
		elseif($search_user_id)
		{
			$user_id = $search_user_id;
		}
		elseif($reply_user_id)
		{
			$user_id = $reply_user_id;
		}

		//echo '<pre>';print_r($user_id);exit;
		if($detail_sns_id)
		{
			$sns_id = $detail_sns_id;
		}
		elseif($text_sns_id)
		{
			$sns_id = $text_sns_id;
		}
		elseif($search_sns_id)
		{
			$sns_id = $search_sns_id;
		}
		elseif($reply_sns_id)
		{
			$sns_id = $reply_sns_id;
		}

		$sns_lable = request_string('sns_lable');
		//echo '<pre>';print_r($sns_lable);exit;
		$Sns_BaseModel = new Sns_BaseModel();
		$User_InfoModel = new User_InfoModel();
		$User_BaseModel = new User_BaseModel();
		$Sns_CommentModel = new Sns_CommentModel();
		$Sns_CollectionModel = new Sns_CollectionModel();
		$Sns_ForwardModel = new Sns_ForwardModel();
		$User_FriendModel = new User_FriendModel();

		if(($sns_id && $img_status))
		{
			//找出当前点击图片的帖子在总帖中的页数
			$sns_list_prev = $Sns_BaseModel->getBaseSnsPrev($user_id);fb($sns_list_prev);
			foreach($sns_list_prev['items'] as $pkey=>$pvalue)
			{
				if($sns_id == $pvalue['sns_id'])
				{
					$page = intval($pkey)+1;
				}
			}
			$sns_list = $Sns_BaseModel->getBaseSns($user_id, $page, 1, 'desc');
			$sns_type_row = array(7,8);
			if(!in_array($sns_list['items'][0]['sns_type'], $sns_type_row))
			{
				$sns_list['items'][0]['sns_content'] = base64_decode($sns_list['items'][0]['sns_content']);
			}
			else
			{
				$link =  current($this->RegExp($sns_list['items'][0]['sns_content']));
				$sns_list['items'][0]['new_title'] = "title";
				$sns_list['items'][0]['new_h5'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.$link;
			}
			if($sns_list['items'][0]['sns_forward'])
			{
				$forward = $Sns_ForwardModel->getForwardByFid($sns_list['items'][0]['sns_id']);

				$forward = current($forward);
				//echo '<pre>';print_r($forward);exit;
				$forword_sns = $Sns_BaseModel->getBaseById($forward['sns_id']);
				$forword_sns = current($forword_sns);
				$sns_type_row = array(7,8);
				if(!in_array($forword_sns['sns_type'], $sns_type_row))
				{
					$forword_sns['sns_content'] = base64_decode($forword_sns['sns_content']);
				}
				//判断转发的用户是否是自己的好友
				$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $forword_sns['user_id']);
				if($friend_status)
				{
					$forword_sns['is_friend'] = 1;
				}
				else
				{
					$forword_sns['is_friend'] = 0;
				}

				$source_sns = $Sns_BaseModel->getBaseById($forward['source_sns_id']);
				$source_sns = current($source_sns);
				$sns_type_row = array(7,8);
				if(!in_array($source_sns['sns_type'], $sns_type_row))
				{
					$source_sns['sns_content'] = base64_decode($source_sns['sns_content']);
				}
				$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $source_sns['user_id']);
				if($friend_status)
				{
					$source_sns['is_friend'] = 1;
				}
				else
				{
					$source_sns['is_friend'] = 0;
				}
				if($source_sns['sns_img'])
				{
					$source_sns['img'] = array_filter(explode(',', $source_sns['sns_img']));
				}else
				{
					$source_sns['img'] = array();
				}
				if($source_sns['sns_lable'])
				{
					$source_sns['lable'] = array_filter(explode(',', $source_sns['sns_lable']));
				}else
				{
					$source_sns['lable'] = array();
				}
				foreach($forword_sns['user_info']['user_sns'] as $sfkey=>$sfvalue)
				{
					$sns_type_row = array(7,8);
					if(!in_array($forword_sns['user_info']['user_sns'][$sfkey]['sns_type'], $sns_type_row))
					{
						$forword_sns['user_info']['user_sns'][$sfkey]['sns_content'] = base64_decode($forword_sns['user_info']['user_sns'][$sfkey]['sns_content']);
					}
				}
				foreach($source_sns['user_info']['user_sns'] as $sskey=>$ssvalue)
				{
					$sns_type_row = array(7,8);
					if(!in_array($source_sns['user_info']['user_sns'][$sskey]['sns_type'], $sns_type_row))
					{
						$source_sns['user_info']['user_sns'][$sskey]['sns_content'] = base64_decode($source_sns['user_info']['user_sns'][$sskey]['sns_content']);
					}
				}
				$sns_list['items'][0]['sns']['forword_sns'] = $forword_sns;
				$sns_list['items'][0]['sns']['source_sns'] = $source_sns;

			}else
			{
				$sns_list['items'][0]['sns']['forword_sns'] = array();
				$sns_list['items'][0]['sns']['source_sns'] = array();
			}
			//点赞人数
			$sns_like_user = $sns_list['items'][0]['sns_like_user'];
			$like_user_row = explode(',', $sns_like_user);
			if(in_array($user_friend_id, $like_user_row))
			{
				$sns_list['items'][0]['islike'] = 1;
			}
			else
			{
				$sns_list['items'][0]['islike'] = 0;
			}

			$user_friend_status = $User_FriendModel->getUserFriendIdById($user_friend_id, $user_id);
			if($user_friend_status)
			{
				$sns_list['items'][0]['user_friend_status'] = 1;
			}
			else
			{
				$sns_list['items'][0]['user_friend_status'] = 0;
			}

			$sns_user_collection = $Sns_CollectionModel->getCollectBySidUid($sns_list['items'][0]['sns_id'], $user_friend_id);
			if($sns_user_collection)
			{
				$sns_list['items'][0]['sns_user_collection'] = 1;
			}
			else
			{
				$sns_list['items'][0]['sns_user_collection'] = 0;
			}
		}
		else if($sns_lable)
		{
			$sns_list = $Sns_BaseModel->getBaseSns($user_id, 1, 100, 'desc', $sns_lable);
			$sns_type_row = array(7,8);
			if(!in_array($sns_list['items'][0]['sns_type'], $sns_type_row))
			{
				$sns_list['items'][0]['sns_content'] = base64_decode($sns_list['items'][0]['sns_content']);
			}
			else
			{
				$link =  current($this->RegExp($sns_list['items'][0]['sns_content']));
				$sns_list['items'][0]['new_title'] = "title";
				$sns_list['items'][0]['new_h5'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.$link;
			}
			if($sns_list['items'][0]['sns_forward'])
			{
				$forward = $Sns_ForwardModel->getForwardByFid($sns_list['items'][0]['sns_id']);
				$forward = current($forward);
				$forword_sns = $Sns_BaseModel->getBaseById($forward['sns_id']);
				$forword_sns = current($forword_sns);
				$sns_type_row = array(7,8);
				if(!in_array($forword_sns['sns_type'], $sns_type_row))
				{
					$forword_sns['sns_content'] = base64_decode($forword_sns['sns_content']);
				}
				//判断转发的用户是否是自己的好友
				$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $forword_sns['user_id']);
				if($friend_status)
				{
					$forword_sns['is_friend'] = 1;
				}
				else
				{
					$forword_sns['is_friend'] = 0;
				}

				$source_sns = $Sns_BaseModel->getBaseById($forward['source_sns_id']);
				$source_sns = current($source_sns);
				$sns_type_row = array(7,8);
				if(!in_array($source_sns['sns_type'], $sns_type_row))
				{
					$source_sns['sns_content'] = base64_decode($source_sns['sns_content']);
				}
				$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $source_sns['user_id']);
				if($friend_status)
				{
					$source_sns['is_friend'] = 1;
				}
				else
				{
					$source_sns['is_friend'] = 0;
				}
				if($source_sns['sns_img'])
				{
					$source_sns['img'] = array_filter(explode(',', $source_sns['sns_img']));
				}else
				{
					$source_sns['img'] = array();
				}
				if($source_sns['sns_lable'])
				{
					$source_sns['lable'] = array_filter(explode(',', $source_sns['sns_lable']));
				}else
				{
					$source_sns['lable'] = array();
				}
				foreach($forword_sns['user_info']['user_sns'] as $sfkey=>$sfvalue)
				{
					$sns_type_row = array(7,8);
					if(!in_array($forword_sns['user_info']['user_sns'][$sfkey]['sns_type'], $sns_type_row))
					{
						$forword_sns['user_info']['user_sns'][$sfkey]['sns_content'] = base64_decode($forword_sns['user_info']['user_sns'][$sfkey]['sns_content']);
					}
				}
				foreach($source_sns['user_info']['user_sns'] as $sskey=>$ssvalue)
				{
					$sns_type_row = array(7,8);
					if(!in_array($source_sns['user_info']['user_sns'][$sskey]['sns_type'], $sns_type_row))
					{
						$source_sns['user_info']['user_sns'][$sskey]['sns_content'] = base64_decode($source_sns['user_info']['user_sns'][$sskey]['sns_content']);
					}
				}
				$sns_list['items'][0]['sns']['forword_sns'] = $forword_sns;
				$sns_list['items'][0]['sns']['source_sns'] = $source_sns;

			}else
			{
				$sns_list['items'][0]['sns']['forword_sns'] = array();
				$sns_list['items'][0]['sns']['source_sns'] = array();
			}
			if($sns_list['items'])
			{
				$user_friend_status = $User_FriendModel->getUserFriendIdById($user_friend_id, $user_id);
				if($user_friend_status)
				{
					$sns_list['items'][0]['user_friend_status'] = 1;
				}
				else
				{
					$sns_list['items'][0]['user_friend_status'] = 0;
				}

				//点赞人数
				$sns_like_user = $sns_list['items'][0]['sns_like_user'];
				$like_user_row = explode(',', $sns_like_user);
				if(in_array($user_friend_id, $like_user_row))
				{
					$sns_list['items'][0]['islike'] = 1;
				}
				else
				{
					$sns_list['items'][0]['islike'] = 0;
				}

				$sns_user_collection = $Sns_CollectionModel->getCollectBySidUid($sns_list['items'][0]['sns_id'], $user_friend_id);
				if($sns_user_collection)
				{
					$sns_list['items'][0]['sns_user_collection'] = 1;
				}
				else
				{
					$sns_list['items'][0]['sns_user_collection'] = 0;
				}
				foreach($sns_list['items'] as $k=>$v)
				{
					$sns_list['items'][$k]['mix'] = array();
				}
			}
		}
		else if($search_sns_id || $text_sns_id ||$reply_sns_id ||$detail_sns_id)
		{

			//找出当前点击图片的帖子在总帖中的页数
			$sns_list_prev = $Sns_BaseModel->getBaseSnsPrev($user_id);fb($sns_list_prev);
			foreach($sns_list_prev['items'] as $pkey=>$pvalue)
			{
				if($sns_id == $pvalue['sns_id'])
				{
					$page = intval($pkey)+1;
				}
			}
			$sns_list = $Sns_BaseModel->getBaseSns($user_id, $page, 1, 'desc');
			$sns_type_row = array(7,8);
			if(!in_array($sns_list['items'][0]['sns_type'], $sns_type_row))
			{
				$sns_list['items'][0]['sns_content'] = base64_decode($sns_list['items'][0]['sns_content']);
			}
			else
			{
				$link =  current($this->RegExp($sns_list['items'][0]['sns_content']));
				$sns_list['items'][0]['new_title'] = "title";
				$sns_list['items'][0]['new_h5'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.$link;
			}
			if($sns_list['items'][0]['sns_forward'])
			{
				$forward = $Sns_ForwardModel->getForwardByFid($sns_list['items'][0]['sns_id']);
				$forward = current($forward);
				$forword_sns = $Sns_BaseModel->getBaseById($forward['sns_id']);
				$forword_sns = current($forword_sns);
				$sns_type_row = array(7,8);
				if(!in_array($forword_sns['sns_type'], $sns_type_row))
				{
					$forword_sns['sns_content'] = base64_decode($forword_sns['sns_content']);
				}
				//判断转发的用户是否是自己的好友
				$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $forword_sns['user_id']);
				if($friend_status)
				{
					$forword_sns['is_friend'] = 1;
				}
				else
				{
					$forword_sns['is_friend'] = 0;
				}

				$source_sns = $Sns_BaseModel->getBaseById($forward['source_sns_id']);
				$source_sns = current($source_sns);
				$sns_type_row = array(7,8);
				if(!in_array($source_sns['sns_type'], $sns_type_row))
				{
					$source_sns['sns_content'] = base64_decode($source_sns['sns_content']);
				}
				$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $source_sns['user_id']);
				if($friend_status)
				{
					$source_sns['is_friend'] = 1;
				}
				else
				{
					$source_sns['is_friend'] = 0;
				}
				if($source_sns['sns_img'])
				{
					$source_sns['img'] = array_filter(explode(',', $source_sns['sns_img']));
				}else
				{
					$source_sns['img'] = array();
				}
				if($source_sns['sns_lable'])
				{
					$source_sns['lable'] = array_filter(explode(',', $source_sns['sns_lable']));
				}else
				{
					$source_sns['lable'] = array();
				}
				foreach($forword_sns['user_info']['user_sns'] as $sfkey=>$sfvalue)
				{
					$sns_type_row = array(7,8);
					if(!in_array($forword_sns['user_info']['user_sns'][$sfkey]['sns_type'], $sns_type_row))
					{
						$forword_sns['user_info']['user_sns'][$sfkey]['sns_content'] = base64_decode($forword_sns['user_info']['user_sns'][$sfkey]['sns_content']);
					}
				}
				foreach($source_sns['user_info']['user_sns'] as $sskey=>$ssvalue)
				{
					$sns_type_row = array(7,8);
					if(!in_array($source_sns['user_info']['user_sns'][$sskey]['sns_type'], $sns_type_row))
					{
						$source_sns['user_info']['user_sns'][$sskey]['sns_content'] = base64_decode($source_sns['user_info']['user_sns'][$sskey]['sns_content']);
					}
				}
				$sns_list['items'][0]['sns']['forword_sns'] = $forword_sns;
				$sns_list['items'][0]['sns']['source_sns'] = $source_sns;

			}else
			{
				$sns_list['items'][0]['sns']['forword_sns'] = array();
				$sns_list['items'][0]['sns']['source_sns'] = array();
			}
			$user_friend_status = $User_FriendModel->getUserFriendIdById($user_friend_id, $user_id);
			if($user_friend_status)
			{
				$sns_list['items'][0]['user_friend_status'] = 1;
			}
			else
			{
				$sns_list['items'][0]['user_friend_status'] = 0;
			}

			//点赞人数
			$sns_like_user = $sns_list['items'][0]['sns_like_user'];
			$like_user_row = explode(',', $sns_like_user);
			if(in_array($user_friend_id, $like_user_row))
			{
				$sns_list['items'][0]['islike'] = 1;
			}
			else
			{
				$sns_list['items'][0]['islike'] = 0;
			}

			$sns_user_collection = $Sns_CollectionModel->getCollectBySidUid($sns_list['items'][0]['sns_id'], $user_friend_id);
			if($sns_user_collection)
			{
				$sns_list['items'][0]['sns_user_collection'] = 1;
			}
			else
			{
				$sns_list['items'][0]['sns_user_collection'] = 0;
			}
		}
		else
		{
			$sns_list = $Sns_BaseModel->getBaseSns($user_id, $page, 1, 'desc');
			$sns_type_row = array(7,8);
			if(!in_array($sns_list['items'][0]['sns_type'], $sns_type_row))
			{
				$sns_list['items'][0]['sns_content'] = base64_decode($sns_list['items'][0]['sns_content']);
			}
			else
			{
				$sns_list['items'][0]['new_content'] = $this->RegExp($sns_list['items'][0]['sns_content']);
			}
			if($sns_list['items'][0]['sns_forward'])
			{
				$forward = $Sns_ForwardModel->getForwardByFid($sns_list['items'][0]['sns_id']);
				$forward = current($forward);
				$forword_sns = $Sns_BaseModel->getBaseById($forward['sns_id']);
				$forword_sns = current($forword_sns);
				$sns_type_row = array(7,8);
				if(!in_array($forword_sns['sns_type'], $sns_type_row))
				{
					$forword_sns['sns_content'] = base64_decode($forword_sns['sns_content']);
				}
				//判断转发的用户是否是自己的好友
				$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $forword_sns['user_id']);
				if($friend_status)
				{
					$forword_sns['is_friend'] = 1;
				}
				else
				{
					$forword_sns['is_friend'] = 0;
				}
				$source_sns = $Sns_BaseModel->getBaseById($forward['source_sns_id']);
				$source_sns = current($source_sns);
				$sns_type_row = array(7,8);
				if(!in_array($source_sns['sns_type'], $sns_type_row))
				{
					$source_sns['sns_content'] = base64_decode($source_sns['sns_content']);
				}
				$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $source_sns['user_id']);
				if($friend_status)
				{
					$source_sns['is_friend'] = 1;
				}
				else
				{
					$source_sns['is_friend'] = 0;
				}
				if($source_sns['sns_img'])
				{
					$source_sns['img'] = array_filter(explode(',', $source_sns['sns_img']));
				}else
				{
					$source_sns['img'] = array();
				}
				if($source_sns['sns_lable'])
				{
					$source_sns['lable'] = array_filter(explode(',', $source_sns['sns_lable']));
				}else
				{
					$source_sns['lable'] = array();
				}
				foreach($forword_sns['user_info']['user_sns'] as $sfkey=>$sfvalue)
				{
					$sns_type_row = array(7,8);
					if(!in_array($forword_sns['user_info']['user_sns'][$sfkey]['sns_type'], $sns_type_row))
					{
						$forword_sns['user_info']['user_sns'][$sfkey]['sns_content'] = base64_decode($forword_sns['user_info']['user_sns'][$sfkey]['sns_content']);
					}
				}
				foreach($source_sns['user_info']['user_sns'] as $sskey=>$ssvalue)
				{
					$sns_type_row = array(7,8);
					if(!in_array($source_sns['user_info']['user_sns'][$sskey]['sns_type'], $sns_type_row))
					{
						$source_sns['user_info']['user_sns'][$sskey]['sns_content'] = base64_decode($source_sns['user_info']['user_sns'][$sskey]['sns_content']);
					}
				}
				$sns_list['items'][0]['sns']['forword_sns'] = $forword_sns;
				$sns_list['items'][0]['sns']['source_sns'] = $source_sns;

			}else
			{
				$sns_list['items'][0]['sns']['forword_sns'] = array();
				$sns_list['items'][0]['sns']['source_sns'] = array();
			}
			$user_friend_status = $User_FriendModel->getUserFriendIdById($user_friend_id, $user_id);
			if($user_friend_status)
			{
				$sns_list['items'][0]['user_friend_status'] = 1;
			}
			else
			{
				$sns_list['items'][0]['user_friend_status'] = 0;
			}

			//点赞人数
			$sns_like_user = $sns_list['items'][0]['sns_like_user'];
			$like_user_row = explode(',', $sns_like_user);
			if(in_array($user_friend_id, $like_user_row))
			{
				$sns_list['items'][0]['islike'] = 1;
			}
			else
			{
				$sns_list['items'][0]['islike'] = 0;
			}

			$sns_user_collection = $Sns_CollectionModel->getCollectBySidUid($sns_list['items'][0]['sns_id'], $user_friend_id);

			if($sns_user_collection)
			{
				$sns_list['items'][0]['sns_user_collection'] = 1;
			}
			else
			{
				$sns_list['items'][0]['sns_user_collection'] = 0;
			}

		}
		if($sns_list['total'] > 0)
		{
			foreach($sns_list['items'] as $key=>$value)
			{
				if($value['sns_lable'])
				{
					$sns_list['items'][$key]['sns_lable'] = array_filter(explode(',',$value['sns_lable']));
				}
				else
				{
					$sns_list['items'][$key]['sns_lable'] = array();
				}
				if($value['sns_img'])
				{
					$sns_list['items'][$key]['sns_img'] = array_filter(explode(',',$value['sns_img']));
				}
				else
				{
					$sns_list['items'][$key]['sns_img'] = array();
				}
				if($value['sns_like_user'])
				{
					$sns_list['items'][$key]['sns_like_user'] = explode(',',$value['sns_like_user']);
				}
				else
				{
					$sns_list['items'][$key]['sns_like_user'] = array();
				}
				if($value['sns_like_user_addtime'])
				{
					$sns_list['items'][$key]['sns_like_user_addtime'] = explode(',',$value['sns_like_user_addtime']);
				}
				else
				{
					$sns_list['items'][$key]['sns_like_user_addtime'] = array();
				}
				$comment_list = $Sns_CommentModel->getCommentBySid($value['sns_id']);	//取出评论的全部数据
				$collection_user = $Sns_CollectionModel->getCollectBySid($value['sns_id']);	//取出全部收藏用户id
				$forward_list = $Sns_ForwardModel->getForwardrow($value['sns_id']);
				$forward_list = array_values($forward_list);
				//取出个人主页用户的头像和签名
				$sns_user_info = current($User_InfoModel->getInfo($value['user_name']));
				$sns_date[$key] = date('Y-m-d', $sns_list['items'][$key]['sns_create_time']);
				$sns_list['items'][$key]['month'] = (int)substr($sns_date[$key], 5, 2);//取得月份
				$sns_list['items'][$key]['day'] = (int)substr($sns_date[$key], 8, 2);//取得几号
				$sns_list['items'][$key]['user_avatar'] = $sns_user_info['user_avatar'];
				$sns_list['items'][$key]['user_sign'] = $sns_user_info['user_sign'];

				$sns_list['items'][$key]['hot_count'] = $value['sns_copy_count'] + $value['sns_like_count'] + $value['sns_collection'];
			}

			if(!$sns_lable)
			{
				//将每个收藏用户的信息整合在一起
				if ($collection_user) {
					foreach ($collection_user as $col_key => $col_value) {
						$collection_user_base[] = current($User_BaseModel->getUser($col_value['user_id']));    //user_base表中取出收藏用户数据
						$sns_list['items'][$key]['collection'][$col_key]['addtime'] = $col_value['addtime'];
						$sns_list['items'][$key]['collection'][$col_key]['user_id'] = $col_value['user_id'];
						$collection_user_ids[] = $col_value['user_id'];
					}

					foreach ($collection_user_base as $ccol_key => $ccol_value) {
						$collection_user_info[] = current($User_InfoModel->getInfo($ccol_value['user_account']));    //user_info表中取出收藏用户数据
					}
					foreach ($collection_user_info as $cccol_key => $cccol_value) {
						$sns_list['items'][$key]['collection'][$cccol_key]['user_name'] = $cccol_value['user_name'];
						$sns_list['items'][$key]['collection'][$cccol_key]['user_avatar'] = $cccol_value['user_avatar'];
					}
					foreach ($sns_list['items'][$key]['collection'] as $ccccol_key => $ccccol_value) {
						$ccccol_value['status'] = 'collection';
						$ccccol_value['user_id'] = $collection_user_ids[$ccccol_key];
						if($ccccol_value)
						{
							$sns_list['items'][$key]['mix'][] = $ccccol_value;
						}
						else
						{
							$sns_list['items'][$key]['mix'][] = array();
						}
					}
				}
				else
				{
					$collection_user = array();
				}
			}
			if(!$sns_lable)
			{
				//将每个评论用户的信息整合在一起
				if ($comment_list) {
					$comment_list = array_values($comment_list);
					foreach ($comment_list as $com_key => $com_value) {
						$comment_user_info = current($User_InfoModel->getInfo($com_value['user_name']));
						$comment_list[$com_key]['addtime'] = $com_value['commect_addtime'];
						$comment_list[$com_key]['user_avatar'] = $comment_user_info['user_avatar'];
						$comment_user_ids[] = $com_value['user_id'];
					}

					$sns_list['items'][$key]['comment'] = $comment_list;
				}
				else
				{
					$comment_list = array();
				}
			}
			if(!$sns_lable)
			{
				//将点赞的用户信息整合到一起
				if ($sns_list['items'][$key]['sns_like_user']) {
					foreach ($sns_list['items'][$key]['sns_like_user'] as $lkey => $lvalue) {
						if ($lvalue !== '') {
							$like_user_base[] = current($User_BaseModel->getUser($lvalue));
							$like_user_time[] = $sns_list['items'][$key]['sns_like_user_addtime'][$lkey - 1];
						}
					}

					foreach ($like_user_base as $llkey => $llvalue) {
						$like_user_info[] = current($User_InfoModel->getInfo($llvalue['user_account']));
						$like_user_name[] = $llvalue['user_account'];
					}

					foreach ($like_user_info as $lllkey => $lllvalue) {
						$like_user_thumb[] = $lllvalue['user_avatar'];
					}
					foreach ($like_user_time as $llllkey => $llllvalue) {
						$like_data[$llllkey]['addtime'] = $llllvalue;
						$like_data[$llllkey]['user_name'] = $like_user_name[$llllkey];
						$like_data[$llllkey]['user_avatar'] = $like_user_thumb[$llllkey];
						$like_data[$llllkey]['status'] = 'like';
						$like_data[$llllkey]['user_id'] = $like_user_base[$llllkey]['user_id'];
					}

					foreach ($like_data as $ldkey => $ldvalue) {
						if($ldvalue)
						{
							$sns_list['items'][$key]['mix'][] = $ldvalue;
						}
						else
						{
							$sns_list['items'][$key]['mix'][] = array();
						}
					}
				}
				else
				{
					$sns_list['items'][$key]['sns_like_user'] = array();
				}
			}
			if(!$sns_lable)
			{
				//将每个转发用户的信息整合在一起
				if ($forward_list) {
					foreach ($forward_list as $fkey => $fvalue) {
						$forward_time[] = $fvalue['addtime'];
						$forward_sns_data[] = current($Sns_BaseModel->getBase($fvalue['forward_sns_id']));
					}
					$forward_user_id = array_column($forward_sns_data, 'user_id');
					$forward_user_id  = array_unique($forward_user_id);

					foreach ($forward_sns_data as $ffkey => $ffvalue) {
						if($forward_user_id[$ffkey] == $ffvalue['user_id'])
						{
							$forward_name[] = $ffvalue['user_name'];
							$forward_user_info[] = current($User_InfoModel->getInfo($ffvalue['user_name']));
							$forward_user_ids[] = $ffvalue['user_id'];
						}
					}
					foreach ($forward_user_info as $fffkey => $fffvalue) {
						$forward_thumb[] = $fffvalue['user_avatar'];
					}
					foreach ($forward_thumb as $ffffkey => $ffffvalue) {
						$forward_data[$ffffkey]['addtime'] = $forward_time[$ffffkey];
						$forward_data[$ffffkey]['user_name'] = $forward_name[$ffffkey];
						$forward_data[$ffffkey]['user_avatar'] = $ffffvalue;
						$forward_data[$ffffkey]['status'] = 'transmit';
						$forward_data[$ffffkey]['user_id'] = $forward_user_ids[$ffffkey];
					}
					foreach ($forward_data as $fdkey => $fdvalue) {
						if($fdvalue)
						{
							$sns_list['items'][$key]['mix'][] = $fdvalue;
						}
						else
						{
							$sns_list['items'][$key]['mix'][] = array();
						}
					}
				}
				else
				{
					$forward_list = array();
				}
			}
			//如果是按照标签搜索，则不显示预览的帖子图片
			if(!$sns_lable)
			{
				//算出有图片帖子的总数，然后判断当前帖子后面有图片帖子的数目
				$sns_id_row = $Sns_BaseModel->getBaseSnsImgTotal($user_id, 1);
				//点击图标进入个人主页时候取出最新一篇帖子的ID
				if(!$sns_id)
				{
					$sns_data_row = $Sns_BaseModel->getBaseSns($user_id, $page, 1, 'desc');
					$sns_id = $sns_data_row['items'][0]['sns_id'];
					$limit = 4;
					//判断最新一篇帖子是不是有图片，为了取预览图片时判断起始页数
					if(in_array($sns_id, $sns_id_row))
					{
						$page_status = 1;
						$next_sns_data = $Sns_BaseModel->getBaseSnsNext($user_id, $page, 'desc', $page_status, $limit);
					}
					else
					{
						$page_status = 2;
						$next_sns_data = $Sns_BaseModel->getBaseSnsNext($user_id, $page, 'desc', $page_status, $limit);
					}
				}
				else if($sns_id && $img_status)
				{
					$total_img_count = count($sns_id_row);
					$total_key = current(array_keys($sns_id_row, $sns_id));
					$page = $total_key+1;
					$page_surplus = $total_img_count - $total_key;
					if($page_surplus < 4)
					{
						$limit = $page_surplus;
					}
					else
					{
						$limit = 4;
					}
					//判断最新一篇帖子是不是有图片，为了取预览图片时判断起始页数
					if(in_array($sns_id, $sns_id_row))
					{
						$page_status = 1;
						$next_sns_data = $Sns_BaseModel->getBaseSnsNext($user_id, $page, 'desc', $page_status, $limit);
					}
					else
					{
						$page_status = 2;
						$next_sns_data = $Sns_BaseModel->getBaseSnsNext($user_id, $page, 'desc', $page_status, $limit);
					}
				}
				elseif($search_sns_id ||$text_sns_id ||$reply_sns_id ||$detail_sns_id)
				{
					//点击上一页和下一页显示预览图片,找到要显示的帖子ID
					$sns_id_total = $Sns_BaseModel->getBaseSnsImgTotal($user_id, $page=1, 'desc', $sns_id);fb($sns_id_total);
					$total_img_count = count($sns_id_row);
					$total_key = current(array_keys($sns_id_row, $sns_id));
					$page = $total_key+1;
					$page_surplus = $total_img_count - $total_key;
					if($page_surplus < 4)
					{
						$limit = $page_surplus;
					}
					else
					{
						$limit = 4;
					}
					//判断最新一篇帖子是不是有图片，为了取预览图片时判断起始页数
					if(in_array($sns_id, $sns_id_row))
					{
						$page_status = 1;
						$next_sns_data = $Sns_BaseModel->getBaseSnsNext($user_id, $page, 'desc', $page_status, $limit);
					}
					else
					{
						$page_status = 2;
						$next_sns_data = $Sns_BaseModel->getBaseSnsNext($user_id, $page, 'desc', $page_status, $limit);
					}
				}
				else
				{
					//点击上一页和下一页显示预览图片,找到要显示的帖子ID
					$sns_id_total = $Sns_BaseModel->getBaseSnsImgTotal($user_id, $page=1, 'desc', $sns_id);
					foreach($sns_id_total as $tkey=>$tvalue)
					{
						if($sns_id == $tvalue && $pre_tip)
						{
							if($tkey == 0)
							{
								$sns_id_next = 0;
							}
							else
							{
								$sns_id_next = $sns_id_total[$tkey-1];
							}
						}
						elseif($sns_id == $tvalue)
						{
							$sns_id_next = $sns_id_total[$tkey+1];
						}
					}

					$total_key = current(array_keys($sns_id_row, $sns_id_next));
					$page_btn = 0;
					$total_img_count = count($sns_id_row);
					if(!$total_key)
					{
						$sns_img_id_total = $Sns_BaseModel->getBaseSnsImgTotal($user_id, $page=1);
						foreach($sns_img_id_total as $sikey=>$sivalue)
						{
							$diff = $sns_id_next - $sivalue;
							if($diff>0)
							{
								$total_key = $sikey;
								break;
							}
						}
					}
					$page_surplus = $total_img_count - $total_key;
					if($page_surplus < 4)
					{
						$limit = $page_surplus;
					}
					else
					{
						$limit = 4;
					}
					fb($sns_id_next);
					//判断最新一篇帖子是不是有图片，为了取预览图片时判断起始页数
					if(in_array($sns_id_next, $sns_id_row))
					{
						$page_status = 1;
						$next_sns_data = $Sns_BaseModel->getBaseSnsNext($user_id, $page_btn, 'desc', $page_status, $limit, $sns_id_next);
					}
					else
					{
						$page_status = 2;
						$next_sns_data = $Sns_BaseModel->getBaseSnsNext($user_id, $page_btn, 'desc', $page_status, $limit, $sns_id_next);
					}
				}
				if($next_sns_data)
				{
					foreach($next_sns_data as $nkey=>$nvalue)
					{
						$next_data[$nkey]['sns_img'] = $nvalue['sns_img'][0];
						$next_data[$nkey]['sns_id'] = current($nvalue['sns_id']);
					}
					foreach($next_data as $ndkey=>$ndvalue)
					{
						$sns_list['items'][$key]['sns_preview_images'][] = $ndvalue;
					}
				}
				else
				{
					$next_sns_data = array();
				}
			}
			if(!$sns_lable)
			{
				if($sns_list['items'][$key]['mix'])
				{
					foreach ($sns_list['items'][$key]['mix'] as $mkey => $mvalue) {
						$sns_list['items'][$key]['mix'][$mkey]['addtime'] = date("Y-m-d H:i:s", $mvalue['addtime']);
					}
					$datetime = array();
					foreach ($sns_list['items'][$key]['mix'] as $user) {
						$datetime[] = $user['addtime'];
					}
					array_multisort($datetime, SORT_DESC, $mix);
				}
				else
				{
					$sns_list['items'][$key]['mix'] = array();
				}
			}
			$msg = 'success';
			$status = 200;
		}
		else
		{
			$img_user_data = array();
			$user_base = current($User_BaseModel->getUser($user_id));
			$user_info = current($User_InfoModel->getInfo($user_base['user_account']));
			$img_user_data['user_name'] = $user_base['user_account'];
			$img_user_data['user_id'] = $user_id;
			$img_user_data['user_avatar'] = $user_info['user_avatar'];
			$img_user_data['user_sign'] = $user_info['user_sign'];
			if($img_user_data)
			{
				$msg = 'success';
				$status = 200;
			}
			else
			{
				$msg = 'failure';
				$status = 250;
			}
			$sns_list = $img_user_data;

		}

		fb($sns_list);
		$this->data->addBody(-140, $sns_list, $msg, $status);
	}

	/**
	 * 发布转发的帖子 2016.11.27
	 */
	public function publishTransmit()
	{
		$sns_id = request_string('sns_id');
		$user_id = request_int('user_id');
		$user_name = request_string('user_account');

		$sns_img = request_string('sns_img');
		$sns_lable = request_string('sns_lable');
		$sns_title = request_string('sns_title');
		$sns_content = request_string('sns_content');
		$sns_type = request_int('sns_type');
		$sns_status = request_int('sns_status',0);
		$sns_privacy = request_int('sns_privacy',1);
		$sns_forward = 1;
		//将帖子内容编码
		$sns_type_row = array(7,8);
		if(!in_array($sns_type, $sns_type_row))
		{
			$sns_content = base64_encode($sns_content);
		}
		if($sns_type == 7)
		{
			$sns_content = $this->str_check($sns_content);
		}
		$data = array();

		$Sns_BaseModel = new Sns_BaseModel();
		$sns_data = current($Sns_BaseModel->getBase($sns_id));
		$sns_user_id = $sns_data['user_id'];
		if (!empty($sns_img)) {
			$sns_img = implode(',', $sns_img);
		}
		if (!empty($sns_lable)) {
			$sns_lable = implode(',', $sns_lable);
		}

		$field = array(
			'user_id' => $user_id,
			'user_name' => $user_name,
			'sns_title' => $sns_title,
			'sns_content' => $sns_content,
			'sns_img' => $sns_img,
			'sns_type' => $sns_type,
			'sns_create_time' => time(),
			'sns_status' => $sns_status,
			'sns_privacy' => $sns_privacy,
			'sns_lable' => $sns_lable,
			'sns_forward' => $sns_forward,
		);
		Yf_Log::log('$user_id : ' . $user_id, Yf_Log::INFO, 'publish');
		Yf_Log::log('$_FILES : ' . json_encode($_FILES), Yf_Log::INFO, 'publish');
		Yf_Log::log('$_REQUEST : ' . json_encode($_REQUEST), Yf_Log::INFO, 'publish');

		$da = $Sns_BaseModel->addBase($field, true);
		if ($da) {
			$Sns_ForwardModel = new Sns_ForwardModel();
			$Sns_TimelineModel = new Sns_TimelineModel();
			$User_BaseModel = new User_BaseModel();
			$User_FriendModel = new User_FriendModel();
			$User_InfoModel = new User_InfoModel();
			//转发成功将转发的帖子sns_copy_count加1
			$sns_data = current($Sns_BaseModel->getBase($sns_id));
			$sns_copy_count = $sns_data['sns_copy_count'];
			$fields['sns_copy_count'] = $sns_copy_count + 1;
			$flag = $Sns_BaseModel->editBase($sns_id,$fields);

			{
				//查找出所有用户id
				$user_id_row = $User_BaseModel->getUser('*');
				$user_id_row = array_keys($user_id_row);
			}
			if ($sns_privacy == 1) //好友可见
			{
				//查出所有好友id
				$user_friend_rows = array();
				$user_friend_rows = $User_FriendModel->getUserUserIdByFriendId($user_id);
				$user_id_row = array_filter_key('user_id', $user_friend_rows);
				$flagl = in_array($user_id, $user_id_row);
				if (!$flagl) {
					array_unshift($user_id_row, $user_id);
				}
			}
			if ($sns_privacy == 2) //仅自己可见
			{
				$user_id_row[] = $user_id;
			}
			$time = date('Y-m-d H:i:s', time());
			$rows = current($Sns_ForwardModel->getForwardByFid($sns_id));
			if(!$rows)
			{
				$source_sns_id = $sns_id;
			}
			else
			{
				$source_sns_id = $rows['source_sns_id'];
			}

			$forward_fileds = array(
				'sns_id' => $sns_id,
				'forward_sns_id' => $da,
				'source_sns_id' => $source_sns_id,
				'addtime' => time(),
			);
			$Sns_ForwardModel->addForward($forward_fileds, true);
			foreach ($user_id_row as $key => $value) {
				$file = array();
				$file = array(
					'user_id' => $value,
					'sns_id' => $da,
					'action_time' => $time,
				);
				$Sns_TimelineModel->addTimeline($file);
			}
			$flag = 1;
		} else {
			$flag = 0;
		}

		if ($flag) {
			$msg = 'success';
			$status = 200;
		} else {
			$msg = 'failure';
			$status = 250;
		}
		$data[] = $da;
		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 * 根据用户名或电子邮件地址来查询用户	2016.11.30
	 * @author houpeng
	 * @access public
	 */
	public function searchFriend()
	{
		$pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
		$input_word = request_string('input_word');
		$user_id = request_string('user_id');
		$User_BaseModel = new User_BaseModel();
		$Sns_BaseModel = new Sns_BaseModel();
		$User_FriendModel = new User_FriendModel();
		$User_InfoModel = new User_InfoModel();
		if(preg_match($pattern, $input_word))
		{
			$user_data = $User_BaseModel->searchUser($input_word, 2);
		}
		else
		{
			$user_data = $User_BaseModel->searchUser($input_word, 1);
		}
		$user_data = array_values($user_data);
		$data = array();
		if($user_data)
		{
			foreach($user_data as $key=>$value)
			{
				$data[$key]['user_id'] = $value['user_id'];
				$data[$key]['user_name'] = $value['user_account'];
				$data[$key]['friend_id'] = $value['user_id'];
				$user_info[$key] = current($User_InfoModel->getInfo($value['user_account']));
				$friend_status = $User_FriendModel->friend($user_id, $value['user_id']);
				if($friend_status)
				{
					$data[$key]['friend_status'] = 1;
				}
				else
				{
					$data[$key]['friend_status'] = 0;
				}
				$sns_base = current($Sns_BaseModel->getLastBaseByUserId($value['user_id']));
				if($sns_base)
				{
					$now_time = time();
					$action_time = $sns_base['sns_create_time'];
					if($now_time != $action_time)
					{
						$gap_time = ceil(($now_time - $action_time)/24/60/60);
						if($gap_time>7 && $gap_time<30)
						{
							$gap_time = '1周';
						}
						elseif($gap_time>30 && $gap_time<365)
						{
							$gap_time = '1月';
						}
						elseif($gap_time<7)
						{
							$gap_time = $gap_time.'天';
						}
					}
					$data[$key]['gap_time'] = $gap_time;
				}
				else
				{
					$data[$key]['gap_time'] = '';
				}
			}
			foreach($user_info as $k=>$v)
			{
				$data[$k]['user_avatar'] = $v['user_avatar'];
			}
			//echo '<pre>';print_r($data);exit;
			$status = 200;
			$msg = 'success';
		}
		else
		{
			$status = 250;
			$msg = 'failure';
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}

	//音乐视频帖网址正则匹配 hp
	public function RegExp($str)
	{
		$pattern  = '/[a-zA-z]+:\/\/[^\s]*/';
		preg_match($pattern, $str, $result);
		return $result;
	}

//转发网易云音乐帖的时候修改传入网址 hp
	public function str_check($str)
	{
		$needle = 'music.163.com';
		$pos = strpos($str, $needle);
		$substr = 'http:';
		if($pos)
		{
			$new_str = $this->str_insert($str, $pos-2, $substr);
		}
		else
		{
			$new_str = $str;
		}
		return $new_str;
	}

	//转发网易云音乐帖的时候修改传入网址 hp
	public function str_insert($str, $i, $substr)
	{
		$startstr = '';
		$laststr = '';
		for($j=0; $j<$i; $j++){
			$startstr .= $str[$j];
		}
		for ($j=$i; $j<strlen($str); $j++){
			$laststr .= $str[$j];
		}
		$str = ($startstr . $substr . $laststr);
		return $str;
	}



}
?>
