<?php if (!defined('ROOT_PATH')) exit('No Permission');
/**
 * @author     Xinze <xinze@live.cn>
 */
class Sns_BaseModel extends Sns_Base
{

	/**
	 * 读取分页列表
	 *
	 * @param  int $user_friend_id 主键值
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getBaseList($user_name=null,$page=1, $rows=100, $sort='asc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);
		$this->sql->setLimit($offset, $rows);
		$sns_id_row = array();
		$this->sql->setWhere('is_del','2','!=');
		if($user_name)
		{
			$this->sql->setWhere('user_name','%'.$user_name.'%','LIKE');
		}
		$sns_id_row = $this->selectKeyLimit();
		//读取主键信息
		$total = $this->getFoundRows();
		$data_rows = array();
		if ($sns_id_row)
		{
			$data_rows = $this->getBase($sns_id_row);
		}
		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $total;
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);
		return $data;
	}

	//获取自己的动态分页
	public function getBaseSns($user_id=null, $page=1, $rows=99999999, $sort='desc', $sns_lable=null, $sns_id=null, $friend_ids=null)
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);
		$this->sql->setLimit($offset, $rows);
		$sns_id_row = array();
		$this->sql->setWhere('is_del', '0');
		$this->sql->setWhere('user_id', $user_id);
		$this->sql->setWhere('user_id', $friend_ids, '!IN');
		if($sns_lable)
		{
			$this->sql->setWhere('sns_lable', '%'.$sns_lable.'%', $symbol='LIKE');
		}
		$this->sql->setOrder('sns_create_time', $sort);fb(111);
		$sns_id_row = $this->selectKeyLimit();
		//读取主键信息
		$total = $this->getFoundRows();
		$data_rows = array();
		if ($sns_id_row)
		{
			if($sns_lable)
			{
				foreach($sns_id_row as $key=>$value)
				{
					$data_rows[] = current($this->getBase($value));
				}
			}
			else
			{
				$data_rows = $this->getBase($sns_id_row);
			}
		}
		rsort($data_rows);
		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $total;
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);
		return $data;
	}

	//获取自己的动态分页，为了浏览预览图的帖子添加的方法
	public function getBaseSnsPrev($user_id=null,$page=1, $rows=1000, $sort='desc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);
		$this->sql->setLimit($offset, $rows);
		$sns_id_row = array();
		$this->sql->setWhere('is_del','0');
		//$this->sql->setWhere('sns_forward','0');
		$this->sql->setWhere('user_id',$user_id);
		$this->sql->setOrder('sns_create_time',$sort);
		$sns_id_row = $this->selectKeyLimit();
		//读取主键信息
		$total = $this->getFoundRows();
		$data_rows = array();
		if ($sns_id_row)
		{
			$data_rows = $this->getBase($sns_id_row);
		}
		rsort($data_rows);
		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $total;
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);
		return $data;
	}
	

	/**
	 * //获取当前帖子后四篇有图片的帖子
	 * @param $user_id
	 * @param int $page
	 * @param string $sort
	 * @param $page_status 判断当前显示的帖子是不是有图片的帖子，$page_status=1表示是有图片的帖子，2表示没图片，默认为空
	 * @return array
	 */
	public function getBaseSnsNextLimit($user_id=array(), $page=1, $sort='desc', $page_status, $limit=4, $sns_id=null)
	{
		$sns_id_row = array();
		$this->sql->setWhere('is_del', 0, '=');
		$this->sql->setWhere('user_id', $user_id, 'IN');
		if($sns_id)
		{
			$this->sql->setWhere('sns_id', $sns_id, $symbol = '<');
		}
		$this->sql->setOrder('sns_id', $sort);
		if($page_status == 1)
		{
			$this->sql->setWhere('sns_img', ' ','!=');	//图片不为空的帖子
		}
		elseif($page_status == 2)
		{
			$this->sql->setWhere('sns_img', ' ');	//图片为空的帖子
		}
		$this->sql->setLimit($page-1, $limit);
		$sns_id_row = $this->selectKeyLimit();

		$data_rows = array();
		if ($sns_id_row)
		{
			$data_rows = $this->getBase($sns_id_row);
		}

		if($data_rows)
		{
			foreach($data_rows as $key => $val)
			{
				$img_row = explode(',',$val['sns_img']);
				array_filter($img_row);
				$data_rows[$key]['sns_img'] = $img_row[0];
			}
		}
		return $data_rows;
	}

	public function getBaseSnsNext($user_id, $page=1, $sort='desc', $page_status, $limit=4, $sns_id=null)
	{
		$sns_id_row = array();
		$this->sql->setWhere('is_del', 0, $symbol = '=');
		$this->sql->setWhere('sns_img', ' ', $symbol = '!=');	//图片不为空的帖子
		//$this->sql->setWhere('sns_forward', 0, $symbol = '=');	//非转发的帖子
		$this->sql->setWhere('user_id', $user_id, $symbol = '=');
		if($sns_id)
		{
			$this->sql->setWhere('sns_id', $sns_id, $symbol = '<');
		}
		$this->sql->setOrder('sns_id', $sort);
		if($page_status == 1)
		{
			$this->sql->setLimit($page, $limit);
		}
		elseif($page_status == 2)
		{
			$this->sql->setLimit($page-1, $limit);
		}
		$sns_id_row = $this->selectKeyLimit();
		fb($page);
		fb($limit);
		fb($sns_id_row);
		$data_rows = array();
		if ($sns_id_row)
		{
			foreach($sns_id_row as $key=>$value)
			{
				$data_rows[] = current($this->getBase($value));
			}
			foreach($data_rows as $k=>$v)
			{
				$data_rows[$k]['sns_img'] = explode(',',$v['sns_img']);
				$data_rows[$k]['sns_id'] = explode(',',$v['sns_id']);
			}
		}
		//echo '<pre>';print_r($data_rows);exit;
		return $data_rows;
	}

	/**
	 * //获取符合条件的帖子ID总数
	 * @param $user_id
	 * @param int $page
	 * @param string $sort
	 * @param null $sns_id	如果不存在表示找出图片不为空的帖子ID
	 * @return array
	 */
	public function getBaseSnsImgTotal($user_id, $page=1, $sort='desc', $sns_id=null)
	{
		$sns_id_row = array();
		$this->sql->setWhere('is_del', 0, $symbol = '=');
		if(!$sns_id)
		{
			$this->sql->setWhere('sns_img', '', $symbol = '!=');	//图片不为空的帖子
		}
		//$this->sql->setWhere('sns_forward', 0, $symbol = '=');	//非转发的帖子
		$this->sql->setWhere('user_id', $user_id, $symbol = '=');
		$this->sql->setOrder('sns_id', $sort);
		$this->sql->setLimit($page-1, 99999999);
		$sns_id_row = $this->selectKeyLimit();
		//echo '<pre>';print_r($sns_id_row);exit;
		return $sns_id_row;
	}
	
	
	public function getBaseByUserId($user_id)
	{
		$data = array();

		$this->sql->setWhere('user_id', $user_id);

		$data_rows =  $this->selectKeyLimit();

		return $data_rows;
	}

	public function getLastBaseByUserId($user_id)
	{
		$data = array();

		$this->sql->setWhere('user_id', $user_id);
		$this->sql->setLimit(0, 1);
		$this->sql->setOrder('sns_create_time', 'desc');
		$row =  $this->selectKeyLimit();

		$data_rows = array();
		if($row)
		{
			$data_rows = $this->getBase($row);
		}

		return $data_rows;
	}

	public function getBaseById($sns_id)
	{
		$data = array();

		$this->sql->setWhere('sns_id', $sns_id);

		$data_rows = $this->getBase('*');
		if($data_rows)
		{
			//获取发帖者的用户信息
			$User_BaseModel = new User_BaseModel();
			$user_info_row = $User_BaseModel->getBaseInfo($data_rows[$sns_id]['user_id']);

			$data_rows[$sns_id]['user_info'] = $user_info_row;
		}



		return $data_rows;
	}

	public function addCommentCount($sns_id)
	{
		$data = $this->getBase($sns_id);

		$count = $data[$sns_id]['sns_comment_count'] + 1;

		$field = array('sns_comment_count' => $count);

		$re = $this->editBase($sns_id,$field);
		return $re;
	}

	public function addForwardCount($sns_id)
	{
		$data = $this->getBase($sns_id);

		$count = $data[$sns_id]['sns_copy_count'] + 1;

		$field = array('sns_copy_count' => $count);

		$re = $this->editBase($sns_id,$field);
		return $re;
	}
	
	public function editLikeCount($sns_id = null ,$edit = null)
	{
		$data = $this->getBase($sns_id);

		if($edit == 1)  //取消点赞，人数减1
		{
			$count = $data[$sns_id]['sns_like_count'] - 1;
		}
		if($edit == 2) //点赞，人数加1
		{
			$count = $data[$sns_id]['sns_like_count'] + 1;
		}
		$field = array('sns_like_count' => $count);
		$re = $this->editBase($sns_id,$field);
		return $re;
	}

	//修改收藏数
	public function editCollectCount($sns_id = null ,$edit = null)
	{
		$data = $this->getBase($sns_id);

		if($edit == 1)  //取消点赞，人数减1
		{
			$count = $data[$sns_id]['sns_collection'] - 1;
		}
		if($edit == 2) //点赞，人数加1
		{
			$count = $data[$sns_id]['sns_collection'] + 1;
		}
		$field = array('sns_collection' => $count);
		$re = $this->editBase($sns_id,$field);
		return $re;
	}
	
	public function getBaseByOrder($id,$page,$rows)
	{
		$offset = $rows * ($page - 1);
		$this->sql->setLimit($offset, $rows);
		$this->sql->setWhere('sns_id',$id,'IN');
		$this->sql->setWhere('is_del','1','!=');
		$this->sql->setOrder('sns_create_time','desc');
		$datas=$this->getBase($id);
		// var_dump($data);die;
		$data['items']=array_values($datas);
		$data['total']=$this->getFoundRows();
		$data['totalsize']=ceil_r($data['total']/$rows);
		return $data;
	}

	//根据用户名获取用户展示的分享的图片
	public function getImgs($user_account=null){
		$this->sql->setwhere('user_name',$user_account)->setorder('sns_create_time','desc');
		$data=$this->getBase('*');
		$img=array();
		$count=0;
		foreach($data as $key=>$val){
			if($val['sns_img']){
				array_push($img,$val['sns_img']);
				$count++;
				if($count>2){
					return $img;
				}
			}
		}
		return $img;
	}

	/**
	 * 读取分页列表
	 * @author houpeng
	 * @param  string $name 用户名
	 * @param  int $kind_name 用来判断用户是按用户名搜索还是按标签搜索，1代表用户名，2代表标签, 3代表推荐帖子。默认是1
	 * @param  int $user_id 用来排除当前用户
	 * @return array $rows 返回的查询内容
	 * @access public
	 */
	public function getSnsList($user_id ,$search_name, $kind_type = 1, $page=1, $rows=10,  $sort='desc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);
		$this->sql->setLimit($offset, $rows);
		$this->sql->setWhere('is_del',0);
		$sns_id_row = array();
		//用户名
		if($kind_type == 1)
		{
			$this->sql->setwhere('user_name', '%'.$search_name.'%','LIKE');
		}//标签
		elseif($kind_type == 2)
		{
			$this->sql->setwhere('sns_lable',  '%'.$search_name.'%','LIKE');
		}//推荐帖子
		elseif($kind_type == 3)
		{
			$this->sql->setwhere('sns_collection', 0, '>=');
			$this->sql->setwhere('user_id', $user_id,'!=');
		}
		if($kind_type == 3)
		{
			$this->sql->setOrder('sns_collection', $sort);
		}
		else
		{
			$this->sql->setOrder('sns_id', $sort);
		}

		$sns_id_row = $this->selectKeyLimit();
		//echo '<pre>';print_r($sns_id_row);exit;
		//读取主键信息
		$total = $this->getFoundRows();
		$data_rows = array();
		if ($sns_id_row)
		{
			$data_rows = $this->getBase($sns_id_row);
		}

		if($kind_type !== 3)
		{
			rsort($data_rows);
		}
		else
		{
			//$data_rows = array_values($data_rows);
			$arr = array();
			foreach ($data_rows as $key=>$value)
			{
				$arr[$key] = $value['user_id'];
			}
			$data_rows_id = $arr;
			$data_rows_id = array_unique($data_rows_id);
			$data_rows_id = array_values($data_rows_id);
			foreach($data_rows_id as $ke=>$va)
			{
				foreach($data_rows as $k=>$v)
				{
					if($v['user_id'] == $va)
					{
						$user_info[$ke]['user_id'] = $va;
						$user_info[$ke]['user_name'] = $v['user_name'];
					}
				}
			}
			$data_rows_id = $user_info;
			$data_rows = array_slice($data_rows_id, 0, 5);
		}
		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);
		return $data;
	}

	public function getSnsIdList($user_id,$search_name, $kind_type = 1, $page=1, $rows=10,  $sort='desc')
	{
		//需要分页如何高效，易扩展
		$offset = $rows * ($page - 1);
		$this->sql->setLimit($offset, $rows);
		$sns_id_row = array();
		//用户名
		if($kind_type == 1)
		{
			$this->sql->setwhere('user_name', '%'.$search_name.'%','LIKE');
		}//标签
		elseif($kind_type == 2)
		{
			$this->sql->setwhere('sns_lable',  '%'.$search_name.'%','LIKE');
		}//推荐帖子
		elseif($kind_type == 3)
		{
			$this->sql->setwhere('sns_collection', 0, '>=');
			$this->sql->setwhere('user_id', $user_id,'!=');
		}
		if($kind_type == 3)
		{
			$this->sql->setOrder('sns_collection', $sort);
		}
		else
		{
			$this->sql->setOrder('sns_id', $sort);
		}

		$sns_id_row = $this->selectKeyLimit();

		return $sns_id_row;
	}

	/**
	 * 获取当前图片不为空且不是当前用户发布的最热帖
	 * @param int $user_id 当前登录用户的ID
	 */
	public function getRecommendBlogList($user_id, $page=1, $rows=3)
	{
		$offset = $rows * ($page - 1);
		$this->sql->setLimit($offset, $rows);
		$sns_id_row = array();

		$this->sql->setwhere('sns_collection', 0, '>=');
		$this->sql->setwhere('sns_img', '', '!=');
		$this->sql->setwhere('user_id', $user_id,'!=');
		$this->sql->setOrder('sns_collection', 'desc');
		$sns_id_row = $this->selectKeyLimit();

		//读取主键信息
		$total = $this->getFoundRows();
		$data_rows = array();
		if ($sns_id_row)
		{
			$data_rows = $this->getBase($sns_id_row);
		}

		$data = array();
		$data['page'] = $page;
		$data['total'] = ceil_r($total / $rows);  //total page
		$data['totalsize'] = $data['total'];
		$data['records'] = count($data_rows);
		$data['items'] = array_values($data_rows);

		fb($data);
		return $data;
	}
	public function getRecommendBlog($user_id)
	{
		//收藏数>=0
		$this->sql->setwhere('sns_collection', 0, '>=');
		//用户未删除
		$this->sql->setWhere('is_del',0);
		//隐私可见度：所有人
		$this->sql->setWhere('sns_privacy',0);
		$this->sql->setwhere('user_id', $user_id,'!=');
		$this->sql->setOrder('sns_collection', 'desc');
		$sns_id_row = $this->selectKeyLimit();

		$data_rows = array();
		if ($sns_id_row)
		{
			$data_rows = $this->getBase($sns_id_row);
		}

		return $data_rows;
	}

	/**
	 * 获取当前图片不为空最热帖
	 * @param 表示广场显示帖子的种类
	 * @param 表示当前登录用户的ID
	 * @param 表示广场搜索的内容
	 * @param 表示广场搜索的类型，分为用户名和标签，1为用户名,2为标签,默认3为用户名和标签同时搜索
	 */
	public function getHotBlog($type, $user_id, $search_content=null,$search_kind=3,$page=1, $rows=100, $sort='asc')
	{
        $offset = $rows * ($page - 1);

        $this->sql->setLimit($offset, $rows);
		$this->sql->setwhere('is_del', 0);
		$this->sql->setwhere('sns_privacy', 0);
		$this->sql->setwhere('sns_forward', 0);

        if($search_content)
		{
            if($search_kind == 1)
            {
                $this->sql->setwhere('user_name', '%'.$search_content.'%','LIKE');
            }
			if($search_kind == 2)
            {
                $this->sql->setwhere('sns_lable', '%'.$search_content.'%','LIKE');
            }
        }

		if($type == 'text')  //文字
		{
			$this->sql->setWhere('sns_type', 5);
		}
		elseif($type == 'pic')  //图片
		{
            $this->sql->setWhere('sns_type', 6);
		}
        elseif($type == 'music') //音乐
        {
            $this->sql->setWhere('sns_type', 7);
        }
        elseif($type == 'vudio')  //视频
        {
            $this->sql->setWhere('sns_type', 8);
        }
		else  //热门(按照点赞数排序)
		{
			$this->sql->setOrder('sns_like_count','desc');
		}

		if($user_id)
		{
			$this->sql->setWhere('user_id', $user_id, '!=');
		}

		$sns_id_row = $this->selectKeyLimit();
        $total = $this->getFoundRows();
		//echo '<pre>';print_r($type);exit;
        $data_rows = array();
        if ($sns_id_row)
        {
            $data_rows = $this->getBase($sns_id_row);
        }
        $data = array();
        $data['page'] = $page;
        $data['total'] = ceil_r($total / $rows);
        $data['total_row'] = $total;
        $data['totalsize'] = $data['total'];
        $data['records'] = count($data_rows);
        $data['items'] = array_values($data_rows);
		return $data;
	}
}
?>