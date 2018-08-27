<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Api接口, 让App等调用
 *
 *
 * @category   Game
 * @package    User
 * @author     charles
 * @copyright  Copyright (c) 2016, 班常乐
 * @version    1.0
 * @todo
 */
class Controller extends Yf_AppController
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
	{      //共用数据
		parent::__construct($ctl, $met, $typ);
		$this->title       = '';
		$this->description = '';
		$this->keyword     = '';
	}

	public function initData()
	{
		//头部公用的平台基本配置
		$this->web = $this->webConfig();

		//当前用户信息
		//$this->user_info = $this->userInfo();

		//头部公用的平台分类
		$this->cat = $this->catIndex();
		//尾部分类
		$this->foot = $this->footIndex();

		//控制器
		$this->ctl = request_string("ctl");

		//方法名
		$this->met = request_string("met");

		//头部导航
		$this->nav = $this->navIndex();

        //底部导航
		$this->bnav = $this->bnavIndex();

		//热门搜索
		$this->searchWord = $this->searchWord();

	}

	public function catIndex()
	{

		$Goods_CatModel = new Goods_CatModel();
		$data           = $Goods_CatModel->getCatListAll();
		return $data;
	}

	public function footIndex()
	{
		$Article_GroupModel = new Article_GroupModel();
		$data_article_foot  = $Article_GroupModel->getArticleGroupList();
		
		return $data_article_foot;
	}

	public function navIndex()
	{
		$platformNavModel = new Platform_NavModel();
		$cond_row         = array(
			"nav_location:NOT IN" => array(1,2),
			"nav_active" => "1"
		);
		$order_row        = array('nav_displayorder' => 'asc');
		$data             = $platformNavModel->getNavList($cond_row, $order_row, 1);


		if($data['items'])
		{
			foreach($data['items'] as $key=>$value)
			{
				//团购频道关闭
				if(!Web_ConfigModel::value('groupbuy_allow'))
				{
					if(preg_match("/ctl=GroupBuy/", $value['nav_url']))
					{
						unset($data['items'][$key]);
					}
				}
				//积分商城频道关闭
				if(!Web_ConfigModel::value('pointshop_isuse'))
				{
					if(preg_match("/ctl=Points/", $value['nav_url']))
					{
						unset($data['items'][$key]);
					}
				}
			}
		}


		return $data;
	}

	public function bnavIndex()
	{
		$platformNavModel = new Platform_NavModel();
		$cond_row         = array(
			"nav_location:NOT IN" => array(0,1),
			"nav_active" => "1"
		);
		$order_row        = array('nav_displayorder' => 'asc');
		$data             = $platformNavModel->getNavList($cond_row, $order_row, 1);

		return $data;
	}
//
	//默认设置
	public function webConfig()
	{
		$web['web_logo']       = Web_ConfigModel::value("setting_logo");//首页logo
		$web['web_name']       = Web_ConfigModel::value("site_name");//首页名称
		$web['buyer_logo']     = Web_ConfigModel::value("setting_buyer_logo");//会员中心logo
		$web['seller_logo']    = Web_ConfigModel::value("setting_seller_logo");//卖家中心logo
		$web['goods_image']    = Web_ConfigModel::value("photo_goods_logo");//商品图片
		$web['shop_head_logo'] = Web_ConfigModel::value("photo_shop_head_logo");//店铺头像
		$web['shop_logo']      = Web_ConfigModel::value("photo_shop_logo");//店铺标志
		$web['user_logo']      = Web_ConfigModel::value("photo_user_logo");//默认头像

		return $web;
	}

    public function searchWord() {
        $searchWordModel = new Search_WordModel();
        $order_row        = array('search_nums' => 'desc');
        $data             = $searchWordModel->listByWhere(array(), $order_row, 1,12);
        return $data['items'];

    }

    /**
     * 检查用户权限，未登录和非商家用户不可查阅
     * @return type
     */
    public function checkSupplierAuth(){
        $data = array('status'=>true,'msg'=>'验证通过');
        if (Perm::checkUserPerm()) {
			$user_id = Perm::$userId;
            $shop__model = new Shop_BaseModel();
            $shop_info = $shop__model->getByWhere(array('user_id'=>$user_id));
            if(!$shop_info){
                $data = array('status'=>false,'msg'=>'您还未成为入驻商家');
            }else if($shop_info['shop_status'] != $shop__model::SHOP_STATUS_OPEN){
                $data =  array('status'=>false,'msg'=>'您的店铺还未开通，请联系管理员');
            }else  if($shop_info['shop_end_time'] <= date('Y-m-d H:i:s')){
                $data =  array('status'=>false,'msg'=>'您的店铺已到期，请联系管理员');
            }
         
		} else {
			$data['status'] = false;
            $data['msg'] = '请登录！';
		}
        if($data['status'] == false){
            $this->view->setMet('404');
            include $view = $this->view->getView();exit;
        }else{
            return $data;
        }
    } 
}

?>