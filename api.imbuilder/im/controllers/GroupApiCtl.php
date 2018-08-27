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
class GroupApiCtl extends Yf_AppController
{
	public $User_GroupModel = null;
	public $User_GroupRelModel = null;
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
		$this->User_GroupModel = new User_GroupModel();
		$this->User_GroupRelModel = new User_GroupRelModel();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		include $this->view->getView();
	}

	/**
	 * 管理界面
	 *
	 * @access public
	 */
	public function manage()
	{
		include $this->view->getView();
	}
	/**
	 * 获得用户分组
	 *
	 * @access public
	 */
	public function getUserGroup(){
		$user_id = $_REQUEST["user_id"];
		$data = $this->User_GroupModel->getUserGroup($user_id);
		//获取好友数量
		$User_FriendModel = new User_FriendModel();
		$user_friend_rows = $User_FriendModel->getUserFriendId($user_id);
		$count = count($user_friend_rows);
		fb($data);
		$sum = 0;
		foreach($data as $k =>$v){
			$list = $User_FriendModel->getGroupFriend($v["group_id"]);
			$num = 0;
			foreach($list as $key => $val){
				$num = $num +1;
			}
			$data[$k]["friend_num"] = $num;
		}
		$data["count"] = $count;
		$this->data->addBody(-140, $data);
	}
	/**
	 * 列表数据
	 *
	 * @access public
	 */
	public function groupList()
	{
		$user_id = Perm::$userId;

		$page = $_REQUEST['page'];
		$rows = $_REQUEST['rows'];
		$sort = $_REQUEST['sort'];


		$data = array();

		if (isset($_REQUEST['skey']))
		{
			$skey = $_REQUEST['skey'];

			$data = $this->User_GroupModel->getGroupList('*', $page, $rows, $sort);
		}
		else
		{
			$data = $this->User_GroupModel->getGroupList('*', $page, $rows, $sort);
		}

//		echo '<pre>';print_r(array($page, $rows, $sort ,$data));exit;
		$this->data->addBody(-140, $data);
	}

	/**
	 * 读取
	 *
	 * @access public
	 */
	public function get()
	{
		$user_id = Perm::$userId;

		$group_id = $_REQUEST['group_id'];
		$rows = $this->User_GroupModel->getGroup($group_id);

		$data = array();

		if ($rows)
		{
			$data = array_pop($rows);
		}

		$this->data->addBody(-140, $data);
	}

	/**
	 * 添加
	 *
	 * @access public
	 */
	public function add_shanghai()
	{
		$user_name = Perm::$row['user_name'];
		$user_id = Perm::$row['user_id'];
		$group_name = request_string('group_name');
		$group_type = request_int('group_type');
		$group_permission = request_int('group_permission');
		$group_declared = request_string('group_declared');
		$data = array();

		if (!$group_name)
		{
			$msg = '请输入群组名称';
			$status = 250;
		}
		else
		{
			//判断群组是否存在
			$data['group_name']             = $group_name         ; // 组名称
			$data['group_type']             = $group_type         ; // 组分类（商品等）
			$data['group_permission']       = $group_permission     ; //
			$data['group_declared']         = $group_declared     ; //
			$data['user_id']                = $user_id            ;
			$group_id = $this->User_GroupModel->addGroup($data, true);

			if ($group_id)
			{
				$data['group_id'] = $group_id;
				$msg = 'success';
				$status = 200;
			}
			else
			{
				$msg = 'failure:群组名称已经存在';
				$status = 250;
			}
		}


		$this->data->addBody(-140, $data, $msg, $status);
	}


	/**
	 * 添加
	 *
	 * @access public
	 */
	public function add()
	{
		$user_name = Perm::$row['user_account'];
		$user_id = Perm::$userId;
		$user_row=request_string('user_row');
		$group_type = request_int('group_type','0');
		$group_permission = request_int('group_permission','0');
		$group_declared = request_string('group_declared');
		$group_bind_id=request_string('group_bind_id');
		$group_describe = request_string('group_describe');
		$user_rows=explode(',',$user_row);
		$User_BaseModel = new User_BaseModel();
		$User_InfoModel = new User_InfoModel();
		$datas=array();
		foreach($user_rows as $k=>$v){
			$dataUser = $User_BaseModel->getUser($v);
			$dataInfo = $User_InfoModel->getInfo($dataUser[$v]['user_account']);
			if($dataInfo[$dataUser[$v]['user_account']]['nickname'] != ''){
				$dataInfo[$dataUser[$v]['user_account']]['user_name']=$dataInfo[$dataUser[$v]['user_account']]['nickname'];
			}
			array_push($datas, $dataInfo[$dataUser[$v]['user_account']]['user_name']);
			array_push($datas_user_ids, $dataUser[$v]['user_id']);
		}
		$data['group_name']=implode(',',$datas);
		$group_name=$data['group_name'];
		$data = array();

		if (!$group_name)
		{
			$msg = '请输入群组名称';
			$status = 250;
		}
		else
		{
			//判断群组是否存在
			$data['group_name']             = $group_name         ; // 组名称
			$data['group_type']             = $group_type         ; // 组分类（商品等）
			$data['group_permission']       = $group_permission   ; //申请加入模式 0：默认直接加入1：需要身份验证 2:私有群组
			$data['group_declared']         = $group_declared     ; //群组公告
			$data['user_id']                = $user_id            ;//管理员id
			$data['group_user']				= $user_row;
			$data['group_bind_id']			= $group_bind_id;
			$data['group_describe']			= $group_describe;
			$data['group_nickname']			= $group_name;
			$group_id = $this->User_GroupModel->addGroup($data, true);

			if ($group_id)
			{
				foreach($user_rows as $dk=>$dv)
				{
					$data_rel['group_bind_id']          = $group_bind_id           ; // 好友组绑定ID
					$data_rel['user_id']                = $dv            ; // 成员id
					$data_rel['user_label']             = $data['group_name']         	 ; //暂时存储为群组成员名
					$data_rel['group_is_disturb']       = User_GroupRelModel::GROUP_NORMAL       ; //默认群组状态为正常
					$group_rel_id = $this->User_GroupRelModel->addGroupRel($data_rel, true);
				}
				$data = [];
				$msg = 'success';
				$status = 200;
			}
			else
			{
				$data = [];
				$msg = 'failure:群组名称已经存在';
				$status = 250;
			}
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 拉好友入群
	 * @param group_bind_id int 群主id
	 * @param user_row string 拉入群的好友id字符串
	 * 2017-07-06 hp
	 */
	public function pushUser()
	{
		$User_BaseModel = new User_BaseModel();
		$group_bind_id = request_string('group_bind_id');
		$user_id_row = explode(',',request_string('user_row'));
		$cond_row['group_bind_id'] = $group_bind_id;
		$group_info = $this->User_GroupModel->getOneByWhere($cond_row);
//		echo '<pre>';print_r($group_bind_id);exit;
		foreach($user_id_row as $k=>$v)
		{
			if(strpos($group_info['group_user'], "$v") !== false)
			{
				unset($user_id_row[$k]);
			}
			else
			{
				$user_data[] = $User_BaseModel->getOne($v);
			}
		}
//		echo '<pre>';print_r($user_data);exit;
		if(count($user_id_row) > 0)
		{
			$user_name_new = array_column($user_data, 'user_account');
			$user_name_old = explode(',', $group_info['group_name']);
			$group_user_old = explode(',', $group_info['group_user']);
			foreach($user_name_new as $uk=>$uv)
			{
				array_unshift($user_name_old, $uv);//将不重复的用户名加入原来的用户名
				array_unshift($group_user_old, $user_id_row[$uk]);//将不重复的用户名加入原来的用户名
			}

			$edit_row['group_user'] = implode(',', $group_user_old);
			$edit_row['group_name'] = implode(',', $user_name_old);
//			echo '<pre>';print_r([$edit_row, $user_name_old]);exit;
			//开启事务
			$this->User_GroupModel->sql->startTransactionDb();
			$flag = $this->User_GroupModel->editGroup($group_info['group_id'], $edit_row);
			if($flag)
			{
				foreach($user_id_row as $key=>$val)
				{
					$add_row['group_bind_id'] = $group_bind_id;
					$add_row['user_id'] = $val;
					$flag1[] = $this->User_GroupRelModel->addGroupRel($add_row);
				}
				if(!in_array('false', $flag1) && $this->User_GroupModel->sql->commitDb())
				{
					$msg = 'success';
					$status = 200;
				}
				else
				{
					$this->User_GroupModel->sql->rollBackDb();
					$msg = 'failure:加入群失败';
					$status = 250;
				}
			}
		}
		else
		{
			$msg = 'failure:群中已经有这些好友，不能重复添加';
			$status = 250;
		}
		$data = [];
		$this->data->addBody(-140, $data, $msg, $status);

		//获取全部的id数组
		$id_row = array_column($data, 'id');
		//去掉数组中重复项
		$unique_arr = array_unique($id_row);
		//找到重复的项
		$repeat_arr = array_diff_assoc($id_row, $unique_arr);
		//原数组循环
		foreach($data as $key=>$val)
		{
			//重复项循环，格式为 原数组键=>原数组id
			foreach($repeat_arr as $k=>$v)
			{
				//如果原数组id值等于重复性id值
				if($val['id'] == $v)
				{
					//将重复项list拼接在原数组list后面
					$data[$key]['list'] .= ','.$data[$k];
					//删除原数组该重复项
					unset($data[$k]);
					//删除重复项数组该项，防止下次重复循环
					unset($repeat_arr[$k]);
				}
			}
		}
	}

	//添加分组
	public function addGroup()
	{
		$user_name = $_REQUEST['user_name'];
		$user_id = request_int('user_id');
		$group_name = request_string('group_name');
		$group_type = request_int('group_type');
		$group_permission = request_int('group_permission');
		$group_declared = request_string('group_declared');
		$group_describe = request_string('group_describe');
		$data = array();
		if($user_id){
		if (!$group_name)
		{
			$msg = '请输入群组名称';
			$status = 250;
		}
		else
		{

			$data['group_name']             = $group_name         ; // 组名称
			$data['group_type']             = $group_type         ; // 组分类（商品等）
			$data['group_permission']       = $group_permission     ; //
			$data['group_declared']         = $group_declared     ; //
			$data['user_id']                = $user_id            ;
			$data['group_describe']		=$group_describe;

			$group_id = $this->User_GroupModel->addGroup($data, true);

			if ($group_id)
			{

				$data['group_id'] = $group_id;
				$msg = 'success';
				$status = 200;
			}
		}

		}else{
			$msg = '请登录';
			$status = 250;
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}



	/**
	 * 绑定sdk group id
	 *
	 * @access public
	 */
	public function bind()
	{
		$user_name = Perm::$row['user_name'];
		$user_id = Perm::$row['user_id'];

		$group_id = request_int('group_id');
		$group_bind_id = request_string('group_bind_id');

		$rows = $this->User_GroupModel->getGroup($group_id);

		$data = array();
		$data['group_bind_id'] = $group_bind_id;

		if ($rows && $rows[$group_id]['user_id'] == $user_id)
		{
			$flag = $this->User_GroupModel->editGroup($group_id, $data);

			if ($flag)
			{
				$msg = 'sucess';
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
			$msg = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, $data);
	}

	/**
	 * 群主转换接口
	 * @param group_bind_id string 群组id
	 * @param user_id int 群成员id
	 */
	public function GroupAdminChange()
	{
		$user_id = Perm::$row['user_id'];
		$group_bind_id = request_string('group_bind_id');
		$group_user_id = request_int('user_id');

		if($group_user_id && $group_bind_id)
		{
			$rows = $this->User_GroupModel->getOneByWhere(['group_bind_id'=>$group_bind_id]);
			if(strpos($rows['group_user'], "$group_user_id") !== false)
			{
				//说明该成员在此群组
				$cond_row['user_id'] = $group_user_id;
				$flag = $this->User_GroupModel->editGroup($rows['group_id'], $cond_row);
				if($flag)
				{
					$msg = 'success:修改成功';
					$status = 200;
				}
				else
				{
					$msg = 'failure:修改失败';
					$status = 250;
				}
			}
			else
			{
				$msg = 'failure:群组内无此成员';
				$status = 250;
			}
		}
		else
		{
			$msg = 'failure:缺少群id或成员id';
			$status = 250;
		}
		$data = [];
		$this->data->addBody(-140, $data, $msg, $status);
	}
	
	
	/**
	 * 删除群组
	 * @param group_bind_id string
	 * hp 2017.7.3
	 */
	public function delect()
	{
		$user_id = Perm::$row['user_id'];
		$group_bind_id = request_string('group_bind_id');
		if ($group_bind_id)
		{
			$rows = $this->User_GroupModel->getOneByWhere(['group_bind_id'=>$group_bind_id]);
			if($rows['user_id'] == $user_id)
			{
				//开启事务
				$this->User_GroupModel->sql->startTransactionDb();
				$flag = $this->User_GroupModel->removeGroup($rows['group_id']);
				if($flag)
				{
					$user_group_rel = $this->User_GroupRelModel->getByWhere(['group_bind_id'=>$group_bind_id]);
					if($user_group_rel)
					{
						$keys = array_keys($user_group_rel);
						$flag1 = $this->User_GroupRelModel->removeGroupRel($keys);
						if($flag1 && $this->User_GroupModel->sql->commitDb())
						{
							$msg = 'success:删除成功';
							$status = $flag1;
						}
						else
						{
							$this->User_GroupModel->sql->rollBackDb();
							$msg = 'success:删除失败';
							$status = 250;
						}
					}
					else
					{
						$this->User_GroupModel->sql->commitDb();
						$msg = 'success:删除成功';
						$status = 200;
					}
				}
				else
				{
					$this->User_GroupModel->sql->rollBackDb();
					$msg = 'failure:删除失败';
					$status = 250;
				}
			}
			else
			{
				$msg = 'failure:管理员才可执行此操作';
				$status = 250;
			}
		}
		else
		{
			$msg = 'failure:群组id接收失败';
			$status = 250;
		}
		$data = [];
		$this->data->addBody(-140, $data, $msg, $status);
	}
	/**
	 * 删除操作
	 * @param id int group_bind_id string
	 * @access public
	 * hp 2017-07-04
	 */


	public function remove()
	{
		$user_id = Perm::$row['user_id'];
		$id = request_int('id');//要退群的群成员id
		$group_bind_id = request_string('group_bind_id');//群组id

		if ($group_bind_id)
		{
			$group_rel_data = $this->User_GroupRelModel->getByWhere(['group_bind_id'=>$group_bind_id]);
			foreach($group_rel_data as $key=>$val)
			{
				if($val['user_id'] == $id)
				{
					$group_rel_key = $key;//找出群组关系表中当前退群用户的当前群主键id
				}
			}
//		echo '<pre>';print_r(count($group_rel_data));exit;
			$group_data = $this->User_GroupModel->getOneByWhere(['group_bind_id'=>$group_bind_id]);
			$user_rows = explode(',', $group_data['group_user']);//成员id分割为数组
			$user_name_rows = explode(',', $group_data['group_name']);//群组名分割为数组
			if(count($user_rows) > 1) //如果群成员大于1
			{
				foreach($user_rows as $k=>$v)
				{
					if($v == $id)
					{
						unset($user_rows[$k]);
						unset($user_name_rows[$k]);
					}
				}
				//如果是管理员退群，则将当前群组用户第一个设为群主
				if($id == $group_data['user_id'])
				{
					$cond_row['user_id'] = $user_rows[0];
					$cond_row['group_name'] = implode(',', $user_name_rows);
					$cond_row['group_user'] = implode(',', $user_rows);
				}
				else
				{
					$cond_row['group_name'] = implode(',', $user_name_rows);
					$cond_row['group_user'] = implode(',', $user_rows);
				}
				//开启事务
				$this->User_GroupModel->sql->startTransactionDb();
				$flag = $this->User_GroupModel->editGroup($group_data['group_id'], $cond_row);
				if($flag)
				{
					$flag1 = $this->User_GroupRelModel->removeGroupRel($group_rel_key);
					if($flag1 && $this->User_GroupModel->sql->commitDb())
					{
						$msg = 'success';
						$status = 200;
					}
					else
					{
						$this->User_GroupModel->sql->rollBackDb();
						$msg = 'failure:退出失败';
						$status = 250;
					}
				}
				else
				{
					$this->User_GroupModel->sql->rollBackDb();
					$msg = 'failure:退出失败';
					$status = 250;
				}
			}
			else
			{
				//群成员等于1相当于只剩群主，如果此时群主退群，相当于解散此群
				//开启事务
				$this->User_GroupModel->sql->startTransactionDb();
				$flag = $this->User_GroupModel->removeGroup($group_data['group_id']);
				$flag1 = $this->User_GroupRelModel->removeGroupRel($group_rel_key);
				if($flag && $flag1 &&  $this->User_GroupModel->sql->commitDb())
				{
					$msg = 'success:群解散成功';
					$status = 200;
				}
				else
				{
					$this->User_GroupModel->sql->rollBackDb();
					$msg = 'failure:群解散不成功';
					$status = 250;
				}
			}
		}
		else
		{
			$msg = 'failure:群组id接收失败';
			$status = 250;
		}
		$data = [];
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit_shanghai()
	{
		$user_name = Perm::$row['user_name'];
		$user_id = Perm::$row['user_id'];

		$group_bind_id = request_string('group_bind_id');
		$group_name = request_string('group_name');
		//$group_type = request_int('group_type');
		$group_permission = request_int('group_permission');
		$group_declared = request_string('group_declared');


		//判断群组是否存在
		$data = array();
		$data['group_name']             = $group_name         ; // 组名称
		$data['group_permission']       = $group_permission     ; //

		if ($group_declared)
		{
			$data['group_declared']         = $group_declared     ; //
		}

		$data_rs = $data;

		unset($data['group_id']);


		if ($group_bind_id)
		{
			$group_id = $this->User_GroupModel->getIdByGroupBindId($group_bind_id);

			$rows = $this->User_GroupModel->getGroup($group_id);
		}

		if ($rows && $rows[$group_id]['user_id'] == $user_id)
		{
			$flag = $this->User_GroupModel->editGroup($group_id, $data);
		}
		else
		{
			$msg = 'failure';
			$status = 250;
		}

		$this->data->addBody(-140, $data_rs);
	}


	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		//只有群主可以修改
		$user_name = Perm::$row['user_account'];
		$user_id = Perm::$row['user_id'];

		$group_bind_id = request_string('group_bind_id');
		$group_declared = request_string('group_declared');//群公告
		$group_nickname = request_string('group_nickname');//群昵称
		$group_describe = request_string('group_describe');//群描述

		$datas=array();
		//判断群组是否存在
		$res = $this->User_GroupModel->checkUserGroup($user_id,$group_bind_id);

		if($res)
		{
			$data = array();

			if ($group_declared)
			{
				$data['group_declared'] = $group_declared     ; //
			}
			if($group_nickname)
			{
				$data['group_nickname'] = $group_nickname;
			}
			if($group_describe)
			{
				$data['group_describe'] = $group_describe;
			}
			$rows = array();

			$rows = $this->User_GroupModel->getGIdByGroupBindId($group_bind_id);

			if ($rows)
			{
				$flag = $this->User_GroupModel->editGroup($rows, $data);
				if($flag){
					$msg='success';
					$status=200;
				}else
				{
					$msg = 'failure:修改失败';
					$status = 250;
				}

			}
			else
			{
				$msg = 'failure:此群组不存在';
				$status = 250;
			}
		}
		else
		{
			$msg = 'failure';
			$status = 250;
		}
		$data = [];
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改群组
	 *
	 * @access public
	 */
	public function editGroup()
	{
		$user_name = $_REQUEST['user_name'];
		$user_id = $_REQUEST['user_id'];
		$group_name = request_string('group_name');
		$group_type = request_int('group_type');
		$group_permission = request_int('group_permission');
		$group_declared = request_string('group_declared');
		$group_describe = request_string('group_describe');
		$group_id = request_int('group_id');
		$data = array();

		//判断群组是否存在
		$data['group_name'] = $group_name; // 组名称
		$data['group_type'] = $group_type; // 组分类（商品等）
		$data['group_permission'] = $group_permission; //
		$data['group_declared'] = $group_declared; //
		$data['user_id'] = $user_id;
		$data['group_describe'] = $group_describe;

//		$group_id =127;
//		$user_id = 10036;
//		$data['group_name'] = "名称111"; // 组名称
//		$data['group_type'] = 1; // 组分类（商品等）
//		$data['group_permission'] = 2; //
//		$data['group_declared'] = 3; //
//		$data['user_id'] = 10036;
//		$data['group_describe'] = 4;

		//查询分组是否存在

		$list = $this->User_GroupModel->getUserGroup($user_id,$group_id);

		if (!empty($list)) {
			$group = $this->User_GroupModel->editGroup($group_id, $data);
		}

		if (!empty($group))
		{
			$data['group_id'] = $group;
			$msg = 'success';
			$status = 200;
			}else{
				$msg = 'failure';
				$status = 250;
		}


		$this->data->addBody(-140, $data);
	}

	//

	/**
	 * 读取
	 *
	 * @access public
	 */
	public function getGroupMember()
	{
		$user_id = Perm::$userId;

		$group_rel_id = $_REQUEST['group_rel_id'];

		$rows = $this->User_GroupRelModel->getGroupRel($group_rel_id);

		$data = array();

		if ($rows)
		{
			$data = array_pop($rows);
		}

		$this->data->addBody(-140, $data);
	}

	/**
	 * 添加
	 *
	 * @access public
	 */
	public function addGroupMember()
	{
		$user_name = Perm::$row['user_name'];
		$user_id = Perm::$row['user_id'];

		$data['group_id']               = $_REQUEST['group_id']           ; // 好友组ID
		$data['user_id']                = $_REQUEST['user_id']            ; // 成员id
		$data['user_label']             = $_REQUEST['user_label']         ; //

		$group_rel_id = $this->User_GroupRelModel->addGroupRel($data, true);

		if ($group_rel_id)
		{
			$msg = 'success';
			$status = 200;
		}
		else
		{
			$msg = 'failure';
			$status = 250;
		}

		$data['group_rel_id'] = $group_rel_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function removeGroupMember()
	{
		$user_name = Perm::$row['user_name'];
		$user_id = Perm::$row['user_id'];
		$remove_user_id = request_int('user_id');
		$group_bind_id = request_string('group_bind_id');
		if ($group_bind_id && $user_id != $remove_user_id)
		{
			$group_id = $this->User_GroupModel->getIdByGroupBindId($group_bind_id);
			if ($group_id)
			{
				$rows = $this->User_GroupModel->getGroup($group_id);
				if ($rows && $rows[$group_id]['user_id'] == $user_id)
				{
					$user_row_old = $rows[$group_id]['group_user'];
					$user_name_old = $rows[$group_id]['group_name'];
					$user_row = explode(',',$user_row_old);
					$user_name = explode(',',$user_name_old);
					foreach ($user_row as $key=>$value)
					{
						//删除成员和群组成员名
						if($remove_user_id == $value)
						{
							unset($user_row[$key]);
							unset($user_name[$key]);
						}
					}
					$user_row_new = implode(',', $user_row);
					$user_name_new = implode(',', $user_name);
					$flag = $this->User_GroupModel->editGroup($group_id, array('group_user'=>$user_row_new, 'group_name'=>$user_name_new));
					if($flag)
					{
						$msg = 'success';
						$status = 200;
					}
					else
					{
						$msg = 'failure:踢人失败';
						$status = 250;
					}
				}
				else
				{
					$msg = 'failure:只有群主可以执行此操作';
					$status = 250;
				}
			}
			else
			{
				$msg = 'failure:没有这个群组';
				$status = 250;
			}
		}
		else
		{
			$msg = 'failure:不能踢出群主或群组名获取失败';
			$status = 250;
		}

		$data['group_id'] = $group_id;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function editGroupMember()
	{
		$cond_row['user_id:=']                = request_int('user_id')                  ; // 用户id
		$cond_row['group_bind_id:=']          = request_string('group_bind_id')         ; // 绑定id
		$group_is_disturb      				= request_int('group_is_disturb')         ; // 是否免打扰
		$group_rel_info = current($this->User_GroupRelModel->getByWhere($cond_row));
		//开启事务
		$this->User_GroupRelModel->sql->startTransactionDb();
		$flag = $this->User_GroupRelModel->editGroupRel($group_rel_info['group_rel_id'], array('group_is_disturb'=>$group_is_disturb));
		if($flag && $this->User_GroupRelModel->sql->commitDb())
		{
			//如果修改成功，取出用户所有群组信息
			$user_group_info = array_values($this->User_GroupRelModel->getByWhere(array('user_id'=>request_int('user_id'), 'group_is_disturb'=>User_GroupRelModel::GROUP_DISTURB)));
			$msg = 'success';
			$status = 200;
		}
		else
		{
			$this->User_GroupRelModel->sql->rollBackDb();
			$user_group_info = [];
			$msg = 'failure';
			$status = 250;
		}
		$this->data->addBody(-140, $user_group_info, $msg, $status);
	}



	//获取用户组成员的信息
	//参数 用户组的id号
	public function getGroupInfo()
	{
		$user_id = Perm::$row['user_id'];
		$group_bind_id = request_string('group_bind_id');//gg80153831871
		$group_id = $this->User_GroupModel->getIdByGroupBindId($group_bind_id);
		$dataGroup = $this->User_GroupModel->getGroup($group_id);
		$user_row = $dataGroup[$group_id]['group_user'];
		$user_row=explode(',',$user_row);
		$user_Base=new User_BaseModel();
		$user_Info=new User_InfoModel();
		$dataUserInfo=array();
		foreach($user_row as $key=>$values){
			$dataUser=$user_Base->getUser($values);
			$user_id=$dataUser[$values]['user_id'];
			$dataUserInfos=$user_Info->getInfo($dataUser[$values]['user_account']);
			$dataUserInfos=current($dataUserInfos);
			$dataUserInfos['user_id']=$user_id;
			array_push($dataUserInfo,$dataUserInfos);
		}
		$data['user_info']=$dataUserInfo;
		$data['group_name']=$dataGroup[$group_id]['group_name'];
		$data['group_describe']=$dataGroup[$group_id]['group_describe'];
		$data['group_declared']=$dataGroup[$group_id]['group_declared'];
		$data['group_nickname']=$dataGroup[$group_id]['group_nickname'];
		$data['boss']=$dataGroup[$group_id]['user_id'];

		$this->data->addBody(-140, $data);
	}
}
?>