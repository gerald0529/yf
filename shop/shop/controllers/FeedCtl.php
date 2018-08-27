<?php if (!defined('ROOT_PATH'))
{
	exit('No Permission');
}

/**
 * @author     Xinze <xinze@live.cn>
 */
class FeedCtl extends Yf_AppController
{
	public function __construct(&$ctl, $met, $typ)
	{
		parent::__construct($ctl, $met, $typ);

		$this->feedGroupModel    = new Feed_GroupModel();
		$this->feedBaseModel     = new Feed_BaseModel();
	}


	//反馈插入
	public function addFeed()
	{
		//问题插入
		if (request_string('feed_desc'))
		{
			$feedback = array(
				"feed_group_id" => 0,
				"feed_desc" => request_string('feed_desc'),
				"feed_url" => request_string('feed_url'),
				"user_id" => request_int('u')
			);
			$rs = $this->feedBaseModel->addBase($feedback);
			$url = 'index.php?ctl=Index&met=feedback';
			//location_to($url);
		}

		if ('json' == $this->typ)
		{
			$this->data->addBody(-140, array());
		}
		else
		{
			include $this->view->getView();
		}
	}
}

?>