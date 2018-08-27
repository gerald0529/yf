<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/5/19
 * Time: 9:26
 */
class Api_Promotion_ActGiftCtl extends Api_Controller
{
	public $ManSong_BaseModel  = null;
	public $ManSong_QuotaModel = null;
	public $ManSong_RuleModel  = null;

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
		$this->ManSong_BaseModel  = new ManSong_BaseModel();
		$this->ManSong_QuotaModel = new ManSong_QuotaModel();
		$this->ManSong_RuleModel  = new ManSong_RuleModel();
	}

	/* 满减活动*/
	//满送活动列表
	public function getManSongList()
	{
		$page         	= request_int('page', 1);
		$rows         	= request_int('rows', 100);
		$mansong_name 	= trim(request_string('mansong_name'));   //活动名称
		$shop_name    	= trim(request_string('shop_name'));      //店铺名称
		$mansong_state	= request_int('mansong_state');           //活动状态

		$cond_row = array();

		if ($mansong_state)
		{
			$cond_row['mansong_state'] = $mansong_state;
		}
		if ($mansong_name)
		{
			$cond_row['mansong_name:LIKE'] = $mansong_name . '%';
		}
		if ($shop_name)
		{
			$cond_row['shop_name:LIKE'] = $shop_name . '%';
		}

		$data = $this->ManSong_BaseModel->getManSongActList($cond_row, array('mansong_id' => 'DESC'), $page, $rows);

		$this->data->addBody(-140, $data);
	}

	public function getManSongInfo()
	{
		$cond_row['mansong_id'] = request_int('id');
		$data                   = $this->ManSong_BaseModel->getManSongActItem($cond_row);
		$this->data->addBody(-140, $data);
	}

	public function cancelManSong()
	{
		$data       = array();
		$mansong_id = request_int('mansong_id');
		if ($mansong_id)
		{
			$field_row['mansong_state'] = ManSong_BaseModel::CANCEL;
			$this->ManSong_BaseModel->editManSong($mansong_id, $field_row);
			$data = $this->ManSong_BaseModel->getManSongByID($mansong_id);
		}

		$this->data->addBody(-140, $data);
	}

	//删除满送活动
	public function removeManSongActivity()
	{
		$data       = array();
		$mansong_id = request_int('man_song_id');

		$this->ManSong_BaseModel->sql->startTransactionDb();

		$flag = $this->ManSong_BaseModel->removeManSongActItem($mansong_id);

		if ($flag && $this->ManSong_BaseModel->sql->commitDb())
		{
			$msg    = __('删除成功');
			$status = 200;
		}
		else
		{
			$this->ManSong_BaseModel->sql->rollBackDb();
			$msg    = __('删除失败');
			$status = 250;
		}

		$data['mansong_id'] = $mansong_id;
		$this->data->addBody(-140, $data, $msg, $status);
	}

	/* 活动套餐*/
	//套餐列表
	function getManSongQuotaList()
	{
		$cond_row  = array();
		$page      = request_int('page', 1);
		$rows      = request_int('rows', 100);
		$shop_name = trim(request_string('shop_name'));

		if ($shop_name)
		{
			$cond_row['shop_name:LIKE'] = $shop_name . '%';
		}

		$data = $this->ManSong_QuotaModel->getManSongQuotaList($cond_row, array('combo_id' => 'DESC'), $page, $rows);

		$this->data->addBody(-140, $data);
	}
}