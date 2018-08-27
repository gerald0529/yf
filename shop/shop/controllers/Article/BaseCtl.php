<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class Article_BaseCtl extends Controller
{ 
	public $articleBaseModel = null;

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
		$this->initData();
		$this->web = $this->webConfig();
		$this->nav = $this->navIndex();
		$this->cat = $this->catIndex();
		//include $this->view->getView();
		$this->articleBaseModel = new Article_BaseModel();
	}

	/**
	 * 首页
	 * @param int article_id    文章id
     * @param article_group_id  文章分组id
	 * @access public
     *
	 */
	public function index()
	{
		$article_id       = request_int('article_id');
		$article_group_id = request_int('article_group_id');

		$Article_BaseModel  = new Article_BaseModel();
		$Article_GroupModel = new Article_GroupModel();

		//头部
		$Goods_CatModel = new Goods_CatModel();
		$data           = $Goods_CatModel->getCatListAll();

		//底部
		$data_article_foot = $Article_GroupModel->getArticleGroupList();

		//所有分类
		$data_all_group = $Article_GroupModel->getByWhere(array('article_group_parent_id' => 0));


		//最近文章
		$Article_BaseModel->sql->setLimit(0, 5);
		$cond_row = array();
		$cond_row['article_type'] = $Article_BaseModel::ARTICLE_TYPE_ARTICLE;
//		$cond_row['article_status'] = $Article_BaseModel::ARTICLE_STATUS_TRUE;
		$order_row = array();
		$order_row['article_sort'] = 'ASC';
		$order_row['article_add_time'] = 'DESC';
		$data_recent_article = $Article_BaseModel->getByWhere($cond_row, $order_row);


		if ($article_id)
		{
			$data_article      = $Article_BaseModel->getOne($article_id);
			$data_near_article = $Article_BaseModel->getNearArticle($article_id);

            $title             = Web_ConfigModel::value("article_title_content");//首页名;
            $this->keyword     = Web_ConfigModel::value("article_keyword_content");//关键字;
            $this->description = Web_ConfigModel::value("article_description_content");//描述;
            $this->title       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $title);
            $this->title       = str_replace("{name}", $data_article['article_title'], $this->title);
            $this->keyword       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->keyword);
            $this->keyword      = str_replace("{name}", $data_article['article_title'], $this->keyword);
            $this->description       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->description);
            $this->description       = str_replace("{name}", $data_article['article_title'], $this->description);
		}
		elseif ($article_group_id)
		{
			$data_article_list = $Article_GroupModel->getArticleGroupLists($article_group_id);
			
            $title             = Web_ConfigModel::value("article_title");//首页名;
            $this->keyword     = Web_ConfigModel::value("article_keyword");//关键字;
            $this->description = Web_ConfigModel::value("article_description");//描述;
            $this->title       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $title);
            $this->title       = str_replace("{name}", $data_article_list['group_name'], $this->title);
            $this->keyword       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->keyword);
            $this->keyword      = str_replace("{name}", $data_article_list['group_name'], $this->keyword);
            $this->description       = str_replace("{sitename}", Web_ConfigModel::value("site_name"), $this->description);
            $this->description       = str_replace("{name}", $data_article_list['group_name'], $this->description);
		}
		
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
	 * @param int $page 页码
     * @param int $rows 每页显示条数
     * @param string $sort 排序方式
     * @return array $data 查询数据
     *
	 * @access public
	 */
	public function lists()
	{
		$user_id = Perm::$userId;

		$page = request_int('page');
		$rows = request_int('rows');
		$sort = request_int('sord');

		$cond_row  = array();
		$order_row = array();

		$data = array();

		if ($skey = request_string('skey'))
		{
			$data = $this->articleBaseModel->getBaseList($cond_row, $order_row, $page, $rows);
		}
		else
		{
			$data = $this->articleBaseModel->getBaseList($cond_row, $order_row, $page, $rows);
		}


		$this->data->addBody(-140, $data);
	}

	/**
	 * 读取
     * @param int $article_id 文章id
     * @return array $data 查询结果
	 *
	 * @access public
	 */
	public function get()
	{
		$user_id = Perm::$userId;

		$article_id = request_int('article_id');
		$rows       = $this->articleBaseModel->getBase($article_id);

		$data = array();

		if ($rows)
		{
			$data = array_pop($rows);
		}

		$this->data->addBody(-140, $data);
	}

	/**
	 * 添加
	 * @param int       $article_id     文章id
     * @param string    $article_desc   描述
     * @param string    $article_title  标题
     * @param string    $article_url    调用url
     * @param int       $article_group_id   分组id
     * @param string    $article_template   模版
     * @aram  string    $article_seo_title  seo标题
     * @aram  string    $article_seo_keywords  SEO关键字
     * @aram  string    $article_seo_description  SEO描述
     * @aram  string    $article_seo_description  SEO描述
     *
	 * @access public
	 */
	public function add()
	{
		$data['article_id']              = request_string('article_id'); // ID
		$data['article_desc']            = request_string('article_desc'); // 描述
		$data['article_title']           = request_string('article_title'); // 标题
		$data['article_url']             = request_string('article_url'); // 调用网址-url，默认为本页面构造的网址，可填写其它页面
		$data['article_group_id']        = request_string('article_group_id'); // 组
		$data['article_template']        = request_string('article_template'); // 模板
		$data['article_seo_title']       = request_string('article_seo_title'); // SEO标题
		$data['article_seo_keywords']    = request_string('article_seo_keywords'); // SEO关键字
		$data['article_seo_description'] = request_string('article_seo_description'); // SEO描述
		$data['article_reply_flag']      = request_string('article_reply_flag'); // 是否启用问答留言
		$data['article_lang']            = request_string('article_lang'); // 语言
		$data['article_type']            = request_string('article_type'); // 类型-暂时忽略
		$data['article_sort']            = request_string('article_sort'); // 排序
		$data['article_status']          = request_string('article_status'); // 状态 1:启用  2:关闭
		$data['article_add_time']        = request_string('article_add_time'); // 添加世间
		$data['article_pic']             = request_string('article_pic'); // 文章图片


		$article_id = $this->articleBaseModel->addBase($data, true);

		if ($article_id)
		{
			$msg    = __('success');
			$status = 200;
		}
		else
		{
			$msg    = __('failure');
			$status = 250;
		}

		$data['article_id'] = $article_id;

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 删除操作
	 * @param  int article_id 文章id
	 * @access public
	 */
	public function remove()
	{
		$article_id = request_int('article_id');

		$flag = $this->articleBaseModel->removeBase($article_id);

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

		$data['article_id'] = array($article_id);

		$this->data->addBody(-140, $data, $msg, $status);
	}

	/**
	 * 修改
	 *
	 * @access public
	 */
	public function edit()
	{
		$data['article_id']              = request_string('article_id'); // ID
		$data['article_desc']            = request_string('article_desc'); // 描述
		$data['article_title']           = request_string('article_title'); // 标题
		$data['article_url']             = request_string('article_url'); // 调用网址-url，默认为本页面构造的网址，可填写其它页面
		$data['article_group_id']        = request_string('article_group_id'); // 组
		$data['article_template']        = request_string('article_template'); // 模板
		$data['article_seo_title']       = request_string('article_seo_title'); // SEO标题
		$data['article_seo_keywords']    = request_string('article_seo_keywords'); // SEO关键字
		$data['article_seo_description'] = request_string('article_seo_description'); // SEO描述
		$data['article_reply_flag']      = request_string('article_reply_flag'); // 是否启用问答留言
		$data['article_lang']            = request_string('article_lang'); // 语言
		$data['article_type']            = request_string('article_type'); // 类型-暂时忽略
		$data['article_sort']            = request_string('article_sort'); // 排序
		$data['article_status']          = request_string('article_status'); // 状态 1:启用  2:关闭
		$data['article_add_time']        = request_string('article_add_time'); // 添加世间
		$data['article_pic']             = request_string('article_pic'); // 文章图片


		$article_id = request_int('article_id');
		$data_rs    = $data;

		unset($data['article_id']);

		$flag = $this->articleBaseModel->editBase($article_id, $data);
		$this->data->addBody(-140, $data_rs);
	}
}

?>