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
class SnsCtl extends Yf_AppController
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
		$friend_id = request_int('u');
		$user_id = request_int('user_id');
		$sns_id = request_int('sns_id');
		$user_name = request_string('user_account');
		$kindType = request_int('sns_kind');
		$page = request_int('page',1);
		$rows = request_int('rows',10);
		
		fb($user_id);
		fb($user_name);

		$Sns_BaseModel = new Sns_BaseModel();
		$Sns_TimelineModel = new Sns_TimelineModel();
		$Sns_CommentModel = new Sns_CommentModel();
		$Sns_ForwardModel = new Sns_ForwardModel();
		$Sns_CollectionModel = new Sns_CollectionModel();
		$User_FriendModel = new User_FriendModel();
		//这里的判断是为了personal-homepage请求数据
		if($kindType)
		{
			if($sns_id)
			{
				$data['items'][]['sns_id'] = $sns_id;
				$data['page'] = $page;
				$data['total'] = 1;
			}
			else
			{
				$data = $Sns_BaseModel->getBaseSns($user_id, $page, $rows);
				foreach($data['items'] as $dkey=>$dvalue)
				{
					$sns_ids[$dkey]['sns_id'] = $dvalue['sns_id'];
				}
				$data['items'] = array();
				$data['items'] = $sns_ids;
			}
		}
		else
		{
			$data = $Sns_TimelineModel->getTimelineList($user_id,$page,$rows);
		}
		$items = $data['items'];
		fb($items);

		foreach ($items as $key => $value)
		{
			//内容
			$sns = $Sns_BaseModel->getOne($value['sns_id']);
			//将帖子内容转码
			$sns_type_row = array(7,8);
			if(!in_array($sns['sns_type'], $sns_type_row))
			{
				$sns['sns_content'] = base64_decode($sns['sns_content']);
			}
			else
			{
				$link =  current($this->RegExp($sns['sns_content']));
				$sns['new_title'] = "title";
				$sns['new_h5'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.$link;
			}
			$items[$key]['sns'] = $sns;
			$sns_user_name = $sns['user_name'];
			$sns_user_id = $sns['user_id'];
			if($sns['sns_img'])
			{
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

				$sns_type_row = array(7,8);
				if(!in_array($forword_sns['sns_type'], $sns_type_row))
				{
					$forword_sns['sns_content'] = base64_decode($forword_sns['sns_content']);
				}
				foreach($forword_sns['user_info']['user_sns'] as $ukey=>$uvalue)
				{
					$sns_type_row = array(7,8);
					if(!in_array($forword_sns['user_info']['user_sns'][$ukey]['sns_type'], $sns_type_row))
					{
						$forword_sns['user_info']['user_sns'][$ukey]['sns_content'] = base64_decode($forword_sns['user_info']['user_sns'][$ukey]['sns_content']);
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
				$sns_type_row = array(7,8);
				if(!in_array($source_sns['sns_type'], $sns_type_row))
				{
					$source_sns['sns_content'] = base64_decode($source_sns['sns_content']);
				}
				foreach($source_sns['user_info']['user_sns'] as $uukey=>$uuvalue)
				{
					$sns_type_row = array(7,8);
					if(!in_array($source_sns['user_info']['user_sns'][$uukey]['sns_type'], $sns_type_row))
					{
						$source_sns['user_info']['user_sns'][$uukey]['sns_content'] = base64_decode($source_sns['user_info']['user_sns'][$uukey]['sns_content']);
					}
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
				$items[$key]['sns']['forword_sns'] = $forword_sns;
				$items[$key]['sns']['source_sns'] = $source_sns;

			}else
			{
				$items[$key]['sns']['forword_sns'] = array();
				$items[$key]['sns']['source_sns'] = array();
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
			$friend_status = $User_FriendModel->getUserFriendIdById($friend_id, $user_id);

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
			if($sns_user_name)
			{
				$User_InfoModel = new User_InfoModel();
				$user_info_row = $User_InfoModel->getUserInfo($sns_user_name,$sns_user_id);
				foreach($user_info_row['user_sns'] as $uikey=>$uivalue)
				{
					$sns_type_row = array(7,8);
					if(!in_array($user_info_row['user_sns'][$uikey]['sns_type'], $sns_type_row))
					{
						$user_info_row['user_sns'][$uikey]['sns_content'] = base64_decode($user_info_row['user_sns'][$uikey]['sns_content']);
					}
				}
				$fid=$user_BaseModel->getUserIdByAccount($sns_user_name);
				$flag=$user_RnameModel->getRnameId($user_id,$fid[0]);
				if($flag){
					$datas=$user_RnameModel->getRname($flag);
					$user_info_row['rename']=$datas[$flag[0]]['content'];
				}else{
					$user_info_row['rename']='';
				}
				$items[$key]['sns_user'] = $user_info_row;
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

			$items[$key]['like_user_name'] = $like_user_name;
			//获取评论
			$comment = array_values($Sns_CommentModel->getCommentBySid($value['sns_id']));
			//将评论内容转码
			foreach($comment as $comkey=>$comval)
			{
				$comment[$comkey]['commect_content'] = base64_decode($comment[$comkey]['commect_content']);
			}
			$items[$key]['comment'] = $comment;

			foreach($items[$key]['comment'] as $kkk=>$vvv)
			{
				$flag=$user_RnameModel->getRnameId($user_id,$vvv['user_id']);
				if($flag)
				{
					$datas=$user_RnameModel->getRname($flag);
					$items[$key]['comment'][$kkk]['rename']=$datas[$flag[0]]['content'];
				}else{
					$items[$key]['comment'][$kkk]['rename']='';
				}
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
				}else
				{
					$items[$key]['comment'][$kkk]['to_rename']='';
				}
			}
		}

		$User_InfoModel = new User_InfoModel();
		$background=$User_InfoModel->getInfo($user_name);
		$data['background'] = $background[$user_name]['background'];
		$data['items'] = array_values($items);
		if(!$data['items'])
		{
			$data['items'][0]['sns_user'] = current($background);
			$data['items'][0]['sns'] = '';
			$data['items'][0]['user_id'] = $user_id;
		}

		$msg    = 'success';
		$status = 200;
		fb($data);
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 根据sns_id获取状态详情
	 *
	 * @access public
	 */
	public function getSnsBase()
	{
		$sns_id = request_int('sns_id');
		$user_id = request_int('user_id');

		$Sns_BaseModel = new Sns_BaseModel();
		$Sns_ForwardModel = new Sns_ForwardModel();
		$Sns_CollectionModel = new Sns_CollectionModel();
		$User_FriendModel = new User_FriendModel();

		$Sns_CommentModel = new Sns_CommentModel();
		$User_RnameModel=new User_RnameModel();
		$User_BaseModel=new User_BaseModel();
		$User_InfoModel = new User_InfoModel();

		//内容
		$sns_base = $Sns_BaseModel->getOne($sns_id);
		$sns_type_row = array(7,8);
		if(!in_array($sns_base['sns_type'], $sns_type_row))
		{
			$sns_base['sns_content'] = base64_decode($sns_base['sns_content']);
		}
		else
		{
			$link =  current($this->RegExp($sns_base['sns_content']));
			$sns_base['new_title'] = "title";
			$sns_base['new_h5'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.$link;
		}
		$sns_user_name = $sns_base['user_name'];
		$sns_user_id = $sns_base['user_id'];

		if($sns_base['sns_img'])
		{
			$sns_base['sns_img'] = str_replace("amp;", "", $sns_base['sns_img']);
			$sns_base['img_row'] = array_filter(explode(',', $sns_base['sns_img']));
		}else
		{
			$sns_base['img_row'] = array();
		}

		if($sns_base['sns_lable'])
		{
			$sns_base['lable'] = array_filter(explode(',', $sns_base['sns_lable']));
		}else
		{
			$sns_base['lable'] = array();
		}

		//计算帖子的热度（转发，收藏，点赞）
		$sns_base['hot_count'] = $sns_base['sns_copy_count'] + $sns_base['sns_like_count'] + $sns_base['sns_collection'];

		//判断该帖子是否是转载贴，如果是查找出被抓的帖子和原贴的信息
		if($sns_base['sns_forward'])
		{
			$forward = $Sns_ForwardModel->getForwardByFid($sns_base['sns_id']);
			$forward = current($forward);
			$forword_sns = $Sns_BaseModel->getBaseById($forward['sns_id']);
			$forword_sns = current($forword_sns);
			//将帖子内容解码
			$sns_type_row = array(7,8);
			if(!in_array($forword_sns['sns_type'], $sns_type_row))
			{
				$forword_sns['sns_content'] = base64_decode($forword_sns['sns_content']);
			}
			foreach($forword_sns['user_info']['user_sns'] as $ukey=>$uvalue)
			{
				$sns_type_row = array(7,8);
				if(!in_array($forword_sns['user_info']['user_sns'][$ukey]['sns_type'], $sns_type_row))
				{
					$forword_sns['user_info']['user_sns'][$ukey]['sns_content'] = base64_decode($forword_sns['user_info']['user_sns'][$ukey]['sns_content']);
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

			if($forword_sns['sns_img'])
			{
				$forword_sns['img_row'] = array_filter(explode(',', $forword_sns['sns_img']));
			}else
			{
				$forword_sns['img_row'] = array();
			}

			if($forword_sns['sns_lable'])
			{
				$forword_sns['lable'] = array_filter(explode(',', $forword_sns['sns_lable']));
			}else
			{
				$forword_sns['lable'] = array();
			}

			$source_sns = $Sns_BaseModel->getBaseById($forward['source_sns_id']);
			$source_sns = current($source_sns);
			//将帖子内容解码
			$sns_type_row = array(7,8);
			if(!in_array($source_sns['sns_type'], $sns_type_row))
			{
				$source_sns['sns_content'] = base64_decode($source_sns['sns_content']);
			}
			foreach($source_sns['user_info']['user_sns'] as $uukey=>$uuvalue)
			{
				$sns_type_row = array(7,8);
				if(!in_array($source_sns['user_info']['user_sns'][$uukey]['sns_type'], $sns_type_row))
				{
					$source_sns['user_info']['user_sns'][$uukey]['sns_content'] = base64_decode($source_sns['user_info']['user_sns'][$uukey]['sns_content']);
				}
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

			$source_sns['hot_count'] = $source_sns['sns_copy_count'] + $source_sns['sns_like_count'] + $source_sns['sns_collection'];
			$sns_base['forword_sns'] = $forword_sns;
			$sns_base['source_sns'] = $source_sns;
			
		}else
		{
			$sns_base['forword_sns'] = array();
			$sns_base['source_sns'] = array();
		}

		//判断该帖子是否是用户的收藏贴
		$sns_user_collection = $Sns_CollectionModel->getCollectBySidUid($sns_id, $user_id);
		if($sns_user_collection)
		{
			$sns_base['is_collection'] = 1;
		}
		else
		{
			$sns_base['is_collection'] = 0;
		}

		//判断该帖的发帖者是否是用户的好友
		$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $sns_user_id);
		if($friend_status)
		{
			$sns_base['is_friend'] = 1;
		}
		else
		{
			$sns_base['is_friend'] = 0;
		}

		//发布动态者信息
		if($sns_user_name)
		{
			$user_info_row = $User_InfoModel->getUserInfo($sns_user_name,$sns_user_id);
			foreach($user_info_row['user_sns'] as $uikey=>$uivalue)
			{
				$sns_type_row = array(7,8);
				if(!in_array($user_info_row['user_sns'][$uikey]['sns_type'], $sns_type_row))
				{
					$user_info_row['user_sns'][$uikey]['sns_content'] = base64_decode($user_info_row['user_sns'][$uikey]['sns_content']);
				}
			}

			$fid=$User_BaseModel->getUserIdByAccount($sns_user_name);
			$flag=$User_RnameModel->getRnameId($user_id,$fid[0]);
			if($flag){
				$datas=$User_RnameModel->getRname($flag);
				$user_info_row['rename']=$datas[$flag[0]]['content'];
			}else{
				$user_info_row['rename']='';
			}
			$sns_base['sns_user'] = $user_info_row;
		}
		else
		{
			$sns_base['sns_user'] = array();
		}

		//点赞人数
		$sns_like_user = $sns_base['sns_like_user'];
		$like_user_row = explode(',', $sns_like_user);
		if(in_array($user_id,$like_user_row))
		{
			$sns_base['is_like'] = 1;
		}
		else
		{
			$sns_base['is_like'] = 0;
		}

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
					$fid=$User_BaseModel->getUserIdByAccount($user_info[$val]['user_account']);
					$flag=$User_RnameModel->getRnameId($user_id,$fid[0]);
					if($flag){
						$datas=$User_RnameModel->getRname($flag);
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

		$sns_base['like_user_name'] = $like_user_name;
		//获取评论
		$comment = array_values($Sns_CommentModel->getCommentBySid($sns_id));
		foreach($comment as $comkey=>$comval)
		{
			$comment[$comkey]['commect_content'] = base64_decode($comment[$comkey]['commect_content']);
		}
		$sns_base['comment'] = $comment;

		foreach($sns_base['comment'] as $kkk=>$vvv)
		{
			$flag=$User_RnameModel->getRnameId($user_id,$vvv['user_id']);
			if($flag)
			{
				$datas=$User_RnameModel->getRname($flag);
				$sns_base['comment'][$kkk]['rename']=$datas[$flag[0]]['content'];
			}else{
				$sns_base['comment'][$kkk]['rename']='';
			}
			if($vvv['to_commect_id'])
			{
				$fid=$User_BaseModel->getUserIdByAccount($vvv['to_commect_name']);
				$flag=$User_RnameModel->getRnameId($user_id,$fid[0]);
				if($flag)
				{
					$datas=$User_RnameModel->getRname($flag);
					$sns_base['comment'][$kkk]['to_rename']=$datas[$flag[0]]['content'];
				}else{
					$sns_base['comment'][$kkk]['to_rename']='';
				}
			}else
			{
				$sns_base['comment'][$kkk]['to_rename']='';
			}
		}

		fb($sns_base);
		$this->data->addBody(-140, $sns_base);

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
//		echo '<pre>';print_r($data);exit;
		$Sns_BaseModel = new Sns_BaseModel();

		$Sns_CommentModel = new Sns_CommentModel();

		foreach ($items as $key => $value) 
		{
			//内容
			$sns = array_values($Sns_BaseModel->getBase($value['sns_id']));
			$items[$key]['sns'] = $sns[0];
			$sns_type_row = array(7,8);
			if(!in_array($items[$key]['sns']['sns_type'], $sns_type_row))
			{
				$items[$key]['sns']['sns_content'] = base64_decode($items[$key]['sns']['sns_content']);
			}
			else
			{
				$link =  current($this->RegExp($items[$key]['sns']['sns_content']));
				$items[$key]['sns']['new_title'] = "title";
				$items[$key]['sns']['new_h5'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.$link;
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
			$comment = array_values($Sns_CommentModel->getCommentBySid($value['sns_id']));
			foreach($comment as $comkey=>$comval)
			{
				$comment[$comkey]['commect_content'] = base64_decode($comment[$comkey]['commect_content']);
			}

			foreach ($comment as $k => $v) 
			{
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
		//评论内容编码
		$commect_content = base64_encode($commect_content);
//		echo '<pre>';print_r($commect_content);exit;
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

		if($flags)
		{
			//在动态信息表中添加评论数
			if($commect_id == 0)
			{
				$Sns_BaseModel = new Sns_BaseModel();
				$flag = $Sns_BaseModel->addCommentCount($sns_id);
			}
			else
			{
				$flag = 1;
			}
		}
		else
		{
			$flag = 0;
		}


		//查找评论
		$comment_row = $Sns_CommentModel->getCommentBySid($sns_id);
		$User_BaseModel = new User_BaseModel();
		foreach ($comment_row as $key => $value) 
		{
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
		$date = array();
		$date = $comment_row;
		$this->data->addBody(-140, $date, $msg, $status);
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
					array_push($like_user_name,$like_info);
				}
			}
		}
		$like_user_name=array_values($like_user_name);
		// $like_user_name =  substr($like_user_name, 0, -1) ;
		$date = array();
		$date= $like_user_name;
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
		fb(Perm::$row['user_id']);
		$user_id = Perm::$row['user_id'];
		$user_name = Perm::$row['user_account'];

		$sns_title = request_string('sns_title');
		$sns_content = request_string('sns_content');
		//图片
		$sns_type = request_int('sns_type');
		$sns_status = request_int('sns_status',0);
		$sns_privacy = request_int('sns_privacy',1);
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
		// print_r($_FILES);die;
		$aa=json_encode($_FILES);
		$bb=implode('++++',$_FILES['img']);
		file_put_contents('./aa.txt', $aa);
		foreach($_FILES as $kk=>$vv){
			// $count=count($vv['name']);
			// $content=array();
			// for($i=0;$i<$count;$i++){
			// $content['name']=$vv['name'][$i];
			// $content['type']=$vv['type'][$i];
			// $content['tmp_name']=$vv['tmp_name'][$i];
			// $content['error']=$vv['error'][$i];
			// $content['size']=$vv['size'][$i];
			// $_FILES['img']=$content;
			// print_r($_FILES['img']);die;
			if ($vv)
			{
				//处理上传图片
				$upload = new HTTP_Upload('en');
				$files  = $upload->getFiles();
				// print_r($files);die;
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
							// print_r($path);die;
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
								// print_r($data['msg']);die;
							}
							else
							{
								$img = Yf_Registry::get('base_url')  . '/'. APP_DIR_NAME . $p .  $file->upload['name'];

								$sns_img[] = $img;
							}
							// $photo=$path.rand()
							// move_uploaded_file($_FILES["img"]['tmp_name'],$photo)
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
		fb($field);

		Yf_Log::log('$sns_img: ' . json_encode($sns_img), Yf_Log::INFO, 'publish');

		$sns_img_str = implode(',', $sns_img);
		$field['sns_img'] = $sns_img_str;

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
		$sns_content = base64_encode($sns_content);

		//图片
		$sns_img =  request_string('sns_img');
		$sns_type = request_int('sns_type',0);
		$sns_status = request_int('sns_status',0);
		$sns_privacy = request_int('sns_privacy',1);

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
		//$sns_id = request_int('sns_id');
		//$user_id = request_int('user_id',Perm::$row['user_id']);
		$sns_id = $_REQUEST['sns_id'];
		$user_id = $_REQUEST['user_id'];

		$Sns_CollectionModel = new Sns_CollectionModel();

		//按照sns_id 与 user_id 查找collect_id
		$id_row = $Sns_CollectionModel->getCollectBySidUid($sns_id,$user_id);
		fb($id_row);
		$flag = 0;
		if(empty($id_row))
		{
			$field = array(
						'sns_id' => $sns_id,
						'user_id' => $user_id,
						'collect_time' => time(),
						);

			$flag = $Sns_CollectionModel->addCollection($field);
		}else
		{
			$flag = 2;
		}

		if ($flag == 1 )
		{
			$msg    = 'success';
			$status = 200;
		}
		elseif($flag == 2)
		{
			$msg = '已收藏';
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
	 * 朋友圈API - 取消收藏
	 */
	public function delCollect()
	{
		$collect_id = request_int('collect_id');

		$Sns_CollectionModel = new Sns_CollectionModel();

		//按照sns_id 与 user_id 查找collect_id
		$flag = $Sns_CollectionModel->removeCollection($collect_id);

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
	 * 朋友圈API - 获取收藏列表
	 */
	public function getCollectList()
	{
		$user_id = request_int('user_id',Perm::$row['user_id']);
		$page = request_int('page',1);
		$rows = request_int('rows',10);
		
		$Sns_CollectionModel = new Sns_CollectionModel();
		$coll_row = $Sns_CollectionModel->getCollectionList($user_id,$page,$rows);
		$items = $coll_row['items'];
		
		$Sns_BaseModel = new Sns_BaseModel();

		$Sns_CommentModel = new Sns_CommentModel();

		
		foreach ($items as $key => $value) 
		{
			//内容
			$sns = array_values($Sns_BaseModel->getBase($value['sns_id']));

			if($sns)
			{
				$items[$key]['sns'] = $sns[0];
				$sns_user_name = $sns[0]['user_name'];
				$sns_type_row = array(7,8);
				if(!in_array($items[$key]['sns']['sns_type'], $sns_type_row))
				{
					$items[$key]['sns']['sns_content'] = base64_decode($items[$key]['sns']['sns_content']);
				}
				else
				{
					$items[$key]['sns']['new_content'] = $this->RegExp($items[$key]['sns']['sns_content']);
				}

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
		$coll_row['items'] = $items;
		$msg    = 'success';
		$status = 200;
		$this->data->addBody(-140, $coll_row, $msg, $status);
		fb($coll_row);
	}


	/**
	 * 获取好友的动态
	 *@param $id 好友id
	 */
	public function getSnsInfo()
	{
		$user_account=request_string('user_account');
		$user_BaseModel=new User_BaseModel();
		$id=$user_BaseModel->getUserIdByAccount($user_account);
		fb($id);
		$page=request_int('page',1);
		$rows=request_int('rows',20);
		$sns = new Sns_BaseModel();
		$id_row = $sns->getBaseByUserId($id[0]);
		$data = array();
		if($id_row){
			$data = $sns->getBaseByOrder($id_row,$page,$rows);
			foreach($data['items'] as $key=>$values){
				$time=$data['items'][$key]['sns_create_time'];
				$month=date('m',$time);
				$day=date('d',$time);
				$data['items'][$key]['month']=$month;
				$data['items'][$key]['day']=$day;
			}
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
	//修改好友的备注的备注
	public function getRname()
	{
		// $id=request_int('id');
		$id=Perm::$row['user_id'];
		$user_account=request_string('user_account');
		$user_BaseModel=new User_BaseModel();
		$fid_row=$user_BaseModel->getUserIdByAccount($user_account);
		$fid=$fid_row[0];
		$field_row['content']=request_string('content');
		$rname=new User_RnameModel();
		$id_row = $rname->getRnameId($id,$fid);
		//var_dump($id_row);die;
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


	public function changeUserBackground(){
		$user_id=Perm::$row['user_id'];
		$user_account=Perm::$row['user_account'];
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
			$user_Info=new User_InfoModel();
			$flag=$user_Info->editInfo($user_account,$user);
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
		foreach($user_row as $k=>$v){
			$data=$user_info->getInfo($v);
			if($data){
				//根据账户名获取 账户id
				$id_row=$User_BaseModel->getUserIdByAccount($v);
				//说明该用户已经注册,接着判断该用户是否在自己的好友列表里边
				$flag=$User_FriendModel->checkFriend($user_id,$id_row[0]);
				if($flag){
					//说明该用户已经是好友了
					$user[$v]['user_account']=$v;
					$user[$v]['status']=1;
					if($data[$v]['nickname']){
						$user[$v]['nickname']=$data[$v]['nickname'];
					}else{
						$user[$v]['nickname']=$v;
					}

				}else{
					//说明该用户已经是好友了
					$user[$v]['user_account']=$v;
					$user[$v]['status']=2;
					if($data[$v]['nickname']){
						$user[$v]['nickname']=$data[$v]['nickname'];
					}else{
						$user[$v]['nickname']=$v;
					}
				}
			}else{
				$user[$v]['user_account']=$v;
				$user[$v]['status']=3;
				$user[$v]['nickname']=$v;
			}
		}
		$user=array_values($user);
		if($user){
			$msg='success';
			$status=200;
		}else{
			$msg='failuer';
			$status=250;
		}
		$this->data->addBody(-140, $user, $msg, $status);
	}
	
	//SNS-广场（获取热帖）
	public function getHotBlog()
	{
		$user_id = request_int('user_id');
		$type = request_string('type');
		$search_content = request_string('search_content');
		$page = request_int('page', 1);
		$rows = request_int('rows', 10);

		$User_InfoModel = new User_InfoModel();
		$User_BaseModel = new User_BaseModel();
		$Sns_BaseModel = new Sns_BaseModel();
		$Sns_CommentModel = new Sns_CommentModel();
		$User_FriendModel = new User_FriendModel();
		$Sns_CollectionModel = new Sns_CollectionModel();
		$Sns_LikeModel = new Sns_LikeModel();
		$Sns_ForwardModel = new Sns_ForwardModel();

		if(!$search_content)
		{
			$sns_data = $Sns_BaseModel->getHotBlog($type, $user_id, $search_content,0,$page, $rows);
		}
		else
		{
			$sns_data = array();
			$sns_data_name = $Sns_BaseModel->getHotBlog($type, $user_id, $search_content,1,$page, $rows);
			$sns_data_lable = $Sns_BaseModel->getHotBlog($type, $user_id, $search_content,2,$page, $rows);
			$sns_data['items'] = array_merge($sns_data_name['items'],$sns_data_lable['items']);
			$sns_data['page'] = $page;
			$sns_data['total_row'] = $sns_data_name['total_row'] + $sns_data_lable['total_row'];
			$sns_data['total'] = ceil_r($sns_data['total_row'] / $rows);
			$sns_data['totalsize'] = $sns_data['total'];
			$sns_data['records'] = $sns_data_name['records'] + $sns_data_lable['records'];
		}

		if($sns_data['items'])
		{
			foreach($sns_data['items'] as $key=>$value)
			{
				$items[$key] = $value;
				$items[$key]['sns_img'] = array_filter(explode(',', $value['sns_img']));
				$items[$key]['sns_lable'] = array_filter(explode(',', $value['sns_lable']));
				$sns_type_row = array(7,8);
				if(!in_array($items[$key]['sns_type'], $sns_type_row))
				{
					$items[$key]['sns_content'] = base64_decode($items[$key]['sns_content']);
				}
				else
				{
					$link =  current($this->RegExp($items[$key]['sns_content']));
					$items[$key]['new_title'] = "title";
					$items[$key]['new_h5'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.$link;
				}

				//查找发帖者的用户信息
				$user_info = $User_InfoModel->getUserInfo($value['user_name'],$value['user_id']);
				$items[$key]['user_info'] = $user_info;
				foreach($items[$key]['user_info']['user_sns'] as $ukey=>$uvalue)
				{
					$sns_type_row = array(7,8);
					if(!in_array($items[$key]['user_info']['user_sns'][$ukey]['sns_type'], $sns_type_row))
					{
						$items[$key]['user_info']['user_sns'][$ukey]['sns_content'] = base64_decode($items[$key]['user_info']['user_sns'][$ukey]['sns_content']);
					}
				}

				//获取转发该贴的用户
				$forward_data = array_values($Sns_ForwardModel->getForwardrow($value['sns_id']));
				$forward_user_data = array();

				if($forward_data)
				{
					foreach($forward_data as $ke=>$valu)
					{
						$forward_sns_data = current($Sns_BaseModel->getBase($valu['forward_sns_id']));

						$forward_user_data[$ke]['forward_user_id'] = $forward_sns_data['user_id'];
						$forward_user_info = current($User_InfoModel->getInfo($forward_sns_data['user_name']));
						$forward_user_data[$ke]['forward_user_avatar'] = $forward_user_info['user_avatar'];
						$forward_user_data[$ke]['addtime'] = $valu['addtime'];
						$forward_user_data[$ke]['state'] = 2;		//表示该用户是转发的
					}
				}

				//获取收藏该贴的用户
				$collection_data = $Sns_CollectionModel->getCollectBySid($value['sns_id']);
				$collection_user_data = array();
				if($collection_data)
				{
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

				$items[$key]['mix'] = array_merge($forward_user_data, $collection_user_data);
				foreach ($items[$key]['mix'] as $dkey => $dvalue) {
					$items[$key]['mix'][$dkey]['addtime'] = date("Y-m-d H:i:s", $dvalue['addtime']);
				}
				$datetime = array();
				foreach ($items[$key]['mix'] as $user) {
					$datetime[] = $user['addtime'];
				}
				array_multisort($datetime, SORT_DESC, $items[$key]['mix']);

				$items[$key]['hot_count'] = $value['sns_copy_count'] + $value['sns_like_count']+$value['sns_collection'];

				//判断该帖子是否是用户的收藏贴
				$sns_user_collection = $Sns_CollectionModel->getCollectBySidUid($value['sns_id'], $user_id);
				if($sns_user_collection)
				{
					$items[$key]['sns_user_collection'] = 1;
				}
				else
				{
					$items[$key]['sns_user_collection'] = 0;
				}

				//判断该帖子是否是用户的点赞贴
				$sns_user_like = $Sns_LikeModel->getLikeIdByUidSid($value['sns_id'], $user_id);
				if($sns_user_like)
				{
					$items[$key]['is_like'] = 1;
				}
				else
				{
					$items[$key]['is_like'] = 0;
				}

				//判断该帖的发帖者是否是用户的好友
				if($user_id)
				{
					$friend_status = $User_FriendModel->getUserFriendIdById($user_id, $value['user_id']);
					if($friend_status)
					{
						$items[$key]['user_friend_status'] = 1;
					}
					else
					{
						$items[$key]['user_friend_status'] = 0;
					}
				}
				else
				{
					$items[$key]['user_friend_status'] = 0;
				}
			}
		}
		else
		{
			$items = array();
		}

		$sns_data['items'] = $items;
		$sns_data['search_content'] = $search_content;
		$sns_data['type'] = $type;

		fb($sns_data);
		$this->data->addBody(-140, $sns_data);
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}


	/**
	 * 获取帖子的所有评论，并取出每个回复用户对于的头像和名字
	 * 修改此接口的时候注意修改UserSns中的getSnsComment接口。
	 * @author
	 */
	public function getSnsComment()
	{
		$sns_id = request_int('sns_id');
		$user_id = request_int('user_id');
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
				$sns_comment[$key]['commect_content'] = base64_decode($sns_comment[$key]['commect_content']);
				//查找评论用户的用户信息
				$user_info = $User_InfoModel->getOne($val['user_name']);
				//查找备注名
				if($user_id)
				{
					$user_rename = $User_RnameModel->getUserRname($user_id,$val['user_id']);
					if($user_rename)
					{
						$user_rename = current($user_rename);
						$sns_comment[$key]['rename'] = $user_rename['content'];
					}
					else
					{
						$sns_comment[$key]['rename'] = '';
					}
				}
				else
				{
					$sns_comment[$key]['rename'] = '';
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
//		echo '<pre>';print_r($data);exit;
		$this->data->addBody(-140, $data);
		if ($jsonp_callback = request_string('jsonp_callback'))
		{
			exit($jsonp_callback . '(' . json_encode($this->data->getDataRows()) . ')');
		}
	}




	//============== 整理 ========//












	/**
	 * 获取广告位最热帖 测试 2016.11.25
	 * @author houpeng
	 */
	public function getRecommendList()
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
			}
		}
		fb($data);

		$this->data->addBody(-140, $data);


	}
	public function getRecommend()
	{
		$user_id = request_int('user_id');
		$user_friend_id = request_int('user_friend_id');
		$Sns_BaseModel = new Sns_BaseModel();
		$User_InfoModel = new User_InfoModel();
		$Sns_CollectionModel = new Sns_CollectionModel();
		$data = $Sns_BaseModel->getRecommendBlog($user_id);

		$data['sns_img'] = array_filter(explode(',', $data['sns_img']));
		$data['sns_lable'] = array_filter(explode(',', $data['sns_lable']));
		$sns_type_row = array(7,8);
		if(!in_array($data['sns_type'], $sns_type_row))
		{
			$data['sns_content'] = base64_decode($data['sns_content']);
		}
		else
		{
			$link =  current($this->RegExp($data['sns_content']));
			$data['new_title'] = "title";
			$data['new_h5'] = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?ctl=Sns&met=getH5&link='.$link;
		}
		$user_info = current($User_InfoModel->getInfo($data['user_name']));
		$user_avatar = $user_info['user_avatar'];
		$data['user_avatar'] = $user_avatar;
		$sns_islike = current($Sns_BaseModel->getBase($data['sns_id']));
		//点赞人数
		$sns_like_user = $sns_islike['sns_like_user'];
		$like_user_row = explode(',', $sns_like_user);
		if(in_array($user_friend_id, $like_user_row))
		{
			$data['islike'] = 1;
		}
		else
		{
			$data['islike'] = 0;
		}
		$sns_user_collection = $Sns_CollectionModel->getCollectBySidUid($data['sns_id'], $user_friend_id);
		if($sns_user_collection)
		{
			$data['sns_user_collection'] = 1;
		}
		else
		{
			$data['sns_user_collection'] = 0;
		}
		if($data)
		{
			$msg = 'success';
			$status = 200;
		}
		else
		{
			$msg = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 * 根据用户名或电子邮件地址来查询用户	2016.11.30
	 * @author houpeng
	 * @access public
	 */
	public function searchFriendTest()
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

	/**
	 * 登录成功显示当前用户的个人信息
	 * @param houpeng 2016.12.7
	 */
	public function getUserThumb()
	{
		$user_id = request_int('user_id');
		$user_name = request_string('user_name');
		$User_InfoModel = new User_InfoModel();
		$user_info = current($User_InfoModel->getInfo($user_name));
		$user_avatar = $user_info['user_avatar'];
		$data[0]['user_avatar'] = $user_avatar;
		$data[0]['user_name'] = $user_name;
		$data[0]['user_id'] = $user_id;
		$data[0]['nickname'] = $user_info['nickname'];
		$data[0]['user_gender'] = $user_info['user_gender'];
		if($data[0]['user_gender'] == 0)
		{
			$data[0]['user_gender'] = '女';
		}
		elseif($data[0]['user_gender'] == 1)
		{
			$data[0]['user_gender'] = '男';
		}
		else
		{
			$data[0]['user_gender'] = '性别不详';
		}
		$data[0]['user_birth'] = $user_info['user_birth'];
		if($data)
		{
			$msg = 'success';
			$status = 200;
		}
		else
		{
			$msg = 'failure';
			$status = 250;
		}
		//echo '<pre>';print_r($data);exit;
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

	//视频播放页面
	public function getH5(){
		$link = $_GET['link'];
		$title = $_GET['title'];
		echo
		"
		<head>
		<title>title</title>
		</head>
		<html>
		<body>
		<h1 style='text-align: center;margin-top: 20px;font-size: 50px;'>$title</h1>
		<iframe style=\"margin:20px;\" width=95% height=50% src='".$link."' border=0 ></iframe>
		</body>
		</html>
		";
	}
}
?>
