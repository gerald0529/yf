<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Goods_BrandCtl extends Controller
{
	public $goodsBrandModel = null;

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
		$this->goodsBrandModel = new Goods_BrandModel();

		$this->web = $this->webConfig();
		$this->nav = $this->navIndex();
		$this->cat = $this->catIndex();
	}

	/**
	 * 首页
	 *
	 * @access public
	 */
	public function index()
	{
		$this->initData();
		$Goods_BrandModel = new Goods_BrandModel();
		$data             = $Goods_BrandModel->listRecommonBrand();

		//获取关注排行
		if (!empty($data))
		{
			$data_rank = $Goods_BrandModel->getRankRows($data);
		}

		$title             = Web_ConfigModel::value("brand_title");//首页名;
		$this->keyword     = Web_ConfigModel::value("brand_keyword");//关键字;
		$this->description = Web_ConfigModel::value("brand_description");//描述;
		$this->title       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $title);
//		$this->title       = str_replace("{name}", "心悦品牌", $this->title);
		$this->keyword       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->keyword);
//		$this->keyword       = str_replace("{name}", "心悦品牌", $this->keyword);
		$this->description       = str_replace("{sitename}", Web_ConfigModel::value("site_name"),$this->description);
//		$this->description       = str_replace("{name}", "心悦品牌", $this->description);
		include $this->view->getView();
	}

	/*
	 *
	 */
	public function brandList()
	{
		$this->initData();
		$Goods_CommonModel = new Goods_CommonModel();
		$Goods_BrandModel  = new Goods_BrandModel();

		$brand_id = request_int('brand_id');
		
		if ($brand_id)
		{
			//品牌下的商品列表
			$data_common       = $Goods_CommonModel->listByWhere(array(
																	 'brand_id' => $brand_id,
																	 'common_state' => $Goods_CommonModel::GOODS_STATE_NORMAL
																 ));
			$data_goods_common = $Goods_CommonModel->getRecommonRow($data_common);

			//相同分类下的品牌
			$data_cat_goods = $Goods_BrandModel->getCatBrands($brand_id);

			//品牌
			$data_brand = $Goods_BrandModel->getOne($brand_id);

			//大家都在买
			$data_buy     = $Goods_CommonModel->listByWhere(array('brand_id' => $brand_id,), array('common_salenum' => 'desc'), 1, 16);
			$data_all_buy = $Goods_CommonModel->getRecommonRow($data_buy);

			//是否关注
			$User_FavoritesBrandModel = new User_FavoritesBrandModel();
			$user_id                  = Perm::$userId;
			if ($user_id)
			{
				$brand_row['user_id']  = $user_id;
				$brand_row['brand_id'] = $brand_id;
				$data_favorites        = $User_FavoritesBrandModel->getOneByWhere($brand_row);
			}

		}

        $title             = Web_ConfigModel::value("brand_title_content");//首页名;

        $this->keyword     = Web_ConfigModel::value("brand_keyword_content");//关键字;
        $this->description = Web_ConfigModel::value("brand_description_content");//描述;
        $this->title       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $title);
        $this->title       = str_replace("{name}", $data_brand['brand_name'], $this->title);
        $this->keyword       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->keyword);
        $this->keyword       = str_replace("{name}", $data_brand['brand_name'], $this->keyword);
        $this->description   = str_replace("{sitename}", Web_ConfigModel::value("site_name"),$this->description);
        $this->description   = str_replace("{name}", $data_brand['brand_name'], $this->description);

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
	 * 列表数据
	 *
	 * @access public
	 */
	public function lists()
	{
		$user_id = Perm::$userId;

		$page = request_int('page', 1);
		$rows = request_int('rows', 100);
		$sort = request_int('sord');

		$cond_row  = array();
		$order_row = array();

		$data = array();

		$cond_row['brand_recommend'] = Goods_BrandModel::RECOMMEND_TRUE; //获取推荐品牌
		$cond_row['brand_enable'] = Goods_BrandModel::ENABLE_TRUE; //获取审核通过的品牌

		if ($skey = request_string('skey'))
		{
			$data = $this->goodsBrandModel->getBrandList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->goodsBrandModel->getBrandList($cond_row, $order_row, $page, $rows);
		}


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

		$brand_id = request_int('brand_id');
		$rows     = $this->goodsBrandModel->getBrand($brand_id);

		$data = array();

		if ($rows)
		{
			$data = array_pop($rows);
		}

		$this->data->addBody(-140, $data);
	}

	/**
	 * 获取品牌名的首字母 hp
	 * @param $str
	 * @return string
	 */
	function getFirstCharter($str)
	{
		$pattern = '/[a-zA-Z]/';//匹配品牌名字符串的首字母
		$a = $str[0];
		$status = preg_match($pattern, $a);
		if($status)
		{
			$data = $str[0];
		}
		else
		{
			if(empty($str)){return '';}
			$fchar=ord($str{0});
			if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
			$s1=iconv('UTF-8','gb2312',$str);
			$s2=iconv('gb2312','UTF-8',$s1);
			$s=$s2==$str?$s1:$str;
			$asc=ord($s{0})*256+ord($s{1})-65536;
			if($asc>=-20319&&$asc<=-20284) $data = 'A';
			if($asc>=-20283&&$asc<=-19776) $data = 'B';
			if($asc>=-19775&&$asc<=-19219) $data = 'C';
			if($asc>=-19218&&$asc<=-18711) $data = 'D';
			if($asc>=-18710&&$asc<=-18527) $data = 'E';
			if($asc>=-18526&&$asc<=-18240) $data = 'F';
			if($asc>=-18239&&$asc<=-17923) $data = 'G';
			if($asc>=-17922&&$asc<=-17418) $data = 'H';
			if($asc>=-17417&&$asc<=-16475) $data = 'J';
			if($asc>=-16474&&$asc<=-16213) $data = 'K';
			if($asc>=-16212&&$asc<=-15641) $data = 'L';
			if($asc>=-15640&&$asc<=-15166) $data = 'M';
			if($asc>=-15165&&$asc<=-14923) $data = 'N';
			if($asc>=-14922&&$asc<=-14915) $data = 'O';
			if($asc>=-14914&&$asc<=-14631) $data = 'P';
			if($asc>=-14630&&$asc<=-14150) $data = 'Q';
			if($asc>=-14149&&$asc<=-14091) $data = 'R';
			if($asc>=-14090&&$asc<=-13319) $data = 'S';
			if($asc>=-13318&&$asc<=-12839) $data = 'T';
			if($asc>=-12838&&$asc<=-12557) $data = 'W';
			if($asc>=-12556&&$asc<=-11848) $data = 'X';
			if($asc>=-11847&&$asc<=-11056) $data = 'Y';
			if($asc>=-11055&&$asc<=-10247) $data = 'Z';
		}
		return $data;
	}

	/**
	 * 添加
	 *
	 * @access public
	 */
	public function add()
	{
		$data['brand_id']           = request_string('brand_id'); //
		$data['brand_name']         = request_string('brand_name'); //
		$data['brand_name_cn']      = request_string('brand_name_cn'); // 拼音
		$data['cat_id']             = request_string('cat_id'); // 分类id
		$data['brand_initial']      = $this->getFirstCharter(request_string('brand_initial')); // 首字母
		$data['brand_show_type']    = request_string('brand_show_type'); // 展示方式
		$data['brand_pic']          = request_string('brand_pic'); //
		$data['brand_displayorder'] = request_string('brand_displayorder'); //
		$data['brand_enable']       = request_string('brand_enable'); // 是否启用
		$data['brand_recommend']    = request_string('brand_recommend'); // 是否推荐
		$data['shop_id']            = request_string('shop_id'); // 上传店铺的id


		$brand_id = $this->goodsBrandModel->addBrand($data, true);

		if ($brand_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['brand_id'] = $brand_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 *
	 * @access public
	 */
	public function remove()
	{
		$brand_id = request_int('brand_id');

		$flag = $this->goodsBrandModel->removeBrand($brand_id);

		if ($flag)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['brand_id'] = array($brand_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['brand_id']           = request_string('brand_id'); //
		$data['brand_name']         = request_string('brand_name'); //
		$data['brand_name_cn']      = request_string('brand_name_cn'); // 拼音
		$data['cat_id']             = request_string('cat_id'); // 分类id
		$data['brand_initial']      = request_string('brand_initial'); // 首字母
		$data['brand_show_type']    = request_string('brand_show_type'); // 展示方式
		$data['brand_pic']          = request_string('brand_pic'); //
		$data['brand_displayorder'] = request_string('brand_displayorder'); //
		$data['brand_enable']       = request_string('brand_enable'); // 是否启用
		$data['brand_recommend']    = request_string('brand_recommend'); // 是否推荐
		$data['shop_id']            = request_string('shop_id'); // 上传店铺的id


		$brand_id = request_int('brand_id');
		$data_rs  = $data;

		unset($data['brand_id']);

		$flag = $this->goodsBrandModel->editBrand($brand_id, $data);
		$this->data->addBody(-140, $data_rs);
	}

	/*
	 * 收藏品牌
	 * @rd03
	 * @6-30
	 */
	public function collectBrand()
	{
		$brand_id = request_int('brand_id');

		if (Perm::checkUserPerm())
		{
			$user_id = Perm::$userId;
			//用户登录情况下,插入用户收藏品牌表
			$add_row             = array();
			$add_row['user_id']  = $user_id;
			$add_row['brand_id'] = $brand_id;

			$User_FavoritesBrandModel = new User_FavoritesBrandModel();
			//开启事物
			$User_FavoritesBrandModel->sql->startTransactionDb();

			$res = $User_FavoritesBrandModel->getOneByWhere($add_row);

			if ($res)
			{
				$flag        = true;
				$data['msg'] = __("您已收藏过该品牌！");

			}
			else
			{
				$add_row['favorites_brand_time'] = get_date_time();

				$User_FavoritesBrandModel->addFavoritesBrand($add_row);

				//品牌详情中收藏数量增加
				$Goods_BrandModel          = new Goods_BrandModel();
				$edit_row                  = array();
				$edit_row['brand_collect'] = '1';
				$flag                      = $Goods_BrandModel->editBrand($brand_id, $edit_row, true);
			}


		}
		else
		{
			$flag = false;
		}

		if ($flag && $User_FavoritesBrandModel->sql->commitDb())
		{
			$status      = 200;
			$msg         = __('success');
			$data['msg'] = $data['msg'] ? $data['msg'] : __("关注成功！");
		}
		else
		{
			$User_FavoritesBrandModel->sql->rollBackDb();
			$m           = $User_FavoritesBrandModel->msg->getMessages();
			$msg         = $m ? $m[0] : __('failure');
			$status      = 250;
			$data['msg'] = __("关注失败！");
		}

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/*
	 * 取消关注品牌
	 */
	public function  canleCollectBrand()
	{
		$brand_id = request_int('brand_id');
		$data     = array();
		if (Perm::checkUserPerm())
		{
			$user_id = Perm::$userId;
			//用户登录情况下,删除用户收藏商品
			$fav_row             = array();
			$fav_row['user_id']  = $user_id;
			$fav_row['brand_id'] = $brand_id;

			$User_FavoritesBrandModel = new User_FavoritesBrandModel();
			//开启事物
			$User_FavoritesBrandModel->sql->startTransactionDb();
			$res = $User_FavoritesBrandModel->getOneByWhere($fav_row);

			if ($res)
			{
				$User_FavoritesBrandModel->removeFavoritesBrand($res['favorites_brand_id']);

				//商品详情中收藏数量减少
				$Goods_BrandModel          = new Goods_BrandModel();
				$edit_row                  = array();
				$edit_row['brand_collect'] = '-1';
				$flag                      = $Goods_BrandModel->editBrand($brand_id, $edit_row, true);
			}
			else
			{
				$status      = 250;
				$msg         = __('failure');
				$data['msg'] = __("您还没有关注！");
				$flag        = false;
			}
		}
		else
		{
			$flag = false;
		}

		if ($flag && $User_FavoritesBrandModel->sql->commitDb())
		{
			$status      = 200;
			$msg         = __('success');
			$data['msg'] = $data['msg'] ? $data['msg'] : __("取消关注成功！");
		}
		else
		{
			$User_FavoritesBrandModel->sql->rollBackDb();
			$m           = $User_FavoritesBrandModel->msg->getMessages();
			$msg         = $m ? $m[0] : __('failure');
			$status      = 250;
			$data['msg'] = $data['msg'] ? $data['msg'] : __("取消关注失败！");
		}
		$this->data->addBody(-140, $data, $msg, $status);
	}

}

?>